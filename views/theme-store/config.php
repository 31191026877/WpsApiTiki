<?php
/**
Template Name: Store Theme
Description: Store theme là theme chuyên nghiệp được phát triển dùng cho các website bán hàng.
Version: 3.1.0
Author: Nguyễn Hữu Trọng
*/

function store_theme_support() {

	$layout_page            	= theme_layout_list(option::get('layout_page', 'layout-full-width-banner'));
	$layout_post            	= theme_layout_list(option::get('layout_post', 'layout-sidebar-right-banner-2'));
	$layout_post_category   	= theme_layout_list(option::get('layout_post_category', 'layout-sidebar-right-banner-2'));
	$layout_products_category   = theme_layout_list(option::get('layout_products_category', 'layout-sidebar-right-banner-2'));
	$gallery_support 			= option::get('gallery_template_support');

	//template 
	Template::setLayout('home_index','template-home');
    Template::setLayout('home_search','template-sidebar-none');
    Template::setLayout('page_detail',$layout_page['template']);
    Template::setLayout('post_index',$layout_post_category['template']);
    Template::setLayout('post_detail',$layout_post['template']);
    Template::setLayout('products_index',$layout_products_category['template']);
    Template::setLayout('products_detail','template-full-width');
    Template::setLayout('user_index','template-user');
    Template::setLayout('user_password','template-user');
    Template::setLayout('user_login','template-user');
    Template::setLayout('user_register','template-user');
	//template support
    Template::support('page', ['theme']);
    Template::support('post');
    Template::support('products');
    Template::support('post_categories', ['media'], ['content']);
    Template::support('products_categories', ['media'], ['excerpt','content']);
	//Gallery support
	$gallery_support_page = [];

	if(class_exists('sicommerce')) $gallery_support_page[] = 'products';

	if(!empty($gallery_support['page'])) $gallery_support_page[] = 'page';

	if(have_posts($gallery_support_page)) Template::gallerySupport( $gallery_support_page );

	if(isset($gallery_support['category']) && have_posts($gallery_support['category'])) {

		$gallery_support_category = [];

		foreach ($gallery_support['category'] as $cate_key => $cate_value) {

			if($cate_value == 1) $gallery_support_category[] = $cate_key;
		}

		if(have_posts($gallery_support_category)) Template::gallerySupport( 'post_categories', $gallery_support_category );
	}

	if(isset($gallery_support['post']) && have_posts($gallery_support['post'])) {

		$gallery_support_post = [];

		foreach ($gallery_support['post'] as $post_key => $post_value) {

			if($post_value == 1) $gallery_support_post[] = $post_key;
		}

		if(have_posts($gallery_support_post)) Template::gallerySupport('post', $gallery_support_post );
	}
}

add_action( 'init', 'store_theme_support' );

/**
 * chức năng cấu hình layout theme
 * */
include_once 'admin/theme-admin.php';
/**
 * Các input type mới
 * Cấu hình giao diện menu
 */
include_once 'theme-setting/theme-setting.php';
/**
 * Đăng ký widget
 * Cấu hình giao diện menu
 */
include_once 'widget/widget.php';
include_once 'customConfig/customConfig.php';
include_once 'tikiAPI/custom.php';

