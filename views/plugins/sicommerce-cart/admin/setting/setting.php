<?php
include "ajax.php";
if(version_compare(Cms::version(), '6.1.0', '<')) {
    class Admin_Cart_Setting
    {
        static public function page()
        {
            $ci =& get_instance();
            $views = Request::get('view');
            $tab = (int)Request::get('tab');
            include_once 'views/html-settings.php';
        }

        static public function tabs()
        {
            $tabs = [
                'payment' => array('label' => 'Thanh Toán', 'callback' => 'Admin_Cart_Setting_Payment::page', 'icon' => '<i class="fas fa-comment-dollar"></i>'),
                'shipping' => array('label' => 'Shipping', 'callback' => 'Admin_Cart_Setting_Shipping::page', 'icon' => '<i class="fal fa-shipping-fast"></i>'),
                'email' => array('label' => 'Email', 'callback' => 'Admin_Cart_Setting_Email::page', 'icon' => '<i class="fal fa-envelope"></i>'),
                'cancelled' => array('label' => 'Hủy Đơn', 'callback' => 'Admin_Cart_Setting_Cancelled::page', 'icon' => '<i class="fal fa-bell-slash"></i>'),
            ];
            if (!have_posts(payment_gateways())) unset($tabs['payment']);
            if (!have_posts(shipping_gateways())) unset($tabs['shipping']);
            return apply_filters('admin_get_cart_settings_tabs', $tabs);
        }
    }
}
else {
    add_filter('skd_system_tab' , 'Admin_Cart_Setting_Payment::register', 20);
    add_filter('skd_system_tab' , 'Admin_Cart_Setting_Shipping::register', 20);
    add_action('admin_system_cms_notification_html','Admin_Cart_Setting_Email::page', 20);
    add_filter('admin_system_cms_notification_save','Admin_Cart_Setting_Email::save',10,2);
    add_action('admin_system_cms_notification_html','Admin_Cart_Setting_Cancelled::page', 20);
    add_filter('admin_system_cms_notification_save','Admin_Cart_Setting_Cancelled::save',10,2);
}

Class Admin_Cart_Setting_Payment {
    static public function register($tabs) {
        $tabs['payment']   = [
            'label'       => 'Phương thức thanh toán',
            'description' => 'Quản lý, cấu hình phương thức thanh toán của website.',
            'callback'    => 'Admin_Cart_Setting_Payment::page',
            'icon'        => '<i class="fa-duotone fa-credit-card"></i>',
            'form'        => false,
        ];
        return $tabs;
    }
    static public function page($ci, $tab) {
        include_once 'views/html-payment.php';
    }
}

Class Admin_Cart_Setting_Shipping {
    static public function register($tabs) {
        $tabs['shipping']   = [
            'label'       => 'Vận chuyển',
            'description' => 'Quản lý, cấu hình phương thức vận chuyển của website.',
            'callback'    => 'Admin_Cart_Setting_Shipping::page',
            'icon'        => '<i class="fa-duotone fa-truck-fast"></i>',
            'form'        => false,
        ];
        return $tabs;
    }
    static public function page($ci, $tab) {
        include_once 'views/html-shipping.php';
    }
}


Class Admin_Cart_Setting_Email {
    static public function page($tab) {
        $cart_email = Option::get('cart_email',[
            'customer_order_new' 	=> 'on',
            'admin_order_new' 		=> 'on',
        ]);
        if(empty($cart_email)) {
            $cart_email = [
                'customer_order_new' 	=> 'on',
                'admin_order_new' 		=> 'on',
            ];
        }
        include_once 'views/html-settings-tab-email.php';
    }
    static public function save($result, $data) {

        if(isset($data['cart_email'])) {

            $data = $data['cart_email'];

            $cart_email['customer_order_new'] = Str::clear($data['customer_order_new']);

            $cart_email['admin_order_new'] = Str::clear($data['admin_order_new']);

            if( have_posts($cart_email)) {
                Option::update('cart_email', $cart_email);
            }
        }

        return $result;
    }
}

Class Admin_Cart_Setting_Cancelled {
    static public function page($tab) {
        $order_cancelled_reason = option::get('order_cancelled_reason', [
            'KH thay đổi / KH Hủy đơn',
            'Không liên hệ được KH',
            'Đơn hàng sai thông tin',
            'Sản phẩm không có sẳn',
        ]);
        include_once 'views/html-settings-tab-cancelled.php';
    }
    static public function save($result, $data) {

        if(isset($data['reason'])) {

            $reason = $data['reason'];

            if(have_posts($reason)) {

                foreach ($reason as $key => $item) {
                    $reason[$key]          = Str::clear($item);
                }

                Option::update('order_cancelled_reason', $reason);
            }
        }

        return $result;
    }
}