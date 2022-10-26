<?php
Class Product_Admin_Setting_Detail {
    function __construct() {
        add_action('admin_product_setting_detail', 'Product_Admin_Setting_Detail::productDetailGallery', 10);
        add_action('admin_product_setting_detail', 'Product_Admin_Setting_Detail::productDetailSelling', 20);
        add_action('admin_product_setting_detail', 'Product_Admin_Setting_Detail::productDetailWatched', 30);
        add_action('admin_product_setting_detail', 'Product_Admin_Setting_Detail::productDetailRelated', 40);
        add_action('admin_product_setting_detail', 'Product_Admin_Setting_Detail::productDetailItem', 50);
        add_action('admin_product_setting_detail', 'Product_Admin_Setting_Detail::productDetailSupport', 60);
    }
    public static function productDetailGallery() {
        $product_gallery = Option::get('product_gallery', 'product_gallery_vertical');
        Prd::templateInclude('admin/views/settings/product/product-detail-gallery', ['product_gallery' => $product_gallery]);
    }
    public static function productDetailRelated() {
        $product_related  = Option::get('product_related', array(
            'position' => 'content',
            'style' => 'slider',
            'columns' => 4,
            'posts_per_page' => 12,
        ));
        Prd::templateInclude('admin/views/settings/product/product-detail-related', ['product_related' => $product_related]);
    }
    public static function productDetailSelling() {
        $product_selling  = Option::get('product_selling', array(
            'position' => 'content',
            'data'     => 'handmade',
            'style'    => 'slider',
            'columns'  => 4,
            'posts_per_page' => 12,
        ));
        Prd::templateInclude('admin/views/settings/product/product-detail-selling', ['product_selling' => $product_selling]);
    }
    public static function productDetailWatched() {
        $product_watched  = Option::get('product_watched', array(
            'position' => 'sidebar',
            'style' => 'slider',
            'columns' => 4,
            'posts_per_page' => 12,
        ));
        Prd::templateInclude('admin/views/settings/product/product-detail-watched', ['product_watched' => $product_watched]);
    }
    public static function productDetailItem() {
        $layout = Option::get('layout_products','layout-products-1');
        if($layout == 'layout-products-2' || $layout == 'layout-products-3') {
            $product_item = Option::get('product_item', array('enable' => 0, 'title' => '', 'item' => []));
            Prd::templateInclude('admin/views/settings/product/product-detail-item', ['product_item' => $product_item]);
        }
    }
    public static function productDetailSupport() {
        $layout = Option::get('layout_products','layout-products-1');
        if($layout == 'layout-products-2' || $layout == 'layout-products-3') {
            $product_support  = option::get('product_support', array('enable' => 0, 'title' => '', 'image' => '', 'url' => 'lien-he'));
            Prd::templateInclude('admin/views/settings/product/product-detail-support', ['product_support' => $product_support]);
        }
    }
}