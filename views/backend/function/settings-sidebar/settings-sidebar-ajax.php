<?php
function ajax_theme_setting_sidebar_clear($ci, $model) {
    $result['message'] 	= 'Cập nhật cấu hình không thành công!';
    $result['status'] 	= 'error';
    if(Request::post()) {

        $type = Request::post('type');

        if($type == 'css' || $type == 'js') {
            $ci->data['template']->minify_clear($type);
            CacheHandler::delete('theme_custom_css_minify');
        }

        if($type == 'cache') {
            CacheHandler::flush();
        }

        $result['status'] 	= 'success';
        $result['message'] 	= 'Cập nhật thành công!';
    }
    echo json_encode($result);
}
Ajax::admin('ajax_theme_setting_sidebar_clear');

function ajax_theme_setting_sidebar_save($ci, $model) {

    $result['message'] 	= 'Cập nhật cấu hình không thành công!';

    $result['status'] 	= 'empty';

    if(Request::post()) {

        $result = apply_filters('theme_setting_sidebar_save', $result);

        if($result['status'] == 'error') {

            echo json_encode($result); return false;
        }

        $result['status'] 	= 'success';

        $result['message'] 	= 'Cập nhật thành công!';
    }

    echo json_encode($result);

    return true;
}

Ajax::admin('ajax_theme_setting_sidebar_save');