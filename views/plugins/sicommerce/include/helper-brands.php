<?php
Class Brands extends Model {

    static string $table = 'brands';

    static public function insert($insertData = []) {

        $user = Auth::user();

        $columnsTable = [
            'excerpt'           => ['wysiwyg'],
            'seo_title'         => ['string'],
            'seo_description'   => ['string'],
            'seo_keywords'      => ['string'],
            'image'             => ['image'],
            'user_created'      => ['int', (have_posts($user)) ? $user->id : 0],
            'user_updated'      => ['int', (have_posts($user)) ? $user->id : 0],
            'order'             => ['int', 0],
        ];

        $columnsTable = apply_filters('columns_db_'.self::$table, $columnsTable);

        if (!empty($insertData['id'])) {

            $id             = (int) $insertData['id'];

            $update        = true;

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_id', __('ID thương hiệu không chính xác.'));

            if(empty($insertData['name'])) $insertData['name'] = $oldObject->name;

            $slug = empty($insertData['slug']) ? $oldObject->slug : Str::slug($insertData['slug']);

            if(empty($slug)) $slug = Str::slug($insertData['name']);

            if($slug != $oldObject->slug) {

                $slug = Routes::slug($slug, self::$table, $id);

                CacheHandler::delete('routes-'.$slug);
            }
        }
        else {

            if(empty($insertData['name'])) return new SKD_Error('empty_name', __('Không thể cập nhật thương hiệu khi tiêu đề trống.') );

            $update = false;

            $slug = Routes::slug(Str::clear($insertData['name']), self::$table);
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $language = Language::default();

        if(!empty($insertData[$language]['name'])) {
            $insertData['name'] = Str::clear($insertData[$language]['name']);
        }

        if(!empty($insertData[$language]['excerpt'])) {
            $insertData['excerpt'] = $insertData[$language]['excerpt'];
        }

        if(empty($insertData['language'])) {
            foreach (Language::listKey() as $key) {
                if( $key != Language::default()) {
                    if(!empty($insertData[$key]['name']))       $insertData['language'][$key] = $insertData[$key];
                    if(!empty($insertData[$key]['excerpt']))    $insertData['language'][$key] = $insertData[$key];
                }
            }
        }

        $name      = Str::clear($insertData['name']);

        $pre_name  = apply_filters( 'pre_brands_name', $name );

        $name      = trim( $pre_name );

        if(!$update) {
            if(empty($seo_title)) $seo_title = $name;
            if(empty($seo_description)) $seo_description = Str::clear($excerpt);
        }

        $data = compact(array_merge(['name', 'slug', array_keys($columnsTable)]));

        $data = apply_filters('pre_insert_brands_data', $data, $insertData, $update ? $oldObject : null);

        $language   = !empty($insertData['language']) ? $insertData['language'] : [];

        $model = model(self::$table);

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update( $data, Qr::set('id', $id));

            $object_id = (int) $id;

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

        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $object_id = $model->add( $data );

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
                    $langInsert['excerpt']        = (isset($langData['excerpt'])) ? $langData['excerpt'] : '';
                    $langInsert['content']        = (isset($langData['content'])) ? $langData['content'] : '';
                    $langInsert['language']       = $langKey;
                    $langInsert['object_id']      = $object_id;
                    $langInsert['object_type']    = self::$table;
                    Language::insert($langInsert);
                }
            }
        }

        return apply_filters('after_insert_'.self::$table, $object_id, $insertData, $data, $update ? $oldObject : null);
    }

    static public function delete( $brandsID = 0) {

        $ci =& get_instance();

        $brandsID = (int)Str::clear($brandsID);

        if($brandsID == 0) return false;

        $model = model(self::$table);

        $brands  = static::get($brandsID);

        if(have_posts($brands)) {

            $ci->data['module']   = self::$table;

            do_action('delete_'.self::$table, $brandsID);

            if($model->delete(Qr::set('id', $brandsID))) {

                do_action('delete_'.self::$table.'_success', $brandsID );

                $args = Qr::set('object_id', $brandsID)->where('object_type', self::$table);

                //delete language
                $model->settable('language')->delete(clone $args);
                //delete router
                $model->settable('routes')->delete(clone $args);

                //delete gallerys
                Gallery::deleteItemByObject($brandsID, self::$table);

                Metadata::deleteByMid(self::$table, $brandsID);

                //delete menu
                $model->settable('menu')->delete(clone $args);
                CacheHandler::delete('menu_items_', true);

                //xóa liên kết
                $model->settable('relationships')->delete(clone $args);

                return [$brandsID];
            }
        }

        return false;
    }

    static public function deleteList( $brandsID = []) {

        if(have_posts($brandsID)) {

            $model      = model(self::$table);

            $brands = static::gets(Qr::set()->whereIn('id', $brandsID));

            if($model->delete(Qr::set()->whereIn('id', $brandsID))) {

                $args = Qr::set('object_type', self::$table)->whereIn('object_id', $brandsID);

                do_action('delete_brands_list_trash_success', $brandsID);

                //delete language
                $model->settable('language')->delete(clone $args);

                //delete router
                $model->settable('routes')->delete(clone $args);

                //delete router
                foreach ($brands as $brand) {
                    Gallery::deleteItemByObject($brand->id, self::$table);
                    Metadata::deleteByMid(self::$table, $brand->id);
                }

                //delete menu
                $model->settable('menu')->delete(clone $args);
                CacheHandler::delete('menu_items_', true);

                //xóa liên kết
                $model->settable('relationships')->delete(clone $args);

                return $brandsID;
            }
        }

        return false;
    }

    static public function getsOption($args = []) {

        $brands = static::gets($args);

        $options = ['Chọn nhà sản xuất'];

        foreach ($brands as $brand) {
            $options[$brand->id] = $brand->name;
        }
        return apply_filters( 'gets_brands_option', $options );
    }
}