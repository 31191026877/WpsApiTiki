<?php
include 'admin/navigation.php';
include 'admin/roles.php';
include 'admin/brands/brands.php';
include 'admin/suppliers/suppliers.php';
include 'admin/categories/categories.php';
include 'admin/products/products.php';
include 'admin/setting/setting.php';

if(!function_exists('admin_scmc_assets')) {
    function admin_scmc_assets() {
        if(Admin::is() && Auth::check()) {
            Admin::asset()->location('header')->add('scmc', PRODUCT_PATH . 'assets/css/admin/admin-scmc-style.css');
            Admin::asset()->location('footer')->add('scmc', PRODUCT_PATH . 'assets/js/admin/admin-scmc-script.js');
        }
    }
    add_action('admin_init','admin_scmc_assets');
}

function admin_product_hot_key($list) {
    $list['product_add'] = ['key' => 'F2', 'label' => 'Thêm sản phẩm'];
    $list['product_category_add'] = ['key' => 'F3', 'label' => 'Thêm danh mục sản phẩm'];
    $list['product_category_quick'] = ['key' => 'CTRL + F3', 'label' => 'Thêm nhanh danh mục sản phẩm'];
    return $list;
}
add_filter('admin_list_hot_key', 'admin_product_hot_key', 10);