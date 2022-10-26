<?php
/**
Plugin name     : Product Element
Plugin class    : Product_Element
Plugin uri      : http://sikido.vn
Description     : Ứng dụng Product Element
Author          : Nguyễn Hữu Trọng
Version         : 1.1.0
 */
define( 'PR_EL_NAME', 'product-element' );

define( 'PR_EL_PATH', Path::plugin(PR_EL_NAME) );

class Product_Element {

    private $name = 'Product_Element';

    function __construct() {
        $this->loadDependencies();
        $this->loadModules();
        if(Admin::is()) new Product_Element_Admin();
    }

    public function active() {}

    public function uninstall() {}

    private function loadDependencies() {
        require_once PR_EL_PATH.'/admin/product-element-admin.php';
    }

    private function loadModules() {

        $module = static::config('module_active');

        if(have_posts($module)) {
            foreach ($module as $itemKey => $itemName) {
                $path = PR_EL_PATH.'/modules/'.$itemName.'/index.php';
                if(file_exists($path)) require_once $path;
            }
        }

        include_once PR_EL_PATH.'/modules/views_cart/index.php';
    }

    public static function config($key = '') {

        $option = [
            'module'           => [],
            'module_active'    => [],
        ];

        $option_save = Option::get('product_element_config', []);

        if(!isset($option_save['module'])) $option_save['module'] = $option['module'];

        if(!isset($option_save['module_active'])) $option_save['module_active'] = $option['module_active'];

        if(!empty($key)) return Arr::get($option_save , $key);

        return $option_save;
    }

    public static function module($module = '') {

        $modules = apply_filters('product_element_module', [
            'general' => [
                'label' => 'Cấu hình',
                'callback' => 'admin_page_product_element_general'
            ],
        ]);

        if(!empty($module)) return Arr::get($modules , $module);

        return $modules;
    }
}

new Product_Element();