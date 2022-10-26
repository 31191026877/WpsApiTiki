<?php

class wiget_content_timeline extends widget
{

    function __construct()
    {

        parent::__construct('wiget_content_timeline', 'Wiget_content_timeline', ['container' => true, 'position' => 'left']);

        add_action('theme_custom_css', array($this, 'css'), 10);
    }

    function form($left = [], $right = [])
    {

        $this->left
            ->add('image_left', 'image', ['label' => 'Ảnh bên trái'])
            ->add('desc_image', 'wysiwyg', ['label' => 'Mô tả bên phải'])
            ->add('item', 'repeater', ['label' => 'Danh sách timeline', 'fields' => [
                ['name' => 'title', 'type' => 'text',  'label' => __('Năm'), 'col' => 4, 'language' => true],

                ['name' => 'description', 'type' => 'textarea', 'label' => __('Mô tả timeline'), 'col' => 4, 'language' => true],
            ]]);

        $this->right
            ->add('bg_item', 'image', ['label' => 'background item'])
            ->add('color_item', 'color', ['label' => 'background item']);




        parent::form($left, $right);
    }

    function widget()
    {

        $box = $this->container_box('wiget_content_timeline');

        echo $box['before'];

        ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_' . $this->key . '_' . $this->id); ?>


        <div class="row box-content">
            <div class="col-md-6 col-sm-12 text-right">
                <?php Template::img($this->options->image_left); ?>
            </div>
            <div class="col-md-6 col-sm-12 contentDesc">
                <?= $this->options->desc_image; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="contentTimeLine" style="display:inline-block;width:100%;overflow-y:auto;">
                    <ul class="timeline timeline-horizontal">
                        <?php foreach ($this->options->item as $key => $item) : ?>
                            <li class="timeline-item">
                                <div class="timeline-badge primary"><?= $item['title']; ?></div>
                                <div class="timeline-panel aos-init aos-animate" data-aos-delay="0" data-aos="fade" data-aos-duration="2000">
                                    <div class="timeline-body">
                                        <p><?= $item['description']; ?></p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <style>
            .wiget_content_timeline .timeline .timeline-item .timeline-badge.primary {
                background-image: url(<?php echo Template::imgLink($this->options->bg_item); ?>);
                color: #B0976D;
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
    ?>
        <style>
            .wiget_content_timeline .box-content .contentDesc {
                margin-top: 30px;
            }

            .wiget_content_timeline {
                padding-bottom: 100px;
            }

            .wiget_content_timeline .contentTimeLine {
                height: 600px;
            }

            /* Timeline */
            .wiget_content_timeline .timeline,
            .wiget_content_timeline .timeline-horizontal {
                list-style: none;
                padding: 20px;
                position: relative;
            }

            .wiget_content_timeline .timeline:before {
                top: 40px;
                bottom: 0;
                position: absolute;
                content: " ";
                width: 3px;
                background-color: #B0976D;
                left: 50%;
                margin-left: -1.5px;
            }

            .wiget_content_timeline .timeline .timeline-item {
                margin-bottom: 20px;
                position: relative;
                transition-delay: 5s;
            }

            .wiget_content_timeline .timeline .timeline-item:before,
            .wiget_content_timeline .timeline .timeline-item:after {
                content: "";
                display: table;
            }

            .wiget_content_timeline .timeline .timeline-item:after {
                clear: both;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-badge {
                color: #fff;
                width: 132px;
                height: 132px;
                line-height: 127px;
                font-size: 22px;
                text-align: center;
                position: absolute;
                top: 18px;
                left: 50%;
                margin-left: -25px;
                background-color: #7c7c7c;
                border: 3px solid #B0976D;
                z-index: 100;
                border-top-right-radius: 50%;
                border-top-left-radius: 50%;
                border-bottom-right-radius: 50%;
                border-bottom-left-radius: 50%;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-badge i,
            .wiget_content_timeline .timeline .timeline-item .timeline-badge .fa,
            .wiget_content_timeline .timeline .timeline-item .timeline-badge .glyphicon {
                top: 2px;
                left: 0px;
            }



            .wiget_content_timeline .timeline .timeline-item .timeline-badge.info {
                background-color: #5bc0de;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-badge.success {
                background-color: #59ba1f;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-badge.warning {
                background-color: #d1bd10;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-badge.danger {
                background-color: #ba1f1f;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-panel {
                position: relative;
                width: 46%;
                float: left;
                right: 16px;
                border: 1px solid #c0c0c0;
                background: rgba(37, 37, 37, 0.6);
                border-radius: 2px;
                padding: 20px;
                -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
                box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-panel .timeline-body p {
                color: #fff;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-panel:before {
                position: absolute;
                top: 26px;
                right: -16px;
                display: inline-block;
                border-top: 16px solid transparent;
                border-left: 16px solid #c0c0c0;
                border-right: 0 solid #c0c0c0;
                border-bottom: 16px solid transparent;
                content: " ";
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-panel .timeline-title {
                margin-top: 0;
                color: inherit;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-panel .timeline-body>p,
            .wiget_content_timeline .timeline .timeline-item .timeline-panel .timeline-body>ul {
                margin-bottom: 0;
            }

            .wiget_content_timeline .timeline .timeline-item .timeline-panel .timeline-body>p+p {
                margin-top: 5px;
            }

            .wiget_content_timeline .timeline .timeline-item:last-child:nth-child(even) {
                float: right;
            }

            .wiget_content_timeline .timeline .timeline-item:nth-child(even) .timeline-panel {
                float: right;
                left: 16px;
                top: 255px;
            }

            .wiget_content_timeline .timeline .timeline-item:nth-child(even) .timeline-panel:before {
                left: 82px !important;
                top: -29px;
                border-right: 0 solid transparent !important;
                border-top: 29px solid transparent !important;
                border-bottom: 0 solid rgba(37, 37, 37, 0.6) !important;
                border-left: 20px solid rgba(37, 37, 37, 0.6) !important;
                height: 20px;

            }

            .wiget_content_timeline .timeline-horizontal {
                list-style: none;
                position: relative;
                padding: 20px 0px 20px 0px;
                display: inline-block;
            }

            .wiget_content_timeline .timeline-horizontal:before {
                height: 3px;
                top: auto;
                bottom: 26px;
                left: 0px;
                right: 0;
                width: 100%;
                margin-bottom: 20px;
            }

            .wiget_content_timeline .timeline-horizontal .timeline-item {
                display: table-cell;
                height: 280px;
                width: 10%;
                min-width: 240px;
                float: none !important;
                padding-left: 0px;
                padding-right: 0px;
                margin: 0 auto;
                vertical-align: bottom;
            }

            .wiget_content_timeline .timeline-horizontal .timeline-item .timeline-panel {
                top: auto;
                bottom: 115px;
                display: inline-block;
                float: none !important;
                left: 0 !important;
                right: 0 !important;
                width: 100%;
                margin-bottom: 20px;
            }

            .wiget_content_timeline .timeline-horizontal .timeline-item .timeline-panel:before {
                top: auto;
                bottom: -30px;
                left: 72px !important;
                right: auto;
                border-right: 0 solid transparent !important;
                border-top: 30px solid rgba(37, 37, 37, 0.6) !important;
                border-bottom: 0 solid rgba(37, 37, 37, 0.6) !important;
                border-left: 20px solid transparent !important;
            }

            .wiget_content_timeline .timeline-horizontal .timeline-item:before,
            .wiget_content_timeline .timeline-horizontal .timeline-item:after {
                display: none;
            }

            .wiget_content_timeline .timeline-horizontal .timeline-item .timeline-badge {
                top: auto;
                bottom: -36px;
                left: 43px;
            }

            /*// Large devices  Phone */
            @media (max-width: 767px) {
                .wiget_content_timeline .timeline-horizontal:before {
                    display: none;
                }

                .wiget_content_timeline .timeline-horizontal .timeline-item {
                    min-width: 179px;
                    height: 337px;
                }

                .wiget_content_timeline .timeline .timeline-item:nth-child(even) .timeline-panel {
                    top: 300px;
                }

                .wiget_content_timeline .contentTimeLine {
                    height: 645px;
                }

                .wiget_content_timeline {
                    padding-bottom: 110px;
                }

            }
        </style>
<?php
    }
}

Widget::add('wiget_content_timeline');
