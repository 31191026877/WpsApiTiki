<?php
include 'order-save/order-save-search-product.php';

include 'order-save/order-save-search-customer.php';

Class Admin_Page_Order_Add {

    function __construct() {
        add_action( 'admin_order_action_bar_heading', [$this, 'addButtonAddOrder']);
        add_action( 'order_save_sections_primary', [$this, 'renderProductItem'], 10);
        add_action( 'order_save_sections_primary', [$this, 'renderShipping'], 10);
        add_action( 'order_save_sections_primary', [$this, 'renderPayments'], 10);
        add_action( 'order_save_sections_secondary', [$this, 'renderCustomerInfo'], 10);
    }

    public function addButtonAddOrder() {
        if(Auth::hasCap('order_add')) {
            echo '<a href="'.Url::admin(sicommerce_cart::url('order').'&view=create').'" class="btn btn-default"><i class="fal fa-layer-plus"></i> Thêm đơn hàng</a>';
        }
    }

    public function renderProductItem($order) {
        cart_template('admin/order/save/product-items', array('order' => $order));
    }
    public function renderShipping($order) {
        cart_template('admin/order/save/shipping', array('order' => $order));
    }
    public function renderPayments($order) {
        cart_template('admin/order/save/payments', array('order' => $order));
    }
    public function renderCustomerInfo($order) {
        cart_template('admin/order/save/customer', array('order' => $order));
    }
}

new Admin_Page_Order_Add();

//================================ order save function ===========================================//
function order_add_total($total = 0) {
    $total = apply_filters('order_add_total', $total);
    return $total;
}
