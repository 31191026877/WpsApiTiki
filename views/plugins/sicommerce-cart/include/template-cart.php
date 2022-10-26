<?php
if(!function_exists('product_cart_heading')) {
    function product_cart_heading() {
        cart_template('cart/cart-heading');
    }
    add_action('cart_before', 'product_cart_heading', 10);
}