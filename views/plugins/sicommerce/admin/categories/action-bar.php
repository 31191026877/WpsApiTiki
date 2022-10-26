<?php
Class Product_Category_Admin_Action_Bar {
    static public function registerButton($module) {
        $ci =& get_instance();
        if($ci->template->class == 'products_categories') {
            echo '<div class="pull-left">'; do_action('action_bar_products_categories_left', $module);  echo '</div>';
            echo '<div class="pull-right">'; do_action('action_bar_products_categories_right', $module); echo '</div>';
        }
    }
    static public function buttonRight($module) {
        if(Template::isPage('products_categories_index')) {
            if(Auth::hasCap('product_cate_edit')) {
                echo '<a href="'.Url::admin('products/products-categories/add').'" class="btn-icon btn-green">'.Admin::icon('add').' Thêm Mới (F3)</a>';
                echo '<button class="btn-icon btn-green js_products_category_quick_btn">'.Admin::icon('add').'Thêm nhanh (CTRL + F3)</button>';
            }
        }
        if(Template::isPage('products_categories_add')) {
            echo '<button name="save" class="btn-icon btn-green">'.Admin::icon('save').' Lưu</button>';
            echo '<a href="'.Url::admin('products/products-categories').'" class="btn-icon btn-blue">'.Admin::icon('back').' Quay lại</a>';
            echo '<button class="btn-icon btn-green js_products_category_quick_btn">'.Admin::icon('add').'Thêm nhanh (CTRL + F3)</button>';
        }
        if(Template::isPage('products_categories_edit')) {
            echo '<button name="save" class="btn-icon btn-green js_admin_form_btn__save">'.Admin::icon('save').' Lưu</button>';
            echo '<a href="'.Url::admin('products/products-categories/add').'" class="btn-icon btn-blue">'.Admin::icon('add').' Thêm mới</a>';
            echo '<a href="'.Url::admin('products/products-categories').'" class="btn-icon btn-blue">'.Admin::icon('back').' Quay lại</a>';
        }
    }
}
add_action( 'action_bar_before', 'Product_Category_Admin_Action_Bar::registerButton', 10);
add_action( 'action_bar_products_categories_right', 'Product_Category_Admin_Action_Bar::buttonRight', 10);