<?php

class widget_post_style_11 extends widget
{

    function __construct()
    {

        parent::__construct('widget_post_style_11', 'Bài viết (style 11)', ['container' => true, 'position' => 'right']);

        add_action('theme_custom_css', [$this, 'css'], 10);

        $this->tags = ['post'];

        $this->author = 'Ngọc Diệp';
    }

    function form($left = [], $right = [])
    {

        $this->left

            ->add('post_cate_id', 'cate_post_categories', ['label' => 'Danh mục item'])

            ->add('display', '::inputDisplay', ['label' => 'Kiểu hiển thị']);

        $this->right

            ->add('limit', 'number', ['value' => 10, 'label' => 'Số Item lấy ra'])

            ->add('numberShow',         'col', ['label' => 'Số item / hàng',           'value' => 2, 'min' => 1, 'max' => 5])

            ->add('numberShowTablet', 'col', ['label' => 'Số item / hàng - tablet', 'value' => 2, 'min' => 1, 'max' => 5])

            ->add('numberShowMobile', 'col', ['label' => 'Số item / hàng - mobile', 'value' => 1, 'min' => 1, 'max' => 5]);

        parent::form($left, $right);
    }

    function widget()
    {

        $box = $this->container_box('widget_post_style_11');

        echo $box['before'];

        ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_' . $this->key . '_' . $this->id); ?>

        <div class="box-content" id="post_style_11_content_<?php echo $this->id; ?>">

            <?php

            if ($this->options->display['type'] == 0) $this->displayHorizontal($this->options->posts);

            if ($this->options->display['type'] == 1) $this->displayList($this->options->posts);

            ?>

        </div>

        <script>
            $(function() {

                let hg = Math.round(($('.js_widget_post_style_11_<?= $this->id; ?> .item .content .title').outerHeight()) / 2);

                $('.js_widget_post_style_11_<?= $this->id; ?> .item .content').css('margin-bottom', hg + 'px');

            })
        </script>

    <?php

        echo $box['after'];
    }

    function displayHorizontal($posts)
    {

    ?>

        <div class="arrow_box">

            <div class="prev arrow"><i class="fal fa-chevron-left"></i></div>

            <div class="next arrow"><i class="fal fa-chevron-right"></i></div>

        </div>

        <div class="swiper" id="widget_post_<?= $this->id; ?>">
            <div class="swiper-wrapper list-post"><?php foreach ($posts as $key => $val) {
                                                        $this->item($val);
                                                    } ?></div>
        </div>

        <script defer>
            $(function() {

                let id = <?= $this->id; ?>;

                let sliderList = '#post_style_11_content_' + id + ' .swiper';

                let sliderBtnNext = $('#post_style_11_content_' + id + ' .next');

                let sliderBtnPrev = $('#post_style_11_content_' + id + ' .prev');

                function shouldBeEnabled(carousel, numberShow) {

                    const slidesCount = carousel.find('.swiper-slide').length;

                    if (slidesCount < numberShow) {

                        return {
                            loop: false,
                        };

                    }

                    return {
                        loop: true,
                    };

                }

                let config = {

                    ...shouldBeEnabled($(sliderList), <?= $this->options->numberShow; ?>),

                    autoplay: {

                        delay: <?= $this->options->display['time'] * 1000; ?>

                    },

                    speed: <?= $this->options->display['speed'] * 1000; ?>,

                    slidesPerView: parseInt(<?= $this->options->numberShow; ?>),

                    spaceBetween: parseInt(getComputedStyle(document.body).getPropertyValue('--bs-gutter-x')),

                    breakpoints: {

                        0: {

                            ...shouldBeEnabled($(sliderList), <?= $this->options->numberShowMobile; ?>),

                            slidesPerView: <?= $this->options->numberShowMobile; ?>

                        },

                        768: {

                            ...shouldBeEnabled($(sliderList), <?= $this->options->numberShowTablet; ?>),

                            slidesPerView: <?= $this->options->numberShowTablet; ?>

                        },

                        1200: {

                            ...shouldBeEnabled($(sliderList), parseInt(<?= $this->options->numberShow; ?>)),

                            slidesPerView: parseInt(<?= $this->options->numberShow; ?>)

                        },

                    },

                }

                let swiper = new Swiper(sliderList, config);

                sliderBtnNext.click(function() {
                    swiper.slideNext();
                });

                sliderBtnPrev.click(function() {
                    swiper.slidePrev();
                })

            });
        </script>

    <?php

    }

    function displayList($posts)
    {

    ?>

        <style>
            #post_style_11_content_<?= $this->id; ?>.list-post {

                display: grid;

                grid-template-columns: repeat(<?php echo $this->options->numberShow ?>, 1fr);

                grid-gap: <?php echo $this->options->display['margin']; ?>px;

            }

