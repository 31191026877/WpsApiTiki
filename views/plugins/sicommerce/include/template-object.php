<?php
/** PRODUCT-OBJECT ******************************************************************/
if(!function_exists('product_object_image')) {
	function product_object_image( $val ) {
        $image_type = apply_filters('product_object_image_type', 'source');
        Prd::template( 'object/image', array('val' => $val, 'image_type' => $image_type ) );
	}
}

if(!function_exists('product_object_title')) {
	function product_object_title( $val ) {
        Prd::template( 'object/title', array('val' => $val ) );
	}
}

if(!function_exists( 'product_object_price' ) ) {
	function product_object_price( $val ) {
        Prd::template( 'object/price', array('val' => $val ) );
	}
}

if(!function_exists('product_object_description' ) ) {
	function product_object_description( $val ) {
        Prd::template( 'object/description', array('val' => $val ) );
	}
}

if(!function_exists('product_object')) {
	function product_object() {
        $product_hidden_title      	= Prd::itemStyle('title.desktop.show');
        $product_hidden_price      	= Prd::itemStyle('price.desktop.show');
        add_action( 'product_object_image',	'product_object_image', 10, 1);
        if($product_hidden_title) add_action('product_object_info',	'product_object_title', Prd::itemStyle('title.position'));
        if($product_hidden_price) add_action('product_object_info',	'product_object_price', Prd::itemStyle('price.position'));
	}
	add_action( 'init','product_object', 10 );
}