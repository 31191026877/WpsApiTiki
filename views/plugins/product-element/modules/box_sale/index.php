<?php
Class Product_Element_Box_Sale {

    const KEY = 'box_sale';

    public function __construct() {
        add_filter('product_element_module', [$this, 'register'], 10);
        add_filter('product_detail_info', [$this, 'render'], self::config('position'));
        add_action('theme_custom_css', [$this, 'css'], 10);
        if(Admin::is()) {
            Metabox::add('admin_product_element_box_sale', 'Khuyến mãi', 'admin_product_element_box_sale', ['module' => 'products']);
            function admin_product_element_box_sale($object) { Product_Element_Box_Sale::metaboxSale($object);}
            add_action('save_object', [$this, 'saveSale'], 10, 2);
        }
    }

    public function register($module) {
        $module[Product_Element_Box_Sale::KEY] = [
            'label' => 'Khung khuyến mãi',
            'class' => 'Product_Element_Box_Sale'
        ];
        return $module;
    }

    public static function config($key = '') {

        $option = [
            'position'          => 50,
            'style'             => 1,
            'title'             => 'QUÀ TẶNG & KHUYẾN MÃI',
            'padding'           => '20px',
            'margin'            => '50px 0 30px 0',
            'bg_box'            => '',
            'text_color'        => '#fff',
        ];

        $option_save = Option::get('product_element_box_sale', []);

        foreach ($option as $key_default => $item) {
            if(!isset($option_save[$key_default])) $option_save[$key_default] = $item;
        }

        if(!empty($key)) return Arr::get($option_save , $key);

        return $option_save;
    }

    public static function pageConfig() {
        include PR_EL_PATH.'/modules/'.Product_Element_Box_Sale::KEY.'/views/page-config.php';
    }

    public static function saveConfig($ci, $model) {

        $config = Product_Element_Box_Sale::config();

        $form_contact = Request::Post('box_sale');

        foreach ($config as $key => $item) {
            if(isset($form_contact[$key]))
                $config[$key] = Str::clear($form_contact[$key]);
        }

        Option::update('product_element_box_sale', $config);

        $result['status']  = 'success';

        $result['message'] = __('Lưu dữ liệu thành công');

        return $result;

    }

    /****===================================
     * Xử lý metabox
    ===================================*/
    public static function metaboxSale($object) {
        $sale = (have_posts($object)) ? Product::getMeta($object->id, 'box_sale', true) : '';
        $Form = new FormBuilder();
        $Form->add('box_sale', 'wysiwyg', ['label' => 'Khuyến mãi'], $sale)->html(false);
    }

    public static function saveSale($product_id, $module) {
        if($module == 'products') {
            $sale = Request::Post('box_sale', ['clear' => false]);
            Product::updateMeta($product_id, 'box_sale', $sale);
        }
    }

    /****===================================
     * Render frontend
    ===================================*/
    public function render($object) {
        $sale   = Product::getMeta($object->id, 'box_sale', true);
        if(!empty($sale) && $sale != '<p><br data-mce-bogus="1"></p>') {
            $config = Product_Element_Box_Sale::config();
            if ($config['style'] == 1) {
                include PR_EL_PATH . '/modules/' . Product_Element_Box_Sale::KEY . '/views/element.php';
            } else {
                include PR_EL_PATH . '/modules/' . Product_Element_Box_Sale::KEY . '/views/element-2.php';
            }
        }
    }

    public function css() {
        include PR_EL_PATH.'/modules/'.Product_Element_Box_Sale::KEY.'/views/style-element.css';
    }
}

new Product_Element_Box_Sale();