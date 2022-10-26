<?php
do_action( 'before_products_index' );
do_action('view_' . Template::getPage() . '_before', $category, $objects);
/**
 * content_products_index
 *
 * @Hook  woocommerce_products_index - 10
 */
do_action( 'content_products_index' );
do_action( 'after_products_index' );