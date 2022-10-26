<?php
function action_bar_button ($module) {
	$ci = get_instance();
	$barUrl        = Url::adminModule();
	$btn['add']   = '<a href="'.$barUrl.'add'.$ci->urlType.'" class="btn-icon btn-green">'.Admin::icon('add').' Thêm Mới</a>';
	$btn['save']   = '<button name="save" class="btn-icon btn-green js_admin_form_btn__save">'.Admin::icon('save').' Lưu</button>';
	$btn['back']   = '<a href="'.$barUrl.$ci->urlType.'" class="btn-icon btn-blue">'.Admin::icon('back').' Quay lại</a>';
	$btn['trash']  = '<button class="btn-icon btn-red trash" data-table="'.$module.'">'.Admin::icon('delete').' Xóa Tạm</button>';
	$btn['del']    = '<button class="btn-icon btn-red delete" data-table="'.$module.'">'.Admin::icon('delete').' Xóa Vĩnh Viễn</button>';
	$btn['undo']   = '<button class="btn-icon btn-blue undo" data-table="'.$module.'">'.Admin::icon('undo').' Phục hồi</button>';
	return $btn;
}
function action_bar_all_page ($module) {
}
add_action( 'action_bar_before', 'action_bar_all_page', 10 );
/**==================================================================================================
 * ACTION BAR PAGE
 * ==================================================================================================
 */
function action_bar_page_button ( $module ) {
	if(Template::isClass('page')) {
        echo '<div class="pull-left">'; do_action('action_bar_page_left', $module); echo '</div>';
        echo '<div class="pull-right">'; do_action('action_bar_page_right', $module); echo '</div>';
    }
}
function action_bar_page_button_right ( $module ) {
	$btn = action_bar_button($module);
	if(Template::isPage('page_index')) {
        if(Request::get('status') == 'trash' ) {
            echo $btn['back'];
        }
        else if(Auth::hasCap('add_pages')) echo $btn['add'];
    }
    if(Template::isPage('page_add')) { echo $btn['save']; echo $btn['back']; }
    if(Template::isPage('page_edit')) { echo $btn['save']; echo $btn['add']; echo $btn['back']; }

}
add_action( 'action_bar_before', 'action_bar_page_button', 10 );
add_action( 'action_bar_page_right', 'action_bar_page_button_right', 10 );
/**==================================================================================================
 * ACTION BAR POST CATEGORY
 * ==================================================================================================
 */
function action_bar_category_button ($module) {

	$ci =& get_instance();

	if(Template::isClass('post_categories')) {
        echo '<div class="pull-left">';
        do_action('action_bar_cate_left', $module);
        if($ci->cateType != null ) do_action('action_bar_cate_'.$ci->cateType.'_left', $module);
        echo '</div>';
        echo '<div class="pull-right">';
        do_action('action_bar_cate_right', $module);
        if( $ci->cateType != null ) do_action('action_bar_cate_'.$ci->cateType.'_right', $module);
        echo '</div>';
    }
}
function action_bar_post_categories_button_right ( $module ) {
	$btn = action_bar_button($module);
	if(Template::isPage('post_categories_index')) echo $btn['add'];
    if(Template::isPage('post_categories_add')) { echo $btn['save']; echo $btn['back']; }
    if(Template::isPage('post_categories_edit')) { echo $btn['save']; echo $btn['add']; echo $btn['back']; }
}
add_action('action_bar_before', 'action_bar_category_button', 10 );
add_action('action_bar_cate_right', 'action_bar_post_categories_button_right', 10 );
/**==================================================================================================
 * ACTION BAR POST
 * ==================================================================================================
 */
function action_bar_post_button ($module) {
	if(Template::isClass('post')) {
        echo '<div class="pull-left">';
        	do_action('action_bar_post_left', $module);
        	if(Admin::getCateType() != null) do_action(' '.Admin::getCateType().'_left', $module);
        echo '</div>';

        echo '<div class="pull-right">';
        	do_action('action_bar_post_right', $module);
        	if(Admin::getCateType() != null) do_action('action_bar_post_'.Admin::getCateType().'_right', $module);
        echo '</div>';
    }
}
function action_bar_post_button_right ( $module ) {
	$btn = action_bar_button( $module );
    $postType = Taxonomy::getPost(Admin::getPostType());
	if(Template::isPage('post_index')) {
        if(Request::get('status') == 'trash') {
            echo $btn['undo'];
            echo $btn['back'];
            if(!empty($postType['capabilities']['add']) && Auth::hasCap( $postType['capabilities']['add']) ) echo $btn['add'];
        }
        else {
            if(!empty($postType['capabilities']['add']) && Auth::hasCap( $postType['capabilities']['add']) ) echo $btn['add'];
        }
    }

    if(Template::isPage('post_add')) { echo $btn['save']; echo $btn['back']; }
    if(Template::isPage('post_edit')) {
        if(!empty($postType['capabilities']['edit']) && Auth::hasCap( $postType['capabilities']['edit']) ) echo $btn['save'];
        if(!empty($postType['capabilities']['add']) && Auth::hasCap( $postType['capabilities']['add']) ) echo $btn['add'];
        echo $btn['back'];
    }

}
add_action( 'action_bar_before', 'action_bar_post_button', 10 );
add_action( 'action_bar_post_right', 'action_bar_post_button_right', 10 );
/**==================================================================================================
 * ACTION BAR THEME
 * ==================================================================================================
 */
