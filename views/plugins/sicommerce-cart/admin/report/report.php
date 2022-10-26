<?php
include "ajax.php";
Class Admin_Cart_Report {
    static public function page() {
        include_once CART_PATH.'/admin/report/views/html-report.php';
    }
    static public function tabs() {
        $setting_tabs = [
            'order' => array( 'label' => 'Đơn hàng',  'callback' => 'Admin_Cart_Report::renderTab', 'icon' => '<i class="fab fa-elementor"></i>'),
        ];
        return apply_filters('admin_order_report_tabs', $setting_tabs);
    }
    static public function tabOrderSub() {
        $tabs['revenue_time'] 	= array( 'label' => 'Doanh thu theo thời gian', 		'callback' 	=> 'Admin_Cart_Report::pageTime');
        $tabs['revenue_product'] 		= array( 'label' => 'Doanh thu theo sản phẩm', 	'callback' 	=> 'Admin_Cart_Report::pageProduct');
        return apply_filters('admin_order_report_tabs_order_sub', $tabs);
    }
    static public function renderTab() {
        include_once CART_PATH.'/admin/report/views/html-report-order.php';
    }
    static public function pageTime($section) {
        include_once CART_PATH.'/admin/report/views/html-report-order-time.php';
    }
    static public function pageProduct($section) {
        include_once CART_PATH.'/admin/report/views/html-report-order-product.php';
    }
}