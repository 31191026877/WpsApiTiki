<?php
class widget_about_style_9 extends widget {
    function __construct() {
        parent::__construct('widget_about_style_9', 'About (style 9)', [ 'container' => true, 'position' => 'right' ]);
        add_action('theme_custom_css', array( $this, 'css'), 10);
        add_action('theme_custom_script', array( $this, 'script'), 10);
        $this->tags = ['about'];
        $this->author = 'SKDSoftware Dev Team';
    }
    function form( $left = [], $right = []) {
        $this->left
            ->add('description', 'wysiwyg', ['label' => 'Mô tả'])
            ->add('counterHeading', 'color', ['label' => 'Màu tiêu đề counter', 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'])
            ->add('counterNum', 'color', ['label' => 'Màu mô tả counter', 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'])
            ->add('counterBackground', 'background', ['label' => 'Màu nền counter'])
            ->add('counter', '::inputCounter', ['number' => 2])
            ->add('itemHeadingBg', 'color', ['label' => 'Màu tiêu đề item', 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'])
            ->add('itemHeadingColor', 'color', ['label' => 'Màu chữ tiêu đề item', 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'])
            ->add('itemHeadingActiveBg', 'color', ['label' => 'Màu tiêu đề item (được chọn)', 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'])
            ->add('itemHeadingActiveColor', 'color', ['label' => 'Màu chữ tiêu đề item (được chọn)', 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'])
            ->add('itemDes', 'color', ['label' => 'Màu mô tả item', 'after' => '<div class="builder-col-6 col-md-6 form-group group">', 'before'=> '</div>'])
            ->add('itemBackground', 'background', ['label' => 'Màu nền item'])
            ->add('items', 'repeater', ['fields' => [
                ['name' => 'image', 'type' => 'image', 'label' => __('Images'), 'col' => 12],
                ['name' => 'title', 'type' => 'text',  'label' => __('Tiêu đề'), 'col' => 12, 'language' => true],
                ['name' => 'description', 'type' => 'textarea', 'label' => __('Mô tả'), 'col' => 12, 'language' => true],
            ]]);

        $this->right
            ->add('position', 'select', ['label' => 'Vị trí', 'options' => ['text_img' => 'Bài viết - Hình ảnh', 'img_text' => 'Hình ảnh - Bài viết']]);
        parent::form($left, $right);
    }
    function widget() {
        $orderTxt = 1; $orderImg = 2;
        if($this->options->position == 'img_text') {
            $orderTxt = 2; $orderImg = 1;
        }
        $box = $this->container_box('widget_about_style_9'); $i = 0;
        echo $box['before'];
        ?>
        <div class="row">
            <div class="order-<?php echo $orderTxt;?> col-md-7 about-content">
                <?php ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_'.$this->key.'_'.$this->id);?>
                <div class="description"><?php echo $this->options->description;?></div>
                <div class="counter-section-container row-flex" id="js_counter_section_<?php echo $this->id;?>" data-id="<?php echo $this->id;?>" style="<?php echo Template::cssBg($this->options->counterBackground);?>">
                    <?php foreach ($this->options->counter as $key => $counter) { ?>
                        <div class="counter-item">
                            <span class="counter" data-number="<?php echo $counter['number'];?>"></span>
                            <span class="counter-char"><?php echo $counter['char'];?></span>
                            <p class="counter-heading"><?php echo $counter['title'];?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="order-<?php echo $orderImg;?> col-md-5 about-image">
                <div class="item-list" style="<?php echo Template::cssBg($this->options->itemBackground);?>">
                    <?php foreach ($this->options->items as $key => $item) { $i++; ?>
                    <div class="item item<?php echo $key;?> <?php echo ($i == 1) ? 'active' : '';?>">
                        <div class="item-heading">
                            <div class="image"><?php Template::img($item['image'], $item['title'], ['lazy' => 'default']);?></div>
                            <div class="heading"><?php echo $item['title'];?></div>
                        </div>
                        <div class="item-content"><?php echo $item['description'];?></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <style>
            .js_widget_about_style_9_<?php echo $this->id;?> {
                --about9-item-heading-bg:<?php echo (!empty($this->options->itemHeadingBg)) ? $this->options->itemHeadingBg : '#fff';?>;
                --about9-item-heading-color:<?php echo (!empty($this->options->itemHeadingColor)) ? $this->options->itemHeadingColor : '#000';?>;

                --about9-item-heading-active-bg:<?php echo (!empty($this->options->itemHeadingActiveBg)) ? $this->options->itemHeadingActiveBg : '#000';?>;
                --about9-item-heading-active-color:<?php echo (!empty($this->options->itemHeadingActiveColor)) ? $this->options->itemHeadingActiveColor : '#fff';?>;

                --about9-item-des-color:<?php echo (!empty($this->options->itemDes)) ? $this->options->itemDes : '#8a8b8c';?>;

                --about9-counter-heading-color:<?php echo (!empty($this->options->counterHeading)) ? $this->options->counterHeading : '#000';?>;
                --about9-counter-num-color:<?php echo (!empty($this->options->counterNum)) ? $this->options->counterNum : '#8a8b8c';?>;
            }
        </style>
        <?php
        echo $box['after'];
    }
    function css() { include_once('assets/about-style-9.css'); }
    function script() {
        ?>
        <script>
            $(function () {
                var hasCount = [];
                function counterRun(id) {
                    let oTop = $('#js_counter_section_' + id + ' .counter-item').offset().top - window.innerHeight;
                    if(typeof hasCount[id] == 'undefined' && $(window).scrollTop() > oTop) {
                        $('#js_counter_section_' + id + ' .counter-item .counter').each(function () {
                            let $this = $(this), countTo = $this.attr("data-number");
                            $({countNum: $this.text()}).animate({countNum: countTo}, {
                                duration: 850,
                                easing: "swing",
                                step: function () {
                                    $this.text(Math.ceil(this.countNum).toLocaleString("en"));
                                },
                                complete: function () {
                                    $this.text(Math.ceil(this.countNum).toLocaleString("en"));
                                }
                            });
                        });
                        hasCount[id] = id;
                    }
                }
                $('.counter-section-container').each(function () {
                    counterRun($(this).data('id'));
                });
                $(window).scroll(function () {
                    $('.counter-section-container').each(function () {
                        counterRun($(this).data('id'));
                    });
                });

                $('.widget_about_style_9 .item-list .item-heading').click(function () {
                    $(this).closest('.item-list').find('.item').removeClass('active');
                    $(this).closest('.item').addClass('active');
                    return false;
                });
            });
        </script>
        <?php
    }
    function update( $new_instance, $old_instance ) {
        if(isset($new_instance['options']->items)) {
            foreach ($new_instance['options']->items as $key => &$item) {
                $item['image'] = FileHandler::handlingUrl($item['image']);
            }
        }
        return $new_instance;
    }
    function default() {
        if($this->name == 'About (style 9)') $this->name = 'How We Can Help You Achieve Your Business Goal';
        if(!isset($this->options->description)) $this->options->description = '<p>Integer pretium molestie nisl, non blandit lectus suscipit in. Vivamus tellus diam, iaculis eget nulla sit amet, tincidunt consectetur sem. Suspendisse laoreet, quam sed faucibus feugiat, tortor velit suscipit orci, sed consectetur ante eros id urna. Mauris luctus nulla ut pharetra tempor.</p><p>Mauris egestas eleifend sapien eu malesuada. Phasellus at metus eget sapien tristique accumsan non sit amet augue.</p>';
        if(!isset($this->options->box))         $this->options->box = 'container';
        if(!isset($this->options->position))    $this->options->position = 'text_img';
        if(!isset($this->options->itemBackground))    $this->options->itemBackground = [];
        if(!isset($this->options->itemHeading))    $this->options->itemHeading = '#000';
        if(!isset($this->options->itemDes))    $this->options->itemDes = '#8a8b8c';

        if(!isset($this->options->counterBackground)) $this->options->counterBackground = [];
        if(!isset($this->options->counterHeading)) $this->options->counterHeading = '#000';
        if(!isset($this->options->counterDes)) $this->options->counterDes = '#8a8b8c';

        if(!isset($this->options->box_size))    {
            $this->options->box_size = [
                'margin' => ['top' => 0, 'left' => 0, 'right' => 0, 'bottom' => 0],
                'padding' => ['top' => 30, 'left' => 0, 'right' => 0, 'bottom' => 30]
            ];
        }

        if(empty($this->options->items)) {
            $this->options->items    = [];
            $this->options->items[0] = [
                'image'         =>  'http://cdn.sikido.vn/images/widgets/icons-1.png',
                'title'         =>  'Nulla dict posuere veliitae Integer pretium molestie nisl',
                'description'   =>  'Suspendisse ullamcorper mollis orci in facilisis. Integer pretium molestie nisl, non blandit lectus suscipit in. Vivamus tellus diam, iaculis eget nulla sit amet, tincidunt consectetur sem.',
            ];
            $this->options->items[1] = [
                'image'         =>  'http://cdn.sikido.vn/images/widgets/icons-2.png',
                'title'         =>  'Nulla dict posuere veliitae.',
                'description'   =>  'Etiam orci magna, accumsan varius enim volutpat. Integer pretium molestie nisl, non blandit lectus suscipit in. Vivamus tellus diam, iaculis eget nulla sit amet, tincidunt consectetur sem.',
            ];
            $this->options->items[2] = [
                'image'         =>  'http://cdn.sikido.vn/images/widgets/icons-3.png',
                'title'         =>  'Etiam orci magna, accumsan imperdiet turpis euismod quis',
                'description'   =>  'Donec fringilla velit risus, in imperdiet turpis euismod quis. Integer pretium molestie nisl, non blandit lectus suscipit in. Vivamus tellus diam, iaculis eget nulla sit amet, tincidunt consectetur sem.',
            ];
            $this->options->items[3] = [
                'image'         =>  'http://cdn.sikido.vn/images/widgets/icons-4.png',
                'title'         =>  'Aliquam diam tempor non blandit lectus suscipit in',
                'description'   =>  'Aliquam pulvinar diam tempor erat pellentesque Integer pretium molestie nisl, non blandit lectus suscipit in. Vivamus tellus diam, iaculis eget nulla sit amet, tincidunt consectetur sem.',
            ];
        }
        else{
            foreach ($this->options->items as $key => $value) {
                if(!isset($value['image'])) $value['image'] = '';
                if(!isset($value['title'])) $value['title'] = '';
                if(!isset($value['description'])) $value['description'] = '';
            }
        }

        if(empty($this->options->counter)) {
            $this->options->counter    = [];
            $this->options->counter[0] = [
                'title'       =>  'Khách hàng',
                'number'      =>  '3000',
                'char'        =>  '+',
            ];
            $this->options->counter[1] = [
                'title'       =>  'Sản phẩm',
                'number'      =>  '6000',
                'char'        =>  '+'
            ];
            $this->options->counter[2] = [
                'title'       =>  'Kinh nghiệm',
                'number'      =>  '30',
                'char'        =>  'năm',
            ];
        }

        $this->language();
    }
    function language() {
        $current = Language::current();
        if(Language::hasMulti() && Language::default() != $current) {
            if(isset($this->options->{'description_'.$current})) $this->options->description = $this->options->{'description_'.$current};
            foreach ($this->options->item as $key => &$item) {
                if(isset($item['title_'.$current])) $item['title'] = $item['title_'.$current];
            }
        }
    }
    static function inputCounter($param, $value = []) {
        if(!have_posts($value)) $value = [];
        $value_default = ['number' => '', 'title' => '', 'char' => ''];
        //Số Lượng item
        $number = (isset($param->number)) ? (int)$param->number : 1;
        $output = '';
        $Form = new FormBuilder();
        for ( $i = 0; $i <= $number; $i++ ) {
            if(!isset($value[$i]) || !is_array($value[$i])) $value[$i] = [];
            $value[$i] = array_merge($value_default, $value[$i]);
            $output .= '<label for="name" class="control-label">Item '.($i+1).'</label>';
            $output .= '<div class="stote_wg_item row m-1">';
            $Form->add($param->field.'['.$i.'][number]', 'number', [ 'label' => 'Số đếm',
                'after' => '<div class="col-md-12"><div class="form-group group">', 'before' => '</div></div>'
            ], $value[$i]['number']);
            $Form->add($param->field.'['.$i.'][title]', 'text', [ 'label' => 'Tiêu đề',
                'after' => '<div class="col-md-12"><div class="form-group group">', 'before' => '</div></div>'
            ], $value[$i]['title']);
            if(Language::hasMulti()) {
                foreach (Language::list() as $lang_key => $lang_val) {
                    if($lang_key == Language::default()) continue;
                    $value[$i]['title_'.$lang_key] = (!empty($value[$i]['title_'.$lang_key])) ? $value[$i]['title_'.$lang_key] : '';
                    $Form->add($param->field.'['.$i.'][title_'.$lang_key.']', 'text', ['label' => 'Tiêu đề ('.$lang_val['label'].')', 'after' => '<div class="col-md-12"><div class="form-group group">', 'before' => '</div></div>'], $value[$i]['title_'.$lang_key]);
                }
            }
            $Form->add($param->field.'['.$i.'][char]', 'text', ['label' => 'Ký tự', 'after' => '<div class="col-md-12"><div class="form-group group">', 'before' => '</div></div>'], $value[$i]['char']);
            $output .= $Form->html();
            $output .= '</div>';
        }
        return $output;
    }
}
Widget::add('widget_about_style_9');