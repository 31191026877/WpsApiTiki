<?php
include 'customer-table.php';
include 'customer-fields.php';
include 'customer-detail.php';
include 'customer-add.php';

if(!function_exists('admin_customer_action_bar_heading') ) {
    function admin_customer_action_bar_heading() {
        $type = Request::get('type');
        ?>
        <a class="btn btn-default <?php echo (empty($type)) ? 'active' : '';?>" href="<?php echo Url::admin('users');?>"><i class="fad fa-user-shield"></i> Nhân viên</a>
        <a class="btn btn-default <?php echo ($type == 'customer') ? 'active' : '';?>" href="<?php echo Url::admin('users?type=customer');?>"><i class="fad fa-user-tag"></i> Khách hàng</a>
        <?php
    }
    add_action('admin_user_action_bar_heading', 'admin_customer_action_bar_heading');
}

if(!function_exists('edit_customer_index_args')) {

    function edit_customer_index_args($args) {

        $type = Request::get('type');

        if(empty($type)) {
            $args->where('role', '<>', 'customer');
            $args->where('username', '<>', '');
        }

        if($type == 'customer') {
            $args->whereIn('role', ['subscriber', 'customer', '']);
            $args->where('status', 'public');
        }

        return $args;
    }

    add_filter('edit_user_index_args', 'edit_customer_index_args');
}

if(!function_exists('edit_customer_metadata_save')) {

    function edit_customer_metadata_save($user_meta) {

        if (!empty(Request::post('city'))) {
            $user_meta['city'] = Str::clear( Request::post('city') );
        }

        if (!empty(Request::post('districts'))) {
            $user_meta['districts'] = Str::clear( Request::post('districts') );
        }

        return $user_meta;
    }

    add_filter('edit_user_update_profile_meta', 'edit_customer_metadata_save');
}

if(!function_exists('customer_action_status_completed') ) {
	/**
	 * [customer_action_status_completed Khi đơn hàng đã hoàn thành]
	 */
	function customer_action_status_completed( $order ) {
		$status = Str::clear(Request::post('status'));
		if( $order->status != $status ) {
            $customer = User::get($order->user_created);
            if(have_posts($customer)) {
                $customer->order_total += $order->total;
                User::insert((array)$customer);
            }
        }
	}

	add_action( 'admin_order_status_'.ORDER_COMPLETED.'_action', 'customer_action_status_completed' );
}

