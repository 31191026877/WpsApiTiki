<?php
Class Admin_Product_Options_Image {
    static public function tabs($tabs) {
        $tabs['product_option_image_tab'] = [
            'label' => 'Ảnh Các Thuộc Tính',
            'callback' => 'Admin_Product_Options_Image::render'
        ];
        //ver 4.0.0
        return $tabs;
    }
    static public function render($object) {
        include 'views/html-product-tab-option-img.php';
    }
    static public function save($product_id, $module) {
        if($module == 'products') {
            $data = Request::post();
            if(isset($data['attr_op_img'])) {
                $attribute_img = $data['attr_op_img'];
                unset($data['attr_op_img']);
                $variations_img  = [];
                foreach ($attribute_img as $pr_id => $listImg ) {
                    foreach ($listImg as $img_id => $value) {
                        if(empty($value)) {
                            unset($attribute_img[$pr_id][$img_id]);
                            continue;
                        }
                        $variations_img[$img_id] = FileHandler::handlingUrl($attribute_img[$pr_id][$img_id]);
                        if(Str::is('*assets/images/Placeholder.jpg', $variations_img[$img_id])) {
                            unset($variations_img[$img_id]);
                        }
                    }
                }
                Product::updateMeta($product_id, 'variations_img', $variations_img );
            }
        }
    }
}

add_filter('admin_product_detail_options_tabs', 'Admin_Product_Options_Image::tabs', 10 );
add_action('save_object', 'Admin_Product_Options_Image::save', 10, 2);