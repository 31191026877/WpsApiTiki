<?php
include 'product-ajax.php';
include 'product-image.php';

Class Admin_Product_Options_Detail {
    static public function tabs() {
        $tabs = [
            //Tab các thuộc tính
            'tab_attributes' => array( 'label' => 'Các thuộc tính', 'callback' => 'Admin_Product_Options_Detail::attributes'),
            //Tab các biến thể
            'tab_variations' => array( 'label' => 'Các biến thể', 'callback' => 'Admin_Product_Options_Detail::variations',),
        ];
        //ver 4.0.0
        return apply_filters('admin_product_detail_options_tabs', $tabs);
    }
    static public function render($object) {
        include 'views/html-product.php';
    }
    static public function attributes() {
        include 'views/html-product-tab-attributes.php';
    }
    static public function variations() {
        include 'views/html-product-tab-variations.php';
    }
    static public function beforeSave($error, $module) {
        if($module ==  'products') {
            $AttributeNames = Request::post('attribute_names');
            if(have_posts($AttributeNames)) {
                $AttributeValues = Request::post('attribute_values');
                if(!have_posts($AttributeValues)) {
                    return new SKD_Error('error', 'Bạn chưa chọn thuộc tính cho sản phẩm');
                }
            }

            $variations = Request::post('product_variations_id');
            if(have_posts($variations)) {
                $postData = Request::post();
                foreach ($postData as $keyData => $postDatum) {
                    if(str_starts_with($keyData, 'attribute_op_')) {
                        if(!have_posts($postDatum)) {
                            return new SKD_Error('error', 'Kiểm tra lại thuộc tính biến thể');
                        }
                        foreach ($postDatum as $item) {
                            if($item == 0) return new SKD_Error('error', 'Thuộc tính biến thể không thể để trống');
                        }
                    }
                }
            }
        }
        return $error;
    }
    static public function save($product_id, $module) {

        if($module == 'products') {

            $model = get_model();

            $data = Request::post();

            $attribute 			= [];

            $attribute_value 	= [];

            if(isset($data['attribute_names']) && isset($data['attribute_values'])) {

                foreach ($data['attribute_names'] as $key => $option_id) {

                    if( isset($data['attribute_values'][$option_id]) && have_posts($data['attribute_values'][$option_id]) ) {

                        $option = Attributes::get($option_id);

                        $attribute['_op_'.$option_id]['name'] 	= $option->title;
                        $attribute['_op_'.$option_id]['id'] 		= $option->id;

                        foreach ($data['attribute_values'][$option_id] as $option_item_id) {

                            $attribute_value['attribute_op_'.$option_id][] = $option_item_id;
                        }

                        unset($data['attribute_values'][$option_id]);
                    }
                }

                $metabox_attr['_product_attributes'] 		= $attribute;

                $metabox_attr['_product_attributes_value'] 	= $attribute_value;

                //product attributes
                Product::updateMeta( $product_id, 'attributes', $metabox_attr['_product_attributes'] );

                foreach ( $metabox_attr['_product_attributes_value'] as $meta_key => $meta_values) {

                    $model->settable('relationships');

                    $model->delete(Qr::set('object_id', $product_id)
                        ->where('category_id', $meta_key)
                        ->where('object_type', 'attributes'));

                    foreach ($meta_values as $value) {
                        $model->add(array('object_id' => $product_id, 'category_id' => $meta_key, 'object_type' => 'attributes', 'value' =>  $value ));
                    }
                }
            }

            if(isset( $data['product_variations_id'])) {

                $product = Product::get($product_id);

                $session_id = Str::clear($data['product_options_session_id']);

                $default    = (!empty($data['variable_default'])) ? Str::clear($data['variable_default']) : 0;

                //add dữ liệu
                if($session_id != 0) {

                    $model->settable('session');

                    $model->delete(Qr::set('session_id', $session_id));

                    //product variables
                    foreach ($data['product_variations_id'] as $key => $variation_id) {

                        $product_variation_add = [
                            'id' 	 	=> $variation_id,
                            'title'     => $product->title,
                            'status' 	=> 'public',
                            'parent_id' => $product_id,
                            'type'      => 'variations'];

                        Product::insert($product_variation_add);
                    }

                    if($default == 0) $default = (int)$data['product_variations_id'][0];
                }
                else {
                    //product variables
                    $model->settable('products');

                    $model->update(['status' => 'public', 'title' => $product->title], Qr::set('parent_id', $product_id)->where('type', 'variations'));
                }

                $metaBoxTemp  = [];

                $metaBoxVariation = [];

                $codes 		= $data['variable_code']; unset($data['variable_code']);

                $price 		= $data['variable_price']; unset($data['variable_price']);

                $price_sale = $data['variable_price_sale']; unset($data['variable_price_sale']);

                $image 		= $data['upload_image']; unset($data['upload_image']);

                $weight 	= $data['variable_weight']; unset($data['variable_weight']);

                if(have_posts($data)) {
                    foreach ($data as $key => $list_item) {
                        if(have_posts($list_item) && (str_contains($key, 'attribute_')) ) {
                            $name = $key;
                            foreach ($list_item as $object_id => $value) $metaBoxTemp[$object_id][$name] = $value;
                        }
                    }
                }

                foreach ($codes as $id => $code) {

                    if($default == 0) $default = $id;

                    $image[$id] = FileHandler::handlingUrl($image[$id]);

                    $image[$id] = str_replace(SOURCE, '', $image[$id]);

                    $product_variation = [
                        'id' 		 => $id,
                        'code' 		 => Str::clear($code),
                        'image'		 => $image[$id],
                        'price' 	 => $price[$id],
                        'price_sale' => $price_sale[$id],
                        'weight' 	 => $weight[$id],
                    ];

                    $product_variation = apply_filters('admin_product_variation_data_save', $product_variation, $id);

                    Product::insert($product_variation);

                    if(isset($metaBoxTemp[$id])) $metaBoxVariation[$id] = $metaBoxTemp[$id];

                    do_action('admin_product_variation_save_success', $id );
                }

                if($default != 0) {
                    foreach ($codes as $id => $code) {
                        if($default == $id) {
                            $product_update = [
                                'id' 		 => $product->id,
                                'price' 	 => $price[$id],
                                'price_sale' => $price_sale[$id],
                            ];
                            Product::insert($product_update);
                            break;
                        }
                    }
                    Product::updateMeta( $product->id, 'default', $default );
                }

                $listVariationKey = [];

                //product variables
                foreach ($metaBoxVariation as $object_id => $metadata) {
                    if(have_posts($metadata)) {
                        foreach ($metadata as $meta_key => $meta_value) {
                            $listVariationKey[$object_id][] = $meta_key;
                            Product::updateMeta($object_id, $meta_key, $meta_value );
                        }
                    }
                }

                foreach ($listVariationKey as $object_id => $item) {
                    if(count($item) != Metadata::count('product', ['object_id' => $object_id,'where_like' => ['meta_key' => ['attribute_op_']]])) {
                        $meta = Metadata::get('product', $object_id);
                        foreach ($meta as $meta_key => $meta_value) {
                            if(Str::is('attribute_op_*', $meta_key)) {
                                if(in_array($meta_key, $item) === false) {
                                    Metadata::delete('product', $object_id, $meta_key);
                                }
                            }
                        }
                    }
                }
            }

            do_action('admin_product_variation_save', $product_id, $data );
        }
    }
}