            @media(max-width: 1000px) {

                #post_style_11_content_<?= $this->id; ?> {

                    grid-template-columns: repeat(<?php echo $this->options->numberShowTablet ?>, 1fr);

                }

            }

            @media(max-width: 600px) {

                #post_style_11_content_<?= $this->id; ?> {

                    grid-template-columns: repeat(<?php echo $this->options->numberShowMobile ?>, 1fr);

                }

            }
        </style>

        <div class="list-post">

            <?php foreach ($posts as $key => $val) : ?><?php echo $this->item($val);; ?><?php endforeach ?>

        </div>

    <?php

    }

    function item($item)
    {

    ?>

        <div class="swiper-slide">

            <div class="item">

                <div class="img effect-hover-zoom">

                    <a href="<?php echo Url::permalink($item->slug); ?>"><?php Template::img($item->image, $item->title); ?></a>

                </div>

                <div class="content">

                    <div class="title">

                        <p class="header"><a href="<?= Url::permalink($item->slug); ?>"><?= $item->title; ?></a></p>
                        <div class="excerpt">
                            <a href="<?= Url::permalink($item->slug); ?>"><?= $item->excerpt; ?></a>
                        </div>

                        <div class="read-more">

                            <a href="<?php echo Url::permalink($item->slug); ?>"><?php echo _('Xem Thêm'); ?></a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php

    }

    function default()
    {

        if ($this->name == 'Bài viết (style 11)') $this->name = 'BLOG';

        if (!isset($this->options->box))   $this->options->box = 'container';

        if (empty($this->options->display)) $this->options->display = [];

        if (!isset($this->options->display['type'])) $this->options->display['type'] = 0;

        if (!isset($this->options->display['margin'])) $this->options->display['margin'] = 15;

        if (!isset($this->options->display['time'])) $this->options->display['time'] = 3;

        if (!isset($this->options->display['speed'])) $this->options->display['speed'] = 0.7;



        if (!isset($this->options->numberShow)) $this->options->numberShow = 3;

        if (!isset($this->options->numberShowTablet)) $this->options->numberShowTablet = 2;

        if (!isset($this->options->numberShowMobile)) $this->options->numberShowMobile = 1;



        $args = Qr::set('post_type', 'post')->orderBy('order')->orderByDesc('created')->limit((!empty($this->options->limit)) ? $this->options->limit : 20);

        $this->options->slug = '';

        if (!empty($this->options->post_cate_id)) {

            $category = PostCategory::get($this->options->post_cate_id);

            $args->whereByCategory($category);

            if (have_posts($category)) {

                $this->options->slug = Url::permalink($category->slug);
            }
        }

        $this->options->posts = Posts::gets($args);
    }

    function css()
    {
        include_once('assets/post-style-11.css');
    }

    static function inputDisplay($param, $value = [])
    {

        if (!is_array($value)) $value = [];

        if (!isset($value['type']))   $value['type']     = 0;

        if (!isset($value['margin'])) $value['margin']   = 15;

        if (!isset($value['time']))   $value['time']     = 3;

        if (!isset($value['speed']))  $value['speed']    = 0.7;

        $Form = new FormBuilder();

        ob_start();

    ?>

        <div class="js_input_tab_display">

            <ul class="input-tabs with-indicator" style="margin-bottom: 5px;">

                <li class="tab <?php echo ($value['type'] == 0) ? 'active' : ''; ?>" style="width:calc(100%/2)" data-tab="#display_slider">

                    <label for="display_type_0">

                        <input class="display_type" id="display_type_0" type="radio" name="display[type]" value="0" <?php echo ($value['type'] == 0) ? 'checked' : ''; ?>> Sản phẩm chạy ngang

                    </label>

                </li>

                <li class="tab <?php echo ($value['type'] == 1) ? 'active' : ''; ?>" style="width:calc(100%/2)" data-tab="#display_list">

                    <label for="display_type_1">

                        <input class="display_type" id="display_type_1" type="radio" name="display[type]" value="1" <?php echo ($value['type'] == 1) ? 'checked' : ''; ?>> Sản phẩm danh sách

                    </label>

                </li>

                <div class="indicator" style="width:calc(100%/2);"></div>

            </ul>

            <div class="tab-content">

                <div class="<?php echo ($value['type'] == 0) ? 'active in' : ''; ?> tab-pane fade" id="display_slider">

                    <div class="row m-1">

                        <?php $Form->add('display[margin]', 'number', ['label' => 'Khoảng cách giữa các sản phẩm', 'value' => 15], $value['margin']); ?>

                        <?php $Form->add('display[time]', 'number', ['label' => 'Thời gian tự động chạy', 'value' => 3, 'step' => '0.01'], $value['time']); ?>

                        <?php $Form->add('display[speed]', 'number', ['label' => 'Thời gian hoàn thành chạy', 'value' => 0.7, 'step' => '0.01'], $value['speed']); ?>

                        <?php $Form->html(false); ?>

                    </div>

                </div>

                <div class="<?php echo ($value['type'] == 1) ? 'active in' : ''; ?> tab-pane fade" id="display_list"></div>

            </div>

        </div>

        <script defer>
            $('#widget_post_style_11 .js_input_tab_display li .display_type').click(function() {

                let tabID = $(this).closest('li').attr('data-tab');

                let tabBox = $(this).closest('.js_input_tab_display').find('.tab-content .tab-pane');

                tabBox.removeClass('active').removeClass('in');

                $(tabID).addClass('active').addClass('in');

                $('.input-tabs .tab.active').each(function() {

                    let inputBox = $(this).closest('.input-tabs');

                    inputTabsAnimation(inputBox, $(this));

                });

            });
        </script>

<?php

        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }
}

Widget::add('widget_post_style_11');
