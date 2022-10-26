<?php
Class Product_Admin_Setting_Ajax {
    static public function save( $ci, $model ) {

        $result['status']  = 'error';

        $result['message'] = __('Lưu dữ liệu không thành công');

        if( Request::post() ) {

            $product_options = Request::Post();

            $error = apply_filters('admin_product_setting_save_validation', []);

            if(is_skd_error($error)) {
                $result['status']  = 'error';
                foreach ($error->errors as $key => $er) {
                    $result['message'] = $er;
                }
                echo json_encode($result);
                return false;
            }

            if(isset($product_options['item']) && isset($product_options['product_item'])) {
                foreach ($product_options['item'] as &$item) {
                    $item['image']          = process_file($item['image']);
                    $item['description']    = Str::clear($item['description']);
                    $item['url']            = Str::clear($item['url']);
                }
                $product_options['product_item']['item'] = $product_options['item'];
                unset($product_options['item']);
            }

            /**
             * @since 2.1.0
             */
            $product_options = apply_filters('admin_product_save_setting_before', $product_options);

            foreach ($product_options as $key => $value) {
                if(is_string($value)) $value = xss_clean($value);
                Option::update( $key, $value );
            }

            $result['status']  = 'success';

            $result['message'] = __('Lưu dữ liệu thành công.');
        }

        echo json_encode($result);
    }
}

Ajax::admin('Product_Admin_Setting_Ajax::save');