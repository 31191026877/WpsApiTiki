<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Posts extends Model {

    static string $table = 'post';

    static public function getsCategory($object, $args = null) {

        if(is_numeric($object)) $object = self::get(Qr::set($object)->select('id', 'post_type'));

        if(!have_posts($object)) return [];

        if($args instanceof Qr) {
            $argsCategory = clone $args;
        }
        else {
            $argsCategory = Qr::set();
            $taxonomy = Taxonomy::getCategoryByPost($object->post_type);
            if(have_posts($taxonomy)) {
                $argsCategory->where('cate_type', $taxonomy[0]);
            }
        }

        if(is_string($args)) {
            $argsCategory->removeWhere('cate_type');
            $argsCategory->where('cate_type', $args);
        }

        return PostCategory::gets($argsCategory);
    }

    static public function getsRelated($object, $args = null) {
        if($args == null) $args = Qr::set();
        if(is_numeric($object)) $object = static::get($object);
        if(!have_posts($object)) return new \Illuminate\Support\Collection();
        $listCategoryID = PostCategory::getsByPost($object->id);
        if(!have_posts($listCategoryID)) return new \Illuminate\Support\Collection();
        $listCategoryIdTemp = [];
        foreach ($listCategoryID as $item) {
            $listCategoryIdTemp[] = $item->id;
        }
        $args->distinct('r.object_id')->where(self::$table.'.id', '<>', $object->id)->where('r.object_type', 'post');
        $args->join('relationships as r', 'r.object_id', '=', self::$table.'.id');
        $args->whereIn('r.category_id', $listCategoryIdTemp);
        return static::gets($args);
    }

    static public function insert($insertData = []) {

        $user = Auth::user();

        $columnsTable = [
            'content'           => ['wysiwyg'],
            'excerpt'           => ['wysiwyg'],
            'seo_title'         => ['string'],
            'seo_description'   => ['string'],
            'seo_keywords'      => ['string'],
            'status'            => ['int', 0],
            'image'             => ['image'],
            'user_created'      => ['int', (have_posts($user)) ? $user->id : 0],
            'user_updated'      => ['int', (have_posts($user)) ? $user->id : 0],
            'post_type'         => ['string', 'post'],
            'public'            => ['int', 1],
            'trash'             => ['int', 0],
            'order'             => ['int', 0],
        ];

        $columnsTable = apply_filters('columns_db_'.self::$table, $columnsTable);

        if(!empty($insertData['id'])) {

            $id             = (int) $insertData['id'];

            $update        = true;

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_page_id', __('ID bài viết không chính xác.'));

            if(empty($insertData['title'])) $insertData['title'] = $oldObject->title;

            $slug = empty($insertData['slug']) ? $oldObject->slug : Str::slug($insertData['slug']);

            if(empty($slug)) $slug = Str::slug($insertData['title']);

            if($slug != $oldObject->slug) {
                $slug = Routes::slug($slug, self::$table, $id);
                CacheHandler::delete('routes-'.$oldObject->slug);
                CacheHandler::delete('routes-'.$slug);
            }
        }
        else {

            if(empty($insertData['title'])) return new SKD_Error('empty_post_title', __('Không thể cập nhật bài viết khi tiêu đề trống.', 'empty_post_title') );

            $update = false;

            $slug = Routes::slug(Str::clear($insertData['title']), self::$table);
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $title      = trim(Str::clear($insertData['title']));

        if(!$update) {
            if(empty($seo_title)) $seo_title = $title;
            if(empty($seo_description)) $seo_description = Str::clear($excerpt);
        }

        $data = compact(array_merge(['title', 'slug', array_keys($columnsTable)]));

        $data = apply_filters('pre_insert_post_data', $data, $insertData, $update ? $oldObject : null);

        $language   = !empty($insertData['language']) ? $insertData['language'] : [];

        $taxonomies = !empty($insertData['taxonomies']) ? $insertData['taxonomies'] : [];

        $model = model(self::$table);

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set('id', $id));

            $object_id = (int)$id;

            # [Router]
            $router['object_type']  = 'post';

            $router['object_id']    = $object_id;

            if(empty(Routes::update(['slug' => $slug], $router))) {
                $router['slug']         = $slug;
                $router['directional']  = 'post';
                $router['controller']   = 'frontend/post/detail/';
                Routes::insert($router);
            }

            # [LANGUAGE]
            if(have_posts($language)) {
                $objectLanguage = Language::gets(Qr::set('object_id', $id)->where('object_type', 'post'));
                if(!empty($objectLanguage)) {
                    foreach ($objectLanguage as $objLang) {
                        foreach ($language as $key => $insLangData) {
                            if($key == $objLang->language) {
                                if(Language::update($insLangData, Qr::set('id', $objLang->id))) {
                                    unset($language[$key]);
                                }
                            }
                        }
                    }
                }

                if(!empty($language)) {
                    foreach ($language as $key => $insLangData) {
                        $insLangData['language'] 	= $key;
                        $insLangData['object_id']  	= $id;
                        $insLangData['object_type'] = 'post';
                        if(Language::insert($insLangData)) unset($language[$key]);
                    }
                }
            }
            # [TAXONOMY]
            if(isset($taxonomies)) {

                $model->settable('relationships');

                if(have_posts($taxonomies)) {

                    $temp = $model->gets(Qr::set('object_id', $object_id)->where('object_type', 'post')->select('value')->groupBy('value'));

                    $taxonomy_cate_type = [];

                    foreach ($temp as $temp_value) {
                        $taxonomy_cate_type[$temp_value->value] = $temp_value->value;
                    }

                    $taxonomy['object_id'] 		= $object_id;

                    $taxonomy['object_type'] 	= 'post';

                    foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {

                        if(isset($taxonomy_cate_type[$taxonomy_key])) unset($taxonomy_cate_type[$taxonomy_key]);

                        $taxonomy['value'] 		= $taxonomy_key;

                        $temp_old = $model->gets(Qr::set('object_id',$object_id)->where('object_type','post')->where('value', $taxonomy_key)->select('id', 'category_id'));

                        $taxonomies_old = [];

                        if(have_posts($temp_old)) {
                            foreach ($temp_old as $temp_old_value) {
                                $taxonomies_old[$temp_old_value->category_id] = $temp_old_value->category_id;
                            }
                        }

                        //Trường hợp không có taxonomy old và có taxonomy mới
                        if(!have_posts($taxonomies_old) && have_posts($taxonomy_value)) {
                            foreach ($taxonomy_value as $taxonomy_id) {
                                $taxonomy['category_id'] = $taxonomy_id;
                                $model->add($taxonomy);
                            }
                        }
                        //Trường hợp có taxomomy old và không có taxonomy mới
                        else if(have_posts($taxonomies_old) && !have_posts($taxonomy_value)) {
                            $model->delete(Qr::set('object_id', $object_id)->where('object_type','post')->where('value', $taxonomy_key));
                        }
                        else {
                            foreach ($taxonomy_value as $taxonomy_id) {
                                //Đã có trong taxonomy old
                                if(in_array($taxonomy_id, $taxonomies_old) !== false) {
                                    unset($taxonomies_old[$taxonomy_id]);
                                    continue;
                                }
                                //Không có thì thêm mới
                                $taxonomy['category_id'] = $taxonomy_id;
                                $model->add($taxonomy);
                            }
                            //Còn $taxonomies_old
                            if(have_posts($taxonomies_old)) {
                                $model->delete(Qr::set('object_id', $object_id)
                                    ->where('object_type', 'post')
                                    ->where('value', $taxonomy_key)
                                    ->whereIn('category_id', $taxonomies_old));
                            }
                        }
                    }

                    if(have_posts($taxonomy_cate_type)) {
                        foreach ($taxonomy_cate_type as $ta_cate_type) {
                            $model->delete(Qr::set('object_id', $object_id)->where('object_type', 'post')->where('value', $ta_cate_type));
                        }
                    }
                }
                else {
                    $model->delete(Qr::set('object_id', $object_id)->where('object_type', 'post'));
                }
            }
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $object_id = $model->add($data);

            # [Router]
            $router['slug']         = $slug;
            $router['object_type']  = 'post';
            $router['directional']  = 'post';
            $router['controller']   = 'frontend/post/detail/';
            $router['object_id']    = $object_id;
            Routes::insert($router);

            # [LANGUAGE]
            if(have_posts($language)) {

                foreach ($language as $langKey => $langData) {
                    $langInsert = [];
                    $langInsert['title']          = Str::clear($langData['title']);
                    $langInsert['excerpt']        = (isset($langData['excerpt'])) ? $langData['excerpt'] : '';
                    $langInsert['content']        = (isset($langData['content'])) ? $langData['content'] : '';
                    $langInsert['language']       = $langKey;
                    $langInsert['object_id']      = $object_id;
                    $langInsert['object_type']    = 'post';
                    Language::insert($langInsert);
                }
            }

            # [TAXONOMY]
            if(isset($taxonomies) && have_posts($taxonomies) ) {

                $model->settable('relationships');

                $taxonomy['object_id']      = $object_id;

                $taxonomy['object_type']    = 'post';

                foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {
                    $taxonomy['value']      = $taxonomy_key;
                    foreach ($taxonomy_value as $taxonomy_id) {
                        $taxonomy['category_id'] = $taxonomy_id;
                        $model->add($taxonomy);
                    }
                }
            }
        }

        return apply_filters('after_insert_post', $object_id, $insertData, $data, $update ? $oldObject : null);
    }

    static public function update($update, $args) {

        if(!have_posts($update)) {
            return new SKD_Error('invalid_update', __('Không có trường dữ liệu nào được cập nhật.'));
        }

        if(!have_posts($args)) {
            return new SKD_Error('invalid_update', __('Không có điều kiện cập nhật.'));
        }
        $update['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
        return apply_filters('update_post', model(self::$table)->update($update, $args), $update, $args);
    }

    static public function restore($args) {
        return model(self::$table)->update(['trash' => 0], $args, 'post');
    }

    static public function delete($postID = 0, $trash = false ) {

        $ci =& get_instance();

        $postID = (int)Str::clear($postID);

        if($postID == 0) return false;

        $post  = static::get($postID);

        if(have_posts($post)) {

            $model      = model(self::$table);

            $ci->data['module'] = 'post';

            $args = Qr::set('object_id',$postID)->where('object_type', 'post');

            //nếu bỏ vào thùng rác
            if($trash) {

                /**
                 * @since 2.5.0 add action delete_post_trash
                 */
                do_action('delete_post_trash', $postID );

                if($model->update(['trash' => 1], Qr::set('id',$postID))) {
                    $menuItems = ThemeMenu::getsItem(clone $args);
                    $model->settable('menu')->delete(clone $args);
                    if(have_posts($menuItems)) {
                        foreach ($menuItems as $menuItem) {
                            Metadata::deleteByMid('menu', $menuItem->id);
                            CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                        }
                    }
                    return [$postID];
                }

                return false;
            }
            /**
             * @since 2.5.0 add action delete_post
             */
            do_action('delete_post', $postID );

            if($model->delete(Qr::set($postID))) {

                do_action('delete_post_success', $postID );

                //delete language
                $model->setTable('language')->delete(clone $args);

                //delete router
                $model->setTable('routes')->delete(clone $args);

                //delete gallerys
                Gallery::deleteItemByObject($postID, 'post_'.$post->post_type);

                Metadata::deleteByMid('post', $postID);

                //delete menu
                $menuItems = ThemeMenu::getsItem(clone $args);
                $model->setTable('menu')->delete(clone $args);
                if(have_posts($menuItems)) {
                    foreach ($menuItems as $menuItem) {
                        Metadata::deleteByMid('menu', $menuItem->id);
                        CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                    }
                }

                //xóa liên kết
                $model->settable('relationships')->delete(clone $args);

                return [$postID];
            }
        }

        return false;
    }

    static public function deleteList($postID = [], $trash = false ) {

        if(have_posts($postID)) {

            $args = Qr::set('object_type', 'post')->whereIn('object_id', $postID);

            $model = model(self::$table);

            if($trash) {

                do_action('delete_post_list_trash', $postID);

                if($model->update(['trash' => 1], Qr::set()->whereIn('id', $postID))) {
                    //delete menu
                    $menuItems = ThemeMenu::getsItem($args);
                    $model->settable('menu')->delete($args);
                    if(have_posts($menuItems)) {
                        foreach ($menuItems as $menuItem) {
                            Metadata::deleteByMid('menu', $menuItem->id);
                            CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                        }
                    }
                    return $postID;
                }
                return false;
            }

            $posts = static::gets(Qr::set()->whereIn('id', $postID));

            if($model->delete(Qr::set()->whereIn('id', $postID))) {

                do_action('delete_post_list_trash_success', $postID );

                //delete language
                $model->setTable('language')->delete(clone $args);

                //delete router
                $model->setTable('routes')->delete(clone $args);

                //delete router
                foreach ($posts as $post) {

                    delete_gallery_by_object($post->id, 'post_'.$post->post_type);

                    Metadata::deleteByMid('post', $post->id);
                }

                //delete menu
                $menuItems = ThemeMenu::getsItem(clone $args);
                $model->setTable('menu')->delete(clone $args);
                if(have_posts($menuItems)) {
                    foreach ($menuItems as $menuItem) {
                        Metadata::deleteByMid('menu', $menuItem->id);
                        CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                    }
                }

                //xóa liên kết
                $model->setTable('relationships')->delete(clone $args);

                return $postID;
            }
        }

        return false;
    }

    static public function handleParams($args = null) {

        $query = ($args instanceof Qr) ? clone $args : Qr::set();

        if($args instanceof Qr && !empty($args->related)) {
            $id = $args->related;
            $query->distinct('r.object_id')->where(self::$table.'.id', '<>', $id)->where('r2.object_type', 'post');
            $query->join('relationships as r', 'r.object_id', '=', self::$table.'.id');
            $query->join('relationships as r2', 'r2.object_id', '=', self::$table.'.id');
            //$query->where('r.category_id', 'cle_r2.category_id');
        }

        if(is_array($args)) {
            $args = self::handleParamsArr($args);
            $query = Qr::convert($args);
            if(!$query) return $query;
            if(isset($args['related'])) {
                $id = $args['related'];
                $query->distinct('r.object_id')->where(self::$table.'.id', '<>', $id)->where('r2.object_type', 'post');
                $query->join('relationships as r', 'r.object_id', '=', self::$table.'.id');
                $query->join('relationships as r2', 'r2.object_id', '=', self::$table.'.id');
                $query->where('r.category_id', 'cle_r2.category_id');
            }
            if(isset($args['tax_query'])) {

                $table  = CLE_PREFIX.'relationships';

                $sql = 'SELECT SQL_CALC_FOUND_ROWS `'.$table.'`.`object_id` FROM `'.$table.'`';

                $taxQuery  = $args['tax_query'];

                $taxonomyID = [];

                $relation 	= 'AND';

                if(!empty($taxQuery['relation'])) {
                    $relation = $taxQuery['relation']; unset($taxQuery['relation']);
                }

                if($relation != 'AND' || $relation != 'OR') $relation = 'AND';

                foreach ($taxQuery as $key => $tax) {
                    $taxonomy = PostCategory::get(Qr::set($tax['field'], Str::clear($tax['terms']))->where('cate_type', Str::clear($tax['taxonomy'])));
                    if(have_posts($taxonomy)) {
                        $dataID  = PostCategory::children(['category' => $taxonomy, 'andParent' => true]);
                        if(have_posts($dataID)) {
                            $sql .= ' INNER JOIN `'.$table.'` AS txnm'.$key.' ON (`'.$table.'`.id = txnm'.$key.'.id) ';
                            $taxonomyID['txnm'.$key] = ['data' => $dataID, 'taxonomy' => $tax['taxonomy']];
                        }
                    }
                }

                $sql .= ' WHERE 1=1';

                if(have_posts($taxonomyID)) {

                    $sql .= ' AND (';

                    foreach ($taxonomyID as $txnmkey => $taxonomyData) {

                        $sql    .= '(';

                        $sql .= $txnmkey.'.`category_id` IN ('.implode(',',$taxonomyData['data']).')';

                        $sql .= ' AND '.$txnmkey.'.`value` = \''.$taxonomyData['taxonomy'].'\'';

                        $sql .= ') '.$relation.' ';
                    }

                    $sql = trim( $sql, ' '.$relation.' ' );

                    $sql .= ')';
                }

                $query->whereIn('id', Qr::set()->select($sql));
            }
        }

        if(is_numeric($args)) $query = Qr::set('id', $args);

        if(!Admin::is()) {
            if(!$query->isWhere(self::$table.'.public') && !$query->isWhere('public')) $query->where(self::$table.'.public', 1);
            if(!$query->isWhere(self::$table.'.trash') && !$query->isWhere('trash')) $query->where(self::$table.'.trash', 0);
            $query = Language::join($query, 'post');
        }

        if(!empty($query->category['object'])) {
            if(is_object($query->category['object'])) $query->category['object'] = $query->category['object']->id;
            if(is_numeric($query->category['object'])) {
                $listSubCategoryID = PostCategory::children(['andParent' => true, 'id' => $query->category['object']]);
                if(!have_posts($listSubCategoryID)) return [];
                $query
                    ->join('relationships', 'relationships.object_id', '=', 'post.id')
                    ->distinct('post.id')
                    ->whereIn('relationships.category_id', $listSubCategoryID)
                    ->where('relationships.object_type', 'post');
            }
            if(is_array($query->orders) && have_posts($query->orders)) {
                foreach ($query->orders as $key => $order) {
                    if(!str_contains($order['column'], '.')) $query->orders[$key]['column'] = self::$table.'.'.$order['column'];
                }
            }
        }

        return $query;
    }

    static public function handleParamsArr($args): array {

        if(!have_posts($args)) $args = ['where' => [], 'params' => []];

        $args = array_merge(['where' => [], 'params' => []], $args);

        if(!Admin::is() && isset($args['where'])) {
            $args['where'] = array_merge(['public'=> 1, 'trash' => 0] , $args['where']);
        }

        if(isset($args['post_type'])) {
            $args['where']['post_type'] = $args['post_type'];
            unset($args['post_type']);
        }

        if(!empty($args['where_category']) && is_numeric($args['where_category'])) {
            $args['where_category'] = PostCategory::get($args['where_category']);
        }

        return $args;
    }
}

