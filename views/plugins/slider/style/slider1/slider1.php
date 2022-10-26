<?php
class SliderRevolution {
    static string $path = SLIDER_PATH.'style/slider1/';
    static function itemForm($item): void {
        $item = SliderRevolution::metaData($item);
        if($item->caption_key != 'none') {
            $caption_key = $item->caption_key;
            $caption = SliderRevolutionHtml::getCaptions($caption_key, $item->id);
        }
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
            'data_transition'   => Request::post('data_transition'),
            'data_slotamount'   => Request::post('data_slotamount'),
            'data_masterspeed'  => Request::post('data_masterspeed'),
            'caption_key'       => Request::post('caption_key'),
        ];

        $galleryItemMeta['caption'] = [];

        if(have_posts(Request::post('caption'))) {
            $galleryItemMeta['caption'] = Request::post('caption');
        }

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
            'data_transition'   => Gallery::getItemMeta($item->id, 'data_transition', true),
            'data_slotamount'   => Gallery::getItemMeta($item->id, 'data_slotamount', true),
            'data_masterspeed'  => Gallery::getItemMeta($item->id, 'data_masterspeed', true),
            'caption_key'       => Gallery::getItemMeta($item->id, 'caption_key', true),
        ];

        if(!Language::isDefault()) {
            $name = Gallery::getItemMeta($item->id, 'name_'.Language::current(), true);
            if(!empty($name)) $option['name'] = $name;
        }

        $item = (object)array_merge((array)$item, $option);

        return $item;
    }
    static function animation( $key = '') {

        $list_anim['Fade'] = array(
            'fade'                    => 'Fade',
            'boxfade'                 => 'Fade Boxes',
            'slotfade-horizontal'     => 'Fade Slots Horizontal',
            'slotfade-vertical'       => 'Fade Slots Vertical',
            'fadefromright'           => 'Fade and Slide from Right',
            'fadefromleft'            => 'Fade and Slide from Left',
            'fadefromtop'             => 'Fade and Slide from Top',
            'fadefrombottom'          => 'Fade and Slide from Bottom',
            'fadetoleftfadefromright' => 'Fade To Left and Fade From Right',
            'fadetorightfadetoleft'   => 'Fade To Right and Fade From Left',
        );

        $list_anim['Zoom'] = array(
            'scaledownfromright'  => 'Zoom Out and Fade From Right',
            'scaledownfromleft'   => 'Zoom Out and Fade From Left',
            'scaledownfromtop'    => 'Zoom Out and Fade From Top',
            'scaledownfrombottom' => 'Zoom Out and Fade From Bottom',
            'zoomout'             => 'ZoomOut',
            'zoomin'              => 'ZoomIn',
            'slotzoom-horizontal' => 'Zoom Slots Horizontal',
            'slotzoom-vertical'   => 'Zoom Slots Vertical',
        );

        $list_anim['Parallax'] = array(
            'parallaxtoright'     => 'Parallax to Right',
            'parallaxtoleft'     => 'Parallax to Left',
            'parallaxtotop'     => 'Parallax to Top',
            'parallaxtobottom'    => 'Parallax to Bottom',
        );

        $list_anim['Slide'] = array(
            'slideup'              => 'Slide To Top',
            'slidedown'            =>'Slide To Bottom',
            'slideright'           =>'Slide To Right',
            'slideleft'            =>'Slide To Left',
            'slidehorizontal'      =>'Slide Horizontal (depending on Next/Previous)',
            'slidevertical'        =>'Slide Vertical (depending on Next/Previous)',
            'boxslide'             =>'Slide Boxes',
            'slotslide-horizontal' =>'Slide Slots Horizontal',
            'slotslide-vertical'   =>'Slide Slots Vertical',
            'curtain-1'            =>'Curtain from Left',
            'curtain-2'            =>'Curtain from Right',
            'curtain-3'            =>'Curtain from Middle',
        );

        $list_anim['Premium'] = array(
            '3dcurtain-horizontal' => '3D Curtain Horizontal',
            '3dcurtain-vertical'   => '3D Curtain Vertical',
            'cubic'                => 'Cube Vertical',
            'cubic'                => 'Cube Horizontal',
            'incube'               => 'In Cube Vertical',
            'incube-horizontal'    => 'In Cube Horizontal',
            'turnoff'              => 'TurnOff Horizontal',
            'turnoff-vertical'     => 'TurnOff Vertical',
            'papercut'             => 'Paper Cut',
            'flyin'                => 'Fly In',
            'random-static'        => 'Random Premium',
            'random'               => 'Random Flat and Premium',
        );

        return (isset($list_anim[$key])) ? $list_anim[$key] : (($key == '') ? $list_anim : []);
    }
    static function assetsEditor(): void {
        ?>
        <link rel="stylesheet" type="text/css" href="<?= self::$path;?>src/css/navstylechange.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?= self::$path;?>src/editor/type/fontello.css">
        <link rel="stylesheet" type="text/css" href="<?= self::$path;?>src/editor/editor.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?= self::$path;?>src/css/style.css" media="screen" />
        <script type="text/javascript" src="<?= self::$path;?>/src/editor/editor.js"></script>
        <?php
    }
    static function assetsAdmin(): void {
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.0/TweenMax.min.js"></script>
        <?php
    }
    static function demo($slotamount = 5, $masterspeed = 700): void {
        $slotamount  = (empty($slotamount)) ? 5 : $slotamount;
        $masterspeed = (empty($masterspeed)) ? 700 : $masterspeed;
        ?>
        <article class="spectaculus">
            <!-- START REVOLUTION SLIDER 3.1 rev5 fullwidth mode -->
            <div class="fullwidthbanner-container roundedcorners">
                <div class="fullwidthbanner" >
                    <ul>
                        <li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
                            <img src="<?= self::$path;?>images-demo/bg1.jpg"   alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                        </li>
                        <li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
                            <img src="<?= self::$path;?>images-demo/bg2.jpg"   alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                        </li>
                        <li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
                            <img src="<?= self::$path;?>images-demo/bg3.jpg"  alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                        </li>
                        <li data-transition="fade" data-slotamount="5" data-masterspeed="700" >
                            <img src="<?= self::$path;?>images-demo/bg4.jpg"  alt="slidebg1"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
                        </li>
                    </ul>
                    <div class="tp-bannertimer"></div>
                </div>
            </div>
            <script type="text/javascript">
                var revApi;
                $(document).ready(function() {
                    revApi = $('.fullwidthbanner').revolution({
                        delay:15000,
                        startwidth:1170,
                        startheight:500,
                        height:500,
                        hideThumbs:10,

                        thumbWidth:100,
                        thumbHeight:50,
                        thumbAmount:5,

                        navigationType:"both",
                        navigationArrows:"solo",
                        navigationStyle:"round",

                        touchenabled:"on",
                        onHoverStop:"on",

                        navigationHAlign:"center",
                        navigationVAlign:"bottom",
                        navigationHOffset:0,
                        navigationVOffset:0,

                        soloArrowLeftHalign:"left",
                        soloArrowLeftValign:"center",
                        soloArrowLeftHOffset:20,
                        soloArrowLeftVOffset:0,

                        soloArrowRightHalign:"right",
                        soloArrowRightValign:"center",
                        soloArrowRightHOffset:20,
                        soloArrowRightVOffset:0,

                        shadow:0,
                        fullWidth:"on",
                        fullScreen:"off",

                        stopLoop:"on",
                        stopAfterLoops:0,
                        stopAtSlide:1,


                        shuffle:"off",

                        autoHeight:"off",
                        forceFullWidth:"off",

                        hideThumbsOnMobile:"off",
                        hideBulletsOnMobile:"on",
                        hideArrowsOnMobile:"on",
                        hideThumbsUnderResolution:0,

                        hideSliderAtLimit:0,
                        hideCaptionAtLimit:768,
                        hideAllCaptionAtLilmit:0,
                        startWithSlide:0,
                        videoJsPath:"plugins/revslider/rs-plugin/videojs/",
                        fullScreenOffsetContainer: ""
                    });
                });	//ready
            </script>
            <!-- END REVOLUTION SLIDER -->
            <!-- Content End -->
        </article> <!-- END OF SPECTACULUS -->
        <article class="toolpad">
            <section class="tool">
                <div data-val="<?= $masterspeed;?>" id="mrtime" class="tooltext">Time: <?= $masterspeed/1000;?>s</div>
                <div class="toolcontrols">
                    <div id="dectime" class="toolcontroll withspace"><i class="icon-minus"></i></div>
                    <div id="inctime" class="toolcontroll withspace2"><i class="icon-plus"></i></div>
                </div>
                <div class="clear"></div>
            </section>

            <section class="tool last">
                <div data-val="<?= $slotamount;?>" class="tooltext" id="mrslot">Slots: <?= $slotamount;?></div>
                <div class="toolcontrols">
                    <div id="decslot" class="toolcontroll withspace"><i class="icon-minus"></i></div>
                    <div id="incslot" class="toolcontroll withspace2"><i class="icon-plus"></i></div>
                </div>
                <div class="clear"></div>
            </section>
            <div class="clear"></div>
        </article>
        <?php
    }
    static function render($items, $slider, $options = null): void {
        SliderRevolutionHtml::render($items, $slider, $options);
    }
}

