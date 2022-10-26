<?php
class SliderWithTitle {
    static function options($id) {
        $sliderOptions = Metadata::get('slider', $id, 'options', true);
        if(!have_posts($sliderOptions)) $sliderOptions = [];
        $sliderOptions['sliderTxtType'] = (isset($sliderOptions['sliderTxtType'])) ? $sliderOptions['sliderTxtType'] : 'in-slider';
        $sliderOptions['sliderTxtBg'] = (!empty($sliderOptions['sliderTxtBg'])) ? $sliderOptions['sliderTxtBg'] : 'rgba(0,0,0,0.5)';
        $sliderOptions['sliderTxtColor'] = (!empty($sliderOptions['sliderTxtColor'])) ? $sliderOptions['sliderTxtColor'] : '#fff';
        $sliderOptions['sliderTxtActive'] = (!empty($sliderOptions['sliderTxtActive'])) ? $sliderOptions['sliderTxtActive'] : 'var(--theme-color)';
        $sliderOptions['sliderTxtBgActive'] = (!empty($sliderOptions['sliderTxtBgActive'])) ? $sliderOptions['sliderTxtBgActive'] : $sliderOptions['sliderTxtBg'];
        $sliderOptions['sliderTxtFontSize'] = (!empty($sliderOptions['sliderTxtFontSize'])) ? $sliderOptions['sliderTxtFontSize'] : '14';
        return $sliderOptions;
    }
    static function optionsForm($slider): void {
        $options = Metadata::get('slider', $slider->id, 'options', true);
        include 'views/options-form.php';
    }
    static function optionsSave($slider): bool {
        $sliderOptions = [
            'sliderTxtType' => Request::post('sliderTxtType'),
            'sliderTxtBg' => Request::post('sliderTxtBg'),
            'sliderTxtColor' => Request::post('sliderTxtColor'),
            'sliderTxtActive' => Request::post('sliderTxtActive'),
            'sliderTxtBgActive' => Request::post('sliderTxtBgActive'),
            'sliderTxtFontSize' => Request::post('sliderTxtFontSize'),
        ];
        Metadata::update('slider', $slider->id, 'options', $sliderOptions);
        return true;
    }
    static function itemForm($item): void {
        $item = SliderWithTitle::metaData($item);
        include 'views/item-form.php';
    }
    static function itemSave($item): int|array|SKD_Error {

        $galleryItem = [
            'id'    => $item->id,
            'value' => Request::post('value')
        ];

        $galleryItemMeta = [
            'name'=> Request::post('name'),
            'url' => Request::post('url'),
        ];

        foreach (Language::list() as $key => $lang) {
            if($key == Language::default()) continue;
            $name = 'name_'.$key;
            $galleryItemMeta[$name] = Request::post($name);
        }

        $errors = Gallery::insertItem($galleryItem);

        if(!is_skd_error($errors)) {

            foreach ($galleryItemMeta as $meta_key => $meta_value) {
                Gallery::updateItemMeta($item->id, $meta_key, $meta_value);
            }
        }

        return $errors;
    }
    static function metaData($item): object {
        $option = [
            'url'   => Gallery::getItemMeta($item->id, 'url', true),
            'name'  => Gallery::getItemMeta($item->id, 'name', true),
        ];
        if(!Language::isDefault()) {
            $name = Gallery::getItemMeta($item->id, 'name_'.Language::current(), true);
            if(!empty($name)) $option['name'] = $name;
        }
        $item = (object)array_merge((array)$item, $option);
        return $item;
    }
    static function render($items, $slider, $options = null): void {
        SliderWithTitleHtml::render($items, $slider, $options);
    }
}