class PostCategory {

    static string $table = 'categories';

    static function get($args = []) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return [];
        return apply_filters('get_post_category', model(self::$table)->get($args), $args);
    }

    static function gets($args = []) {
        $object = [];
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return [];
        if(!$args->isWhere('cate_type')) $args->where('cate_type', 'post_categories');
        $cacheID = 'post_category_'.md5(serialize(Qr::clear($args)));
        if(Language::default() != Language::current()) $cacheID .= '_'.Language::current();
        //if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);
        if(isset($args->category['type'])) {
            if(!empty($args->category['value'])) {
                $args->where('parent_id', (int)$args->category['value']);
            }
            else {
                $args->where('parent_id', 0);
            }
            if($args->category['type'] == 'tree') {
                $args->category = [];
                $object = self::getsTree($object, $args, 'tree');
            }
            else if($args->category['type'] == 'multilevel') {
                $args->category = [];
                $object = self::getsTree($object, $args, 'multilevel');
            }
            else if($args->category['type'] == 'options') {
                $args->category = [];
                $args->select(self::$table.'.id', self::$table.'.name', self::$table.'.level', self::$table.'.lft', self::$table.'.rgt');
                $temp = self::getsTree([], $args, 'tree');
                if(have_posts($temp)) {
                    $object[0] = 'Chọn danh mục';
                    foreach($temp as $item){
                        $object[$item->id] = str_repeat('|-----', (($item->level > 0) ? ($item->level - 1) : 0)).$item->name;
                    }
                }
            }
        }
        else {
            $object = apply_filters('gets_post_category', model(self::$table)->gets($args));
        }

        if(have_posts($object)) CacheHandler::save($cacheID, $object);

        return $object;
    }

    static function getsByPost($postId, $args = null) {
        $model = model(self::$table);
        if($args === null) $args = Qr::set();
        $args->select(self::$table.'.*');
        $args->where('object_id', (int)$postId);
        if(!$args->isWhere('object_type')) $args->where('object_type', 'post');
        if(!$args->isWhere('value')) $args->where('value', 'post_categories');
        $args->join('relationships', 'categories.id', '=', 'relationships.category_id');
        if(!Admin::is()) $args->where(self::$table.'.public', 1);
        return apply_filters('gets_post_category_by_post', $model->gets($args), $args);
    }

    static function count($args = []) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return 0;
        return apply_filters('count_post_category', model(self::$table)->count($args), $args);
    }

    static function getsTree($trees , Qr $args, $type) {
        $model = model(self::$table);
        $args->orderBy('order');
        $root = (object)$model->gets($args)->all();
        if(have_posts($root)) {
            if($type == 'multilevel') {
                if(empty($trees)) {
                    $trees = $root;
                    foreach ($trees as &$item) {
                        if($args->isWhere('parent_id')) $args->removeWhere('parent_id');
                        $args->where('parent_id', $item->id);
                        $item->child = [];
                        $item = static::getsTree($item, $args, $type);
                    }
                }
                else $trees->child = $root;
                if(!empty($trees->child)) {
                    foreach ($trees->child as &$item) {
                        if($args->isWhere('parent_id')) $args->removeWhere('parent_id');
                        $args->where('parent_id', $item->id);
                        $item->child = [];
                        $item = (object)static::getsTree($item, $args, $type);
                    }
                }
            }
            if($type == 'tree') {
                foreach ($root as $item) {
                    if($args->isWhere('parent_id')) $args->removeWhere('parent_id');
                    $args->where('parent_id', $item->id);
                    $trees[] = $item;
                    $trees   = static::getsTree($trees, $args, $type);
                }
            }
        }
        return $trees;
    }

    static function children($params = []): ?array {

        $model = model();

        $catalogues = NULL;

        $params['andParent'] = (isset($params['andParent']) && $params['andParent']) ? '=' : '';

        if(isset($params['lft']) && isset($params['rgt'])) {
            $catalogues['lft'] = $params['lft'];
            $catalogues['rgt'] = $params['rgt'];
            $catalogues = (object)$catalogues;
        }
        else if(isset($params['id'])){
            $catalogues = $model::table(self::$table)->select('id', 'lft', 'rgt')->where('id', $params['id'])->first();
        }
        else if(isset($params['category'])) {
            $catalogues = $params['category'];
        }

        if(!have_posts($catalogues)) return [];

        $temp = [];

        $children = $model::table(self::$table)->select('id')->where('lft', '>'.$params['andParent'], $catalogues->lft)->where('rgt', '<'.$params['andParent'], $catalogues->rgt)->get();

        if(have_posts($children)) {
            foreach($children as $val) $temp[] = $val->id;
        }

        return $temp;
    }

    static function insert($insertData = []) {

        $ci =& get_instance();

        $user = Auth::user();

        $columnsTable = [
            'content'           => ['wysiwyg'],
            'excerpt'           => ['wysiwyg'],
            'seo_title'         => ['string'],
            'seo_description'   => ['string'],
            'seo_keywords'      => ['string'],
            'status'            => ['int', 0],
            'parent_id'         => ['int', 0],
            'level'             => ['int', 0],
            'lft'               => ['int', 0],
            'rgt'               => ['int', 0],
            'image'             => ['image'],
            'user_created'      => ['int', (have_posts($user)) ? $user->id : 0],
            'user_updated'      => ['int', (have_posts($user)) ? $user->id : 0],
            'cate_type'         => ['string', 'post_categories'],
            'public'            => ['int', 1],
            'order'             => ['int', 0],
        ];

        $columnsTable = apply_filters('columns_db_post_category', $columnsTable);

        if(!empty($insertData['id'])) {

            $id             = (int) $insertData['id'];

            $update        = true;

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_page_id', __('ID danh mục không chính xác.'));

            if(empty($insertData['name'])) $insertData['name'] = $oldObject->name;

            $slug = empty($insertData['slug']) ? $oldObject->slug : Str::slug($insertData['slug']);

            if(empty($slug)) $slug = Str::slug($insertData['name']);

            if($slug != $oldObject->slug) {
                $slug = Routes::slug($slug, 'post_categories', $id);
                CacheHandler::delete('routes-'.$slug);
            }
        }
        else {

            if(empty($insertData['name'])) return new SKD_Error('empty_category_name', __('Không thể cập nhật danh mục khi tiêu đề trống.', 'empty_category_name') );

            $update = false;

            $slug = Routes::slug(Str::clear($insertData['name']), 'post_categories');
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $name      = trim(Str::clear($insertData['name']));

        if(!$update) {
            if(empty($seo_title)) $seo_title = $name;
            if(empty($seo_description)) $seo_description = Str::clear($excerpt);
        }

        $data = compact(array_merge(['name', 'slug', array_keys($columnsTable)]));

        $data = apply_filters('pre_insert_post_category_data', $data, $insertData, $update ? $oldObject : null);

        $language   = !empty($insertData['language']) ? $insertData['language'] : [];

        $model = model(self::$table);

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set('id', $id));

            $object_id = (int)$id;

            # [Nested]
            if(!class_exists('Nestedset')) $ci->load->library('Nestedset');
            $nestedSet = new nestedset(['table' => self::$table]);
            $nestedSet->get();
            $nestedSet->recursive(0, $nestedSet->set());
            $nestedSet->action();

            # [Router]
            $router['object_type']  = 'post_categories';
            $router['object_id']    = $object_id;
            if(empty(Routes::update(['slug' => $slug], $router))) {
                $router['slug']         = $slug;
                $router['directional']  = 'post_categories';
                $router['controller']   = 'frontend/post/index/';
                Routes::insert($router);
            }

            # [LANGUAGE]
            if(have_posts($language)) {
                $objectLanguage = Language::gets(Qr::set()->where('object_id', $id)->where('object_type', 'post_categories'));
                if(!empty($objectLanguage)) {
                    foreach ($objectLanguage as $objLang) {
                        foreach ($language as $key => $insLangData) {
                            if($key == $objLang->language) {
                                if(Language::update($insLangData, ['id' => $objLang->id])) {
                                    unset($language[$key]);
                                }
                            }
                        }
                    }
                }
                if(!empty($language)) {
                    foreach ($language as $key => $insLangData) {
                        $insLangData['language'] 	= $key;
                        $insLangData['object_id']  	= $id;
                        $insLangData['object_type'] = 'post_categories';
                        if(Language::insert($insLangData)) unset($language[$key]);
                    }
                }
            }

            CacheHandler::delete('post_category_', true);
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $object_id = $model->add($data);

            # [Nested]
            if(!class_exists('NestedSet')) $ci->load->library('NestedSet');
            $nestedSet = new NestedSet(['table' => self::$table]);
            $nestedSet->get();
            $nestedSet->recursive(0, $nestedSet->set());
            $nestedSet->action();

            # [Router]
            $router['slug']         = $slug;
            $router['object_type']  = 'post_categories';
            $router['directional']  = 'post_categories';
            $router['controller']   = 'frontend/post/index/';
            $router['object_id']    = $object_id;
            Routes::insert($router);

            # [LANGUAGE]
            if(have_posts($language)) {

                foreach ($language as $langKey => $langData) {
                    $langInsert = [];
                    $langInsert['name']          = Str::clear($langData['name']);
                    $langInsert['excerpt']        = (isset($langData['excerpt'])) ? $langData['excerpt'] : '';
                    $langInsert['content']        = (isset($langData['content'])) ? $langData['content'] : '';
                    $langInsert['language']       = $langKey;
                    $langInsert['object_id']      = $object_id;
                    $langInsert['object_type']    = 'post_categories';
                    Language::insert($langInsert);
                }
            }
        }

        return apply_filters('after_insert_post_category', $object_id, $insertData, $data, $update ? $oldObject : null);
    }

    static function update($update, $args) {

        if(!have_posts($update)) {
            return new SKD_Error('invalid_update', __('Không có trường dữ liệu nào được cập nhật.'));
        }

        if(!have_posts($args)) {
            return new SKD_Error( 'invalid_update', __('Không có điều kiện cập nhật.'));
        }

        CacheHandler::delete('post_category_', true);

        $update['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);

        return apply_filters( 'update_post_categories', model(self::$table)->update($update, $args), $update, $args);
    }

    static function delete($cate_ID = 0) {

        $ci =& get_instance();

        $cate_ID = (int)Str::clear($cate_ID);

        if($cate_ID == 0) return false;

        $model      = model(self::$table);

        $category  = static::get($cate_ID);

        if(have_posts($category)) {

            $ci->data['module']   = 'post_categories';

            $listID = self::children(['andParent' => true]);

            if(!have_posts($listID)) $listID = [$cate_ID];

            $model->settable('relationships')->delete(Qr::set('category_id', (string)$cate_ID)->where('value', $category->cate_type));

            if(have_posts($listID)) {

                $args = Qr::set('object_type', 'post_categories')->whereIn('object_id', $listID);

                //xóa router
                $model->settable('routes')->delete(clone $args);

                //xóa ngôn ngữ
                $model->settable('language')->delete(clone $args);

                //xóa gallery
                foreach ($listID as $key => $id) {
                    delete_gallery_by_object($id, 'post_categories_'.$category->cate_type);
                    Metadata::deleteByMid(self::$table, $id);
                }

                //delete menu
                $args->where('type', 'categories');

                $menuItems = ThemeMenu::getsItem(clone $args);

                $model->settable('menu')->delete(clone $args);

                if(have_posts($menuItems)) {
                    foreach ($menuItems as $menuItem) {
                        Metadata::deleteByMid('menu', $menuItem->id);
                        CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                    }
                }

                CacheHandler::delete('menu-', true);

                if($model->settable(self::$table)->delete(Qr::set()->whereIn('id', $listID))) return $listID;
            }

            CacheHandler::delete( 'post_category_', true );
        }

        return false;
    }

    static function deleteList($cate_ID = []) {

        $result = [];

        if(!have_posts($cate_ID)) return false;

        foreach ($cate_ID as $id) {
            if(static::delete($id)) $result[] = $id;
        }

        if(have_posts($result)) return $result;

        return false;
    }

    static function getMeta( $cateID, $key = '', $single = true) {
        return Metadata::get('categories', $cateID, $key, $single);
    }

    static function updateMeta($cateID, $meta_key, $meta_value) {
        return Metadata::update('categories', $cateID, $meta_key, $meta_value);
    }

    static function deleteMeta($cateID, $meta_key = '', $meta_value = '') {
        return Metadata::delete('categories', $cateID, $meta_key, $meta_value);
    }

    static function handleParams($args = null) {

        if(is_array($args)) {
            $args = self::handleParamsArr($args);
            $query = Qr::convert($args);
            if(!empty($args['post_id']) && is_numeric($args['post_id'])) {
                $postType = (!empty($args['post_type'])) ? Str::clear($args['post_type']) : 'post';
                $cateType = (!empty($args['cate_type'])) ? Str::clear($args['cate_type']) : 'post_categories';
                $query->whereIn('categories.id', Qr::set('object_id', (int)$args['post_id'])->where('object_type', $postType)->where('value', $cateType)->from('relationships')->select('relationships.category_id'));
            }
            else if(isset($args['tree'])) {
                $query->categoryType('tree', (!empty($args['tree']['parent_id'])) ? $args['tree']['parent_id'] : 0);
            }
            else if(isset($args['multilevel']) || isset($args['mutilevel'])) {
                $multilevel = (isset($args['multilevel'])) ? $args['multilevel'] : $args['mutilevel'];
                if(is_numeric($multilevel)) {
                    $query->categoryType('multilevel', $multilevel);
                }
                else {
                    $query->where('cate_type', $multilevel)->categoryType('options');
                }
            }
        }

        if(is_numeric($args)) $query = Qr::set('id', $args);

        if($args instanceof Qr) $query = clone $args;

        if(!Admin::is() && isset($query)) {
            if(!$query->isWhere(self::$table.'.public') && !$query->isWhere('public')) $query->where(self::$table.'.public', 1);
            $query = Language::join($query, 'categories', 'post_categories');
        }

        return (isset($query)) ? $query : null;
    }

    static function handleParamsArr($args): array {

        if(!have_posts($args)) $args = ['where' => [], 'params' => []];

        $args = array_merge(['where' => [], 'params' => []], $args);

        if(!Admin::is() && isset($args['where'])) {
            $args['where'] = array_merge(['public' => 1], $args['where']);
        }

        return $args;
    }
}