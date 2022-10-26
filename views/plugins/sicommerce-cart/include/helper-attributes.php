<?php
Class Attributes {

    static string $table = 'attribute';

    static string $tableItem = 'attribute_item';

    static public function handleParams($args = null) {
        if(is_array($args)) {
            $query = Qr::convert($args);
            if(!$query) return $query;
        }
        if(is_numeric($args)) $query = Qr::set(self::$table.'.id', $args);
        if($args instanceof Qr) $query = clone $args;
        return (isset($query)) ? $query : null;
    }

    static public function get($args = null) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        return apply_filters('get_attributes', model(self::$table)->get($args), $args);
    }

    static public function gets($args = null) {

        if($args == null) {
            $cacheID = 'attribute_All';
            if(CacheHandler::has($cacheID)) return CacheHandler::get($cacheID);
        }

        if(is_array($args) && !empty($args['product_id'])) {
            return self::getsByProduct($args['product_id']);
        }

        $args = self::handleParams($args);

        if($args == null) $args = Qr::set();

        if(!$args instanceof Qr) return new Illuminate\Support\Collection();

        $attributes = model(self::$table)->gets($args);

        if(!empty($attributes) && !empty($cacheID)) {
            CacheHandler::save($cacheID, $attributes);
        }

        return apply_filters('gets_attributes', $attributes, $args);
    }

    static public function getsByProduct($productId) {

        $attributes = Product::getMeta($productId, 'attributes', true);

        if (have_posts($attributes)) {

            $result = [];

            $attributes_tmp = [];

            foreach ($attributes as $value) {
                if (empty($value['id'])) continue;
                $attributes_tmp[] = $value['id'];
            }

            $attributes_tmp = static::gets(Qr::set()->whereIn('id', $attributes_tmp));

            $model = model('relationships');

            foreach ($attributes_tmp as $option) {

                $key = '_op_' . $option->id;

                if (have_posts($option)) {

                    $result[$key]['id'] = $option->id;
                    $result[$key]['product_id'] = $productId;
                    $result[$key]['title'] = $option->title;
                    $result[$key]['option_type'] = $option->option_type;

                    $attributes_item = $model->settable('relationships')->gets(Qr::set('object_id', $productId)->where('category_id', 'attribute_op_' . $option->id)->where('object_type', 'attributes'));

                    if($attributes_item instanceof Illuminate\Support\Collection) {
                        $attributes_item = $attributes_item->all();
                    }
                    foreach ($attributes_item as $key_item => $value_item) {
                        $attributes_item[$key_item] = $value_item->value;
                    }

                    $result[$key]['items'] = static::getsItem([
                        'product' => [
                            'product_id' => $productId,
                            'attribute' => 'attribute' . $key
                        ],
                    ]);

                    $result[$key]['attributes_item'] = $attributes_item;
                }
            }

            return $result;
        }

        return $attributes;
    }

    static public function count($args = []) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return 0;
        return apply_filters('count_attributes', model(self::$table)->count($args), $args);
    }

    static public function insert($insertData = []): SKD_Error|int {

        $columnsTable = [
            'option_type'       => ['string', 'label'],
        ];

        $columnsTable = apply_filters('columns_db_attributes', $columnsTable);

        if (empty($insertData['title'])) {
            if (!empty($insertData[Language::default()]['title'])) {
                $insertData['title'] = $insertData[Language::default()]['title'];
            }
        }

        if (!empty($insertData['id'])) {

            $id = (int)$insertData['id'];

            $update = true;

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_product_id', __('ID nhóm thuộc tính không chính xác.'));

            $insertData['title'] = (!empty($insertData['title'])) ? $insertData['title'] : $oldObject->title;

            $slug = empty($insertData['slug']) ? $oldObject->slug : Str::slug($insertData['slug']);

            if(empty($slug)) $slug = Str::slug($insertData['title']);

            if($slug != $oldObject->slug) {
                $slug = Routes::slug($slug, 'attributes', $id);
                CacheHandler::delete('routes-'.$slug);
            }

        }
        else {

            if (!isset($insertData['title']) || (!is_numeric($insertData['title']) && empty($insertData['title']))) return new SKD_Error('empty_option_title', __('Không thể cập nhật nhóm thuộc tính khi tên tên nhóm trống.'));

            $slug = Routes::slug(Str::clear($insertData['title']), 'attributes');

            $update = false;
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        if (empty($insertData['language'])) {
            foreach (Language::list() as $key => $label) {
                if ($key != Language::default()) {
                    if (!empty($insertData[$key]['title'])) {
                        $insertData['language'][$key] = $insertData[$key];
                    }
                }
            }
        }

        $title = Str::clear($insertData['title']);

        $pre_title = apply_filters('pre_title', $title);

        $title = trim($pre_title);

        $data = compact(array_merge(['title', 'slug', array_keys($columnsTable)]));

        $data = apply_filters('pre_insert_attributes_data', $data, $insertData, $update ? $oldObject : null);

        $language = !empty($insertData['language']) ? $insertData['language'] : [];

        if (isset($language[Language::default()])) unset($language[Language::default()]);

        $model = model(self::$table);

        if ($update) {

            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);

            $model->update($data, Qr::set($id));

            $object_id = (int)$id;

            # [Router]
            $router['object_type']  = 'attributes';
            $router['object_id']    = $object_id;
            if(empty(Routes::update(['slug' => $slug], $router))) {
                $router['slug']         = $slug;
                $router['directional']  = 'attributes';
                $router['controller']   = 'frontend/home/page/';
                $router['callback']     = 'attributes_frontend';
                Routes::insert($router);
            }

            # [LANGUAGE]
            if(have_posts($language)) {
                $objectLanguage = Language::gets(Qr::set()->where('object_id', $id)->where('object_type', 'attributes'));
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
                        $insLangData['object_type'] = 'attributes';
                        if(Language::insert($insLangData)) unset($language[$key]);
                    }
                }
            }
        } else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $object_id = $model->add($data);

            # [Router]
            $router['slug']         = $slug;
            $router['object_type']  = 'attributes';
            $router['directional']  = 'attributes';
            $router['controller']   = 'frontend/home/page/';
            $router['callback']     = 'attributes_frontend';
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
                    $langInsert['object_type']    = 'attributes';
                    Language::insert($langInsert);
                }
            }
        }

        CacheHandler::delete('attribute_All');

        return $object_id;
    }

    static public function delete($id): bool|array {

        $model = model(self::$table);

        $object = static::get($id);

        if (have_posts($object)) {

            //xóa các thuộc tính
            static::deleteItemByAttribute($object->id);

            //xóa các thuộc tính ở sản phẩm

            $model->settable('relationships')->delete(Qr::set('category_id', 'attribute_op_' . $object->id)->where('object_type', 'attributes'));

            //Xóa ngôn ngữ

            $model->settable('language')->delete(Qr::set('object_id', $object->id)->where('object_type', 'attributes'));

            //Xóa Router
            $model->settable('routes')->delete(Qr::set('object_id', $object->id)->where('object_type', 'attributes'));

            //xóa attributes
            $product_attributes = $model->settable('products_metadata')->gets(Qr::set('meta_key', 'attributes'));

            foreach ($product_attributes as $attributes) {

                $attributes->meta_value = @unserialize($attributes->meta_value);

                unset($attributes->meta_value['_op_' . $id]);

                if (!have_posts($attributes->meta_value)) {
                    Product::deleteMeta($attributes->object_id, 'attributes');

                } else {
                    Product::updateMeta($attributes->object_id, 'attributes', $attributes->meta_value);
                }
            }

            CacheHandler::delete('attribute_All');

            //xóa metabox
            if ($model->settable(self::$table)->delete(Qr::set($object->id))) {
                return [$object->id];
            }
        }

        return false;
    }

    static public function deleteList($attributeID): bool|array {

        $result = [];

        if (!have_posts($attributeID)) return false;

        foreach ($attributeID as $key => $id) {

            if (static::delete($id)) $result[] = $id;
        }

        if (have_posts($result)) return $result;

        return false;
    }

    static public function handleParamsItem($args = null) {
        if(is_array($args)) {
            $query = Qr::convert($args);
            if(!$query) return $query;
            if (isset($args['attribute'])) {
                $query->where('option_id', $args['attribute']);
            }
        }
        if(is_numeric($args)) $query = Qr::set(self::$tableItem.'.id', $args);
        if($args instanceof Qr) $query = clone $args;
        return (isset($query)) ? $query : null;
    }

    static public function getItem($args = []) {
        $args = self::handleParamsItem($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        return apply_filters('get_attributes_item', model(self::$tableItem)->get($args), $args);
    }

    static public function getsItem($args = []) {
        if(is_array($args) && !empty($args['product'])) {
            return self::getsItemByProduct($args['product']['product_id'], $args['product']['attribute']);
        }
        $args = self::handleParamsItem($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        return apply_filters('gets_attributes_item', model(self::$tableItem)->gets($args), $args);
    }

    static public function getsItemByProduct($productId, $attrId = null) {

        $args = Qr::set('object_id', $productId)->where('object_type', 'attributes');

        if ($attrId != null) {
            if (is_numeric($attrId)) $attrId = 'attribute_op_' . $attrId;
            $args->where('category_id', $attrId);
        }

        $attributes = model('relationships')->gets($args);

        $list_attributes_id = [];

        if (have_posts($attributes)) {
            foreach ($attributes as $attribute) {
                $list_attributes_id[] = $attribute->value;
            }
            $attributes = self::getsItem(Qr::set()->whereIn('id', $list_attributes_id)->select('id', 'title', 'value', 'image', 'type', 'order')->orderBy('order'));
        }

        return $attributes;
    }

    static public function insertItem($insertData = []): SKD_Error|int {

        $columnsTable = [
            'type'  => ['string', 'label'],
            'value'  => ['string'],
            'image'  => ['string'],
            'option_id'  => ['int', 0],
            'order'  => ['int', 0],
        ];

        $columnsTable = apply_filters('columns_db_attributes_item', $columnsTable);

        if (!empty($insertData['id'])) {

            $id = (int)$insertData['id'];

            $update = true;

            $oldObject = static::getItem($id);

            if (!$oldObject) return new SKD_Error('invalid_product_id', __('ID thuộc tính không chính xác.'));

            $insertData['title'] = (!empty($insertData['title'])) ? $insertData['title'] : $oldObject->title;

        } else {

            $update = false;

            if (empty($insertData['title'])) return new SKD_Error('empty_option_title', __('Không thể cập nhật nhóm thuộc tính khi tên tên nhóm trống.'));

            if (empty($insertData['option_id'])) return new SKD_Error('empty_option_id', __('ID option không đúng.'));
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $title = Str::clear($insertData['title']);

        $pre_title = apply_filters('pre_title', $title);

        $title = trim($pre_title);

        $data = compact(array_merge(['title', array_keys($columnsTable)]));

        $data = apply_filters('pre_insert_attributes_item_data', $data, $insertData, $update ? $oldObject : null);

        $model = model(self::$tableItem);

        if ($update) {

            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);

            $model->update($data, Qr::set($id));

            $attribute_id = (int)$id;

        } else {

            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);

            $attribute_id = $model->add($data);
        }

        return $attribute_id;
    }

    static public function deleteItem($id): bool|array {

        $model = model(self::$tableItem);

        $object = static::getItem($id);

        if (have_posts($object)) {

            if ($model->delete(Qr::set($object->id))) {

                //xóa ở liên kết product
                $model->settable('relationships')->delete(Qr::set('value', $object->id)->where('category_id', 'attribute_op_' . $object->option_id)->where('object_type','attributes'));

                //xóa liên kết biến thể
                $model->settable('products_metadata')->delete(Qr::set('meta_value', $object->id)->where('meta_key', 'attribute_op_' . $object->option_id));

                Metadata::delete('metabox_', true);

                return [$object->id];
            }
        }

        return false;
    }

    static public function deleteItemList($attributeID): bool|array {

        $result = [];

        if (!have_posts($attributeID)) return false;

        foreach ($attributeID as $key => $id) {
            if (static::deleteItem($id)) $result[] = $id;
        }

        if (have_posts($result)) return $result;

        return false;
    }

    static public function deleteItemByAttribute($attributeID): bool|array {

        $object = static::getsItem(Qr::set('option_id', $attributeID));

        $attributes = [];

        foreach ($object as $item) {
            $attributes[] = $item->id;
        }
        if (have_posts($attributes)) {
            return static::deleteItemList($attributes);
        }
        return false;
    }

    static public function insertToProduct($product_id, $attributes): bool {

        $model = model('home');

        $attributesGroups = [];

        $attributesValues = [];

        foreach ($attributes as $group_id => $attribute) {
            $group = static::get($group_id);
            if (have_posts($group)) {
                $attributesGroups['_op_' . $group_id] = [
                    'id' => $group->id,
                    'name' => $group->title
                ];
                $attributesValues['attribute_op_' . $group_id] = $attribute;
            }
        }

        if (have_posts($attributesGroups)) {
            Product::updateMeta($product_id, 'attributes', $attributesGroups);
        }

        if (have_posts($attributesValues)) {

            $model->settable('relationships');

            $temp_relationships = $model->gets(Qr::set('object_id', $product_id)->where('object_type', 'attributes'));

            $relationships = [];

            foreach ($temp_relationships as $item) {
                if (Str::is('attribute_op_*', $item->category_id)) {
                    $relationships[$item->object_id][$item->category_id][$item->value] = $item->value;
                }
            }

            foreach ($attributesValues as $meta_key => $meta_values) {

                foreach ($meta_values as $value) {
                    if (!empty($relationships[$product_id][$meta_key][$value])) {
                        unset($relationships[$product_id][$meta_key][$value]);
                        continue;
                    }
                    $model->add(array('object_id' => $product_id, 'category_id' => $meta_key, 'object_type' => 'attributes', 'value' => $value));
                }
            }

            if (have_posts($relationships)) {
                foreach ($relationships[$product_id] as $groupID => $groups) {
                    foreach ($groups as $attrID) {
                        $model->delete(Qr::set('object_id', $product_id)->where('category_id', $groupID)->where('value', $attrID));
                    }
                }
            }
        }

        return true;
    }

    static public function addToProduct($product_id, $attributes): bool {

        $attributesGroups = Product::getMeta($product_id, 'attributes', true);

        if (empty($attributesGroups)) $attributesGroups = [];

        foreach ($attributes as $groupID => $attribute) {

            if (!empty($attributesGroups['_op_' . $groupID])) continue;

            $group = static::get($groupID);

            if (have_posts($group)) {

                $attributesGroups['_op_' . $groupID] = [
                    'id' => $group->id,
                    'name' => $group->title
                ];
            }
        }

        if (have_posts($attributesGroups)) {

            Product::updateMeta($product_id, 'attributes', $attributesGroups);

            $model = model('relationships');

            foreach ($attributes as $groupID => $attribute) {
                foreach ($attribute as $value) {
                    $attr_inser = ['object_id' => $product_id, 'category_id' => 'attribute_op_' . $groupID, 'value' => $value, 'object_type' => 'attributes'];
                    $count = $model->count(
                        Qr::set('object_id', $product_id)
                        ->where('category_id', 'attribute_op_' . $groupID)
                        ->where('value', $value)
                        ->where('object_type', 'attributes')
                    );
                    if ($count == 0) $model->add($attr_inser);
                }
            }
        }

        return true;
    }

    static public function type($key = '', $type = '') {

        $args = [
            'label' => ['label' => 'Text (Label)'],
            'color' => ['label' => 'Màu (Color)'],
            'image' => ['label' => 'Hình (Image)'],
        ];

        if (!empty($key) && !empty($type) && isset($args[$key])) {

            if (!empty($args[$key][$type])) return apply_filters('attribute_type_' . $type, $args[$key][$type], $key, $type);

            return apply_filters('attribute_type', $args[$key], $key, $type);
        }

        return apply_filters('attribute_type', $args, $key);
    }
}

