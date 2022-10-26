<?php
class AdminCacheManager {
    static function getsObject() {
        $cache = array(
            'cms' => array(
                'label' => 'Clear CMS caching: database caching, static blocks... Chạy lệnh này nếu bạn không thấy sự thay đổi khi cập nhật dữ liệu.',
                'btnlabel' => 'Clear All CMS Cache',
                'color'=> 'red'
            ),
            'option' => array(
                'label' => 'Clear CMS option: Xóa cache các option cấu hình của website',
                'btnlabel' => 'Xóa cache cấu hình website',
                'color'=> 'green'
            ),
            'widget' => array(
                'label' => 'Clear CMS widget: Xóa cache widget, server widget',
                'btnlabel' => 'Xóa cache widget',
                'color'=> 'green'
            ),
            'gallery' => array(
                'label' => 'Clear CMS gallery: Xóa cache gallery bài viết, sản phẩm, trang...',
                'btnlabel' => 'Xóa cache gallery',
                'color'=> 'green'
            ),
            'category' => array(
                'label' => 'Clear CMS category: Xóa cache danh mục bài viết',
                'btnlabel' => 'Xóa cache category',
                'color'=> 'green'
            ),
            'user' => array(
                'label' => 'Clear CMS user: Xóa cache thông tin thành viên',
                'btnlabel' => 'Xóa cache user',
                'color'=> 'green'
            ),
            'metadata' => array(
                'label' => 'Clear CMS metadata: Xóa cache dữ liệu metadata',
                'btnlabel' => 'Xóa cache metadata',
                'color'=> 'green'
            ),
        );
        return apply_filters('cache_manager_object', $cache);
    }
    //Xóa cache khi save table
    static function tableEditDeleteCache($module, $id = 0) {
        $listId = $id;
        if(is_numeric($id)) {
            $listId = []; $listId[] = $id;
        }
        foreach ($listId as $id) {
            if($module == 'post_categories' || $module == 'categories') {
                CacheHandler::delete('post_category_', true );
                CacheHandler::delete('breadcrumb_post_index_'.$id, true );
            }
            if($module == 'post') {
                CacheHandler::delete('breadcrumb_post_detail_'.$id, true );
            }
        }
    }

    //Xóa cache khi save object
    static function objectEditDeleteCache($id, $module) {
        $listId = $id;
        if(is_numeric($id)) {
            $listId = [];  $listId[] = $id;
        }
        foreach ($listId as $id) {

            if($module == 'post_categories' || $module == 'categories') {
                CacheHandler::delete('post_category_', true );
                CacheHandler::delete('breadcrumb_post_index_'.$id, true);
                CacheHandler::delete('breadcrumb_post_detail_', true);
            }

            if($module == 'page') {
                $ci = get_instance();
                $page = Pages::get($id);
                if(isset($ci->data['object']->slug) && $ci->data['object']->slug != $page->slug) {
                    CacheHandler::delete( 'page_'.md5($ci->data['object']->slug), true );
                }
                CacheHandler::delete('page_'.md5($page->id), true );
                CacheHandler::delete('page_'.md5($page->slug), true );
                CacheHandler::delete('breadcrumb_page_detail_'.$page->id, true );
            }

            if($module == 'post') {
                CacheHandler::delete( 'breadcrumb_post_detail_'.$id, true);
            }
        }
    }
}

//Xóa cache khi cho vào thùng rác
add_action('ajax_trash_object_success',    'AdminCacheManager::tableEditDeleteCache', 10, 2);
//xóa cache khi xóa danh mục
add_action('ajax_delete_after_success',    'AdminCacheManager::tableEditDeleteCache', 10, 2);
//xóa cache khi up hiển thị
add_action('up_boolean_success',           'AdminCacheManager::tableEditDeleteCache', 10, 2);
//xóa cache khi up thứ tự
add_action('up_table_success',             'AdminCacheManager::tableEditDeleteCache', 10, 2);
//Xóa dữ liệu dữ liệu khi save
add_action('save_object', 'AdminCacheManager::objectEditDeleteCache', 10, 2);