<?php
Class Product_Admin_Setting_Object {
    function __construct() {
        add_action('admin_product_setting_object', 'Product_Admin_Setting_Object::productBorder', 10);
        add_action('admin_product_setting_object', 'Product_Admin_Setting_Object::productShadow', 20);
        add_action('admin_product_setting_object', 'Product_Admin_Setting_Object::productShadowHover', 30);
        add_action('admin_product_setting_object', 'Product_Admin_Setting_Object::productImg', 40);
        add_action('admin_product_setting_object', 'Product_Admin_Setting_Object::productInfo', 50);
    }
    public static function productBorder() {
        $border = Prd::itemStyle('border');
        $borderStyle = ['none', 'solid', 'dotted', 'dashed', 'double', 'groove'];
        Prd::templateInclude('admin/views/settings/product-object/product-object-border', ['border' => $border, 'borderStyle' => $borderStyle]);
    }
    public static function productShadow() {
        $shadow = productItemStyle('shadow');
        $shadowStyle = [
            1 => ['h'=> 0, 'v' => 0, 'b' => 10, 'sp' => 10],
            2 => ['h'=> 4, 'v' => 4, 'b' => 6, 'sp' => 0],
            3 => ['h'=> 0, 'v' => 8, 'b' => 6, 'sp' => -6],
            4 => ['h'=> 4, 'v' => 4, 'b' => 0, 'sp' => 0],
            5 => ['h'=> 0, 'v' => 2, 'b' => 0, 'sp' => 4],
        ];
        Prd::templateInclude('admin/views/settings/product-object/product-object-shadow', ['shadow' => $shadow, 'shadowStyle' => $shadowStyle]);
    }
    public static function productShadowHover() {
        $shadow = Prd::itemStyle('shadowHover');
        $shadowStyle = [
            1 => ['h'=> 0, 'v' => 0, 'b' => 10, 'sp' => 10],
            2 => ['h'=> 4, 'v' => 4, 'b' => 6, 'sp' => 0],
            3 => ['h'=> 0, 'v' => 8, 'b' => 6, 'sp' => -6],
            4 => ['h'=> 4, 'v' => 4, 'b' => 0, 'sp' => 0],
            5 => ['h'=> 0, 'v' => 2, 'b' => 0, 'sp' => 4],
        ];
        Prd::templateInclude('admin/views/settings/product-object/product-object-shadow-hover', ['shadow' => $shadow, 'shadowStyle' => $shadowStyle]);
    }
    public static function productImg() {
        $img = Prd::itemStyle('img');
        Prd::templateInclude('admin/views/settings/product-object/product-object-img', ['img' => $img]);
    }
    public static function productInfo() {
        $title = Prd::itemStyle('title');
        $price = Prd::itemStyle('price');
        Prd::templateInclude('admin/views/settings/product-object/product-object-info', [
            'title' => $title,
            'price' => $price
        ]);
    }
}