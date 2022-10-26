<?php
Class Brands_Admin_Action_Bar {
    static public function registerButton($module) {
        if(Template::isClass('plugins')) {
            $page = Request::get('page');
            if($page == 'brands') {
                echo '<div class="pull-left">'; do_action('action_bar_brands_left', $module); echo '</div>';
                echo '<div class="pull-right">'; do_action('action_bar_brands_right', $module); echo '</div>';
            }
        }
    }
    static public function buttonRight($module) {
        switch (Request::get('view')) {
            case 'edit':
            case 'add':
                echo '<button name="save" class="btn-icon btn-green js_admin_form_btn__save">'.Admin::icon('save').' Lưu</button>';
                echo '<a href="'.Url::admin('plugins?page=brands').'" class="btn-icon btn-blue">'.Admin::icon('back').' Quay lại</a>';
                break;
            default:
                echo '<a href="'.Url::admin('plugins?page=brands&view=add').'" class="btn-icon btn-green">'.Admin::icon('add').' Thêm Mới</a>';
                break;
        }
    }
}
add_action('action_bar_before', 'Brands_Admin_Action_Bar::registerButton', 10);
add_action('action_bar_brands_right', 'Brands_Admin_Action_Bar::buttonRight', 10);