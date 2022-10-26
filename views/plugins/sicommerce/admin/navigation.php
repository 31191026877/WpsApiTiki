<?php
if (!function_exists('admin_navigation_product')) {

    function admin_navigation_product(): void {
        //sản phẩm
        if(Auth::hasCap('product_list')) {

            AdminMenu::add('products', 'Sản phẩm', 'products', ['position' => 'post', 'icon' => '<img src="'.PRODUCT_PATH.'assets/images/wcmc.png" />']);

            AdminMenu::addSub('products', 'products', 'Sản phẩm', 'products');

            if(Auth::hasCap('product_cate_list')) {
                AdminMenu::addSub('products', 'products_categories', 'Danh mục', 'products/products-categories');
            }

            $position_settings = 'products_categories';

            foreach (Taxonomy::getCategoryByPost('products') as $taxonomy_key => $taxonomy_value) {
                $position_settings = $taxonomy_key;
                AdminMenu::addSub('products', $taxonomy_key, $taxonomy_value->labels['name'],'post/post_categories?cate_type='.$taxonomy_key.'&post_type=products');
            }

            if(Auth::hasCap('product_setting')) {
                AdminMenu::addSub('products', 'product_settings', 'Cài đặt', sicommerce::url('setting'), [
                    'callback' => 'Admin_Product_Setting::pageSetting',
                    'position' => $position_settings
                ]);
            }

            if(Auth::hasCap('product_cate_list') && (int)option::get('product_supplier') == 1) {
                AdminMenu::addSub('products', 'suppliers', 'Nhà sản xuất', 'plugins?page=suppliers', [
                    'callback' => 'SuppliersAdmin::page',
                    'position' => 'products_categories'
                ]);
            }

            if(Auth::hasCap('product_cate_list') && (int)option::get('product_brands') == 1) {
                AdminMenu::addSub('products', 'brands', 'Thương hiệu', 'plugins?page=brands', [
                    'callback' => 'BrandsAdmin::page',
                    'position' => 'products_categories'
                ]);
            }
        }
    }

    add_action('init', 'admin_navigation_product', 10);
}