class SliderRevolutionHtml {
    static string $path = SLIDER_PATH.'style/slider1/';
    static function render($items, $slider, $options = null): void {
        self::assets();
        $options = (is_array($options)) ? $options : [];
        $options = array_merge(['delay' => 3000, 'fullScreen' => 'on', 'hideThumbs' => 10], $options);
        ?>
        <div class="js_slider_revolution js_slider_container" style="position: relative;" data-options="<?php echo htmlentities(json_encode($options));?>">
            <div class="js_slider_revolution_box"><ul><?php foreach ($items as $item) { SliderRevolutionHtml::item($item); } ?></ul></div>
        </div>
        <?php
        self::script();
    }
    static function item($item): void {
        $item = SliderRevolution::metaData($item);
        $output = '';
        if(isset($item->value) && $item->value != '') {
            $transition = 'data-transition="'.$item->data_transition.'" data-slotamount="'.$item->data_slotamount.'" data-masterspeed="'.$item->data_masterspeed.'"';
            $output .= '<li '.$transition.' data-link="'.$item->url.'">';
            if($item->type == 'youtube') {
                $output .= '<div class="tp-caption tp-fade fadeout fullscreenvideo" data-x="0" data-y="0" data-speed="1000" data-start="1100"
							data-easing="Power4.easeOut" data-endspeed="1500" data-endeasing="Power4.easeIn" data-autoplay="true" data-autoplayonlyfirsttime="false"
							data-nextslideatend="true" data-forceCover="1" data-aspectratio="16:9" data-forcerewind="on" style="z-index: 2">';
                $output .= '<iframe src="https://www.youtube.com/embed/'.Url::getYoutubeID($item->value).'?controls=0&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                $output .= '</div>';
            }
            if($item->type == 'video') {
                $output .= '<div class="tp-caption tp-fade fadeout fullscreenvideo" data-x="0" data-y="0" data-speed="1000" data-start="1100"
							data-easing="Power4.easeOut" data-endspeed="1500" data-endeasing="Power4.easeIn" data-autoplay="true" data-autoplayonlyfirsttime="false"
							data-nextslideatend="true" data-forceCover="1" data-aspectratio="16:9" data-forcerewind="on" style="z-index: 2">';
                $output .= '<video muted class="video-js vjs-default-skin" preload="none" width="100%" height="100%" poster="uploads/banne-slider-1.jpg" data-setup="{}"><source src="'.Template::imgLink($item->value).'" type="video/mp4"/></video>';

                $output .= '</div>';
            }
            if($item->type == 'image') {
                $output .= '<img src="'.Template::imgLink($item->value).'"  alt="'.$item->name.'"  data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">';
            }
            if($item->caption_key != 'none') {
                $caption = self::getCaptions($item->caption_key, $item->id);
                ob_start();
                self::caption($item->caption_key, $caption);
                $output .= ob_get_contents();
                ob_clean();
            }
            $output .= '</li >';
        }
        echo $output;
    }
    static function script(): void {
        static $called = false; if ($called) return;
        ?>
        <script>
            $(() => {
                $.each($('.js_slider_revolution'), function (index, element) {
                    let options = $(this).data('options');
                    if(typeof options.ratioHeight == 'undefined') {
                        options.ratioHeight = 1;
                    }
                    if(typeof options.ratioWidth == 'undefined') options.ratioWidth = 3;
                    let sliderWidth = $(this).width();
                    let sliderHeight = Math.ceil(sliderWidth*(parseFloat(options.ratioHeight)/parseFloat(options.ratioWidth)));
                    $(this).css('height', sliderHeight + 'px');
                    $(this).find('.js_slider_revolution_box').revolution(options);
                });
            });
        </script>
        <?php
        $called = true;
    }
    static function assets(): void {
        static $calledAssets = false; if ($calledAssets) return;
        ?>
        <link rel="stylesheet" type="text/css" href="<?= self::$path;?>src/css/settings.css" media="screen" />
        <script type="text/javascript" src="<?= self::$path;?>src/js/jquery.themepunch.plugins.min.js"></script>
        <script type="text/javascript" src="<?= self::$path;?>src/js/jquery.themepunch.revolution.min.js"></script>
        <?php
        $calledAssets = true;
    }
    static function getCaptions($caption_key = '', $item_id = 0): array {
        $captions = [
            'caption1' => [ 'label' => 'Caption 1' ],
            'caption2' => [ 'label' => 'Caption 2' ],
            'caption3' => [ 'label' => 'Caption 3' ],
            'caption4' => [ 'label' => 'Caption 4' ],
            'caption5' => [ 'label' => 'Caption 5' ]
        ];
        if(!empty($caption_key) && !empty($captions[$caption_key])) {
            if(!empty($item_id)) {
                $caption_key_meta    = Gallery::getItemMeta($item_id, 'caption_key', true);
                if($caption_key == $caption_key_meta) $caption = Gallery::getItemMeta($item_id, 'caption', true);
            }
            include self::$path.'captions/'.$caption_key.'/config.php';
            return $captions[$caption_key];
        }
        include self::$path.'captions/caption1/config.php';
        include self::$path.'captions/caption2/config.php';
        return $captions;
    }
    static function caption($key, $caption): void {
        if(!empty($key)) include self::$path.'captions/'.$key.'/layer_caption.php';
    }
}

class SliderRevolutionAjax {
    static function itemCaptionLoad($ci, $model) {

        $result['message'] 	= 'Load dữ liệu không thành công!';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $caption_key = Request::post('caption_key');

            $id = Request::post('id');

            $caption = SliderRevolutionHtml::getCaptions($caption_key, $id);

            if(!empty($caption)) {

                $result['data'] 	= '';

                $result['slider'] 	= '';

                if(file_exists(SliderRevolution::$path.'captions/'.$caption_key.'/layer_form.php')) {

                    ob_start();
                    include_once SliderRevolution::$path.'captions/'.$caption_key.'/layer_form.php';
                    $result['data'] = ob_get_contents();
                    ob_clean();

                    ob_start();
                    include_once SliderRevolution::$path.'views/form-caption.php';
                    $result['slider'] = ob_get_contents();
                    ob_clean();
                }

                $result['status'] 	= 'success';

                $result['message'] 	= 'Cập nhật thành công';
            }
        }
        echo json_encode($result);
    }
}
Ajax::admin('SliderRevolutionAjax::itemCaptionLoad');