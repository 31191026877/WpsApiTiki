<?php
Class ProductCategory {

    static string $table = 'products_categories';
    
    static public function get($args = []) {
        $args = self::handleParams($args);
        $cacheID = 'products_category_'.md5(serialize(Qr::clear($args)));
        if(!Language::isDefault()) $cacheID .= '_'.Language::current();
        if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);
        $category 	= model(self::$table)->get($args);
        if(have_posts($category)) CacheHandler::save($cacheID, $category);
        return $category;
    }

    static public function gets( $args = [] ) {

        $args = self::handleParams($args);

        $cacheID = 'products_categories_'.md5(serialize(Qr::clear($args)));

        if(!Language::isDefault()) $cacheID .= '_'.Language::current();

        if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);

        $object = [];

        if(isset($args->category['type'])) {
            if(isset($args->category['value']) && is_numeric($args->category['value'])) {
                $args->where('parent_id', (int)$args->category['value']);
            }
            if($args->category['type'] == 'tree') {
                $args->category = [];
                $args->orderBy('order');
                $object = self::getsTree($object, $args, 'tree');
            }
            else if($args->category['type'] == 'multilevel') {
                $args->category = [];
                $args->orderBy('order');
                $object = self::getsTree($object, $args, 'multilevel');
            }
            else if($args->category['type'] == 'options') {
                $args->category = [];
                $args->select(self::$table.'.id', self::$table.'.name', self::$table.'.level', self::$table.'.lft', self::$table.'.rgt');
                $args->orderBy('order');
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
            $object = model(self::$table)->gets($args);
        }

        if(have_posts($object)) CacheHandler::save($cacheID, $object);

        return $object;
    }

    static public function getsByProduct($productId, $args = null) {

        if($args == null) $args = Qr::set();

        $args = $args->whereIn(self::$table.'.id', Qr::set('object_id', (int)$productId)->where('object_type', 'products')->where('value', 'products_categories')->from('relationships')->select('relationships.category_id'));

        return model(self::$table)->gets($args);
    }

    static public function getsTree($trees , Qr $args, $type) {
        $model = model(self::$table);
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
                else {
                    $trees->child = $root;
                }
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
                    if($args->isWhere('parent_id')) {
                        $args->removeWhere('parent_id');
                    }
                    $args->where('parent_id', $item->id);
                    $trees[] = $item;
                    $trees   = static::getsTree($trees, $args, $type);
                }
            }
        }
        return $trees;
    }

    static public function children($params = []): ?array {

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

    static public function count( $args = [] ) {

        $args = self::handleParams($args);

        $cacheID = 'products_categories_count_'.md5(serialize(Qr::clear($args)));

        if(!Language::isDefault()) $cacheID .= '_'.Language::current();

        if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);

        $object = 0;

        if(isset($args->category['type'])) {
            if(!empty($args->category['value'])) {
                $args->where('parent_id', (int)$args->category['value']);
            }
            if($args->category['type'] == 'tree' || $args->category['type'] == 'multilevel' || $args->category['type'] == 'options') {
                $args->category = [];
                $args->select(self::$table.'.id', self::$table.'.name', self::$table.'.level', self::$table.'.lft', self::$table.'.rgt');
                $temp = self::getsTree([], $args, 'tree');
                $object = count($temp);
            }
        }
        else {
            $object = model(self::$table)->count($args);
        }

        CacheHandler::save($cacheID, $object);

        return $object;
    }

    static public function toSql($args = []): string {
        return model(self::$table)->toSql(self::handleParams($args));
    }

    static public function insert($insertData = []) {

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
            'public'            => ['int', 1],
            'order'             => ['int', 0],
        ];

        $columnsTable = apply_filters('columns_db_'.self::$table, $columnsTable);

        if(!empty($insertData['id'])) {

            $id             = (int) $insertData['id'];

            $update        = true;

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_id', __('ID danh mục không chính xác.'));

            if(empty($insertData['name'])) $insertData['name'] = $oldObject->name;

            $slug = empty($insertData['slug']) ? $oldObject->slug : Str::slug($insertData['slug']);

            if(empty($slug)) $slug = Str::slug($insertData['name']);

            if($slug != $oldObject->slug) {

                $slug = Routes::slug($slug, self::$table, $id);

                CacheHandler::delete('routes-'.$slug);
            }
        }
        else {

            if(empty($insertData['name'])) return new SKD_Error('empty_category_name', __('Không thể cập nhật danh mục khi tiêu đề trống.', 'empty_category_name') );

            $update = false;

            $slug = Routes::slug(Str::clear($insertData['name']), self::$table);
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

        $data = apply_filters('pre_insert_'.self::$table.'_data', $data, $insertData, $update ? $oldObject : null);

        $language   = !empty($insertData['language']) ? $insertData['language'] : [];

        $model = model(self::$table);

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set('id', $id));

            $object_id = (int)$id;

            # [Nested]
            if(!class_exists('NestedSet')) $ci->load->library('NestedSet');
            $nestedSet = new NestedSet(['table' => self::$table]);
            $nestedSet->get();
            $nestedSet->recursive(0, $nestedSet->set());
            $nestedSet->action();

            # [Router]
            $router['object_type']  = self::$table;
            $router['object_id']    = $object_id;
            if(empty(Routes::update(['slug' => $slug], $router))) {
                $router['slug']         = $slug;
                $router['directional']  = self::$table;
                $router['controller']   = 'frontend/products/index/';
                Routes::insert($router);
            }

            # [LANGUAGE]
            if(have_posts($language)) {
                $objectLanguage = Language::gets(Qr::set()->where('object_id', $id)->where('object_type', self::$table));
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
                        $insLangData['object_type'] = self::$table;
                        if(Language::insert($insLangData)) unset($language[$key]);
                    }
                }
            }

            CacheHandler::delete('products_categories_', true);
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
            $router['object_type']  = self::$table;
            $router['directional']  = self::$table;
            $router['controller']   = 'frontend/products/index/';
            $router['object_id']    = $object_id;
            Routes::insert($router);

            # [LANGUAGE]
            if(have_posts($language)) {
                foreach ($language as $langKey => $langData) {
                    $langInsert = [];
                    $langInsert['name']          = Str::clear($langData['name']);
                    if(isset($langData['excerpt'])) $langInsert['excerpt']        = $langData['excerpt'];
                    if(isset($langData['content'])) $langInsert['content']        = $langData['content'];
                    $langInsert['language']       = $langKey;
                    $langInsert['object_id']      = $object_id;
                    $langInsert['object_type']    = self::$table;
                    Language::insert($langInsert);
                }
            }
        }

        return apply_filters('after_insert_'.self::$table, $object_id, $insertData, $data, $update ? $oldObject : null);
    }

    static public function delete( $cate_ID = 0 ) {

        $ci =& get_instance();

        $cate_ID = (int)Str::clear($cate_ID);

        if($cate_ID == 0) return false;

        $model  = model(self::$table);

        $category  = static::get($cate_ID);

        if( have_posts($category) ) {

            $ci->data['module']   = self::$table;

            $listID = self::children(['category' => $category, 'andParent' => true]);

            if(!have_posts($listID)) $listID = [$cate_ID];

            if(have_posts($listID)) {

                $args = Qr::set('object_type', 'products')->where('value', 'products_categories')->whereIn('category_id', $listID);

                $model->settable('relationships')->delete(clone $args);

                $args = Qr::set('object_type', self::$table)->whereIn('object_id', $listID);
                //xóa router
                $model->settable('routes')->delete(clone $args);

                //xóa ngôn ngữ
                $model->settable('language')->delete(clone $args);

                //xóa gallery
                foreach ($listID as $key => $id) {
                    Gallery::deleteItemByObject($id, self::$table);
                    Metadata::deleteByMid(self::$table, $id);
                }

                if($model->settable(self::$table)->delete(Qr::set()->whereIn('id', $listID))) {

                    do_action(self::$table.'_delete_success', $listID); //ver 3.0.2

                    foreach ($listID as $id) {
                        CacheHandler::delete('breadcrumb_products_index_'.$id, true);
                    }
                    CacheHandler::delete('breadcrumb_products_detail_', true);

                    //delete menu
                    $model->settable('menu')->delete(clone $args);

                    CacheHandler::delete('menu_items_', true);

                    CacheHandler::delete('products_categories_', true);

                    return $listID;
                }
            }
        }

        return false;
    }

    static public function deleteList( $cate_ID = [] ) {

        $result = [];

        if(!have_posts($cate_ID)) return false;

        foreach ($cate_ID as $key => $id) {
            if(static::delete($id)) $result[] = $id;
        }
        if(have_posts($result)) return $result;

        return false;
    }

    static public function getMeta( $cateID, $key = '', $single = true) {
        return Metadata::get(self::$table, $cateID, $key, $single);
    }

    static public function updateMeta($cateID, $meta_key, $meta_value) {
        return Metadata::update(self::$table, $cateID, $meta_key, $meta_value);
    }

    static public function deleteMeta($cateID, $meta_key = '', $meta_value = '') {
        return Metadata::delete(self::$table, $cateID, $meta_key, $meta_value);
    }

    static public function handleParams($args = null) {
        if(is_array($args)) {
            $args = self::handleParamsArr($args);
            $query = Qr::convert($args);
            if(!empty($args['product_id']) && is_numeric($args['product_id'])) {
                $query->whereIn(self::$table.'.id', Qr::set('object_id', (int)$args['product_id'])->where('object_type', 'products')->where('value', 'products_categories')->from('relationships')->select('relationships.category_id'));
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
                    $query->categoryType('options');
                }
            }
        }

        if(is_numeric($args)) $query = Qr::set(self::$table.'.id', $args);

        if($args instanceof Qr) $query = clone $args;

        if(!Admin::is() && isset($query)) {
            if(!$query->isWhere(self::$table.'.public')) $query->where(self::$table.'.public', 1);
            $query = Language::join($query, self::$table, 'products_categories');
        }

        return (isset($query)) ? $query : null;
    }

    static public function handleParamsArr($args): array {

        if(!have_posts($args)) $args = ['where' => [], 'params' => []];

        $args = array_merge(['where' => [], 'params' => []], $args);

        if(!Admin::is() && isset($args['where'])) {
            $args['where'] = array_merge(['public' => 1], $args['where']);
        }

        return $args;
    }
}

