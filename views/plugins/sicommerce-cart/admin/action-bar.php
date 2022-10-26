<?php
/**==================================================================================================
 * ACTION BAR order
 * ==================================================================================================
 */
function action_bar_order ( $module ) {
    if(Template::isClass('plugins')) {
        $page = Request::get('page');
        if($page == 'order') {
            echo '<div class="pull-left">'; do_action('action_bar_order_left', $module); echo '</div>';
            echo '<div class="pull-right">'; do_action('action_bar_order_right', $module); echo '</div>';
        }
    }
}
add_action( 'action_bar_before', 'action_bar_order', 10 );

function action_bar_order_button_left ( $module ) {
    $view = Request::get('view');
    if(empty($view)) {
        ?>
        <form action="<?php echo Url::admin('plugins');?>" method="get" class="order_search_form" role="form" autocomplete="off">
        	<input type="hidden" name="page" value="order">
            <?php
                $From = new FormBuilder();
                $From = apply_filters('admin_order_index_search', $From);
                $From->html(false);
                do_action('order_index_search');
            ?>
        	<button type="submit" class="btn btn-blue"><?php echo Admin::icon('search');?> Tìm</button>
        </form>
        <style>
            .order_search_form {
                display: flex; align-items: center;
            }
            .order_search_form .form-group {
                margin-bottom: 0;
            }
        </style>
        <?php
    }
}
add_action( 'action_bar_order_left', 'action_bar_order_button_left', 10 );

function action_bar_order_button_right ( $module ) {
    $view = Request::get('view');
    if($view == 'create' || $view == 'edit') {
        ?>
        <button name="save" class="btn-icon btn-green" form="order_save__form"><?php echo Admin::icon('save');?> Lưu</button><?php
    }
}
add_action( 'action_bar_order_right', 'action_bar_order_button_right', 10 );
/**==================================================================================================
 * ACTION BAR SUPPLIERS
 * ==================================================================================================
 */
function action_bar_plugin_attribute_button ($module) {
    if(Template::isClass('plugins') && Request::get('page') == 'attribute') {
        echo '<div class="pull-right">'; do_action('action_bar_plugin_attribute_right', $module); echo '</div>';
    }
}
add_action( 'action_bar_before', 'action_bar_plugin_attribute_button', 10 );

function action_bar_plugin_attribute_right ($module) {
    $view 	= Str::clear(Request::get('view'));
    if($view == '') {
        ?>
        <a href="<?php echo Url::admin(sicommerce_cart::url('attribute').'&view=add');?>" class="btn-icon btn-green"><?php echo Admin::icon('add');?> Thêm mới</a>
        <?php
    }
    if($view == 'add' || $view == 'edit') {
        ?>
        <button form="js_attribute_form_save" name="save" class="btn-icon btn-green"><?php echo Admin::icon('save');?> Lưu</button>
        <a href="<?php echo Url::admin(sicommerce_cart::url('attribute'));?>" class="btn-icon btn-blue hvr-sweep-to-right"><?php echo Admin::icon('back');?> Quay lại</a>
        <?php
    }
}
add_action( 'action_bar_plugin_attribute_right', 'action_bar_plugin_attribute_right', 10 );