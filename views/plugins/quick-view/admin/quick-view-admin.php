<?php
if(!function_exists('admin_quick_view_settings_tabs')) {
    function admin_quick_view_settings_tabs( $tabs ) {
        $tabs['quick_view'] 	= array( 'label' => 'Quick view', 	'callback' => 'admin_page_quick_view_settings');
        return $tabs;
    }
    add_filter( 'admin_product_settings_tabs', 'admin_quick_view_settings_tabs' );
}

if(!function_exists('admin_page_quick_view_settings')) {
    function admin_page_quick_view_settings($ci, $tab) {
        include_once 'views/html-settings.php';
    }
}

if(!function_exists('admin_quick_view_ajax_object_save')) {

    function admin_quick_view_ajax_object_save($ci, $model) {

        $result['status'] 	= 'error';

        $result['message'] 	= 'Lưu dữ liệu không thành công!';

        if(InputBuilder::post()) {
            $data   = InputBuilder::post();
            $active = InputBuilder::Post('style');
            unset($data['action']);
            unset($data['post_type']);
            unset($data['cate_type']);
            unset($data['object_key']);
            unset($data['style']);
            Option::update('quick_view_active', $active);
            Option::update('quick_view_config', $data);
            $result['status'] = 'success';
            $result['message'] = 'Lưu dữ liệu thành công';
        }

        echo json_encode($result);
    }
    Ajax::admin('admin_quick_view_ajax_object_save');
}