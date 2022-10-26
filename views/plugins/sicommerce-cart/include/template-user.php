<?php
function wcmc_my_action_links( $args ) {
	$newArgs = [];

	foreach ($args as $key => $value) {
		if( $key == 'logout' ) {
			$newArgs['order'] = array(
				'label' => __('Đơn hàng'),
				'icon'  => '<i class="fal fa-shopping-cart"></i>',
				'url'	=> my_account_url().'/order/history',
			);
		}

		$newArgs[$key] = $value;
	}

	return $newArgs;
}

add_filter('my_action_links', 'wcmc_my_action_links');

function wcmc_my_account_order_view( $view ) {

	$ci =& get_instance();

	$method = $ci->uri->segment('3');

	$lang = $ci->uri->segment('1');

	if( (Language::default() != $ci->language['current'] || $ci->uri->segment('1') ==  Language::default()) && $lang == $ci->language['current'] ) $method = $ci->uri->segment('4');
	
	$file = 'order';

	if( $method == 'detail' ) $file = $file.'_'.$method;

	$view = get_instance()->template->name.'/'.CART_NAME.'/user/'.$file;

	if( !file_exists($view) ) $view = CART_PATH.'template/user/'.$file;

	return $view;
}

add_filter('my_account_view_order', 'wcmc_my_account_order_view');

