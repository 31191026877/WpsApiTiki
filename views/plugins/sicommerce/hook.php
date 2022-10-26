<?php
Class Sicommerce_List_Hook {
    function __construct() {
        if(Template::isPage('products_index')) add_filter('setting_sidebar_hook_list', [$this, 'productIndex'], 10);
        if(Template::isPage('products_detail')) add_filter('setting_sidebar_hook_list', [$this, 'productsDetail'], 10);
    }

    function productIndex($listHook) {
        $listHook['productIndexBreadcrumb'] = [
            'name' => 'Hook breadcrumb',
            'list' => [
                'breadcrumb_open',
                'breadcrumb_first',
                'breadcrumb_item',
                'breadcrumb_icon',
                'breadcrumb_item_last',
            ]
        ];
        $listHook['productIndex'] = [
            'name' => 'Hook content',
            'list' => [
                'content_products_index',
                'page_products_index_view',
            ]
        ];
        $listHook['productIndexItem'] = [
            'name' => 'Hook item product',
            'list' => [
                'product_item_before',
                'product_object_before_image',
                'product_object_image',
                'product_object_after_image',
                'product_object_info',
                'product_item_after',
            ]
        ];
        return $listHook;
    }

    function productsDetail($listHook) {
        $listHook['productDetailBreadcrumb'] = [
            'name' => 'Hook breadcrumb',
            'list' => [
                'breadcrumb_open',
                'breadcrumb_first',
                'breadcrumb_item',
                'breadcrumb_icon',
                'breadcrumb_item_last',
            ]
        ];
        $listHook['productDetail'] = [
            'name' => 'Hook content',
            'list' => [
                'product_detail_before',
                'product_detail_slider',
                'product_detail_info',
                'product_detail_support',
                'product_detail_tabs',
                'product_tabs',
                'product_detail_sidebar',
                'product_detail_after'
            ]
        ];
        return $listHook;
    }
}