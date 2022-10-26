<?php
class AdminSystemStatus {
    static public function render($ci, $tab): void {
        do_action('admin_system_cms_status_html');
    }
    static public function renderStatus($tab): void {
        Admin::partial('function/system/html/status/status');
    }
    static public function renderColor($tab): void {
        $form = new FormBuilder();
        $form
            ->add('cms_config[theme_color]', 'color', [
                'label' => 'Admin Theme color',
                'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
            ], Cms::config('theme_color'))
            ->add('cms_config[menu_bg]', 'color', [
                'label' => 'Admin menu background',
                'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
            ], Cms::config('menu_bg'))
            ->add('cms_config[menu_active_bg]', 'color', [
                'label' => 'Admin menu active background',
                'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
            ], Cms::config('menu_active_bg'))
            ->add('cms_config[content_bg]', 'color', [
                'label' => 'Admin content background',
                'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
            ], Cms::config('content_bg'));

        Admin::partial('function/system/html/default', [
            'title'       => 'Màu sắc admin',
            'description' => 'Quản lý màu sắc hệ thống admin',
            'form'        => $form
        ]);
    }
    static public function renderPagination($tab): void {
        $form = new FormBuilder();
        $form
            ->add('cms_config[admin_post_number]', 'number', [
                'label' => 'Admin số post / trang',
                'after' => '<div class="col-md-4"><div class="form-group group">', 'before'=> '</div></div>'
            ], Cms::config('admin_post_number'))
            ->add('cms_config[client_post_number]', 'number', [
                'label' => 'Client số post / trang',
                'after' => '<div class="col-md-4"><div class="form-group group">', 'before'=> '</div></div>'
            ], Cms::config('client_post_number'))
            ->add('cms_config[admin_page_number]', 'number', [
                'label' => 'Admin số page / trang',
                'after' => '<div class="col-md-4"><div class="form-group group">', 'before'=> '</div></div>'
            ],
                Cms::config('admin_page_number'))
            ->add('cms_config[heading]', 'switch', [
                'label' => 'Bật / tắt tiêu đề Form',
                'after' => '<div class="col-md-4"><div class="form-group group">', 'before'=> '</div></div>'
            ], Cms::config('heading'));
        Admin::partial('function/system/html/default', [
            'title'       => 'Phân trang',
            'description' => 'Quản lý thông tin phân trang hệ thống',
            'form'        => $form
        ]);
    }
    static public function renderCache($tab): void {
        Admin::partial('function/system/html/status/cache');
    }
    static public function save($result, $data) {

        $cms['cms_status']   	    = Str::clear($data['cms_status']);

        $cms['cms_password']   	    = Str::clear($data['cms_password']);

        $cms['cms_close_title']   	= Str::clear($data['cms_close_title']);

        $cms['cms_close_content']   = Str::clear($data['cms_close_content']);

        $cms['cms_config']          = Str::clear($data['cms_config']);

        foreach ($cms as $key => $value) {
            Option::update( $key , $value );
        }

        return $result;
    }
}
add_action('admin_system_cms_status_html','AdminSystemStatus::renderStatus', 10);
add_action('admin_system_cms_status_html','AdminSystemStatus::renderColor', 20);
add_action('admin_system_cms_status_html','AdminSystemStatus::renderPagination', 30);
if(Auth::hasCap('edit_setting_cache')) {
    add_action('admin_system_cms_status_html','AdminSystemStatus::renderCache', 40);
}
add_filter('admin_system_cms_status_save','AdminSystemStatus::save',10,2);

class AdminSystemContact {
    static public function render($ci, $tab): void {
        do_action('admin_system_cms_contact_html', $tab);
    }
    static public function renderInfo($tab): void {

        $contact = [
            array(
                'label' 	=> 'Email',
                'note'		=> 'Email liên hệ dùng để nhận mail',
                'field' 	=> 'contact_mail',
                'type' 		=> 'email',
                'group'     => 'contact',
            ),
            array(
                'label' 	=> 'Điện Thoại',
                'note'		=> 'Số điện thoại chăm sóc khách hàng, hotline tư vấn...',
                'field' 	=> 'contact_phone',
                'type' 		=> 'phone',
                'group'     => 'contact',
            ),
            array(
                'label' 	=> 'Điạ chỉ',
                'note'		=> 'Địa chỉ công ty, shop của bạn.',
                'field' 	=> 'contact_address',
                'type' 		=> 'text',
                'group'     => 'contact',
            ),
        ];

        $contact = apply_filters('system_contact_input', $contact);

        $form = new FormBuilder();

        foreach ($contact as $key => $input) {
            $form->add($input['field'], $input['type'], $input, Option::get($input['field']));
        }

        Admin::partial('function/system/html/default', [
            'title'       => 'Thông tin liên hệ',
            'description' => 'Thông tin liên hệ đến chủ website email, số điện thoại, địa chỉ',
            'form'        => $form
        ]);
    }
    static public function save($result, $data) {

        $contact['contact_mail']   	= Str::clear($data['contact_mail']);

        $contact['contact_phone']   = Str::clear($data['contact_phone']);

        $contact['contact_address'] = Str::clear($data['contact_address']);

        $contact = apply_filters('skd_system_cms_contact_save', $contact, $data);

        foreach ($contact as $key => $value) {
            Option::update( $key , $value );
        }

        return $result;
    }
}
add_action('admin_system_cms_contact_html','AdminSystemContact::renderInfo', 10);
add_filter('admin_system_cms_contact_save','AdminSystemContact::save',10,2);

class AdminSystemFonts {
    static public function render($ci, $tab): void {
        Admin::partial('function/system/html/fonts');
    }
    static public function save($result, $data) {
        $fontsFamily  = $data['fonts_family'];
        foreach ($fontsFamily as $key => &$item) {
            if(empty($item['load'])) $item['load'] = '';
            if(empty($item['label'])) $item['label'] = '';
        }
        Option::update('tinymce_config_font_family', $fontsFamily);
        return $result;
    }
}
add_filter('admin_system_cms_fonts_save','AdminSystemFonts::save',10,2);

class AdminSystemNotification {
    static public function render($ci, $tab): void {
        do_action('admin_system_cms_notification_html', $tab);
    }
    static public function renderSmtp($tab): void {
        Admin::partial('function/system/html/email-smtp');
    }
    static public function save($result, $data) {

        $smtp['smtp-user']   = (!empty($data['smtp-user']))? Str::clear($data['smtp-user']) 	: '';

        $smtp['smtp-pass']   = (!empty($data['smtp-pass']))? Str::clear($data['smtp-pass']) 	: '';

        $smtp['smtp-server'] = (!empty($data['smtp-server']))? Str::clear($data['smtp-server']) : '';

        $smtp['smtp-port']   = (!empty($data['smtp-port']))? Str::clear($data['smtp-port']) 	: '';

        $smtp['smtp-encryption']   = (!empty($data['smtp-encryption']))? Str::clear($data['smtp-encryption']) 	: 'tls';

        foreach ($smtp as $key => $value) {
            Option::update( $key , $value );
        }

        return $result;
    }
}
add_action('admin_system_cms_notification_html','AdminSystemNotification::renderSmtp', 10);
add_filter('admin_system_cms_notification_save','AdminSystemNotification::save',10,2);


class AdminSystemInfo {
    static public function render($ci, $tab): void {
        Admin::partial('function/system/html/info');
    }
}