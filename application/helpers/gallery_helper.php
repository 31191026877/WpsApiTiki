<?php
Class Gallery {
    static string $table = 'galleries';
    static public function get($args = null) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return [];
        return model('group')->get($args);
    }
    static public function gets($args = null) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return [];
        return model('group')->gets($args);
    }
    static public function toSql($args = null): string {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return '';
        return model('group')->toSql($args);
    }
    static public function insert($insertData = []) {

        $columnsTable = [
            'name'         => ['string'],
            'object_type'  => ['string', 'gallery'],
            'options'      => ['string'],
            'order'        => ['int', 0],
            'public'       => ['int', 1],
        ];

        $columnsTable = apply_filters('columns_db_gallery', $columnsTable);

        if(!empty($insertData['id'])) {

            $id             = (int) $insertData['id'];

            $update        = true;

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_page_id', __('ID thư viện không chính xác.'));
        }
        else {

            if(empty($insertData['name'])) return new SKD_Error('empty_gallery_name', __('Không thể thêm thư viện khi tên thư viện để trống.', 'empty_gallery_name'));

            $update = false;
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        if(is_array($options)) $options = serialize($options);

        $data = compact(array_keys($columnsTable));

        $data = apply_filters('pre_insert_gallery_data', $data, $insertData, $update ? $oldObject : null);

        $model = model('group');

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set('id', $id));
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $id = $model->add($data);
        }

        return apply_filters('after_insert_post', $id, $insertData, $data, $update ? $oldObject : null);
    }
    static public function delete($id): bool {

        if(empty($id)) return false;

        $model = model('group');

        $items = static::getsItem($id);

        if($model->delete(Qr::set('id', $id))) {

            if(have_posts($items)) {
                foreach ($items as $item) {
                    static::deleteItem($item->id);
                }
            }

            return true;
        }

        return false;
    }
    static public function addOption ($module = '', $args = [], $position = 1): bool {

        if(empty($module) || !have_posts($args)) return false;

        $ci =& get_instance();

        $options = $ci->galleryOptions;

        if($module == 'gallery') {
            if(isset($options[$module])) {
                $options[$module][] = $args;
            }
            else {
                if(!isset($options) || !have_posts($options))  $options = [];
                $options = [$module => array( $args)];
            }
        }
        else {

            if(empty($args['field']) && empty($args['name'])) return false;

            $id = (!empty($args['name'])) ? Str::clear($args['name']) : Str::clear($args['field']);

            if($module == 'post' || $module == 'post_categories') {

                if(empty($args['object_type'])) return false;

                $object_type = Str::clear($args['object_type']); unset($args['object_type']);

                $options['object'][$module][$object_type][$id] = $args;
            }
            else {

                $options['object'][$module][$id] = $args;
            }

        }

        $ci->galleryOptions = $options;

        return true;
    }
    static public function getOption ($module = '', $object_type = '') {

        $galleryOptions = get_instance()->galleryOptions;

        if($module == 'gallery') return (isset($galleryOptions['gallery'])) ? $galleryOptions['gallery'] : false;

        if($module == 'object') return (isset($galleryOptions['object'])) ? $galleryOptions['object'] : false;

        if($module == 'post' || $module == 'post_categories') {

            return (isset($galleryOptions['object'][$module][$object_type])) ? $galleryOptions['object'][$module][$object_type] : false;
        }
        else {

            return (isset($galleryOptions['object'][$module])) ? $galleryOptions['object'][$module] : false;
        }

        return false;
    }
    static public function getItem($args = []) {
        $args = self::handleParamsItem($args);
        if(!$args instanceof Qr) return [];
        $cacheID = 'gallery_'.md5(serialize(Qr::clear($args)));
        if(CacheHandler::has($cacheID) !== false) return apply_filters('get_gallery_item', CacheHandler::get($cacheID));
        $object = model(self::$table)->get($args);
        if(have_posts($object) && isset($object->id)) {
            $meta = static::getItemMeta($object->id, '', false);
            $object = (object)array_merge((array)$meta, (array)$object);
        }
        if(have_posts($object)) CacheHandler::save($cacheID, $object);
        return apply_filters('get_gallery_item', $object, $args);
    }
    static public function getsItem($args = []) {
        if(is_numeric($args)) $args = Qr::set('group_id', (int)$args);
        $args = self::handleParamsItem($args);
        if(!$args instanceof Qr) return [];
        $cacheID = 'gallery_'.md5(serialize(Qr::clear($args))).'_s';
        if(CacheHandler::has($cacheID) !== false) return apply_filters('gets_gallery_item', CacheHandler::get($cacheID));
        $object = model(self::$table)->gets($args);
        if(have_posts($object)) CacheHandler::save($cacheID, $object);
        return apply_filters('gets_gallery_item', $object, $args);
    }
    static public function countItem($args = []) {
        if(is_numeric($args)) $args = Qr::set('group_id', (int)$args);
        $args = self::handleParamsItem($args);
        if(!$args instanceof Qr) return 0;
        $cacheID = 'gallery_count_'.md5(serialize(Qr::clear($args)));
        if(CacheHandler::has($cacheID) !== false) return apply_filters('count_gallery_item', CacheHandler::get($cacheID));
        $object = model(self::$table)->count($args);
        if(have_posts($object)) CacheHandler::save($cacheID, $object);
        return apply_filters('count_gallery_item', $object, $args);
    }
    static public function insertItem($insertData = []): int|SKD_Error|array {

        $columnsTable = [
            'group_id'      => ['int'],
            'object_id'     => ['int'],
            'object_type'   => ['string', 'post_post'],
            'value'         => ['file'],
            'order'         => ['int', 0],
        ];

        $columnsTable = apply_filters('columns_db_gallery_item', $columnsTable);

        $update = false;

        if(!empty($insertData['id'])) {

            $id         = (int) $insertData['id'];

            $update     = true;

            $oldObject = static::getItem($id);

            if (!$oldObject) return new SKD_Error( 'invalid_gallery_id', __('ID gallery item không chính xác.') );
        }
        else {
            if(empty($insertData['value'])) return new SKD_Error('empty_value', __('Không được để trống đường dẫn File.'));
        }


        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $type 		 	= FileHandler::type($value);

        $options        = (isset($insertData['options']) && have_posts($insertData['options'])) ? $insertData['options'] : [];

        $data = compact(array_merge(['type', array_keys($columnsTable)]));

        $data = apply_filters( 'pre_insert_gallery_data', $data, $insertData, $update ? $oldObject : null );

        $model = model(self::$table);

        if ($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set('id', $id));
            if(have_posts($options)) {
                foreach ($options as $meta_key => $meta_value ) {
                    static::updateItemMeta($id, $meta_key, $meta_value );
                }
            }
        }
        else {
            if(have_posts(json_decode($value))) {
                $id = [];
                $value = json_decode($value);
                foreach ($value as $path) {
                    $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                    $data['value'] = $path;
                    $data['type']  = FileHandler::type($path);
                    $id[] = $model->settable(self::$table)->add($data);
                }
                foreach ($id as $objectId) {
                    foreach ($options as $meta_key => $meta_value ) {
                        static::updateItemMeta($objectId, $meta_key, $meta_value);
                    }
                }
            }
            else {
                $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
                $id = $model->add($data);
                foreach ($options as $meta_key => $meta_value ) {
                    static::updateItemMeta($id, $meta_key, $meta_value);
                }
            }
        }

        CacheHandler::delete('gallery_', true);

        return $id;
    }
    static public function deleteItem($id) {
        if(empty($id)) return false;
        $model 		= model(self::$table);
        $data       = (is_numeric($id)) ? [$id] : $id;
        if($model->delete(Qr::set()->whereIn('id', $data))) {
            foreach ($data as $id) {
                Metadata::deleteByMid(self::$table, $id);
            }
            CacheHandler::delete('gallery_', true);
            return $data;
        }
        return false;
    }
    static public function deleteItemByObject($id, $object_type) {
        if(empty($id)) return false;
        $model  = model(self::$table);
        $data   = (is_numeric($id)) ? [$id] : $id;
        $args         = Qr::set('object_type', $object_type)->whereIn('object_id', $data);
        $galleryItems = static::getsItem($args);
        if($model->delete($args)) {
            foreach ($galleryItems as $item) {
                Metadata::deleteByMid(self::$table, $item->id);
            }
            CacheHandler::delete('gallery_', true);
            return $data;
        }
        return false;
    }
    static public function getItemMeta($gallery_id, $key = '', $single = true) {
        return Metadata::get(self::$table, $gallery_id, $key, $single);
    }
    static public function updateItemMeta(int $gallery_id, $meta_key, $meta_value) {
        CacheHandler::delete('gallery_'.$gallery_id, true );
        return Metadata::update(self::$table, $gallery_id, $meta_key, $meta_value);
    }
    static public function deleteItemMeta($gallery_id, $meta_key, $meta_value) {
        return Metadata::delete(self::$table, $gallery_id, $meta_key, $meta_value);
    }
    static public function removeItemOption($id = null, $object = null, $type = null): bool {
        $ci =&get_instance();
        if( !$id || !$object ) return false;
        $options = $ci->galleryOptions;
        if( $object == 'post' || $object == 'post_categories' ) {
            if(!$type) return false;
            unset($options['object'][$object][$type][$id]);
        }
        else {
            unset($options['object'][$object][$id]);
        }
        $ci->galleryOptions = $options;
        return true;
    }
    static public function handleParams($args = null) {
        if(is_array($args)) $args = Qr::convert($args);
        if(is_numeric($args)) $args = Qr::set('id', $args);
        if($args == null) {
            $args = Qr::set('object_type', 'gallery');
        }
        else if(!$args->isWhere('object_type')) {
            $args->where('object_type', 'gallery');
        }
        return $args;
    }
    static public function handleParamsItem($args = null) {
        if(is_array($args)) {
            $args = self::handleParamsItemArr($args);
            $args = Qr::convert($args, 'galleries_metadata');
            if(!$args) return $args;
        }
        if(is_numeric($args)) $args = Qr::set('id', $args);
        return $args;
    }
    static public function handleParamsItemArr($args): array {
        if(!have_posts($args)) $args = ['where' => [], 'params' => []];
        if(!empty($args['group']))          {
            $args['where']['group_id']      = (int)Str::clear($args['group']);
            unset($args['group']);
        }
        if(!empty($args['object']))         {
            $args['where']['object_id']     = (int)Str::clear($args['object']);
            unset($args['object']);
        }
        if(!empty($args['object_id']))      {
            $args['where']['object_id']     = (int)Str::clear($args['object_id']);
            unset($args['object_id']);
        }
        if(!empty($args['object_type']))    {
            $args['where']['object_type']   = Str::clear($args['object_type']);
            unset($args['object_type']);
        }
        return array_merge(['where' => [], 'params' => []], $args);
    }
}