<?php
Class Product_Element_Admin {

    public function __construct() {
        if(Admin::isRoot()) {
            AdminMenu::addSub('products', PR_EL_NAME, 'Element', 'plugins?page=' . PR_EL_NAME, [
                'callback' => 'admin_page_product_element'
            ]);
        }
    }

    public static function pageConfig() {
        $tabs = Product_Element::module();
        include PR_EL_PATH.'/admin/views/page-config.php';
    }
    public static function pageGeneral($tab, $key) {
        include PR_EL_PATH.'/admin/views/page-general.php';
    }
}

function admin_page_product_element() {
    Product_Element_Admin::pageConfig();
}

function admin_page_product_element_general($tab, $key) {
    Product_Element_Admin::pageGeneral($tab, $key);
}


Class Product_Element_Ajax {

    public function __construct() {
        Ajax::admin('admin_ajax_product_element_save');
    }

    public static function saveGeneral($ci, $modal) {

        $config = Product_Element::config();

        $module_active = Request::Post('module_active');

        if(empty($module_active)) {
            $config['module_active'] = [];
        }
        else {
            $config['module_active'] = $module_active;
        }

        if(in_array('viewscart', $config['module_active']) === true) {

            if(in_array('views', $config['module_active']) === true || in_array('buttoncart', $config['module_active']) === true) {

                $result['status']  = 'error';

                $result['message'] = __('Không thể bật đồng thời Views & Cart cùng với các element cùng loại');

                return $result;
            }
        }

        Option::update('product_element_config', $config);

        $result['status']  = 'success';

        $result['message'] = __('Lưu dữ liệu thành công');

        return $result;
    }
}

new Product_Element_Ajax();

function admin_ajax_product_element_save($ci, $modal) {
    $result['status']  = 'error';
    $result['message'] = __('Lưu dữ liệu không thành công');
    if(Request::Post()) {
        $module = trim(Request::post('module'));
        if($module == 'general') {
            $result = Product_Element_Ajax::saveGeneral($ci, $modal);
        }
        else {
            $module = Product_Element::module($module);
            if(have_posts($module) && method_exists($module['class'], 'saveConfig')) {
                $result = $module['class']::saveConfig($ci, $modal);
            }
        }
    }
    echo json_encode($result);
}