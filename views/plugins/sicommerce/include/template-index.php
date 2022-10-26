<?php
/** PRODUCT-INDEX ******************************************************************/
if (!function_exists( 'page_products_index')) {
	/**
	 * @Hiển thị trang danh mục sản phẩm
	 */
	function page_products_index() {
        Prd::template( 'product_index' );
	}
	add_action('content_products_index', 'page_products_index', 10);
}

if (!function_exists( 'page_products_index_sort')) {
    function page_products_index_sort() {
        Prd::template( 'index/sort' );
    }
    add_action('page_products_index_view', 'page_products_index_sort', 10);
}

if (!function_exists( 'page_products_index_list_content_top')) {
    function page_products_index_list_content_top() {
        if(!empty(sicommerce::config('product_content.content_top.enable'))) {
            Prd::template( 'index/content-top' );
        }
    }
    add_action('page_products_index_view', 'page_products_index_list_content_top', 20);
}

if (!function_exists( 'page_products_index_list_category')) {
    function page_products_index_list_category() {
        if(!empty(sicommerce::config('product_content.category.enable'))) {
            Prd::template( 'index/categories' );
        }
    }
    add_action('page_products_index_view', 'page_products_index_list_category', 30);
}

if (!function_exists( 'page_products_index_list_product')) {
	function page_products_index_list_product() {
        Prd::template( 'index/products' );
	}
	add_action('page_products_index_view', 'page_products_index_list_product', 40);
}

if (!function_exists( 'page_products_index_pagination')) {
	/**
	 * @Hiển thị phân trang
	 */
	function page_products_index_pagination() {
        Prd::template( 'index/pagination' );
	}
	add_action('page_products_index_view', 'page_products_index_pagination', 50);
}

if (!function_exists( 'page_products_index_list_content_bottom')) {
    function page_products_index_list_content_bottom() {
        if(!empty(sicommerce::config('product_content.content_bottom.enable'))) {
            Prd::template( 'index/content-bottom' );
        }
    }
    add_action('page_products_index_view', 'page_products_index_list_content_bottom', 60);
}