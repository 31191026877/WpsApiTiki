<?php
Class Suppliers {

    static string $table = 'suppliers';

    static public function get($args = []) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        return apply_filters('get_'.self::$table, model(self::$table)->get($args), $args);
    }

    static public function getBy($field, $value) {
        return apply_filters('get_'.self::$table.'_by', static::get(Qr::set(Str::clear($field), Str::clear($value))), $field, $value );
    }

    static public function gets($args = []) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        return apply_filters('gets_'.self::$table, model(self::$table)->gets($args), $args);
    }

    static public function getsBy( $field, $value, $params = [] ) {
        return apply_filters('gets_'.self::$table.'_by', static::gets(Qr::set(Str::clear($field), Str::clear($value))), $field, $value );
    }

    static public function count($args = []) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return 0;
        return apply_filters('count_'.self::$table, model(self::$table)->count($args), $args);
    }

    static public function insert($insertData = [] ) {

        $user = Auth::user();

        $columnsTable = [
            'firstname'         => ['string'],
            'lastname'          => ['string'],
            'email'             => ['string'],
            'phone'             => ['string'],
            'address'           => ['string'],
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

            if (!$oldObject) return new SKD_Error('invalid_id', __('ID nhà sản xuất không chính xác.'));

            if(empty($insertData['name'])) $insertData['name'] = $oldObject->name;

            $slug = empty($insertData['slug']) ? $oldObject->slug : Str::slug($insertData['slug']);

            if(empty($slug)) $slug = Str::slug($insertData['name']);

            if($slug != $oldObject->slug) {

                $slug = Routes::slug($slug, self::$table, $id);

                CacheHandler::delete('routes-'.$slug);
            }
        }
        else {

            if(empty($insertData['name'])) return new SKD_Error('empty_name', __('Không thể cập nhật nhà sản xuất khi tiêu đề trống.') );

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
                if($key != Language::default()) {
                    if(!empty($insertData[$key]['name']))       $insertData['language'][$key] = $insertData[$key];
                    if(!empty($insertData[$key]['excerpt']))    $insertData['language'][$key] = $insertData[$key];
                }
            }
        }

        $name      = Str::clear($insertData['name']);

        if(!$update) {
            if(empty($seo_title)) $seo_title = $name;
        }

        $data = compact(array_merge(['name', 'slug', array_keys($columnsTable)]));

        $data = apply_filters('pre_insert_'.self::$table.'_data', $data, $insertData, $update ? $oldObject : null);

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
                    $langInsert['excerpt']        = $langData['excerpt'];
                    $langInsert['content']        = $langData['content'];
                    $langInsert['language']       = $langKey;
                    $langInsert['object_id']      = $object_id;
                    $langInsert['object_type']    = self::$table;
                    Language::insert($langInsert);
                }
            }
        }

        return apply_filters('after_insert_'.self::$table, $object_id, $insertData, $data, $update ? $oldObject : null);
    }

    static public function delete($objectID = 0) {

        $ci =& get_instance();

        $objectID = (int)Str::clear($objectID);

        if($objectID == 0) return false;

        $model = model(self::$table);

        $brands  = static::get($objectID);

        if(have_posts($brands)) {

            $ci->data['module']   = self::$table;

            do_action('delete_'.self::$table, $objectID);

            if($model->delete(Qr::set('id', $objectID))) {

                do_action('delete_'.self::$table.'_success', $objectID );

                $args = Qr::set('object_id', $objectID)->where('object_type', self::$table);

                //delete language
                $model->settable('language')->delete(clone $args);
                //delete router
                $model->settable('routes')->delete(clone $args);

                //delete gallerys
                Gallery::deleteItemByObject($objectID, self::$table);

                Metadata::deleteByMid(self::$table, $objectID);

                //delete menu
                $model->settable('menu')->delete(clone $args);
                CacheHandler::delete('menu_items_', true);

                //xóa liên kết
                $model->settable('relationships')->delete(clone $args);

                return [$objectID];
            }
        }

        return false;
    }

    static public function deleteList($objectID = []) {

        if(have_posts($objectID)) {

            $model      = model(self::$table);

            $brands = static::gets(Qr::set()->whereIn('id', $objectID));

            if($model->delete(Qr::set()->whereIn('id', $objectID))) {

                $args = Qr::set('object_type', self::$table)->whereIn('object_id', $objectID);

                do_action('delete_suppliers_list_trash_success', $objectID);

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

                return $objectID;
            }
        }

        return false;
    }

    static public function getMeta( $suppliers_id, $key = '', $single = true) {
        return Metadata::get(self::$table, $suppliers_id, $key, $single);
    }

    static public function updateMeta($suppliers_id, $meta_key, $meta_value) {
        return Metadata::update(self::$table, $suppliers_id, $meta_key, $meta_value);
    }

    static public function deleteMeta($suppliers_id, $meta_key = '', $meta_value = '') {
        return Metadata::delete(self::$table, $suppliers_id, $meta_key, $meta_value);
    }

    static public function getsOption($args = []) {

        $suppliers = static::gets($args);

        $options = ['Chọn nhà sản xuất'];

        foreach ($suppliers as $key => $brand) {
            $options[$brand->id] = $brand->name;
        }
        return apply_filters( 'gets_suppliers_option', $options );
    }

    static public function handleParams($args) {
        $query = Qr::set();
        if(is_array($args)) {
            $query = Qr::convert($args);
            if(!$query) return $query;
        }
        if(is_numeric($args)) $query = Qr::set('id', $args);
        if($args instanceof Qr) $query = clone $args;
        return $query;
    }
}