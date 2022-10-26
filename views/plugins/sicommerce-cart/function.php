<?php
include_once 'include/helper-cities.php';
include_once 'include/helper-order.php';
include_once 'include/helper-cart.php';
include_once 'include/helper-attributes.php';
include_once 'include/helper-customer.php';
include_once 'include/helper-old-version.php';

if(!function_exists('cart_template')) {
    function cart_template( $template_path = '' , $args = '', $return = false ) {
        return plugin_get_include(CART_NAME,$template_path, $args, $return);
    }
}
if(!function_exists('cart_include')) {
    function cart_include( $template_path = '' , $args = '', $return = false) {

        $ci =& get_instance();

        extract($ci->data);

        if (!empty($args) && is_array($args)) {
            extract( $args );
        }

        $path 	= $ci->plugin->dir.'/'.CART_NAME.'/'.$template_path.'.php';

        ob_start();

        include $path;

        if ($return === true) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }

        ob_end_flush();
    }
}
if(!function_exists('order_total')) {
    function order_total($total = 0)
    {

        $total = ($total == 0) ? Scart::total() : $total;

        $shipping_price = 0;

        $data = Request::post();

        if (!empty($data['shipping_type'])) {

            $shipping_type = Str::clear($data['shipping_type']);

            $shipping = shipping_gateways();

            foreach ($shipping as $key => $ship) {

                if (empty($ship['enabled']) || $ship['enabled'] == false) continue;

                $key_temp = str_replace('-', '_', $key);

                if ($key == $shipping_type) {

                    $shipping_price = 0;

                    if (method_exists($ship['class'], 'calculate')) $shipping_price = $ship['class']::calculate($data);

                    $shipping_price = apply_filters('shipping_price_' . $key_temp, $shipping_price); //2.7.2
                }
            }
        }

        $total = $total + (int)$shipping_price;

        $total = apply_filters('order_total', $total);

        return $total;
    }
}
if(!function_exists('get_order_item_totals')) {
    function get_order_item_totals($order) {

        if (isset($order->_shipping_price)) {

            $shipping = shipping_gateways($order->_shipping_type);

            if (have_posts($shipping)) {

                $order->_shipping_label = $shipping['label'];

                if ($order->_shipping_price == 0) $order->_shipping_price = $shipping['price_default'];
            }

            $total[10]['label'] = $order->_shipping_label;

            $total[10]['value'] = ($order->_shipping_price == 0 || !is_numeric($order->_shipping_price)) ? __('Liên hệ') : number_format($order->_shipping_price) . _price_currency();
        }

        $total[20]['label'] = __('Thành tiền', 'cart_thanhtien');

        $total[20]['value'] = number_format($order->total) . _price_currency();

        $totals = apply_filters('get_order_item_totals', $total, $order);

        ksort($totals);

        return $totals;
    }
}
if(!function_exists('customer_client_register')) {
	function customer_client_register( $user_array ) {
        if(!is_admin()) {
			$user_array['customer'] = 1;
			$user_array['role'] = 'customer';
		}
        return $user_array;
	}
	add_filter('pre_user_register', 'customer_client_register');
}
if(!function_exists('payment_gateways')) {

    function payment_gateways($key_payment_gateways = '') {

        $gateways = [];

        $payments = option::get('payments', []);

        if(!have_posts($payments)) $payments = [];

        //ver 2.7
        $gateways = apply_filters('payment_gateways', $gateways, $payments);

        foreach ($gateways as $key => &$gateway) {

            if(empty($payments[$key]) || !have_posts($payments[$key])) {
                $config = [];
            }
            else {
                $config = $payments[$key];
            }

            $gateway['config']['enabled']    = (empty($config['enabled'])) ? 0 : 1;

            $gateway['config']['title']      = (isset($config['title'])) ? $config['title'] : $gateway['label'];

            $gateway['config']['img']        = (!empty($config['img'])) ? $config['img'] : Url::base(CART_PATH.'assets/images/bank.png') ;

            if(!empty($gateway['config']) && have_posts($gateway['config'])) {
                $gateway = array_merge($gateway, $gateway['config']);
            }

            $gateway['description'] = (!empty($gateway['description'])) ? $gateway['description'] : '';

            $gateway['title'] = (!empty($gateway['title'])) ? $gateway['title'] : $gateway['label'];

            $gateway['icon'] = (!empty($gateway['icon'])) ? $gateway['icon'] : '';

            $gateway['img'] = (!empty($gateway['img'])) ? $gateway['img'] : $gateway['icon'];
        }

        if(!empty($key_payment_gateways)) {

            return Arr::get($gateways, $key_payment_gateways);
        }

        return $gateways;
    }
}
if(!function_exists('shipping_gateways')) {

    function shipping_gateways($ke_gateways = '') {

        $default = Option::get('cart_shipping_default');

        $gateways = [];

        $shipping = Option::get('cart_shipping', []);

        $gateways = apply_filters('shipping_gateways', $gateways, $shipping);

        foreach ($gateways as $key => &$gateway) {

            if(empty($shipping[$key]) || !have_posts($shipping[$key])) {
                $config = [];
            }
            else {
                $config = $shipping[$key];
            }

            if(!empty($config) && have_posts($config)) {
                $config['icon']  = (!empty($gateway['icon'])) ? $gateway['icon'] : Url::base(CART_PATH.'assets/images/shipping.png');
                $config['label']  = (!empty($gateway['label'])) ? $gateway['label'] : '';
                $gateway         = array_merge($gateway, $config);
            }

            $gateway['enabled']     = (empty($config['enabled'])) ? 0 : 1;

            $gateway['title']       = (isset($config['title'])) ? $config['title'] : $gateway['label'];

            $gateway['img']         = (!empty($config['img'])) ? $config['img'] : Url::base(CART_PATH.'assets/images/shipping.png');

            $gateway['description'] = (!empty($gateway['description'])) ? $gateway['description'] : '';

            $gateway['icon']        = (!empty($gateway['icon'])) ? $gateway['icon'] : Url::base(CART_PATH.'assets/images/shipping.png');

            $gateway['price_default'] = (!empty($gateway['price_default'])) ? $gateway['price_default'] : 0;

            if(!empty($default) && $key == $default) {
                $gateway['default'] = 1;
            }
            else {
                $gateway['default'] = 0;
            }

            if(!empty($gateway['config']) && have_posts($gateway['config'])) {
                $gateway = array_merge($gateway, $gateway['config']);
            }
        }

        if(!empty($ke_gateways)) {

            return Arr::get($gateways, $ke_gateways);
        }

        return $gateways;
    }
}

