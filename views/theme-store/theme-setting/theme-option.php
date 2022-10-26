<?php
function store_theme_option() {

	$fonts = gets_theme_font();

    ThemeOption::addGroup('general',    ['position' => 10, 'label' => 'Cấu hình chung', 'icon' => '<i class="fa-light fa-screwdriver-wrench"></i>']);
    ThemeOption::addGroup('header',     ['position' => 20, 'label' => 'Header', 'icon' => '<i class="fa-light fa-heading"></i>']);
    ThemeOption::addGroup('header-mb',  ['position' => 30, 'label' => 'Header mobile', 'icon' => '<i class="fa-light fa-credit-card"></i>']);
    ThemeOption::addGroupSub('header-mb', 'menu-mb', ['name' => 'MENU MOBILE']);
    ThemeOption::addGroupSub('header-mb', 'search-mb', ['name' => 'TÌM KIẾM MOBILE']);

    ThemeOption::addGroup('nav',        ['position' => 40, 'label' => 'Navigation', 'icon' => '<i class="fa-light fa-bars"></i>']);
    ThemeOption::addGroup('map',        ['position' => 50, 'label' => 'Map', 'icon' => '<i class="fa-light fa-map-location-dot"></i>']);
    ThemeOption::addGroup('footer',     ['position' => 60, 'label' => 'Footer', 'icon' => '<i class="fa-light fa-credit-card-blank"></i>']);
    ThemeOption::addGroupSub('footer', 'footer-bottom', ['name' => 'FOOTER BOTTOM']);

    ThemeOption::addGroup('fonts',      ['position' => 70, 'label' => 'Font style', 'icon' => '<i class="fa-light fa-book-font"></i>', 'root' => true]);
    ThemeOption::addGroupSub('fonts', 'fonts-footer', ['name' => 'FONT FOOTER']);

    ThemeOption::addGroup('mobile',     ['position' => 80, 'label' => 'Mobile', 'icon' => '<i class="fa-light fa-mobile"></i>', 'root' => true]);
    //general
    ThemeOption::addField('general', 'general_label', 'text', ['label'  => 'Tên website (shop)']);
    ThemeOption::addField('general', 'banner_img', 'image', ['label'  => 'Banner các chuyên mục']);
    ThemeOption::addField('general', 'theme_color', 'color', ['label'  => 'Màu chủ đề']);
    ThemeOption::addField('general', 'bodyBg', 'background', ['label'  => 'Nền website']);
    //Header
    ThemeOption::addField('header','logo_header', 'image', ['after' => '<div class="builder-col-12 col-md-4"><label>Logo website</label><div class="form-group group">', 'before'=> '</div></div>']);
    ThemeOption::addField('header','logo_position', 'tab', [
        'options'   => [
            'left'      => '<i class="fa-thin fa-align-left"></i>&nbsp;Trái',
            'center'    => '<i class="fa-thin fa-align-center"></i>&nbsp;Giửa',
            'right'     => 'Phải&nbsp; <i class="fa-thin fa-align-right"></i>',], 'value' => 'left',
        'after' => '<div class="col-md-4 builder-col-8"><label>Vị trí logo</label><div class="form-group group">', 'before'=> '</div></div>'
    ]);
    ThemeOption::addField('header','logo_height', 'number', [
        'after' => '<div class="col-md-4 builder-col-4"><label>Chiều cao logo</label><div class="form-group group">', 'before'=> '</div></div><div class="clearfix"></div>'
    ]);
    ThemeOption::addField('header','header_bg', 'background', ['label' => 'Nền header']);

    //Header mobile
    ThemeOption::addField('header-mb', 'header_mobile_bg_color',       'color', ['after' => '<div class="builder-col-6 col-md-3"><label>Màu nền header mobile </label><div class="form-group group">', 'before'=> '</div></div>',]);
    ThemeOption::addField('header-mb', 'header_mobile_color_search',   'color', ['after' => '<div class="builder-col-6 col-md-3"><label>Màu icon tìm kiếm mobile </label><div class="form-group group">', 'before'=> '</div></div>']);
    ThemeOption::addField('header-mb', 'header_mobile_icon_menu',      'color', ['after' => '<div class="builder-col-6 col-md-3"><label>Màu icon menu </label><div class="form-group group">', 'before'=> '</div></div>']);
    ThemeOption::addField('header-mb', 'header_mobile_icon_cart',      'image', ['after' => '<div class="builder-col-6 col-md-3"><label>Icon giỏ hàng</label><div class="form-group group">', 'before'=> '</div></div>']);

    ThemeOption::addField('header-mb', 'menu_mobile_position',  'select-img',   ['sub' => 'menu-mb','label' => 'Hiển thị','options' => ['left' => [ 'label' => 'Bên trái', 'img'   => Template::imgLink('overlay-left.svg')], 'right' => [ 'label' => 'Bên phải', 'img' => Template::imgLink('overlay-right.svg')], 'center' => [ 'label' => 'Canh giữa', 'img'   => Template::imgLink('overlay-center.svg')],]]);
    ThemeOption::addField('header-mb', 'menu_mobile_bg_color',  'color',        ['sub' => 'menu-mb','after' => '<div class="builder-col-6 col-md-3"><label>Màu nền menu mobile </label><div class="form-group group">', 'before'=> '</div></div>',]);
    ThemeOption::addField('header-mb', 'menu_mobile_bg_img',    'image',        ['sub' => 'menu-mb','after' => '<div class="builder-col-6 col-md-3"><label>Hình nền menu mobile </label><div class="form-group group">', 'before'=> '</div></div>']);
    ThemeOption::addField('header-mb', 'menu_mobile_txt_color', 'color',        ['sub' => 'menu-mb','after' => '<div class="builder-col-6 col-md-3"><label>Màu chữ menu mobile </label><div class="form-group group">', 'before'=> '</div></div>']);
    ThemeOption::addField('header-mb', 'menu_mobile_br_color',  'color',        ['sub' => 'menu-mb','after' => '<div class="builder-col-6 col-md-3"><label>Màu đường kẽ menu mobile </label><div class="form-group group">', 'before'=> '</div></div>']);

    ThemeOption::addField('header-mb', 'search_mobile_bg_color',    'color', ['sub' => 'search-mb', 'after' => '<div class="col-md-6"><label>Màu nền tìm kiếm mobile </label><div class="form-group group">', 'before'=> '</div></div>']);
    ThemeOption::addField('header-mb', 'search_mobile_bg_img',      'image', ['sub' => 'search-mb', 'after' => '<div class="col-md-6"><label>Hình nền tìm kiếm mobile </label><div class="form-group group">', 'before'=> '</div></div>']);

    //map
    ThemeOption::addField('map', 'maps_embed',   'textarea', ["label" => "Embed Map"]);
	//footer
    ThemeOption::addField('footer', 'footer_bg',  'background', ['after' => '<div class="builder-col-12 col-md-6"><label>Màu nền footer</label><div class="group">', 'before'=> '</div></div>']);
    ThemeOption::addField('footer', 'footer_text_color',    'color', ['after' => '<div class="builder-col-6 col-md-3"><label>Màu chữ footer</label><div class="group">', 'before'=> '</div></div>']);
    ThemeOption::addField('footer', 'footer_header_color',  'color', ['after' => '<div class="builder-col-6 col-md-3"><label>Màu tiêu đề Footer</label><div class="group">', 'before'=> '</div></div>']);
    ThemeOption::addField('footer', 'footer_padding_top',   'number', ['after' => '<div class="builder-col-6 col-md-3" style="margin-top: 5px;"><label>Padding Footer (trên)</label><div class="group">', 'before'=> '</div></div>', 'value' => '50']);
    ThemeOption::addField('footer', 'footer_padding_bottom','number', ['after' => '<div class="builder-col-6 col-md-3" style="margin-top: 5px;"><label>Padding Footer (dưới)</label><div class="group">', 'before'=> '</div></div>', 'value' => '50']);

    ThemeOption::addField('footer', 'footer_bottom_public', 'switch', ['sub' => 'footer-bottom', 'options' => 1, 'after' => '<div class="builder-col-12 col-md-4"><label>Hiển thị footer bottom</label><div class="group">', 'before'=> '</div></div>']);
    ThemeOption::addField('footer', 'footer_bottom_bg_color', 'color', ['sub' => 'footer-bottom', 'after' => '<div class="builder-col-6 col-md-4"><label>Nền footer bottom</label><div class="group">', 'before'=> '</div></div>']);
    ThemeOption::addField('footer', 'footer_bottom_text_color', 'color', ['sub' => 'footer-bottom', 'after' => '<div class="builder-col-6 col-md-4"><label>Màu chữ footer bottom</label><div class="group">', 'before'=> '</div></div>']);

	//Font Style
	$fonts2[] 	= 'Font mặc định';
	$fonts2 	= array_merge($fonts2, gets_theme_font());

    ThemeOption::addField('fonts', 'text_font',  'select', ['value' => 'left', 'options'   => $fonts, 'note'		=> 'Fonts mặc định chữ cho các thẻ p, span,...', 'after' => '<div class="col-md-6"><label>Font Chữ</label>', 'before'=> '</div>']);
    ThemeOption::addField('fonts', 'header_font',  'select', ['options'   => $fonts, 'note'	=> 'Fonts mặc định chữ cho các thẻ h1,h2,h3...', 'after' => '<div class="col-md-6"><label>Font Chữ Tiêu Đề</label>', 'before'=> '</div>']);

    ThemeOption::addField('fonts', 'footer_text_font',  'select', ['sub' => 'fonts-footer', 'value' => 'left','options'   => $fonts2, 'note' => 'Fonts chữ cho các thẻ p, span,...ở footer', 'after' => '<div class="col-md-6"><label>Footer Font</label>', 'before'=> '</div>']);
    ThemeOption::addField('fonts', 'footer_header_font',  'select', ['sub' => 'fonts-footer', 'options'   => $fonts2, 'note'		=> 'Fonts chữ cho các thẻ h1,h2,h3...ở footer', 'after' => '<div class="col-md-6"><label>Footer Font Tiêu Đề</label>', 'before'=> '</div>']);
    ThemeOption::addField('fonts', 'footer_header_size', 'tab', ['sub' => 'fonts-footer', 'label' => 'Footer Font header size', 'value' => 18, 'options' => ['11' => '11px', '13' => '13px', '15' => '15px', '16' => '16px', '17' => '17px', '18' => '18px', '20' => '20px', '32' => '32px', '42' => '42px'], 'value' => 18]);

    ThemeOption::addField('mobile', 'mobile_category_icon', 'switch', ['options' => 1, 'after' => '<div class="col-md-12"><label>Sử dụng danh mục mobile icon</label><div class="group" style="margin-bottom:10px;">', 'before'=> '</div></div>']);
    ThemeOption::addField('mobile', 'mobile_navigation', 'switch', ['options' => 1, 'after' => '<div class="col-md-12"><label>Sử dụng thanh điều hướng</label><div class="group" style="margin-bottom:10px;">', 'before'=> '</div></div>']);
    ThemeOption::addField('mobile', 'mobile_account', 'switch', ['options' => 1, 'after' => '<div class="col-md-12"><label>Hiển thị đăng nhập / đăng ký</label><div class="group" style="margin-bottom:10px;">', 'before'=> '</div></div>']);
}

function store_theme_option_update() {

    $background = Option::get('header_bg');

    if(empty($background)) {
        $background = ['color' => Option::get('header_bg_color'), 'image' => Option::get('header_bg_image')];
        Option::update('header_bg', $background);
    }
}

add_action('theme_option_setup', 'store_theme_option', 10);
add_action('init', 'store_theme_option_update', 20);