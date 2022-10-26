<?php
/**
Plugin name     : Slider v2
Plugin class    : Slider
Plugin uri      : http://sikido.vn
Description     : Tạo và quản lý slider với nhiều hiệu ứng chuyển động.
Author          : SKDSoftware Dev Team
Version         : 3.0.4
 */
const SLIDER_NAME = 'slider';

define('SLIDER_PATH', Path::plugin(SLIDER_NAME).'/');

class Slider {

    private $name = 'Slider';

    function __construct() {}

    public function active(): void {}

    public function uninstall(): void {
        $model = model('group');
        $model->delete(Qr::set('object_type', 'slider'));
        $model->settable('galleries')->delete(Qr::set('object_type', 'slider'));
    }

    static function list($key = null) {
        $slider = [
            'slider1' => [
                'name'  => 'Slider 1',
                'thumb' => SLIDER_PATH.'style/slider1/thumb.png',
                'class' => 'SliderRevolution',
                'options' => false
            ],
            'slider2' => [
                'name' => 'Slider 2',
                'thumb' => SLIDER_PATH.'style/slider2/thumb.png',
                'class' => 'SliderWithTitle',
                'options' => true
            ],
            'slider3' => [
                'name' => 'Slider 3',
                'thumb' => SLIDER_PATH.'style/slider3/thumb.png',
                'class' => 'SliderNoTitle',
                'options' => false
            ]
        ];
        if($key != null) return Arr::get($slider, $key);
        return apply_filters('register_slider', $slider);
    }

    static function render($sliderId, $options = null): void {

        $slider = Gallery::get(Qr::set('id', $sliderId)->where('object_type', 'slider'));

        if(have_posts($slider)) {

            $sliderClass = Slider::list($slider->options . '.class');

            if (class_exists($sliderClass)) {
                $items = Gallery::getsItem(Qr::set('group_id', $sliderId)->where('object_type', 'slider')->orderBy('order'));
                if (have_posts($items)) {
                    $sliderClass::render($items, $slider, $options);
                }
            }
        }
    }
}
include_once 'ajax.php';
include_once 'admin.php';
include_once 'style/slider1/slider1.php';
include_once 'style/slider2/slider2.php';
include_once 'style/slider3/slider3.php';