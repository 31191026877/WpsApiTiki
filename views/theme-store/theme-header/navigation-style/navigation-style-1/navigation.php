<?php
Class ThemeNavigationStyle1 {

    public static $path = 'theme-header/navigation-style/navigation-style-1/';

    static function render() {
        Template::partial(self::$path.'navigation-html');
    }
    static function css() {
        Template::partial(self::$path.'navigation-css');
    }
    static function options() {
        Template::partial(self::$path.'navigation-option');
    }
    static function menuOption(): void {
        ThemeMenu::addItemOption('menu', [
            'field' => 'menuDisplay',
            'label' => 'Loại hiển thị',
            'type'  => 'select',
            'options' => [
                'normal' => 'Menu cơ bản',
                'mega'   => 'Menu mega'
            ],
            'value' => 'normal',
            'level' => 0
        ]);
    }
}

if(!Admin::is()) {
    add_action('cle_header_navigation', 'ThemeNavigationStyle1::render');
    add_action('theme_custom_css_no_tag', 'ThemeNavigationStyle1::css');
}
else {
    add_action('init', 'ThemeNavigationStyle1::menuOption');
    add_action('theme_option_setup', 'ThemeNavigationStyle1::options', 30);
}