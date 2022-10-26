<?php
Class Product_Admin_Setting_Index {
    function __construct() {
        add_action('admin_product_setting_index', 'Product_Admin_Setting_Index::productIndexNumber', 10);
        add_action('admin_product_setting_index', 'Product_Admin_Setting_Index::productIndexSidebar', 20);
        add_action('admin_product_setting_index', 'Product_Admin_Setting_Index::productIndexContent', 30);
    }
    //Danh mục sản phẩm
    public static function productIndexNumber() {
        $product_pr_page           = option::get('product_pr_page');
        $category_row_count        = option::get('category_row_count');
        $category_row_count_tablet = option::get('category_row_count_tablet');
        $category_row_count_mobile = option::get('category_row_count_mobile');
        Prd::templateInclude('admin/views/settings/product-index/product-index-number', [
            'product_pr_page' => $product_pr_page,
            'category_row_count' => $category_row_count,
            'category_row_count_tablet' => $category_row_count_tablet,
            'category_row_count_mobile' => $category_row_count_mobile,
        ]);
    }
    public static function productIndexSidebar() {
        Prd::templateInclude('admin/views/settings/product-index/product-index-sidebar');
    }
    public static function productIndexContent() {
        Prd::templateInclude('admin/views/settings/product-index/product-index-content');
    }
}