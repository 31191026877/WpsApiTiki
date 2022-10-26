<?php
class item_image_widget extends widget
{
    function __construct()
    {
        parent::__construct('item_image_widget', 'item_image_widget', ['container' => true, 'position' => 'left']);
        add_action('theme_custom_css', array($this, 'css'), 10);
    }
    function form($left = [], $right = [])
    {
        $this->left
            ->add('items', '::inputItem', ['args' => ['number' => 3]]);
        parent::form($left, $right);
    }
    function widget()
    {
        $box = $this->container_box('item_image_widget');
        echo $box['before'];
        ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_' . $this->key . '_' . $this->id); ?>
        <div class="contentItem">
            <?php foreach ($this->options->items as $key => $item) : ?>
                <div class="item">
                    <a href="<?= $item['url']; ?>">
                        <div class="icon">
                            <?php Template::img($item['icon']); ?>
                        </div>
                    </a>
                    <div class="box">
                        <a href="<?= $item['url']; ?>">
                            <div class="title">
                                <?= $item['title']; ?>
                            </div>
                        </a>
                        <a href="<?= $item['url']; ?>">
                            <div class="img">
                                <?php Template::img($item['image']); ?>
                            </div>
                        </a>
                        <a href="<?= $item['url']; ?>">
                            <div class="desc">
                                <?= $item['description']; ?>
                            </div>
                        </a>
                        <a class="linkItem" href="<?= $item['url']; ?>">
                            Chi tiết <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <script defer>
            $(document).ready(function() {
                $('.item_image_widget .contentItem').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: false,
                    responsive: [{
                            breakpoint: 769,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2,
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                                rows: 2,
                            }
                        }
                    ]
                });
            });
        </script>
        <style>
            /* .item_image_widget .contentItem {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 20px;
            } */
            /* the slides */
            .item_image_widget .slick-slide {
                margin: 0 10px;
            }

            /* the parent */
            .item_image_widget .slick-list {
                margin: 0 -10px;
            }

            .item_image_widget .slick-slide img {
                display: unset;
            }

            .item_image_widget .contentItem .item .box {
                background-color: #C21010;
                text-align: center;
                margin-top: -25px;
                padding-bottom: 27px;
            }

            .item_image_widget .contentItem .item .icon {
                text-align: center;
            }

            .item_image_widget .contentItem .item .icon img {
                padding: 0px 25px;
                border-radius: 50%;
                background-color: #fff;
                height: 60px;
            }

            .item_image_widget .contentItem .item .box .title {
                padding: 69px 0px 39px 0;
                color: #fff;
                font-weight: 600;
                font-size: 16px;

            }

            .item_image_widget .contentItem .item .box .desc {
                margin-top: 24px;
                padding: 0px 5%;
            }

            .item_image_widget .contentItem .item .box .desc p {
                font-weight: 400;
                font-size: 16px;
                color: #fff;
            }

            .item_image_widget .contentItem .item .box .linkItem {
                font-weight: 400;
                font-size: 18px;
                color: #fff;

            }
        </style>
<?php
        echo $box['after'];
    }
    function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
    function css()
    {
    }
    static function inputItem($param, $value = [])
    {
        if (!have_posts($value)) $value = [];
        $value_default = array('icon' => '', 'image' => '', 'title' => '', 'url' => '', 'description' => '');
        //Số Lượng item
        $number = (isset($param->number)) ? (int)$param->number : 1;
        $output = '';
        $Form = new FormBuilder();
        for ($i = 0; $i <= $number; $i++) {
            if (!isset($value[$i]) || !is_array($value[$i])) $value[$i] = [];
            $value[$i] = array_merge($value_default, $value[$i]);
            $output .= '<label for="name" class="control-label">Item ' . ($i + 1) . '</label>';
            $output .= '<div class="stote_wg_item row m-1">';
            $Form->add($param->field . '[' . $i . '][icon]', 'image', [
                'label' => 'icon',
                'after' => '<div class="col-md-6"><div class="form-group group">', 'before' => '</div></div>'
            ], $value[$i]['icon']);
            $Form->add($param->field . '[' . $i . '][image]', 'image', [
                'label' => 'Hình ảnh',
                'after' => '<div class="col-md-6"><div class="form-group group">', 'before' => '</div></div>'
            ], $value[$i]['image']);
            $Form->add($param->field . '[' . $i . '][title]', 'text', [
                'label' => 'Tiêu đề',
                'after' => '<div class="col-md-6"><div class="form-group group">', 'before' => '</div></div>'
            ], $value[$i]['title']);
            $Form->add($param->field . '[' . $i . '][description]', 'wysiwyg', [
                'label' => 'Mô tả',
                'after' => '<div class="col-md-12"><div class="form-group group">', 'before' => '</div></div>'
            ], $value[$i]['description']);
            // if(Language::hasMulti()) {
            //     foreach (Language::list() as $lang_key => $lang_val) {
            //         if($lang_key == Language::default()) continue;
            //         $value[$i]['title_'.$lang_key] = (!empty($value[$i]['title_'.$lang_key])) ? $value[$i]['title_'.$lang_key] : '';
            //         $value[$i]['description_'.$lang_key] = (!empty($value[$i]['description_'.$lang_key])) ? $value[$i]['description_'.$lang_key] : '';
            //         $Form->add($param->field.'['.$i.'][title_'.$lang_key.']', 'text', [ 'label' => 'Tiêu đề ('.$lang_val['label'].')',
            //             'after' => '<div class="col-md-4"><div class="form-group group">', 'before' => '</div></div>'
            //         ], $value[$i]['title_'.$lang_key]);
            //         $Form->add($param->field.'['.$i.'][description_'.$lang_key.']', 'text', [ 'label' => 'Mô tả ('.$lang_val['label'].')',
            //             'after' => '<div class="col-md-4"><div class="form-group group">', 'before' => '</div></div>'
            //         ], $value[$i]['description_'.$lang_key]);
            //     }
            // }
            $Form->add($param->field . '[' . $i . '][url]', 'text', [
                'label' => 'Liên kết',
                'after' => '<div class="col-md-4"><div class="form-group group">', 'before' => '</div></div>'
            ], $value[$i]['url']);
            $output .= $Form->html();
            $output .= '</div>';
        }
        return $output;
    }
}
Widget::add('item_image_widget');
