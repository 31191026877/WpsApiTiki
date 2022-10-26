<?php

class widget_hover_test extends widget
{

    function __construct()
    {

        parent::__construct('widget_hover_test', 'widget_hover_test', ['container' => true, 'position' => 'left']);

        add_action('theme_custom_css', array($this, 'css'), 10);
    }

    function form($left = [], $right = [])
    {
        $this->left
            ->add('item', 'repeater', ['label' => 'Danh sách item trái', 'fields' => [

                ['name' => 'image', 'type' => 'image', 'label' => __('image'), 'col' => 4],

                ['name' => 'title', 'type' => 'text',  'label' => __('Tiêu đề'), 'col' => 4, 'language' => true],
            ]]);

        parent::form($left, $right);
    }

    function widget()
    {

        $box = $this->container_box('widget_hover_test');

        echo $box['before'];
?>
        <div class="row">
            <div class="col-md-4">
                <?php foreach ($this->options->item as $key => $item) : ?>
                    <div class="item" data-src="<?= $item['image']; ?>" data-text="<?= $item['title']; ?>">
                        <?= $item['title']; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-8">
                <div class="img">

                </div>
            </div>
        </div>
        <style>
            .img {
                background-image: url('http://ktoan.sikidodemo.com/hd9365/uploads/source//dont-delete/image.png');
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
}

Widget::add('widget_hover_test');
