<?php
class AdminSlider {
    static function navigation(): void {
        AdminMenu::addSub('theme', 'slider', 'Slider', 'plugins?page=slider', ['callback' => 'AdminSlider::slider', 'position' => 'option']);
    }
    static function assets(): void {
        Admin::asset()->location('footer')->add('slider', Path::plugin('slider').'/assets/js/slider-script.js');
    }
    static function slider(): void {
        $view = Request::get('view');

        if(empty($view)) {
            $sliders = Gallery::gets(Qr::set('object_type', 'slider'));
            include_once 'admin/views/index.php';
        }
        else if($view == 'detail') {
            $id = (int)Request::get('id');
            $slider = Gallery::get(Qr::set('id', $id)->where('object_type', 'slider'));
            $items = Gallery::getsItem(Qr::set('group_id', $id)->where('object_type', 'slider')->orderBy('order'));
            include_once 'admin/views/detail.php';
        }
    }
}

add_action('init', 'AdminSlider::navigation');
add_action('admin_init', 'AdminSlider::assets');