Class Variation {

    static string $table = 'products';

    static public function handleParams($args = null) {
        if(is_array($args)) {
            if(isset($args['product'])) {
                $args['where']['parent_id'] = (int)$args['product'];
                $args['where']['status'] 	= 'public';
                $args['params']['select'] 	= 'id, code, title, image, price, price_sale, parent_id, type, weight';
                unset($args['product']);
            }
            $query = Qr::convert($args);

            if(!$query) return $query;
        }

        if(is_numeric($args)) $query = Qr::set(self::$table.'.id', $args);

        if($args instanceof Qr) $query = clone $args;

        if(!isset($query)) $query = Qr::set();

        if(!$query->isWhere(self::$table.'.type') && !$query->isWhere('type')) {
            $query->where(self::$table.'.type', 'variations');
        }

        return $query;
    }

    static public function get($args = []) {

        $args = self::handleParams($args);

        $variable = (object)Product::get($args);

        if( have_posts($variable)) {
            $metadata = Metadata::get('products', $variable->id );
            $variable->items = [];
            foreach ($metadata as $key_meta => $meta_value) {
                if(str_starts_with($key_meta, 'attribute_op_')) {
                    $variable->items[substr($key_meta, 13)] = $meta_value;
                    unset($metadata->{$key_meta});
                }
            }
            $variable = (object)array_merge( (array)$metadata, (array)$variable);
        }

        return apply_filters('get_variations', $variable, $args);
    }

    static public function gets($args = []) {

        $args = self::handleParams($args);

        $args = apply_filters('variation_gets_args', $args);//2.7.2

        $variations = Product::gets($args);

        if(have_posts($variations)) {
            foreach ($variations as $key => &$variable) {
                $metadata = Metadata::get('products', $variable->id );
                foreach ($metadata as $key_meta => $meta_value) {
                    if(str_starts_with($key_meta, 'attribute_op_')) {
                        $variable->items[substr($key_meta, 13)] = $meta_value;
                        unset($metadata->{$key_meta});
                    }
                }
                $variable = (object)array_merge( (array)$metadata, (array)$variable);
            }
        }

        return apply_filters('gets_variations', $variations, $args);
    }

    static public function getsByProduct($productId) {

        return self::gets(Qr::set('parent_id', $productId)
            ->where('status', 'public')
            ->select('id', 'code', 'title', 'image', 'price', 'price_sale', 'parent_id', 'type', 'weight'));

    }

    static public function getsByAttribute($optionId , $productId) {

        $variations = Product::gets(Qr::set('type', 'variations')
            ->where('parent_id', (int)$productId)
            ->where('status', 'public')
            ->select('id', 'code', 'title', 'image', 'price', 'price_sale', 'parent_id', 'type', 'weight'));

        $variationsTemp = [];

        if(have_posts($variations)) {
            foreach ($variations as $key => $variable) {

                $metadata = Metadata::get('products', $variable->id );

                $check = false;

                foreach ($metadata as $key_meta => $meta_value) {
                    if($optionId == $meta_value) {
                        $check = true; break;
                    }
                }

                if($check) {
                    foreach ($metadata as $key_meta => $meta_value) {
                        if($optionId == $meta_value) { continue; }
                        if(str_starts_with($key_meta, 'attribute_op_')) {
                            $variable->items[] = Attributes::getItem($meta_value);
                            unset($metadata->{$key_meta});
                        }
                    }
                    $variationsTemp[] = (object)array_merge((array)$metadata, (array)$variable);
                }
            }
        }

        return apply_filters('gets_variations', $variationsTemp);
    }

    static public function checkByAttribute($product_variation_id, $variations): bool {

        $model = model('products_metadata');

        foreach ($variations as $groupID => $itemId) {

            $attr = $model->get(Qr::set('object_id', $product_variation_id)
                    ->where('meta_key', 'attribute_op_'.$groupID)
                    ->where('meta_value', $itemId));

            if(!have_posts($attr)) return false;
        }

        return true;
    }

    static public function insertAttribute($product_id, $variations) {

        $model = model('products_metadata');

        $temps = $model->gets(Qr::set('meta_key', 'like', 'attribute_op_%')->where('object_id',$product_id));

        $attributes = [];

        foreach ($temps as $temp) {
            $attributes[$temp->meta_key] = $temp->meta_value;
        }

        foreach ($variations as $groupID => $itemID) {

            $attr = Product::getMeta($product_id, 'attribute_op_'.$groupID, true);

            if($attr != $itemID) {
                Product::updateMeta($product_id, 'attribute_op_'.$groupID , $itemID);
            }

            if(!empty($attributes['attribute_op_'.$groupID])) unset($attributes['attribute_op_'.$groupID]);
        }

        if(have_posts($attributes)) {
            foreach ($attributes as $meta_key => $itemID) {
                Product::deleteMeta($product_id, $meta_key);
            }
        }
    }
}