function action_bar_theme_button ( $module ) {
	if(Template::isClass('theme')) {
        echo '<div class="pull-left">'; do_action('action_bar_theme_left', $module); echo '</div>';
        echo '<div class="pull-right">'; do_action('action_bar_theme_right', $module); echo '</div>';
    }
}
function action_bar_theme_button_left ( $module ) {
    if(Template::isPage('theme_editor')) { echo '<div class="breadcrumbs"></div>'; }
}
function action_bar_theme_button_right ( $module ) {
    if(Template::isPage('theme_editor')) { echo '<div class="search"> <input type="search" placeholder="Find a file.." /> </div>'; }
    if(Template::isPage('theme_option')) { echo '<button type="button" class="btn-icon btn-green" id="item-data-save">'.Admin::icon('save').'Lưu</button>'; }
}
add_action('action_bar_before', 'action_bar_theme_button', 10 );
add_action('action_bar_theme_left', 'action_bar_theme_button_left', 10 );
add_action('action_bar_theme_right', 'action_bar_theme_button_right', 10 );
/**==================================================================================================
 * ACTION BAR SYSTEM THEME
 * ==================================================================================================
 */
function action_bar_system_button ( $module ) {
	if(Template::isPage('home_system')) {
        echo '<div class="pull-left">'; do_action('action_bar_system_left', $module); echo '</div>';
        echo '<div class="pull-right">'; do_action('action_bar_system_right', $module); echo '</div>';
    }
}
function action_bar_system_button_left ( $module ) {
    echo '<a href="'.Url::admin('system').'" class="btn-icon btn-blue">'.admin_button_icon('back').' Quay lại</a>';
}
function action_bar_system_button_right ( $module ) {
    echo '<button type="submit" class="btn-icon btn-green" form="system_form">'.admin_button_icon('save').'Lưu</button>';
}
add_action( 'action_bar_before', 'action_bar_system_button', 10 );
add_action( 'action_bar_system_left', 'action_bar_system_button_left', 10 );
add_action( 'action_bar_system_right', 'action_bar_system_button_right', 10 );
/**==================================================================================================
 * ACTION BAR WIDGET THEME
 * ==================================================================================================
 */
function action_bar_widgets_button( $module ) {
	if(Template::isClass('widgets')) {
        echo '<div class="pull-left">'; do_action('action_bar_widgets_left', $module); echo '</div>';
        echo '<div class="pull-right">'; do_action('action_bar_widgets_right', $module); echo '</div>';
    }
}
function action_bar_widgets_button_right($module) {
    if(Template::isPage('widgets_index')) { echo '<button class="btn-icon btn-blue" id="service-widget">'.Admin::icon('add').' Thêm widget</button>'; }
}
add_action( 'action_bar_before', 'action_bar_widgets_button', 10 );
add_action( 'action_bar_widgets_right', 'action_bar_widgets_button_right', 10 );
/**==================================================================================================
 * ACTION BAR MENU
 * ==================================================================================================
 */
function action_bar_menu_button ( $module ) {
	if(Template::isClass('menu')) {
        echo '<div class="pull-left">'; do_action('action_bar_menu_left', $module); echo '</div>';
        echo '<div class="pull-right">'; do_action('action_bar_menu_right', $module); echo '</div>';
    }
}
function action_bar_menu_button_left ( $module ) {
}
function action_bar_menu_button_right ( $module ) {
}
add_action( 'action_bar_before', 'action_bar_menu_button', 10 );
add_action( 'action_bar_menu_left', 'action_bar_menu_button_left', 10 );
add_action( 'action_bar_menu_right', 'action_bar_menu_button_right', 10 );
/**==================================================================================================
 * ACTION BAR PLUGINS
 * ==================================================================================================
 */
function action_bar_plugins_button ( $module ) {}
function action_bar_plugins_button_right ( $module ) {}
add_action( 'action_bar_before', 'action_bar_plugins_button', 10 );
add_action( 'action_bar_plugins_right', 'action_bar_plugins_button_right', 10 );
/**==================================================================================================
 * ACTION BAR USER
 * ==================================================================================================
 */
function action_bar_user_button ( $module ) {
    if(Template::isClass('users') && Request::get('page') == '') {
        echo '<div class="pull-left">'; do_action('action_bar_user_left', $module); echo '</div>';
        echo '<div class="pull-right">'; do_action('action_bar_user_right', $module); echo '</div>';
    }
}
function action_bar_user_button_right ( $module ) {
    $bar_url        = Url::adminModule();
    if(Template::isPage('users_index')) : ?>
        <?php if(Auth::hasCap('create_users') ) { ?><a href="<?php echo $bar_url;?>add" class="btn-green btn-icon"><?php echo admin_button_icon('add');?> Thêm thành viên</a><?php } ?>
    <?php endif;
    if(Template::isPage('users_add')) : ?>
        <?php if(Auth::hasCap('create_users') ) { ?><button type="submit" class="btn-green btn-icon"><?php echo admin_button_icon('add');?> Lưu thông tin</button><?php } ?>
    <?php endif;
}
add_action('action_bar_before', 'action_bar_user_button', 10 );
add_action('action_bar_user_right', 'action_bar_user_button_right', 10 );