<?php
Class Theme_Setting_Sidebar_Layout {

    public static function content() {
        $layout_list                = theme_layout_list();
        if(Template::isPage('home_index')) self::layoutHome($layout_list);
        if(Template::isPage('page_detail')) self::layoutPage($layout_list);
        if(Template::isPage('post_index')) self::layoutPostIndex($layout_list);
        if(Template::isPage('post_detail')) self::layoutPostDetail($layout_list);
        if(Template::isPage('products_index')) self::layoutProductIndex($layout_list);
        if(Template::isPage('products_detail')) self::layoutProductDetail($layout_list);
    }

    public static function layoutHome($layout_list) {
        $layout_home = Option::get('layout_home',              'layout-home-1');
        include 'html/setting-sidebar/layout-home.php';
    }

    public static function layoutPage($layout_list) {
        $layout_page = option::get('layout_page',              'layout-full-width-banner');
        include 'html/setting-sidebar/layout-page-index.php';
    }

    public static function layoutPostIndex($layout_list) {
        $layout_post_category = option::get('layout_post_category',     'layout-sidebar-right-banner-2');
        include 'html/setting-sidebar/layout-post-index.php';
    }

    public static function layoutPostDetail($layout_list) {
        $layout_post = option::get('layout_post', 'layout-sidebar-right-banner-2');
        include 'html/setting-sidebar/layout-post-detail.php';
    }

    public static function layoutProductIndex($layout_list) {
        $layout_products_category   = Option::get('layout_products_category', 'layout-sidebar-right-banner-2');
        include 'html/setting-sidebar/layout-product-index.php';
    }

    public static function layoutProductDetail($layout_list) {
        $layout_products            = Option::get('layout_products',          'layout-products-1');
        include 'html/setting-sidebar/layout-product-detail.php';
    }

    public static function save($result) {

        if($result['status'] == 'error') return $result;

        $layout 				= Request::post('layout');

        $layout_list = theme_layout_list();

        if(!empty($layout['home-layout'])) {

            $layout_home = Str::clear($layout['home-layout']);

            Option::update('layout_home', $layout_home );
        }

        if(!empty($layout['page-layout'])) {

            $layout_page  = Str::clear($layout['page-layout']);

            if(isset($layout_list[$layout_page])) {
                Option::update('layout_page', $layout_page );
            }
        }

        if(!empty($layout['post-layout'])) {

            $layout_post  = Str::clear($layout['post-layout']);

            if(isset($layout_list[$layout_post])) {
                Option::update('layout_post', $layout_post );
            }
        }

        if(!empty($layout['post-category-layout'])) {

            $layout_post_category  = Str::clear($layout['post-category-layout']);

            if(isset($layout_list[$layout_post_category])) {
                Option::update('layout_post_category', $layout_post_category );
            }
        }

        if(isset($layout['products-category-layout'])) {

            $layout_products_category   = Str::clear($layout['products-category-layout']);

            if(isset($layout_list[$layout_products_category])) 	Option::update('layout_products_category', $layout_products_category );
        }

        if(isset($layout['products-layout'])) {

            $layout_products   = Str::clear($layout['products-layout']);

            Option::update('layout_products', $layout_products );
        }

        $result['message'] 	= 'Cập nhật dữ liệu thành công';

        $result['status'] 	= 'success';

        return $result;
    }
}

add_action('setting_sidebar_template', 'Theme_Setting_Sidebar_Layout::content');

add_filter('theme_setting_sidebar_save', 'Theme_Setting_Sidebar_Layout::save');