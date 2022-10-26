<?php
include 'admin/roles.php';
include 'admin/action-bar.php';
include 'admin/cache.php';
include 'admin/report/report.php';
include 'admin/attribute/attribute.php';
include 'admin/setting/setting.php';
include 'admin/order/order.php';
include 'admin/customer/customer.php';
include 'admin/dashboard/dashboard.php';
include 'admin/product/product.php';

Class Sicommerce_Cart_Admin {
    static public function navigation() {
        if(Auth::hasCap('order_list')) {
            AdminMenu::add('order', 'Đơn hàng', sicommerce_cart::url('order'), ['position' => 'products', 'callback' => 'Admin_Page_Order::page', 'icon' => '<img src="'.CART_PATH.'assets/images/woocommerce.png" />', 'count' => Order::count(Qr::set('status', ORDER_WAIT))]);
            if(Auth::hasCap('order_list'))     AdminMenu::addSub('order', 'order', 'Đơn hàng', sicommerce_cart::url('order'), ['callback' => 'Admin_Page_Order::page']);
            if(version_compare(Cms::version(), '6.1.0', '<')) {
                if (Auth::hasCap('order_setting')) {
                    AdminMenu::addSub('order', 'cart_setting', 'Cài đặt', sicommerce_cart::url('setting'), ['callback' => 'Admin_Cart_Setting::page']);
                }
            }
        }
        if(Auth::hasCap('attributes_list')) AdminMenu::addSub('products', 'attribute', 'Thuộc tính', 'plugins?page=attribute', ['callback' => 'Admin_Attribute_Page::page', 'position' => 'products_categories']);
        AdminMenu::addSub('order', 'report', 'Báo cáo', sicommerce_cart::url('report'), ['callback' => 'Admin_Cart_Report::page']);
    }
    static public function assets() {
        if(Auth::check()) {
            $asset = CART_PATH.'assets/';
            Admin::asset()->location('header')->add('cart-metabox', $asset.'css/admin/wc-product-metabox.css');
            Admin::asset()->location('footer')->add('cart-metabox', $asset.'js/admin/wc-product-metabox.js');
            Admin::asset()->location('footer')->add('cart-ot', $asset.'js/admin/wc-product-options.js');
            Admin::asset()->location('footer')->add('cart-order', $asset.'js/admin/wc-product-order.js');
            Admin::asset()->location('footer')->add('cart-order', $asset.'js/admin/wc-order.js');
        }
    }
    static public function deleteProduct($module, $data, $r) {
        if($module == 'products') {

            $listID = [];

            //xóa object
            if(is_numeric($data)) $listID[] = $data;

            //xóa nhiều dữ liệu
            if(have_posts($data)) $listID   = $data;

            if(have_posts($listID)) {

                $model = model('relationships');

                foreach ($listID as $product_id) {
                    Metadata::delete('product', $product_id, 'attributes');
                    $model->settable('relationships')->delete(Qr::set('object_id',$product_id)->where('object_type', 'attributes'));
                }
            }
        }
    }
    static public function hotKey($list) {
        $list['order_add'] = ['key' => 'F1', 'label' => 'Thêm đơn hàng'];
        $list['order_report'] = ['key' => 'F4', 'label' => 'Báo cáo doanh thu'];
        return $list;
    }
}

add_action('admin_init', 'Sicommerce_Cart_Admin::navigation', 20);
add_action('admin_init','Sicommerce_Cart_Admin::assets');
add_filter('admin_list_hot_key', 'Sicommerce_Cart_Admin::hotKey', 20);
/**
 * =====================================================================================================================
 * XỬ LÝ DỮ LIỆU KHI XÓA SẢN PHẨM
 * =====================================================================================================================
 */
add_action('ajax_delete_before_success', 'Sicommerce_Cart_Admin::deleteProduct', 10, 3);

