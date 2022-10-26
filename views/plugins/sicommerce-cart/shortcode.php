<?php
/**
 * WooCommerce Shortcodes class.
 */
function cart_shortcode() {
    add_shortcode('woocommerce_cart' , ['Cart_ShortCode', 'pageCart']);
    add_shortcode('woocommerce_checkout' , ['Cart_ShortCode', 'pageCheckout']);
    add_shortcode('woocommerce_success' , ['Cart_ShortCode', 'pageSuccess']);

    add_shortcode('page_cart' , ['Cart_ShortCode', 'pageCart']);
    add_shortcode('page_checkout' , ['Cart_ShortCode', 'pageCheckout']);
    add_shortcode('page_success' , ['Cart_ShortCode', 'pageSuccess']);
}
add_action( 'init', 'cart_shortcode' );

Class Cart_ShortCode {
    public static function pageCart() {
        ob_start();
        if(Request::get('action') ==  'del' ) {
            $rowid = Request::get('key');
            Scart::delete($rowid);
            redirect('gio-hang');
        }
        if(Request::post('action') == 'update' ) {
            $rowid = Request::post('qty');
            foreach ($rowid as $id => $qty ) {
                $data = array( 'rowid' => $id, 'qty' => $qty );
                Scart::update($data);
            }
            redirect('gio-hang');
        }
        do_action('wcmc_page_cart');
        cart_template('cart/cart');
        return ob_get_clean();
    }
    public static function pageCheckout() {
        ob_start();
        do_action('wcmc_page_checkout');
        cart_template('checkout/checkout');
        return ob_get_clean();
    }
    public static function pageSuccess() {
        ob_start();

        $id     = (int)Request::get('id');

        $token  = Request::get('token');

        $order = Order::get( $id );

        do_action('wcmc_page_success', $order);

        $token = apply_filters('cart_success_token', $token, $order);

        if(isset($_SESSION['token']) && have_posts($order) && $_SESSION['token'] != null && $token == $_SESSION['token']) {

            if(!empty($order->_payment)) {

                $payment = payment_gateways($order->_payment);

                if(have_posts($payment)) {
                    if(class_exists($payment['class']) && method_exists($payment['class'], 'webhook')) {
                        $payment['class']::webhook($order);
                    }
                }
            }

            cart_template('success/success', ['order' => $order]);
        }

        return ob_get_clean();
    }
}