class SliderWithTitleHtml {
    static function render($items, $slider, $options = null): void {
        $sliderOptions = SliderWithTitle::options($slider->id);
        $options = (is_array($options)) ? $options : [];
        $options = array_merge(['numberItem' => count($items)], $options);
        $id = (!empty($options['id'])) ? $options['id'] : uniqid();
        ?>
        <div id="sliderWidthTitle_<?php echo $id;?>" class="sliderWidthTitle <?php echo $sliderOptions['sliderTxtType'];?> js_slider_title box-content slider_box" style="position: relative" data-id="<?php echo $id;?>" data-options="<?php echo htmlentities(json_encode($options));?>">
            <div class="arrow_box js_slider_title_arrow">
                <div class="prev arrow"><i class="fal fa-chevron-left"></i></div>
                <div class="next arrow"><i class="fal fa-chevron-right"></i></div>
            </div>
            <div id="js_slider_title_list_<?php echo $id;?>" class="js_slider_title_list slider_list_item owl-carousel">
                <?php foreach ($items as $item) {
                    SliderWithTitleHtml::item($item);
                } ?>
            </div>
            <div id="js_slider_title_thumb_<?php echo $id;?>" class="js_slider_title_thumb slider_list_thumb owl-carousel">
                <?php foreach ($items as $item) {
                    SliderWithTitleHtml::thumb($item);
                } ?>
            </div>
        </div>
        <style>
            #sliderWidthTitle_<?php echo $id;?> {
                --slider-thumb-color:<?php echo $sliderOptions['sliderTxtColor'];?>;
                --slider-thumb-color-active:<?php echo $sliderOptions['sliderTxtActive'];?>;
                --slider-thumb-bg:<?php echo $sliderOptions['sliderTxtBg'];?>;
                --slider-thumb-bg-active:<?php echo $sliderOptions['sliderTxtBgActive'];?>;
                --slider-thumb-font-size:<?php echo $sliderOptions['sliderTxtFontSize'];?>px;
            }
        </style>
        <?php
        self::css();
        self::script();
    }
    static function item($item): void {
        $item = SliderWithTitle::metaData($item);
        ?>
        <div class="item">
            <a aria-label='slide' href="<?php echo $item->url;?>">
                <?php Template::img($item->value, $item->name, array('style' => 'cursor:pointer'));?>
            </a>
        </div>
        <?php
    }
    static function thumb($item): void {
        $item = SliderWithTitle::metaData($item);
        ?>
        <div class="item"><p class="heading"><?php echo $item->name;?></p></div>
        <?php
    }
    static function script(): void {
        static $called = false; if ($called) return;
        ?>
        <script>
            $(() => {
                $.each($('.js_slider_title'), function (index, element) {
                    let options = $(this).data('options');
                    let sliderId = $(this).data('id');
                    let sliderWidth = $(this).width();
                    let sliderHeight = Math.ceil(sliderWidth*(parseFloat(options.ratioHeight)/parseFloat(options.ratioWidth)));

                    $(this).find('.js_slider_title_list .item').css('height', sliderHeight+'px');

                    $(window).resize(function () {
                        sliderWidth = $(this).width();
                        sliderHeight = Math.ceil(sliderWidth*(parseFloat(options.ratioHeight)/parseFloat(options.ratioWidth)));
                        $(this).find('.js_slider_title_list .item').css('height', sliderHeight + 'px');
                    });

                    let sliderMain = $(this).find('.js_slider_title_list');
                    let sliderThumb = $(this).find('.js_slider_title_thumb');
                    let arrowNext = $(this).find('.js_slider_title_arrow .next');
                    let arrowPrev = $(this).find('.js_slider_title_arrow .prev');

                    sliderMain.slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        fade: true,
                        autoplay: true,
                        asNavFor: '#js_slider_title_thumb_' + sliderId,
                        loop:true
                    });
                    arrowNext.click(function() {
                        sliderMain.slick('slickNext'); return false;
                    });
                    arrowPrev.click(function() {
                        sliderMain.slick('slickPrev'); return false;
                    });
                    sliderThumb.slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        asNavFor: '#js_slider_title_list_'  + sliderId,
                        focusOnSelect: true,
                        loop:true,
                        arrows: false,
                        responsive: [{ breakpoint: 600, settings: { slidesToShow: 2, }}]
                    });
                });
            });
        </script>
        <?php
        $called = true;
    }
    static function css(): void {
        static $calledCss = false; if ($calledCss) return;
        ?>
        <style>
            .sliderWidthTitle .slider_list_item .item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .sliderWidthTitle .slider_list_thumb {
                background-color: var(--slider-thumb-bg);
            }
            .sliderWidthTitle .slider_list_thumb .item {
                width: 100%;
                cursor: pointer;
                padding: 10px 10px;
                outline: none;
            }
            .sliderWidthTitle .slider_list_thumb .item .heading {
                font-size: var(--slider-thumb-font-size); line-height: calc(var(--slider-thumb-font-size) + var(--slider-thumb-font-size)*0.5);
                text-align: center;
                margin: 0;
                height: calc((var(--slider-thumb-font-size) + var(--slider-thumb-font-size)*0.5)*2); overflow: hidden;
                color: var(--slider-thumb-color, #fff);
                display: flex; align-items: center;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2; /* number of lines to show */
                line-clamp: 2;
                -webkit-box-orient: vertical;
            }
            .sliderWidthTitle .slider_list_thumb .slick-current .item {
                background-color:var(--slider-thumb-bg-active);
            }
            .sliderWidthTitle .slider_list_thumb .slick-current .item .heading {
                color:var(--slider-thumb-color-active);
            }
            .sliderWidthTitle .slider_list_thumb .item {
                margin-right: 1px;
            }
            .sliderWidthTitle.in-slider .slider_list_thumb {
                position: absolute; bottom: 0; left: 0; width: 100%;
            }
            .sliderWidthTitle .thumb-hidden .slider_list_thumb {
                display: none;
            }
        </style>
        <?php
        $calledCss = true;
    }
}