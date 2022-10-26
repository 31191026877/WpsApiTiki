<?php
function admin_customer_profile ($tab) {
    $tab['profile']['callback'] = 'admin_page_customer_profile';
    return $tab;
}

add_filter('admin_my_action_links', 'admin_customer_profile', 30, 1);

function admin_page_customer_profile() {
    $id = (int)Request::get('id');
    if(empty($id)) $id = Auth::userID();
    $customer = User::get($id);
    if(have_posts($customer)) {
        include 'views/html-customer-detail.php';
    }
}

if(!function_exists('customer_detail_primary_content')) {

    function customer_detail_primary_content( $customer ) {
        include 'views/detail/html-content.php';
    }

    add_action('customer_detail_sections_primary', 'customer_detail_primary_content', 10, 1);
}

if(!function_exists('customer_detail_primary_order')) {

    function customer_detail_primary_order( $customer ) {
        $orders = Order::gets(Qr::set('user_created', $customer->id)->limit(5));
        include 'views/detail/html-content-order.php';
    }

    add_action('customer_detail_sections_primary', 'customer_detail_primary_order', 15, 1);
}

if(!function_exists('customer_detail_secondary_info')) {
    function customer_detail_secondary_info( $customer ) {
        include 'views/detail/html-customer-info.php';
    }
    add_action('customer_detail_sections_secondary', 'customer_detail_secondary_info', 20, 1);
}