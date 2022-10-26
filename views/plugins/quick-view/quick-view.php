<?php

/**

Plugin name     : Quick View - Xem Nhanh

Plugin class    : quick_view

Plugin uri      : http://sikido.vn

Description     : Plugin cho khách hàng xem nhanh thông tin sản phẩm.

Author          : Nguyễn Hữu Trọng

Version         : 1.1.0

*/

const QV_NAME = 'quick-view';



define( 'QV_PATH', plugin_dir_path( QV_NAME ) );



class Quick_View {



    private string $name = 'quick_view';



    function __construct() {

        $active    = option::get('quick_view_active');

        if(!empty($active)) {

            add_action('theme_custom_css', array($this, 'css'));

            add_action('theme_custom_script_no_tag', array($this, 'script'));

            add_action('cle_header', array($this, 'cssCustom'), 1000);

            add_action('product_object_after_image', array($this, 'render'));

        }

    }



    public function active() {}



    public function uninstall() {}



    public function css() {

        include 'styles/style.css.php';

    }



    public function script() {

        include 'styles/style.script.php';

    }



    public function render($item) {

        $active    = Option::get('quick_view_active');

        if(!empty($active)) {

            $config = static::style($active);

            include 'styles/html-item-'.$active.'.php';

        }

    }



    public function cssCustom() {

        $active = option::get('quick_view_active');

        $config = static::style($active);

        include 'styles/css-'.$active.'.php';

    }



    static public function style($key = '') {

        $active    = option::get('quick_view_active');

        $config    = option::get('quick_view_config');

        $style = [

            'style_1' => [

                'btn_bg_color'          => Option::get('theme_color'),

                'txt_color'             => '#fff',

                'btn_bg_color_hover'    => Option::get('theme_color'),

                'txt_color_hover'       => '#fff',

                'txt_quickview'         => htmlentities('<i class="far fa-eye"></i>'),

                'txt_buy'               => htmlentities('<i class="far fa-shopping-cart"></i>'),

                'txt_view'              => htmlentities('<i class="far fa-link"></i>'),

            ],

            'style_2' => [

                'btn_bg_color'          => Option::get('theme_color'),

                'txt_color'             => '#fff',

                'btn_bg_color_hover'    => Option::get('theme_color'),

                'txt_color_hover'       => '#fff',

                'txt_quickview'         => htmlentities('<i class="far fa-eye"></i>'),

                'txt_buy'               => htmlentities('<i class="far fa-shopping-cart"></i>'),

                'txt_view'              => htmlentities('<i class="far fa-link"></i>'),

            ],

            'style_3' => [

                'btn_bg_color'          => Option::get('theme_color'),

                'txt_color'             => '#fff',

                'btn_bg_color_hover'    => Option::get('theme_color'),

                'txt_color_hover'       => '#fff',

                'txt_quickview'         => htmlentities('Xem nhanh'),

                'txt_buy'               => htmlentities('Thêm vào giỏ hàng'),

                'txt_view'              => htmlentities('Xem chi tiết'),

            ],

        ];



        if(isset($style[$active]) && have_posts($config)) {

            $style[$active] = $config;

            $style[$active]['active'] = 1;

            if($key == 'active') return $style[$active];

        }



        if(isset($style[$key])) return $style[$key];



        return apply_filters( 'quick_view_style', $style );

    }

}



new quick_view();



include 'admin/quick-view-admin.php';



function quickview_ajax_product_load( $ci, $model ) {



    $id = (int)InputBuilder::get('id');



    $object = Product::get( $id );



    if(have_posts($object) ) {

        $ci->data['object'] = $object;

        product_detail_cart_data($object);

        ?>

        <div class="products-detail">

            <div class="row">

                <div class="col-md-7" id="surround">

                    <?php do_action( 'product_detail_slider', $object );?>

                </div>

                <div class="col-md-5">

                    <!-- <h1 class="title-head"><?= $object->title;?></h1> -->

                    <?php do_action('product_detail_info', $object); ?>

                </div>

            </div>

        </div>

        <script>

            $(function () {

                $('.addtocart_quantity').each(function() {

                    let spinner = $(this),

                        input = spinner.find('input[type="number"]'),

                        btnUp = spinner.find('.quantity-up'),

                        btnDown = spinner.find('.quantity-down'),

                        min = input.attr('min'),

                        max = input.attr('max');



                    btnUp.click(function() {

                        let oldValue = parseFloat(input.val());

                        if (oldValue >= max) {

                            var newVal = oldValue;

                        } else {

                            var newVal = oldValue + 1;

                        }

                        spinner.find("input").val(newVal);

                        spinner.find("input").trigger("change");

                    });



                    btnDown.click(function() {

                        let oldValue = parseFloat(input.val());

                        if (oldValue <= min) {

                            var newVal = oldValue;

                        } else {

                            var newVal = oldValue - 1;

                        }

                        spinner.find("input").val(newVal);

                        spinner.find("input").trigger("change");

                    });

                });

            })

        </script>

        <?php

    }

}

Ajax::client('quickview_ajax_product_load');