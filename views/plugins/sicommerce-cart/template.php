<?php
include 'include/template-detail.php';
include 'include/template-cart.php';
include 'include/template-checkout.php';
include 'include/template-user.php';
if(!function_exists('cart_assets')) {
	function cart_assets() {
	    Template::asset()->location('header')->add('cart', CART_PATH.'assets/css/cart-style.css', ['minify' => true,'path' => ['image' => CART_PATH.'assets/images']]);
        Template::asset()->location('footer')->add('cart', CART_PATH.'assets/js/cart-script.js', ['minify' => true]);
	}
    add_action('init','cart_assets', 30);
}
if(!function_exists('cart_heading_bar')) {
	function cart_heading_bar() {
        cart_template('heading-bar');
	}
	add_action('cart_before', 'cart_heading_bar');
	add_action('checkout_before','cart_heading_bar');
	add_action('page_cart_success_before', 'cart_heading_bar');
}