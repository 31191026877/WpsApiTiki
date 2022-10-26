<?php
Class ThemeWidget {
    static public function registerSidebar() {
        $layout_home = Option::get('layout_home', 'layout-home-1');
        Sidebar::add('home-index', 'Trang chủ');
        if($layout_home == 'layout-home-3') {
            Sidebar::add('sidebar-main','Sidebar');
        }
        if($layout_home == 'layout-home-2') {
            Sidebar::add('footer-top', 'Footer Top');
        }
        Sidebar::add('footer-main', 'Footer');
    }
    static public function loadWidget() {
        foreach (glob(Path::theme().'/widget/*.widget.php') as $filename) {
            include_once $filename;
        }
        foreach (glob(Path::theme().'/widget/*', GLOB_ONLYDIR) as $foldername) {
            foreach (glob($foldername.'/*.widget.php') as $filename) {
                include_once $filename;
            }
        }
    }
    static public function registerHeading($key = '') {
        $heading = apply_filters('theme_widget_heading', []);
        return (!empty($key)) ? Arr::get($heading, $key) : $heading;
    }
    static public function heading($name, $options, $id) {
        if(empty($name)) return false;
        $sidebar = (empty($options['style']) || $options['style'] == 'none') ? Option::get('widget_heading_style') : $options['style'];
        if(empty($sidebar) || $sidebar == 'none') {
            self::headingHtmlDefault($name);
        }
        else {
            $style = self::registerHeading($sidebar.'.class');
            if(!empty($style) && class_exists($style)) {
                $style::html($name, $options);
            }
            else self::headingHtmlDefault($name);

        }
        self::headingCss($sidebar, $options, $id);
    }
    static public function headingCss($style = '', $options = [], $id = '') {
        $sidebar = (empty($style) || $style == 'none') ? Option::get('widget_heading_style') : $style;
        $style = self::registerHeading($sidebar.'.class');
        $id = (!empty($id)) ? $id : '.js_widget_builder';
        if(!empty($style) && class_exists($style)) $style::css($options, $id);
    }
    static public function headingHtmlDefault($name) {
        ?><div class="header-title"><p class="header"><?= $name;?></p></div><?php
    }
}
add_action('init', 'ThemeWidget::registerSidebar', 10);
//add style add script
add_action('theme_custom_css', 'ThemeWidget::headingCss', 10);
//auto load
ThemeWidget::loadWidget();

