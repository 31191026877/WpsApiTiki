<?php
/**
Plugin name     : sicommerce
Plugin class    : sicommerce
Plugin uri      : http://sikido.vn
Description     : Tạo và quản lý sản phẩm thương mại của bạn.
Author          : Nguyễn Hữu Trọng
Version         : 3.7.6
*/
const PRODUCT_NAME      = 'sicommerce';

const PRODUCT_VERSION   = '3.7.6';

const PRODUCT_TEMPLATE  = '2.1.0';

const PRODUCT_URL       = URL_ADMIN . '/plugins?page=' . PRODUCT_NAME . '&view=';

define("PRODUCT_PATH", Path::plugin(PRODUCT_NAME).'/');

class Sicommerce {

    private string $name = 'sicommerce';

    function __construct() {
        $this->loadDependencies();
        if(!Admin::is() && Admin::isRoot()) { new Sicommerce_List_Hook(); }
        new ProductsCache();
        new ProductsBreadcrumb();
    }

    public function active(): void {
        ProductActivator::activate();
    }

    public function uninstall(): void {
        ProductDeactivator::uninstall();
    }

    private function loadDependencies(): void
    {
        require_once PRODUCT_PATH.'hook.php';
        require_once PRODUCT_PATH.'function.php';
        require_once PRODUCT_PATH.'ajax.php';
        require_once PRODUCT_PATH.'cache.php';
        require_once PRODUCT_PATH.'sidebar.php';
        if(Admin::is()) {
            require_once PRODUCT_PATH.'update.php';
            require_once PRODUCT_PATH.'admin.php';
        }
        else {
            require_once PRODUCT_PATH.'controller.php';
        }
        require_once PRODUCT_PATH.'template.php';
    }

    static function url($key): string
    {
        $url = [
            'setting'   => 'plugins?page=product_settings',
        ];
        return (!empty($url[$key])) ? $url[$key] : '';
    }

    static function config($key = '') {

        if(empty($key)) return '';

        if(Str::is('product_content.*', $key)) {

            $config = Option::get('product_content');

            if(!have_posts($config)) $config = [];

            if(empty($config['category'])) {
                $config['category'] = ['enable' => false];
            }

            if(empty($config['content_top'])) {
                $config['content_top'] = ['enable' => false];
            }

            if(empty($config['content_bottom'])) {
                $config['content_bottom'] = ['enable' => false];
            }

            $key = str_replace('product_content.', '', $key);

            return Arr::get($config, $key);
        }

        if(Str::is('product_sidebar.*', $key)) {

            $config = Option::get('product_sidebar');

            if(!have_posts($config)) $config = [];

            if(empty($config['category'])) {
                $config['category'] = [ 'title' => 'Sản phẩm bán chạy', 'enable' => false, 'order'  => 10];
            }

            if(empty($config['selling'])) {
                $config['selling'] = [ 'title' => 'Sản phẩm bán chạy', 'enable' => false, 'order'  => 20];
            }

            if(empty($config['hot'])) {
                $config['hot'] = [ 'title' => 'Sản phẩm nổi bật', 'enable' => false, 'order'  => 30];
            }

            if(empty($config['sale'])) {
                $config['sale'] = [ 'title' => 'Sản phẩm khuyến mãi', 'enable' => false, 'order'  => 40];
            }

            $key = str_replace('product_sidebar.', '', $key);

            return Arr::get($config, $key);
        }

        return '';
    }
}

new Sicommerce();



