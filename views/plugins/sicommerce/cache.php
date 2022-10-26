<?php
Class ProductsCache {

    function __construct() {
        if(Admin::is()) {
            //xóa cache khi xóa danh mục
            add_action('ajax_delete_before_success',    [$this, 'deleteCache'], 10, 2);
            //xóa cache khi up hiển thị
            add_action('up_boolean_success',            [$this, 'deleteCache'], 10, 2);
            //xóa cache khi up thứ tự
            add_action('up_table_success',              [$this, 'deleteCache'], 10, 2);
            //xóa cache khi lưu
            add_action('save_object', [$this, 'deleteCacheSave'], 10, 2);

            //Cache Editor manager
            add_filter('cache_manager_object', [$this, 'registerCacheManager'], 1);
        }
    }

    public function deleteCache($module, $id) {
        if( $module == 'products_categories') {
            CacheHandler::delete( 'products_', true );
        }
        if( $module == 'products') {
            CacheHandler::delete( 'products_', true );
        }
    }

    public function deleteCacheSave($id, $module) {
        if($module == 'products_categories' || $module == 'products') {
            $this->deleteCache($module, $id);
            ProductsBreadcrumb::deleteCache($module, $id);
        }
    }

    public function registerCacheManager($cache) {
        $cache['product_category'] = array(
            'label'     => 'Clear product category: Xóa dữ liệu cache danh mục sản phẩm.',
            'btnlabel'  => 'Xóa cache product category',
            'color'     => 'green',
            'callback'  => 'product_category_cache_manager'
        );
        return $cache;
    }
}

if(!function_exists('product_category_cache_manager')) {
    function product_category_cache_manager(): void {
        CacheHandler::delete('products_categories_', true);
        CacheHandler::delete('products_category_', true);
        CacheHandler::delete('breadcrumb_products_index_', true);
    }
}