<?php
if( !function_exists('admin_cart_setting_cache_manager') ) {
    
    function admin_cart_setting_cache_manager( $cache ) {

        $cache['product_order'] = array(
            'label' => 'Clear order: Xóa dữ liệu cache đơn hàng.',
            'btnlabel' => 'Xóa cache order',
            'color'=> 'green',
            'callback' => 'admin_cart_setting_cache_order'
        );

        return $cache;
    }

    add_filter('cache_manager_object', 'admin_cart_setting_cache_manager', 1);
}

if(!function_exists('admin_cart_setting_cache_order')) {
    
    function admin_cart_setting_cache_order( ) {

        delete_cache('order_', true);
    }
}