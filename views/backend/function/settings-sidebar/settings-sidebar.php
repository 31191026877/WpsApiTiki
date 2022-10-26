<?php
if(!Admin::isRoot()) return false;
include_once 'settings-sidebar-ajax.php';
if(!function_exists('theme_setting_sidebar_view'))  {
    function theme_setting_sidebar_view() {
        include 'html/setting-sidebar.php';
    }
    add_action('cle_footer', 'theme_setting_sidebar_view');
}