Class Cart_Notice {
    static public function add($message, $notice_type = 'success') {
        $notices = (isset($_SESSION['wc_notices']))?$_SESSION['wc_notices']:[];
        // Backward compatibility.
        if ( 'success' === $notice_type ) {
            $message = apply_filters( 'cart_add_message', $message );
        }
        $message = Str::clear( $message );
        $notices[ $notice_type ][] = apply_filters( 'cart_add_' . $notice_type, $message );
        $_SESSION['cart_notices'] = $notices;
    }
    static public function get($notice_type = '') {
        $all_notices = (isset($_SESSION['cart_notices']))?$_SESSION['cart_notices']:[];
        if (empty($notice_type)) {
            $notices = $all_notices;
            unset( $_SESSION['cart_notices'] );
        } elseif ( isset( $all_notices[ $notice_type ] ) ) {
            $notices = $all_notices[ $notice_type ];
            unset( $_SESSION['cart_notices'][$notice_type] );
        } else {
            $notices = [];
        }
        return $notices;
    }
    static public function print($message, $notice_type = 'success', $return = true) {
        if ('success' === $notice_type) {
            $message = apply_filters('cart_add_message', $message);
        }
        return cart_template( "notices/{$notice_type}", array(
            'messages' => array( apply_filters( 'cart_add_' . $notice_type, $message ) ),
        ), $return );
    }
    static public function printLabel( $message, $notice_type = 'success', $return = true ) {
        if ('success' === $notice_type) {
            $message = apply_filters( 'cart_add_message_label', $message );
        }
        return apply_filters( 'cart_add_' . $notice_type.'_label', $message );
    }
}

