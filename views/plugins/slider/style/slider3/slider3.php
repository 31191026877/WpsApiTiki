<?php
class SliderNoTitle {
    static function itemForm($item): void {
        $item = SliderNoTitle::metaData($item);
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
        SliderNoTitleHtml::render($items, $slider, $options);
    }
}

class SliderNoTitleHtml {
    static function render($items, $slider, $options = null): void {
        $options = (is_array($options)) ? $options : [];
        $options = array_merge(['numberItem' => count($items)], $options);
        $id = (!empty($options['id'])) ? $options['id'] : uniqid();
        ?>
        <div id="sliderNoTitle_<?php echo $id;?>" class="sliderNoTitle stick-dots js_slider_title box-content slider_box" style="position: relative" data-id="<?php echo $id;?>" data-options="<?php echo htmlentities(json_encode($options));?>">
            <div class="arrow_box js_slider_title_arrow">
                <div class="prev arrow"><i class="fal fa-chevron-left"></i></div>
                <div class="next arrow"><i class="fal fa-chevron-right"></i></div>
            </div>
            <div id="js_slider_title_list_<?php echo $id;?>" class="js_slider_title_list slider_list_item owl-carousel">
                <?php foreach ($items as $item) {
                    SliderNoTitleHtml::item($item);
                } ?>
            </div>
        </div>
        <?php
        self::css();
        self::script();
    }
    static function item($item): void {
        $item = SliderNoTitle::metaData($item);
        ?>
        <div class="item">
            <a aria-label='slide' href="<?php echo $item->url;?>">
                <?php Template::img($item->value, $item->name, array('style' => 'cursor:pointer'));?>
            </a>
        </div>
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
                    let arrowNext = $(this).find('.js_slider_title_arrow .next');
                    let arrowPrev = $(this).find('.js_slider_title_arrow .prev');

                    sliderMain.slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        fade: true,
                        autoplay: true,
                        loop:true,
                        lazyLoad: 'progressive',
                        dots: true,
                    });
                    arrowNext.click(function() {
                        sliderMain.slick('slickNext'); return false;
                    });
                    arrowPrev.click(function() {
                        sliderMain.slick('slickPrev'); return false;
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
            .sliderNoTitle .slider_list_item .item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            body .sliderNoTitle .arrow {
                font-size: 30px;
                background-color:transparent!important;
                box-shadow: none;
            }
            body .sliderNoTitle .arrow i {
                text-shadow: 0 0 5px #fff;
            }
            body .sliderNoTitle .arrow:hover {
                background-color:transparent!important;
            }
            .sliderNoTitle .slick-dots {
                position: absolute;
                bottom: 25px;
                list-style: none;
                display: block;
                text-align: center;
                padding: 0;
                margin: 0;
                width: 100%;
            }
            .sliderNoTitle .slick-dots li {
                position: relative;
                display: inline-block;
                margin: 0 5px;
                padding: 0;
                cursor: pointer;
            }
            .sliderNoTitle .slick-dots li button {
                border: 0;
                display: block;
                outline: none;
                line-height: 0px;
                font-size: 0px;
                color: transparent;
                padding: 5px;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            .sliderNoTitle .slick-dots li button:before {
                display: none;
            }
            .sliderNoTitle .slick-dots li button:hover,
            .sliderNoTitle .slick-dots li button:focus {
                outline: none;
            }
            .sliderNoTitle .stick-dots .slick-dots li {
                height: 3px;
                width: 50px;
            }
            .sliderNoTitle .stick-dots .slick-dots li button {
                position: relative;
                background-color: white;
                opacity: 0.25;
                width: 50px;
                height: 3px;
                padding: 0;
            }
            .sliderNoTitle .stick-dots .slick-dots li button:hover,
            .sliderNoTitle .stick-dots .slick-dots li button:focus {
                opacity: 1;
            }
            .sliderNoTitle .stick-dots .slick-dots li.slick-active button {
                color: white;
                opacity: 0.75;
            }
            .sliderNoTitle .stick-dots .slick-dots li.slick-active button:hover,
            .sliderNoTitle .stick-dots .slick-dots li.slick-active button:focus {
                opacity: 1;
            }
        </style>
        <?php
        $calledCss = true;
    }
}