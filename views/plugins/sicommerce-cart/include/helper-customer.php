<?php

if( ! function_exists('insert_user_data_order_colum') ) {
    /**
     * Add colum order_total to function insert_user
     *  */
	function insert_user_data_order_colum( $data, $userdata, $old_user_data ) {

        if (!have_posts($old_user_data)) {

            $order_total 	= empty( $userdata['order_total'] ) ? 0 : $userdata['order_total'];

            $order_count 	= empty( $userdata['order_count'] ) ? 0 : $userdata['order_count'];

            $customer 	    = empty( $userdata['customer'] ) ? 0 : $userdata['customer'];
	    }
	    else {

            $order_total 	= isset($userdata['order_total']) ? Str::clear(xss_clean($userdata['order_total'])) : $old_user_data->order_total;

            $order_count 	= isset($userdata['order_count']) ? Str::clear(xss_clean($userdata['order_count'])) : $old_user_data->order_count;

            $customer 	    = isset($userdata['customer']) ? Str::clear(xss_clean($userdata['customer'])) : $old_user_data->customer;
        }
        
        $data['order_total'] = $order_total;

        $data['order_count'] = $order_count;

        $data['customer']    = $customer;

        return $data;
    }
    
    add_filter('pre_insert_user_data','insert_user_data_order_colum', 10,3);
}

if( ! function_exists('set_customer_order_total') ) {

	function set_customer_order_total($order) {

		$ci =& get_instance();

        return $order;
	}
}