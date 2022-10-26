<?php
Class ProductsBreadcrumb {
    function __construct() {
        add_filter('theme_breadcrumb_id',    'ProductsBreadcrumb::registerId', 20, 2);
        add_action('theme_breadcrumb_table','ProductsBreadcrumb::registerTable', 20, 2);
        add_filter('theme_breadcrumb_data',    'ProductsBreadcrumb::data', 20, 2);

        if(Admin::is()) {
            add_action('ajax_delete_before_success',    'ProductsBreadcrumb::deleteCache', 10, 2);
        }
    }
    static function registerId($id, $page) {
        if( $page == 'products_index') {
            $category = get_object_current('category');
            if(have_posts($category)) $id = $category->id;
        }
        if( $page == 'products_detail') {
            $object = get_object_current('object');
            if(have_posts($object)) $id = $object->id;
        }
        return $id;
    }
    static function registerTable($breadcrumbTable, $page): string {
        if($page == 'products_index' || $page == 'products_detail') {
            $breadcrumbTable = 'products_categories';
        }
        return $breadcrumbTable;
    }
    static function data($breadcrumb, $page) {
        if($page == 'products_index' || $page == 'products_detail') {
            $temp[] = (object)[
                'name' => __('Sản phẩm', 'theme_san_pham'),
                'slug' => 'san-pham'
            ];
            foreach ($breadcrumb as $key => $value) {
                $temp[] = $value;
            }
            $breadcrumb = $temp;
        }

        return $breadcrumb;
    }
    static function deleteCache($module, $id): void {
        if( $module == 'products_categories') {
            if(is_array($id)) {
                foreach ($id as $item) {
                    CacheHandler::delete( 'breadcrumb_products_index_'.$item, true);
                }
            }
            else CacheHandler::delete( 'breadcrumb_products_index_'.$id, true);
            CacheHandler::delete('breadcrumb_products_detail_', true);
        }
        if( $module == 'products') {
            if(is_array($id)) {
                foreach ($id as $item) {
                    CacheHandler::delete( 'breadcrumb_products_detail_'.$item, true);
                }
            }
            else CacheHandler::delete( 'breadcrumb_products_detail_'.$id, true);
        }
    }
}