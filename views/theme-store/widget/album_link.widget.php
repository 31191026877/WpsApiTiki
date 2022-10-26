<?php
class widget_album_link extends widget
{
    function __construct()
    {
        parent::__construct('widget_album_link', 'widget_album_link', ['container' => true, 'position' => 'left']);
        add_action('theme_custom_css', array($this, 'css'), 10);
    }
    function form($left = [], $right = [])
    {
        $this->left
            ->add('item', 'repeater', ['label' => 'Danh sách item trái', 'fields' => [

                ['name' => 'image', 'type' => 'image', 'label' => __('image'), 'col' => 4],

                ['name' => 'title', 'type' => 'text',  'label' => __('Tiêu đề'), 'col' => 4, 'language' => true],

                ['name' => 'url', 'type' => 'text', 'label' => __('Liên kết'), 'col' => 6],
            ]])
            ->add('item2', 'repeater', ['label' => 'Danh sách item giữa', 'fields' => [

                ['name' => 'image', 'type' => 'image', 'label' => __('image'), 'col' => 4],

                ['name' => 'title', 'type' => 'text',  'label' => __('Tiêu đề'), 'col' => 4, 'language' => true],

                ['name' => 'url', 'type' => 'text', 'label' => __('Liên kết'), 'col' => 6],
            ]])
            ->add('item3', 'repeater', ['label' => 'Danh sách item phải', 'fields' => [

                ['name' => 'image', 'type' => 'image', 'label' => __('image'), 'col' => 4],

                ['name' => 'title', 'type' => 'text',  'label' => __('Tiêu đề'), 'col' => 4, 'language' => true],

                ['name' => 'url', 'type' => 'text', 'label' => __('Liên kết'), 'col' => 6],
            ]])
            ->add('item4', 'repeater', ['label' => 'Danh sách item bottom', 'fields' => [

                ['name' => 'image', 'type' => 'image', 'label' => __('image'), 'col' => 4],

                ['name' => 'title', 'type' => 'text',  'label' => __('Tiêu đề'), 'col' => 4, 'language' => true],

                ['name' => 'url', 'type' => 'text', 'label' => __('Liên kết'), 'col' => 6],
            ]]);
        parent::form($left, $right);
    }
    function widget()
    {
        $box = $this->container_box('widget_album_link');
        echo $box['before'];
?>
        <div class="itemAlbum">
            <div class="contentItem">
                <div class="leftRow">
                    <?php foreach ($this->options->item as $key => $item1) : ?>
                        <div class="item">
                            <a href="<?= $item1['url']; ?>">
                                <div class="img">
                                    <?php Template::img($item1['image']); ?>
                                </div>
                            </a>
                            <div class="titleInside">
                                <div class="title">
                                    <a href="<?= $item1['url']; ?>">
                                        <?= $item1['title']; ?>
                                    </a>
                                </div>
                                <a href="<?= $item1['url']; ?>">Xem thêm</a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
                <div class="midleRow">
                    <?php foreach ($this->options->item2 as $key => $item2) : ?>
                        <div class="item">
                            <a href="<?= $item2['url']; ?>">
                                <div class="img">
                                    <?php Template::img($item2['image']); ?>
                                </div>
                            </a>
                            <div class="titleInside">
                                <div class="title">
                                    <a href="<?= $item2['url']; ?>">
                                        <?= $item2['title']; ?>
                                    </a>
                                </div>
                                <a href="<?= $item2['url']; ?>">Xem thêm</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="rightRow">
                    <?php foreach ($this->options->item3 as $key => $item3) : ?>
                        <div class="item">
                            <a href="<?= $item3['url']; ?>">
                                <div class="img">
                                    <?php Template::img($item3['image']); ?>
                                </div>
                            </a>
                            <div class="titleInside">
                                <div class="title">
                                    <a href="<?= $item3['url']; ?>">
                                        <?= $item3['title']; ?>
                                    </a>
                                </div>
                                <a href="<?= $item3['url']; ?>">Xem thêm</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="itemBottom">
                <?php foreach ($this->options->item4 as $key => $item4) : ?>
                    <div class="item">
                        <a href="<?= $item4['url']; ?>">
                            <div class="img">
                                <?php Template::img($item4['image']); ?>
                            </div>
                        </a>
                        <div class="titleInside">
                            <div class="title">
                                <a href="<?= $item4['url']; ?>">
                                    <?= $item4['title']; ?>
                                </a>
                            </div>
                            <a href="<?= $item4['url']; ?>">Xem thêm</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <style>
            .widget_album_link .contentItem {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }

            .widget_album_link .contentItem .leftRow .item:nth-child(odd) .img,
            .widget_album_link .contentItem .rightRow .item:nth-child(odd) .img {
                width: 100%;
                height: 240px;
            }

            .widget_album_link .contentItem .leftRow .item:nth-child(even) .img,
            .widget_album_link .contentItem .rightRow .item:nth-child(even) .img {
                width: 100%;
                height: 520px;
            }

            .widget_album_link .contentItem .midleRow .item:nth-child(odd) .img {
                width: 100%;
                height: 520px;
            }

            .widget_album_link .contentItem .midleRow .item:nth-child(even) .img {
                width: 100%;
                height: 240px;
            }

            .widget_album_link .itemBottom {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-top: 20px;
            }

            .widget_album_link .itemAlbum img {
                width: 100%;
                height: 100%;
                object-fit: cover;

            }

            .widget_album_link .itemAlbum .item {
                position: relative;
            }

            .widget_album_link .itemAlbum .item:nth-child(odd) {
                margin-bottom: 20px;
            }

            .widget_album_link .itemAlbum .titleInside1 {
                position: absolute;
                top: 30%;
                left: 50px;
                width: calc(100% - 100px);
                color: #1A4A4A;
                font-size: 20px;
                font-weight: 300;
                padding: 40px 48px;
                text-align: center;
            }

            .widget_album_link .itemAlbum .titleInside {
                position: absolute;
                bottom: 30px;
                left: 50px;
                width: calc(100% - 100px);
                background-color: #fff;
                color: #1A4A4A;
                font-size: 20px;
                font-weight: 300;
                padding: 40px 48px;
                text-align: center;
                opacity: 0;
                transition: 0.8s all;
            }

            .widget_album_link .itemAlbum .titleInside a {
                font-style: italic;
                font-weight: 400;
                font-size: 14px;
                color: #54BAB9;
            }

            .widget_album_link .itemAlbum .titleInside .title a {
                font-weight: 300;
                font-size: 20px;
                line-height: 27px;
                color: #1A4A4A;
                font-style: normal;
            }

            .widget_album_link .itemAlbum .item:hover .titleInside {
                opacity: 1;
            }

            @media (max-width: 767px) {
                .widget_album_link .contentItem {
                    display: grid;
                    grid-template-columns: repeat(1, 1fr);
                    gap: 20px;
                }

                .widget_album_link .itemBottom {
                    display: grid;
                    grid-template-columns: repeat(1, 1fr);
                    gap: 20px;
                    margin-top: 20px;
                }

                .widget_album_link .itemAlbum .titleInside {
                    opacity: 1;
                }

                .widget_album_link .itemAlbum .titleInside {
                    padding: 20px 28px;
                }
            }

            /*// Large devices  Ipad */
            @media (min-width: 768px) and (max-width: 991.99px) {
                .widget_album_link .itemAlbum .titleInside {
                    opacity: 1;
                    width: calc(100% - 50px);
                    left: 25px;
                    padding: 10px 18px;
                }

                .widget_album_link .itemAlbum .titleInside1 {
                    padding: 10px 18px;
                    width: calc(100% - 50px);
                    left: 25px;
                }
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
Widget::add('widget_album_link');
