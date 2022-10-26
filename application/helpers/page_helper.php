<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pages extends Model{
    static string $table = 'page';
    static function insert($insertData = []) {

        $user = Auth::user();

        $columnsTable = [
            'content'           => ['wysiwyg'],
            'excerpt'           => ['wysiwyg'],
            'seo_title'         => ['string'],
            'seo_description'   => ['string'],
            'seo_keywords'      => ['string'],
            'image'             => ['image'],
            'user_created'      => ['int', (have_posts($user)) ? $user->id : 0],
            'user_updated'      => ['int', (have_posts($user)) ? $user->id : 0],
        ];

        $columnsTable = apply_filters('columns_db_'.self::$table, $columnsTable);

        if(!empty($insertData['id'])) {

            $id             = (int) $insertData['id'];

            $update        = true;

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_id', __('ID trang không chính xác.'));

            if(empty($insertData['title'])) $insertData['title'] = $oldObject->title;

            $slug = empty($insertData['slug']) ? $oldObject->slug : Str::slug($insertData['slug']);

            if($slug != $oldObject->slug) {
                $slug = Routes::slug($slug, self::$table , $id);
                CacheHandler::delete('routes-'.$oldObject->slug);
                CacheHandler::delete('routes-'.$slug);
            }
        }
        else {

            if(empty($insertData['title'])) return new SKD_Error('empty_title', __('Không thể cập nhật trang khi tiêu đề trống.', 'empty_page_title') );

            $update = false;

            $slug = Routes::slug(Str::clear($insertData['title']), self::$table);
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $title      = Str::clear($insertData['title'] );

        $pre_title  = apply_filters('pre_page_title', $title );

        $title      = trim( $pre_title );

        if(!$update) {

            if(empty($seo_title)) $seo_title = $title;

            if(empty($seo_description)) $seo_description = Str::clear($excerpt);
        }

        $data = compact(array_merge(['title', 'slug', array_keys($columnsTable)]));

        $data = apply_filters( 'pre_insert_'.self::$table.'_data', $data, $insertData, $update ? $oldObject : null );

        $language   = !empty($insertData['language']) ? $insertData['language'] : [];

        $model = model(self::$table);

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set($id));

            # [Router]
            $router['object_type']  = 'page';
            $router['object_id']    = $id;

            if(empty(Routes::update(['slug' => $slug], $router))) {
                $router['slug']         = $slug;
                $router['directional']  = 'page';
                $router['controller']   = 'frontend/page/detail/';
                Routes::insert($router);
            }

            # [LANGUAGE]
            if(have_posts($language)) {
                $objectLanguage = Language::gets(Qr::set('object_id', $id)->where('object_type', 'page'));
                if(!empty($objectLanguage)) {
                    foreach ($objectLanguage as $objLang) {
                        foreach ($language as $key => $insLangData) {
                            if($key == $objLang->language) {
                                Language::update($insLangData, Qr::set($objLang->id));
                                unset($language[$key]);
                            }
                        }
                    }
                }
                if(!empty($language)) {
                    foreach ($language as $key => $insLangData) {
                        $insLangData['language'] 	= $key;
                        $insLangData['object_id']  	= $id;
                        $insLangData['object_type'] = 'page';
                        if(Language::insert($insLangData)) unset($language[$key]);
                    }
                }
            }

            CacheHandler::delete('page_'.md5($id), true);
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $id = $model->add($data);

            # [Router]
            $router['slug']         = $slug;
            $router['object_type']  = self::$table;
            $router['directional']  = self::$table;
            $router['controller']   = 'frontend/page/detail/';
            $router['object_id']    = $id;
            Routes::insert($router);

            # [LANGUAGE]
            if(have_posts($language)) {

                foreach ($language as $langKey => $langData) {
                    $langInsert = [];
                    $langInsert['title']          = Str::clear($langData['title']);
                    $langInsert['excerpt']        = (isset($langData['excerpt'])) ? $langData['excerpt'] : '';
                    $langInsert['content']        = (isset($langData['content'])) ? $langData['content'] : '';
                    $langInsert['language']       = $langKey;
                    $langInsert['object_id']      = $id;
                    $langInsert['object_type']    = self::$table;
                    Language::insert($langInsert);
                }
            }
        }

        $model->settable(self::$table);

        return apply_filters('after_insert_page', $id, $insertData, $data, $update ? $oldObject : null);
    }
    static function update($update, $args) {
        if(!have_posts($update)) return new SKD_Error('invalid_update', __('Không có trường dữ liệu nào được cập nhật.'));
        if(!have_posts($args)) return new SKD_Error( 'invalid_update', __('Không có điều kiện cập nhật.'));
        if(is_array($args)) $args = Qr::convert($args);
        CacheHandler::delete('page_', true);
        $update['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
        return apply_filters('update_'.static::$table, model(static::$table)->update($update, $args), $update, $args);
    }
    static function restore($args) {
        CacheHandler::delete('page_', true);
        return model(self::$table)->update(['trash' => 0], $args);
    }
    static function delete($pageID = 0, $trash = false) {

        $ci =& get_instance();

        $pageID = (int)Str::clear($pageID);

        if( $pageID == 0 ) return false;

        $model = model(self::$table);

        $ci->data['module']   = 'page';

        $page  = static::get(Qr::set('id', $pageID)->where('trash', '<>', 3));

        if(have_posts($page)) {
            //nếu bỏ vào thùng rác
            if($trash) {

                do_action('delete_page_trash', $pageID );

                if($model->update(['trash' => 1], Qr::set('id', $pageID))) {

                    CacheHandler::delete('page_'.md5($page->id), true);

                    CacheHandler::delete('page_'.md5($page->slug), true);

                    //delete menu
                    $menuItems = ThemeMenu::getsItem(Qr::set('object_id', $pageID)->where('object_type', 'page'));
                    $model->settable('menu')->delete(Qr::set('object_id', $pageID)->where('object_type', 'page'));
                    if(have_posts($menuItems)) {
                        foreach ($menuItems as $menuItem) {
                            Metadata::deleteByMid('menu', $menuItem->id);
                            CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                        }
                    }

                    return [$pageID];
                }

                return false;
            }

            do_action('delete_page', $pageID );

            if($model->delete(Qr::set($pageID))) {

                do_action('delete_page_success', $pageID);

                $qr = Qr::set('object_id', $pageID)->where('object_type', 'page');

                //delete language
                $model->settable('language')->delete(clone $qr);

                //delete router
                $model->settable('routes')->delete(clone $qr);

                //delete gallerys
                delete_gallery_by_object($pageID, 'page');

                Metadata::deleteByMid('page', $pageID);

                //delete menu
                $menuItems = ThemeMenu::getsItem(clone $qr);
                $model->settable('menu')->delete(clone $qr);
                if(have_posts($menuItems)) {
                    foreach ($menuItems as $menuItem) {
                        Metadata::deleteByMid('menu', $menuItem->id);
                        CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                    }
                }

                CacheHandler::delete( 'page_'.md5($page->id), true );
                CacheHandler::delete( 'page_'.md5($page->slug), true );

                return [$pageID];
            }
        }

        return false;
    }
    static function deleteList($pageID = [], $trash = false) {

        if(have_posts($pageID)) {

            $model  = model(self::$table);

            if($trash) {

                do_action('delete_page_list_trash', $pageID );

                if($model->update(['trash' => 1], Qr::set()->whereIn('id', $pageID))) {

                    CacheHandler::delete('page_', true );
                    //delete menu
                    $menuItems = ThemeMenu::getsItem(Qr::set('object_type', 'page')->whereIn('object_id', $pageID));
                    $model->settable('menu');
                    $model->delete(Qr::set('object_type', 'page')->whereIn('object_id', $pageID));
                    if(have_posts($menuItems)) {
                        foreach ($menuItems as $menuItem) {
                            Metadata::deleteByMid('menu', $menuItem->id);
                            CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                        }
                    }

                    return $pageID;
                }

                return false;
            }

            if($model->delete(Qr::set()->whereIn('id', $pageID))) {

                do_action('delete_page_list_trash_success', $pageID);

                $qr = Qr::set('object_type', 'page')->whereIn('object_id', $pageID);

                //delete language
                $model->settable('language')->delete(clone $qr);

                //delete router
                $model->settable('routes')->delete(clone $qr);

                //delete gallerys
                delete_gallery_by_object($pageID, 'page');

                foreach ($pageID as $key => $id) {
                    Metadata::deleteByMid('page', $id);
                }

                //delete menu
                $menuItems = ThemeMenu::getsItem(clone $qr);
                $model->settable('menu')->delete(clone $qr);
                if(have_posts($menuItems)) {
                    foreach ($menuItems as $menuItem) {
                        Metadata::deleteByMid('menu', $menuItem->id);
                        CacheHandler::delete('menu_items_'.$menuItem->menu_id, true);
                    }
                }

                CacheHandler::delete( 'page_', true );

                return $pageID;
            }
        }

        return false;
    }
    static function handleParams($args = null) {
        if(is_array($args)) {
            $args = self::handleParamsArr($args);
            $args = Qr::convert($args);
            if(!$args) return $args;
        }

        if(is_numeric($args)) $args = Qr::set('id', $args);
        if(!Admin::is()) {
            if(!$args->isWhere('page.public')) $args->where('page.public', 1);
            if(!$args->isWhere('page.trash')) $args->where('page.trash', 0);
            $args = Language::join($args, self::$table);
        }
        return $args;
    }
    static function handleParamsArr($args): array {
        if(!have_posts($args)) $args = ['where' => [], 'params' => []];
        $args = array_merge(['where' => [], 'params' => []], $args);
        if(!Admin::is() && isset($args['where'])) {
            $args['where'] = array_merge(['public'=> 1, 'trash' => 0] , $args['where']);
        }
        return $args;
    }
}