<?php

class widget_slider_style_1 extends widget {

    function __construct() {

        parent::__construct('widget_slider', 'Slider', ['container' => true, 'position' => 'right']);

        $this->tags = ['slider'];

        $this->author = 'SKDSoftware Dev Team';

    }

    function form( $left = [], $right = []) {



        $sliders = Gallery::gets(Qr::set('object_type','slider'));



        $options = [];



        foreach ($sliders as $val) { $options[$val->id] = $val->name; }



        $this->left

            ->add('gallery', 'select', ['label' =>'Nguồn slider', 'options' => $options])

            ->add('ratioWidth', 'number', ['label' => 'Tỉ lệ chiều ngang', 'value' => 3, 'step'  => 0.1, 'after' => '<div class="col-md-6 form-group group">', 'before'=> '</div>'])

            ->add('ratioHeight', 'number', ['label' => 'Tỉ lệ chiều cao','value' => 1, 'step'  => 0.1, 'after' => '<div class="col-md-6 form-group group">', 'before'=> '</div>']);



        parent::form($left, $right);

    }

    function widget() {

        $box = $this->container_box('widget_slider');

        $slider = Gallery::get(Qr::set('id', $this->options->gallery)->where('object_type', 'slider'));
        $items = Gallery::getsItem(Qr::set('group_id',$this->options->gallery)->where('object_type', 'slider')->orderBy('order'));
        show_r($this->options->gallery);

        echo $box['before'];

        if(Plugin::isActive('slider')) {

            Slider::render($this->options->gallery, ['id' => $this->id,'ratioWidth' => $this->options->ratioWidth,'ratioHeight' => $this->options->ratioHeight,]);

        }

        echo $box['after'];

    }

    function default() {

        if(!isset($this->options->ratioWidth)) $this->options->ratioWidth = 3;

        if(!isset($this->options->ratioHeight)) $this->options->ratioHeight = 1;

        if(!isset($this->options->box)) $this->options->box = 'no-container';

        if(!isset($this->options->gallery)) {

            $this->options->gallery = 1;

        }

    }

}



Widget::add('widget_slider_style_1');