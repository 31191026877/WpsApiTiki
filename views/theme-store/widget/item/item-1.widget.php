<?php

class widget_item_style_1 extends widget
{



    function __construct()
    {

        parent::__construct('widget_item_style_1', 'Item 1', ['container' => true, 'position'  => 'right']);

        add_action('theme_custom_css', array($this, 'css'), 10);

        $this->tags = ['item'];

        $this->author = 'SKDSoftware Dev Team';

        $this->heading = false;
    }



    function form($left = [], $right = [])
    {

        $this->left
            ->add('itemDesc', 'wysiwyg', ['label' => 'Mô tả dưới tiêu đề', 'after' => '<div class="builder-col-4 col-md-12 form-group group">', 'before' => '</div>'])

            ->add('itemHeadingColor', 'color', ['label' => 'Màu tiêu đề item', 'after' => '<div class="builder-col-4 col-md-4 form-group group">', 'before' => '</div>'])

            ->add('itemDesColor', 'color', ['label' => 'Màu mô tả item', 'after' => '<div class="builder-col-4 col-md-4 form-group group">', 'before' => '</div>'])

            ->add('itemHeight', 'number', ['label' => 'Chiều cao icon', 'after' => '<div class="builder-col-4 col-md-4 form-group group">', 'before' => '</div>'])

            ->add('item', 'repeater', ['label' => 'Danh sách item', 'fields' => [

                ['name' => 'image', 'type' => 'image', 'label' => __('Icon'), 'col' => 4],

                ['name' => 'title', 'type' => 'text',  'label' => __('Tiêu đề'), 'col' => 4, 'language' => true],

                ['name' => 'description', 'type' => 'textarea', 'label' => __('Mô tả'), 'col' => 4, 'language' => true],

                ['name' => 'url', 'type' => 'text', 'label' => __('Liên kết'), 'col' => 6],

                ['name' => 'animate', 'type' => 'select', 'label' => __('Hiệu ứng'), 'options' => animate_css_option(), 'col' => 6],

            ]]);



        $this->right

            ->add('numberShow', 'col', ['label' => 'Số item/hàng (Desktop)', 'value' => 3, 'min' => 1, 'max' => 5])

            ->add('numberShowTablet', 'col', ['label' => 'Số item/hàng (Tablet)', 'value' => 2, 'min' => 1, 'max' => 4])

            ->add('numberShowMobile', 'col', ['label' => 'Số item/hàng (Mobile)', 'value' => 1, 'min' => 1, 'max' => 2]);



        parent::form($left, $right);
    }

    function widget()
    {

        $box  = $this->container_box('widget_item_style_1');

        echo $box['before'];

        $number = 0;

        ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_' . $this->key . '_' . $this->id);

?>
        <div class="des text-center">
            <?=$this->options->itemDesc;?>
        </div>
        <div class="row-flex">

            <?php foreach ($this->options->item as $key => $item) { ?>

                <div class="item item<?php echo $key; ?>" data-aos-delay="<?php echo $number++ * 100; ?>" data-aos="<?php echo $item['animate']; ?>" data-aos-duration="500">

                    <a href="<?php echo $item['url']; ?>" title="<?php echo $item['title']; ?>">

                        <div class="img">

                            <?php Template::img($item['image'], $item['title']); ?>

                        </div>

                        <div class="title">

                            <p class="heading"><?php echo $item['title']; ?></p>

                            <?php if (!empty($item['description'])) { ?>

                                <p class="description"><?php echo $item['description']; ?></p>

                            <?php } ?>

                        </div>

                    </a>

                </div>

            <?php } ?>

        </div>

        <style>
            .js_widget_item_style_1_<?php echo $this->id; ?> {

                --item1-title: <?php echo (!empty($this->options->itemHeadingColor)) ? $this->options->itemHeadingColor : '#000'; ?>;

                --item1-des: <?php echo (!empty($this->options->itemDesColor)) ? $this->options->itemDesColor : '#8a8b8c'; ?>;

                --item1-height: <?php echo (!empty($this->options->itemHeight)) ? $this->options->itemHeight : '60'; ?>px;



                --item1-per-row: <?php echo $this->options->template; ?>;

                --item1-per-row-tablet: <?php echo $this->options->templateTablet; ?>;

                --item1-per-row-mobile: <?php echo $this->options->templateMobile; ?>;

            }
        </style>

<?php echo $box['after'];
    }

    function default()
    {

        if (!isset($this->options->numberShow)) $this->options->numberShow = 3;

        $this->options->template = '';

        for ($i = 0; $i < $this->options->numberShow; $i++) $this->options->template .= '1fr ';

        if (!isset($this->options->numberShowTablet)) $this->options->numberShowTablet = 2;

        $this->options->templateTablet = '';

        for ($i = 0; $i < $this->options->numberShowTablet; $i++) $this->options->templateTablet .= '1fr ';

        if (!isset($this->options->numberShowMobile)) $this->options->numberShowMobile = 2;

        $this->options->templateMobile = '';

        for ($i = 0; $i < $this->options->numberShowMobile; $i++) $this->options->templateMobile .= '1fr ';

        if (!isset($this->options->box)) $this->options->box = 'container';

        if (empty($this->options->item)) {

            $this->options->item    = [];

            $this->options->item[0] = [

                'image'         =>  'http://cdn.sikido.vn/images/widgets/set-icon-001.svg',

                'title'         =>  'Tiêu đề item 1',

                'url'           =>  'https://sikido.vn',

                'animate'       =>  'fade',

                'description'   =>  'Nội dung mô tả của item 1',

            ];

            $this->options->item[1] = [

                'image'         =>  'http://cdn.sikido.vn/images/widgets/set-icon-002.svg',

                'title'         =>  'Tiêu đề item 2',

                'url'           =>  'https://sikido.vn',

                'animate'       =>  'fade',

                'description'   =>  'Nội dung mô tả của item 2',

            ];

            $this->options->item[2] = [

                'image'         =>  'http://cdn.sikido.vn/images/widgets/set-icon-003.svg',

                'title'         =>  'Tiêu đề item 3',

                'url'           =>  'https://sikido.vn',

                'animate'       =>  'fade',

                'description'   =>  'Nội dung mô tả của item 3',

            ];
        } else {

            foreach ($this->options->item as $key => $value) {

                if (!isset($value['image'])) $value['image'] = '';

                if (!isset($value['title'])) $value['title'] = '';

                if (!isset($value['description'])) $value['description'] = '';

                if (!isset($value['url'])) $value['url'] = '';

                if (!isset($value['animate'])) $value['animate'] = 0;
            }
        }

        $this->language();
    }

    function language()
    {

        $language_current = Language::current();

        if (Language::hasMulti() && Language::default() != $language_current) {

            foreach ($this->options->item as $key => &$item) {

                if (isset($item['title_' . $language_current])) $item['title'] = $item['title_' . $language_current];

                if (isset($item['description_' . $language_current])) $item['description'] = $item['description_' . $language_current];
            }
        }
    }

    function css()
    {
        include_once('css/item-style-1.css');
    }

    function update($new_instance, $old_instance)
    {

        if (isset($new_instance['options']->item)) {

            foreach ($new_instance['options']->item as $key => &$item) {

                $item['image'] = FileHandler::handlingUrl($item['image']);
            }
        }

        return $new_instance;
    }
}



Widget::add('widget_item_style_1');
