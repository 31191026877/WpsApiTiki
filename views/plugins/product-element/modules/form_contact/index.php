<?php
Class Product_Element_Form_Contact {

    const KEY = 'form_contact';

    public function __construct() {
        add_filter('product_element_module', [$this, 'register'], 10);
        add_filter('product_detail_info', [$this, 'render'], self::config('position'));
        add_action('theme_custom_css', array( $this, 'css'), 10);
    }

    public function register($module) {
        $module[Product_Element_Form_Contact::KEY] = [
            'label' => 'Form contact',
            'class' => 'Product_Element_Form_Contact'
        ];
        return $module;
    }

    public static function config($key = '') {

        $option = [
            'position'          => 50,
            'title'             => 'NHẬP THÔNG TIN ĐỂ ĐƯỢC TƯ VẤN',
            'placeholder'       => 'Số điện thoại của bạn',
            'button_icon'       => html_escape('<i class="fal fa-paper-plane"></i>'),
            'padding'           => '10px',
            'bg_box'            => '',
            'bg_input'          => '#fff',
            'bg_button'         => '#fff',
            'bg_button_hover'   => '#fff',
            'color_button'      => '#000',
            'color_button_hover'=> '#000',
        ];

        $option_save = Option::get('product_element_form_contact', []);

        foreach ($option as $key_default => $item) {
            if(!isset($option_save[$key_default])) $option_save[$key_default] = $item;
        }

        if(!empty($key)) return Arr::get($option_save , $key);

        return $option_save;
    }

    public static function pageConfig() {

        if(class_exists('generate_form_register')) {
            $form 	  = Form_Register::getBy('key', 'product_form_contact');
            if(!have_posts($form)) {
                $forms_default = [
                    'key'               => 'product_form_contact',
                    'name'              => 'ĐĂNG KÝ LIÊN HỆ TƯ VẤN SẢN PHẨM',
                    'is_live'           => 1,
                    'send_email'        => 0,
                    'field'             => "data|title|Data|data|true|required\nurl|excerpt|Liên kết|data|true",
                    'metadata'          => '',
                    'taxonomy'          => 'product_form_contact',
                    'taxonomy_config'   => "name='Đăng ký liên hệ tư vấn'",
                ];
                Form_Register::insert($forms_default);
            }
            include PR_EL_PATH.'/modules/'.Product_Element_Form_Contact::KEY.'/views/page-config.php';
        }
        else {
            echo notice('error', 'Plugin <b>generate_form_register</b> chưa được cài đặt.');
        }
    }

    public static function saveConfig($ci, $model) {

        $config = Product_Element_Form_Contact::config();

        $form_contact = Request::Post('form_contact');

        foreach ($config as $key => $item) {
            if(isset($form_contact[$key]))
            $config[$key] = Str::clear($form_contact[$key]);
        }

        Option::update('product_element_form_contact', $config);

        $result['status']  = 'success';

        $result['message'] = __('Lưu dữ liệu thành công');

        return $result;

    }

    public function render($object) {
        $config = Product_Element_Form_Contact::config();
        include PR_EL_PATH.'/modules/'.Product_Element_Form_Contact::KEY.'/views/element.php';
    }

    public function css() {
        include PR_EL_PATH.'/modules/'.Product_Element_Form_Contact::KEY.'/views/style-element.css';
    }
}

new Product_Element_Form_Contact();