Class Product {

    static string $table = 'products';

    static public function get($args = null) {

        $args = apply_filters('get_product_args', self::handleParams($args));

        if(!$args instanceof Qr) return [];

        $product = model(self::$table)->get($args);

        return apply_filters('get_product', $product, $args);
    }

    static public function gets($args = null) {

        $args = apply_filters('gets_product_args', self::handleParams($args));

        if(!$args instanceof Qr) return new Illuminate\Support\Collection();

        $product = model(self::$table)->gets($args);

        return apply_filters('gets_product', $product, $args );
    }

    static public function getsRelated($object, $args = null) {
        if($args == null) $args = Qr::set();
        if(is_numeric($object)) $object = static::get($object);
        if(!have_posts($object)) return new \Illuminate\Support\Collection();
        $listCategoryID = ProductCategory::getsByProduct($object, Qr::set()->select('id'));
        if(!have_posts($listCategoryID)) return new \Illuminate\Support\Collection();
        $listCategoryIdTemp = [];
        foreach ($listCategoryID as $item) {
            $listCategoryIdTemp[] = $item->id;
        }
        $args->distinct('r.object_id')->where(self::$table.'.id', '<>', $object->id)->where('r.object_type', self::$table);
        $args->join('relationships as r', 'r.object_id', '=', self::$table.'.id');
        $args->whereIn('r.category_id', $listCategoryIdTemp);
        return static::gets($args);
    }

    static public function count($args = []) {

        $args = apply_filters('count_product_args', self::handleParams($args));

        if(!$args instanceof Qr) return 0;

        $product = model(self::$table)->count($args);

        return apply_filters('count_product', $product, $args );
    }

    static public function toSql($args = []): string {
        return model(self::$table)->toSql(self::handleParams($args));
    }

    static public function insert($insertData = []): int|SKD_Error {

        $user = Auth::user();

        $columnsTable = [
            'code'              => ['string'],
            'content'           => ['wysiwyg'],
            'excerpt'           => ['wysiwyg'],
            'seo_title'         => ['string'],
            'seo_description'   => ['string'],
            'seo_keywords'      => ['string'],
            'price'             => ['price', 0],
            'price_sale'        => ['price', 0],
            'status'            => ['string', 'public'],
            'status1'           => ['int', 0],
            'status2'           => ['int', 0],
            'status3'           => ['int', 0],
            'image'             => ['image'],
            'user_created'      => ['int', (have_posts($user)) ? $user->id : 0],
            'user_updated'      => ['int', (have_posts($user)) ? $user->id : 0],
            'public'            => ['int', 1],
            'trash'             => ['int', 0],
            'order'             => ['int', 0],
            'parent_id'         => ['int', 0],
            'brand_id'          => ['int', 0],
            'supplier_id'       => ['int', 0],
            'weight'            => ['int', 0],
            'type'              => ['string', 'product'],
        ];

        $columnsTable = apply_filters('columns_db_'.self::$table, $columnsTable);

        if(!empty($insertData['id'])) {

            $id             = (int) $insertData['id'];

            $update        = true;

            $oldObject = static::get(Qr::set($id)->where('type', '<>', 'null'));

            if (!$oldObject) return new SKD_Error('invalid_id', __('ID sản phẩm không chính xác.'));

            if(empty($insertData['title'])) $insertData['title'] = $oldObject->title;

            $slug = empty($insertData['slug']) ? $oldObject->slug : Str::slug($insertData['slug']);

            if(empty($slug)) $slug = Str::slug($insertData['title']);

            if($slug != $oldObject->slug) {
                $slug = Routes::slug($slug, self::$table, $id);
                CacheHandler::delete('routes-'.$slug);
            }
        }
        else {
            if(!isset($insertData['type']) || $insertData['type'] == 'product') {
                if(empty($insertData['title'])) return new SKD_Error('empty_product_title', __('Không thể cập nhật sản phẩm khi tên tên sản phẩm trống.') );
                $slug = Routes::slug(Str::clear($insertData['title']), self::$table);
            }
            else {
                if(empty($insertData['title'])) $insertData['title'] = '';
                $slug = '';
            }

            $update = false;

        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $title      = trim(Str::clear($insertData['title']));

        if(!$update) {
            if(empty($seo_title)) $seo_title = $title;
            if(empty($seo_description)) $seo_description = Str::clear($excerpt);
        }

        $data = compact(array_merge(['title', 'slug', array_keys($columnsTable)]));

        $data = apply_filters('pre_insert_product_data', $data, $insertData, $update ? $oldObject : null);

        $language   = !empty($insertData['language']) ? $insertData['language'] : [];

        $taxonomies = !empty($insertData['taxonomies']) ? $insertData['taxonomies'] : null;

        $model = model(self::$table);

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set($id));

            $object_id = (int)$id;

            CacheHandler::delete('breadcrumb_products_detail_'.$id, true);

            # [Router]
            $router['object_type']  = self::$table;

            $router['object_id']    = $object_id;

            if(empty(Routes::update(['slug' => $slug], $router))) {
                $router['slug']         = $slug;
                $router['directional']  = 'post';
                $router['controller']   = 'frontend/products/detail/';
                Routes::insert($router);
            }

            # [LANGUAGE]
            if(have_posts($language)) {
                $objectLanguage = Language::gets(Qr::set('object_id', $id)->where('object_type', self::$table));
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
                        $insLangData['object_type'] = self::$table;
                        if(Language::insert($insLangData)) unset($language[$key]);
                    }
                }
            }

            # [TAXONOMY]
            if(isset($taxonomies)) {

                $model->settable('relationships');

                if($taxonomies != null && have_posts($taxonomies)) {

                    $temp = $model->gets(Qr::set('object_id',$object_id)->where('object_type',self::$table)->select('value')->groupBy('value'));

                    $taxonomy_cate_type = [];

                    foreach ($temp as $temp_value) {
                        $taxonomy_cate_type[$temp_value->value] = $temp_value->value;
                    }

                    $taxonomy['object_id'] 		= $object_id;

                    $taxonomy['object_type'] 	= self::$table;

                    foreach ($taxonomies as $taxonomy_key => $taxonomy_value) {

                        if(isset($taxonomy_cate_type[$taxonomy_key])) unset($taxonomy_cate_type[$taxonomy_key]);

                        $taxonomy['value'] 		= $taxonomy_key;

                        $temp_old = $model->gets(Qr::set('object_id',$object_id)->where('object_type',self::$table)->where('value', $taxonomy_key)->select('id', 'category_id'));
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
                            $model->delete(Qr::set('object_id', $object_id)->where('object_type',self::$table)->where('value', $taxonomy_key));
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
                                    ->where('object_type', self::$table)
                                    ->where('value', $taxonomy_key)
                                    ->whereIn('category_id', $taxonomies_old));
                            }
                        }
                    }

                    if( have_posts($taxonomy_cate_type) ) {
                        foreach ($taxonomy_cate_type as $ta_cate_type) {
                            $model->delete(Qr::set('object_id', $object_id)->where('object_type', self::$table)->where('value', $ta_cate_type));
                        }
                    }
                }
                else {
                    $model->delete(Qr::set('object_id', $object_id)->where('object_type', self::$table));
                }
            }
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $object_id = $model->add($data);

            # [Router]
            $router['slug']         = $slug;
            $router['object_type']  = self::$table;;
            $router['directional']  = self::$table;;
            $router['controller']   = 'frontend/products/detail/';
            $router['object_id']    = $object_id;
            Routes::insert($router);

            # [LANGUAGE]
            if(have_posts($language)) {

                foreach ($language as $langKey => $langData) {
                    $langInsert = [];
                    $langInsert['title']          = Str::clear($langData['title']);
                    $langInsert['excerpt']        = $langData['excerpt'];
                    $langInsert['content']        = $langData['content'];
                    $langInsert['language']       = $langKey;
                    $langInsert['object_id']      = $object_id;
                    $langInsert['object_type']    = self::$table;
                    Language::insert($langInsert);
                }
            }

            # [TAXONOMY]
            if($taxonomies != null && have_posts($taxonomies) ) {

                $model->settable('relationships');

                $taxonomy['object_id']      = $object_id;

                $taxonomy['object_type']    = self::$table;

                foreach ($taxonomies as $taxonomy_key => $taxonomy_value ) {
                    $taxonomy['value']      = $taxonomy_key;
                    foreach ($taxonomy_value as $taxonomy_id) {
                        $taxonomy['category_id'] = $taxonomy_id;
                        $model->add($taxonomy);
                    }
                }
            }
        }

        return $object_id;
    }

    static public function delete($productID = 0, $trash = false ) {

        $ci =& get_instance();

        $productID = (int)Str::clear($productID);

        if( $productID == 0 ) return false;

        $model = model(self::$table);

        $product  = static::get(Qr::set('id', $productID)->where('type', '<>', 'trash')->where('trash', '<>', -1));

        if(have_posts($product)) {

            $ci->data['module']   = self::$table;

            //nếu bỏ vào thùng rác
            if($trash) {

                /**
                 * @since 2.5.0 add action delete_products_trash
                 */
                do_action('delete_products_trash', $productID );

                if($model->update(['trash' => 1], Qr::set($productID))) {
                    do_action('delete_products_trash_success', $productID );
                    return [$productID];
                }

                return false;
            }
            /**
             * @since 2.5.0 add action delete_product
             */
            do_action('delete_product', $productID );

            if($model->delete(Qr::set($productID))) {

                do_action('delete_product_success', $productID );

                $args = Qr::set('object_id', $productID)->where('object_type', 'products');

                //delete language
                $model->settable('language')->delete(clone $args);

                //delete router
                $model->settable('routes')->delete(clone $args);

                //delete gallerys
                Gallery::deleteItemByObject($productID, 'products');

                Metadata::deleteByMid('products', $productID);

                //delete menu
                $model->settable('menu')->delete(clone $args);

                //xóa liên kết
                $model->settable('relationships')->delete(clone $args);

                if($product->type != 'variations') {

                    //Xóa sản phẩm biến thể
                    $variations = Product::gets(Qr::set('parent_id', $productID)->where('type', 'variations'));

                    foreach ($variations as $variation) {
                        static::delete($variation->id);
                    }
                }

                CacheHandler::delete('breadcrumb_products_detail_'.$productID, true);

                return [$productID];
            }
        }

        return false;
    }

    static public function deleteList( $productID = [], $trash = false ) {

        if(have_posts($productID)) {

            $model = model(self::$table);

            if($trash) {

                do_action('delete_products_list_trash', $productID);

                if($model->update(['trash' => 1], Qr::set()->whereIn('id', $productID))) {

                    do_action('delete_products_list_trash_success', $productID );

                    return $productID;
                }

                return false;
            }

            $products = static::gets(Qr::set()->whereIn('id', $productID));

            if($model->delete(Qr::set()->whereIn('id', $productID))) {

                $args = Qr::set('object_type', self::$table)->whereIn('object_id', $productID);

                do_action('delete_products_list_success', $productID );

                //delete language
                $model->settable('language')->delete(clone $args);

                //delete router
                $model->settable('routes')->delete(clone $args);

                //delete router
                foreach ($products as $product) {

                    Gallery::deleteItemByObject($product->id, self::$table);

                    Metadata::deleteByMid(self::$table, $product->id);

                    CacheHandler::delete('breadcrumb_products_detail_'.$product->id, true);
                }

                //delete menu
                $model->settable('menu')->delete(clone $args);

                //xóa liên kết
                $model->settable('relationships')->delete(clone $args);

                //Xóa sản phẩm biến thể
                foreach ($products as $product) {
                    if($product->type == 'variations') continue;
                    $variations = static::gets(Qr::set('parent_id', $product->id)->where('type', 'variations'));
                    foreach ($variations as $variation) {
                        static::delete($variation->id);
                    }
                }

                return $productID;
            }
        }

        return false;
    }

    static public function update($update, $args) {

        if(!have_posts($update)) {
            return new SKD_Error('invalid_update', __('Không có trường dữ liệu nào được cập nhật.'));
        }

        if(!have_posts($args)) {
            return new SKD_Error('invalid_update', __('Không có điều kiện cập nhật.'));
        }

        if(is_array($args)) $args = Qr::convert($args);

        return apply_filters('update_products', model(self::$table)->update($update, $args, 'products'), $update, $args);
    }

    static public function restore($args): int {
        if(is_array($args)) $args = Qr::convert($args);
        return model(self::$table)->update(['trash' => 0], $args, 'products');
    }

    static public function getMeta($product_id, $key = '', $single = true) {
        return Metadata::get(self::$table, $product_id, $key, $single);
    }

    static public function updateMeta($product_id, $meta_key, $meta_value) {
        return Metadata::update(self::$table, $product_id, $meta_key, $meta_value);
    }

    static public function deleteMeta($product_id, $meta_key = '', $meta_value = '') {
        return Metadata::delete(self::$table, $product_id, $meta_key, $meta_value);
    }

    static public function handleParams($args) {

        $query = ($args instanceof Qr) ? clone $args : Qr::set();

        if($args instanceof Qr && !empty($args->related)) {
            $id = $args->related;
            $query->distinct('r.object_id')->where(self::$table.'.id', '<>', $id)->where('r2.object_type', self::$table);
            $query->join('relationships as r', 'r.object_id', '=', self::$table.'.id');
            $query->join('relationships as r2', 'r2.object_id', '=', self::$table.'.id');
            $query->where('r.category_id', 'r2.category_id');
        }

        if(is_array($args)) {

            $args = self::handleParamsArr($args);

            $query = Qr::convert($args, self::$table.'_metadata');

            if(!$query) return $query;

            if(isset($args['related'])) {
                $id = $args['related'];
                $query->distinct('r.object_id')->where(self::$table.'.id', '<>', $id)->where('r2.object_type', self::$table);
                $query->join('relationships as r', 'r.object_id', '=', self::$table.'.id');
                $query->join('relationships as r2', 'r2.object_id', '=', self::$table.'.id');
                $query->where('r.category_id', 'r2.category_id');
            }

            if(isset($args['attr_query']) && have_posts($args['attr_query'])) {

                $table  = CLE_PREFIX.'relationships';

                $sql = 'SELECT SQL_CALC_FOUND_ROWS `'.$table.'`.`object_id` FROM `'.$table.'`';

                $attrQuery  = $args['attr_query'];

                foreach ($attrQuery as $key => $attr) {
                    $sql .= 'INNER JOIN `'.$table.'` AS attr'.$key.' ON ( `'.$table.'`.id = attr'.$key.'.id ) ';
                }

                $sql .= '(';

                foreach ($attrQuery as $key => $attr) {
                    $attrkey = 'attr'.$key;
                    $sql .= '(';
                    $sql .= $attrkey.'.`object_type` = \'attributes\' AND '.$attrkey.'.`category_id` = \'attribute_op_'.$attr['group'].'\' AND '.$attrkey.'.`value` IN ('.implode(',',$attr['attribute']).')';
                    $sql .= ') AND ';
                }

                $sql = trim($sql,' AND ');

                $sql .= ')';

                $query->whereRaw(self::$table.'.id in ('.$sql.')');
            }
        }

        if(is_numeric($args)) $query = Qr::set(self::$table.'.id', $args);

        if(!Admin::is()) {
            if(!$query->isWhere(self::$table.'.public') && !$query->isWhere('public')) $query->where(self::$table.'.public', 1);
            if(!$query->isWhere(self::$table.'.trash') && !$query->isWhere('trash')) $query->where(self::$table.'.trash', 0);
            $query = Language::join($query, self::$table);
        }

        if(!$query->isWhere(self::$table.'.type') && !$query->isWhere('type')) {
            $query->where(self::$table.'.type', 'product');
        }

        if(!empty($query->category['object'])) {

            $listSubCategoryID = [];

            if(is_numeric($query->category['object']) || is_object($query->category['object'])) {
                if(is_numeric($query->category['object'])) {
                    $listSubCategoryID = ProductCategory::children(['andParent' => true, 'id' => $query->category['object']]);
                }
                if(!empty($query->category['object']->id)) {
                    $listSubCategoryID = ProductCategory::children(['andParent' => true, 'category' => $query->category['object']]);
                }
            }
            else if(is_array($query->category['object'])) {
                foreach ($query->category['object'] as $object) {
                    $subArg = ['andParent' => true];
                    if(is_numeric($object)) {
                        $subArg['id'] = $object;
                    }
                    else {
                        $subArg['category'] = $object;
                    }
                    $children = ProductCategory::children($subArg);
                    $listSubCategoryID = Arr::collapse([$children, $listSubCategoryID]);
                }
            }

            if(!have_posts($listSubCategoryID)) return [];

            $query->whereIn(self::$table.'.id',
                Qr::set('object_type', self::$table)->select('object_id')->distinct()->from('relationships')->whereIn('category_id', $listSubCategoryID)
            );
        }

        return $query;
    }

    static public function handleParamsArr($args): array {

        if(!have_posts($args)) $args = ['where' => [], 'params' => []];

        $args = array_merge(['where' => [], 'params' => []], $args);

        if(!Admin::is() && isset($args['where'])) {
            $args['where'] = array_merge(['public'=> 1, 'trash' => 0] , $args['where']);
        }

        if(!empty($args['where_category']) && is_numeric($args['where_category'])) {
            $args['where_category'] = ProductCategory::get($args['where_category']);
        }

        if(is_array($args) && (!isset($args['where']['type']) && !isset($args['where']['type <>']))) {
            $args['where']['type'] = 'product';
        }

        return $args;
    }
}