Metabox::add('admin_product_metabox_attribute', 'Thuộc tính & biến thể', 'Admin_Product_Options_Detail::render', ['module' => 'products']);
add_action('save_object', 'Admin_Product_Options_Detail::save', 10, 2);
add_filter('admin_form_validation', 'Admin_Product_Options_Detail::beforeSave', 10, 2);


Class Admin_Product_Custom_Table {
    static function customTableData($object) {

        $listId = [];

        foreach ($object as $item) { $listId[] = $item->id; }

        if(have_posts($listId)) {
            $variations = Variation::gets(Qr::set('type', 'variations')->whereIn('parent_id', $listId));
            foreach ($object as &$item) {
                $item->variations = [];
                foreach ($variations as $key => $variation) {
                    if($variation->parent_id == $item->id) {
                        $item->variations[$variation->id] = $variation;
                        unset($variations[$key]);
                    }
                }
                if(have_posts($item->variations)) {
                    foreach ($item->variations as $key => $variable) {
                        $metadata = Metadata::get('products', $variable->id );
                        $item->variations[$key]->options = [];
                        $item->variations[$key]->optionName = '';
                        foreach ($metadata as $key_meta => $meta_value) {
                            if(str_starts_with($key_meta, 'attribute_op_')) {
                                $item->variations[$key]->options[] = Attributes::getItem($meta_value);
                            }
                        }
                        if(have_posts($item->variations[$key]->options)) {
                            foreach ($item->variations[$key]->options as $options) {
                                $item->variations[$key]->optionName .= '<span style="font-weight: bold;">'.$options->title.'</span>'.' - ';
                            }
                            $item->variations[$key]->optionName = trim($item->variations[$key]->optionName, ' - ');
                        }
                    }
                }
            }
        }

        return $object;
    }
    static function tableHeading($columns) {
        $columnsNew = [];
        foreach ($columns as $key => $column) {
            $columnsNew[$key] = $column;
            if($key == 'price_sale') unset($columnsNew[$key]);
            if($key == 'price') {
                $columnsNew['options'] = 'Phân loại';
                $columnsNew['prices'] = 'Giá';
                unset($columnsNew[$key]);
            }
        }
        return $columnsNew;
    }
    static function tableColumns($columnName, $item) {
        switch ($columnName) {
            case 'options':
                $countKey = 0;
                foreach ($item->variations as $key => $variable) {
                    $class = ($countKey++ > 2) ? 'd-hidden' : '';
                    echo '<p class="product-variations-model '.$class.'">'.$variable->optionName.'</p>';
                }
                echo (count($item->variations) > 3) ? '<p class="js_product_variations_more" data-txt-show="Xem thêm" data-txt-hide="Đóng"  role="button">Xem thêm <i class="fa-thin fa-arrow-down-to-line"></i></p>' : '';
                break;
            case 'prices':
                if(!empty($item->variations)) {
                    $countKey = 0;
                    foreach ($item->variations as $key => $variable) {
                        $class = ($countKey++ > 2) ? 'd-hidden' : '';
                        echo '<div class="product-variations-model '.$class.'">
                        <p class="quick-edit-box d-flex gap-3">
                        <span class="product_price_'.$variable->id.'">'.number_format($variable->price).'</span>
                        <span class="product_price_sale_'.$variable->id.'">'.number_format($variable->price_sale).'</span>
                        <span class="quick-edit js_product_quick_edit_price" data-variations="'.htmlentities(json_encode($item->variations)).'"><i class="fa-thin fa-pen"></i></span>
                        </p>
                        </div>';
                    }
                    echo (count($item->variations) > 3) ? '<p>...</p>' : '';
                }
                else {
                    echo '<p class="quick-edit-box d-flex gap-3">';
                    echo '<a href="#" data-pk="'.$item->id.'" data-name="price" class="js_products_price__update" >'.number_format($item->price).'</a>';
                    echo '<a href="#" data-pk="'.$item->id.'" data-name="price_sale" class="js_products_price__update" >'.number_format($item->price_sale).'</a>';
                    echo '</p>';
                }

            break;
        }
    }
    static function quickEditPrice() {
        cart_template('admin/product/views/quick-edit');
    }
}
add_filter('admin_product_controllers_index_object', 'Admin_Product_Custom_Table::customTableData', 10);
add_filter('manage_product_columns', 'Admin_Product_Custom_Table::tableHeading', 10);
add_action('manage_product_custom_column', 'Admin_Product_Custom_Table::tableColumns',10,2);
add_action('admin_footer', 'Admin_Product_Custom_Table::quickEditPrice');



