<?php
/**
Plugin name     : sicommerce cart
Plugin class    : sicommerce_cart
Plugin uri      : http://sikido.vn
Description     : Tạo giỏ hàng và quản lý sản phẩm thương mại của bạn.
Author          : SKDSoftware Dev Team
Version         : 3.4.3
*/
const CART_NAME = 'sicommerce-cart';
const CART_VERSION = '3.4.3';
const CART_DATABASE = '2.5.2';
const CART_TEMPLATE = '2.0.0';
const ORDER_WAIT = 'wc-wait-confirm';
const ORDER_CONFIRM = 'wc-confirm';
const ORDER_PROCESSING = 'wc-processing';
const ORDER_SHIPPING = 'wc-ship';
const ORDER_SHIPPING_FAIL = 'wc-ship-fail';
const ORDER_COMPLETED = 'wc-completed';
const ORDER_CANCELLED = 'wc-cancelled';
define('CART_PATH', plugin_dir_path(CART_NAME));

class Sicommerce_Cart {

    private string $name = 'sicommerce_cart';

    public function active() {
        Cart_Activator::activate();
    }

    public function uninstall() {
        Cart_Deactivator::uninstall();
    }

    static public function url($key) {
        $url = [
            'order'     => 'plugins?page=order',
            'setting'   => 'plugins?page=cart_setting',
            'attribute' => 'plugins?page=attribute',
            'report'    => 'plugins?page=report',
        ];
        return (!empty($url[$key])) ? $url[$key] : '';
    }
}
include 'update.php';
include 'function.php';
include 'ajax.php';
include 'history.php';
if(Admin::is()) {
    include 'include/active.php';
    include 'admin.php';
}
include 'emails.php';
include 'template.php';
include 'shortcode.php';
include 'payment.php';
