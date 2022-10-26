<?php
if(!function_exists('admin_order_action')) {
	function admin_order_action() {
		$action[''] = 'Chọn hành động...';
		$status = Order::status();
		$action['wc-status'] = array(
			'label' => 'Cập nhật trạng thái đơn hàng',
			'value' => [],
		);
		foreach ($status as $key => $value) {
			$action['wc-status']['value'][$key] = $value['label'];
		}
		return apply_filters('admin_order_action', $action);
	}
}

if(!function_exists('order_action_update_status')) {
	function order_action_update_status($order_update, $status, $order ) {
        $status = Str::clear($status);
		if($order->status != $status) $order_update['status'] = $status;
		return $order_update;
	}
	add_filter( 'admin_order_status_'.ORDER_WAIT.'_save', 'order_action_update_status', 10, 3 );
	add_filter( 'admin_order_status_'.ORDER_CONFIRM.'_save', 'order_action_update_status', 10, 3 );
	add_filter( 'admin_order_status_'.ORDER_PROCESSING.'_save', 'order_action_update_status', 10, 3 );
	add_filter( 'admin_order_status_'.ORDER_SHIPPING.'_save', 'order_action_update_status', 10, 3 );
	add_filter( 'admin_order_status_'.ORDER_SHIPPING_FAIL.'_save', 'order_action_update_status', 10, 3 );
	add_filter( 'admin_order_status_'.ORDER_COMPLETED.'_save', 'order_action_update_status', 10, 3 );
}

if( !function_exists('order_action_update_status_pay') ) {
    function order_action_update_status_pay( $order_update, $status_pay, $order ) {
        $status_pay = Str::clear($status_pay);
        if( $order->status_pay != $status_pay ) $order_update['status_pay'] = $status_pay;
        return $order_update;
    }
    add_filter( 'admin_order_status_pay_unpaid_save', 'order_action_update_status_pay', 10, 3 );
    add_filter( 'admin_order_status_pay_paid_save', 'order_action_update_status_pay', 10, 3 );
    add_filter( 'admin_order_status_pay_refunded_save', 'order_action_update_status_pay', 10, 3 );
}