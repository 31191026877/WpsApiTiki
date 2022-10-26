<?php
Class Admin_Cart_Ajax_Setting {
    static public function saveEmail( $ci, $model ) {

        $result['status']  = 'error';

        $result['message'] = __('Lưu dữ liệu không thành công');

        if( Request::post() ) {

            $data = Request::post('cart_email');

            $cart_email['customer_order_new'] = Str::clear($data['customer_order_new']);

            $cart_email['admin_order_new'] = Str::clear($data['admin_order_new']);

            if( have_posts($cart_email) ) {

                Option::update('cart_email', $cart_email);

                $result['status']  = 'success';

                $result['message'] = __('Lưu dữ liệu thành công.');
            }
        }

        echo json_encode($result);
    }
    static public function saveCancelled( $ci, $model ) {

        $result['status']  = 'error';

        $result['message'] = __('Lưu dữ liệu không thành công');

        if( Request::post() ) {

            $reason = Request::post('reason');

            if(have_posts($reason)) {

                foreach ($reason as $key => $item) {
                    $reason[$key]          = Str::clear($item);
                }

                Option::update('order_cancelled_reason', $reason);

                $result['status']  = 'success';

                $result['message'] = __('Lưu dữ liệu thành công.');
            }
        }

        echo json_encode($result);
    }
    static public function savePayment() {

        $result['status'] = 'error';

        $result['message'] = 'Load dữ liệu không thành công!';

        if(Request::post()) {

            $key = Request::Post('payment_key');

            $errors = apply_filters('admin_payment_'.$key.'_config_save_checked', '');

            if(!is_skd_error($errors)) {

                $payment = payment_gateways($key);

                if(have_posts($payment)) {

                    do_action( 'admin_payment_'.$key.'_config_save');

                    $result['status']  = 'success';

                    $result['message'] = 'Lưu dữ liệu thành công!';

                    if(class_exists($payment['class']) && method_exists($payment['class'], 'config')) $result = $payment['class']::config($result);

                    $result = apply_filters('admin_payment_'.$key.'_config_save_result', $result);

                }
            }
            else {
                foreach ($errors->errors as $error_key => $error_value) {
                    $result['message'] = $error_value[0];
                }
            }
        }

        echo json_encode( $result );
    }
    static public function saveShipping() {

        $result['status'] = 'error';

        $result['message'] = 'Load dữ liệu không thành công!';

        if(Request::post()) {

            $key = Request::Post('shipping_key');

            $errors = apply_filters('admin_shipping_'.$key.'_config_save_checked', '');

            if(!is_skd_error($errors)) {

                $shipping = shipping_gateways($key);

                if(have_posts($shipping) && class_exists($shipping['class'])) {

                    if(!empty(Request::Post('default'))) {
                        Option::update('cart_shipping_default', Request::Post('default'));
                    }

                    do_action( 'admin_shipping_'.$key.'_config_save');

                    $result['status']  = 'success';

                    $result['message'] = 'Lưu dữ liệu thành công!';

                    if(class_exists($shipping['class']) && method_exists($shipping['class'], 'config')) $result = $shipping['class']::config($result);

                    $result = apply_filters('admin_shipping_'.$key.'_config_save_result', $result);
                }
            }
            else {
                foreach ($errors->errors as $error_key => $error_value) {
                    $result['message'] = $error_value[0];
                }
            }
        }

        echo json_encode( $result );
    }
}

Ajax::admin('Admin_Cart_Ajax_Setting::saveEmail');
Ajax::admin('Admin_Cart_Ajax_Setting::saveCancelled');
Ajax::admin('Admin_Cart_Ajax_Setting::savePayment');
Ajax::admin('Admin_Cart_Ajax_Setting::saveShipping');