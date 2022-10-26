<?php

class widget_count_content extends widget
{

    function __construct()
    {

        parent::__construct('widget_count_content', 'Widget_count_content', ['container' => true, 'position' => 'left']);

        add_action('theme_custom_css', array($this, 'css'), 10);
    }

    function form($left = [], $right = [])
    {
        $this->left
            ->add('firstCol', 'text', ['label' => 'Số bên trái'])
            ->add('firstColDesc', 'text', ['label' => 'Mô tả bên trái'])
            ->add('bannerMidle', 'image', ['label' => 'Banner giữa'])
            ->add('secondCol', 'text', ['label' => 'Số bên phải'])
            ->add('secondColDesc', 'text', ['label' => 'Mô tả bên phải'])
            ->add('bottomDesc', 'wysiwyg', ['label' => 'Mô tả bên dưới']);

        parent::form($left, $right);
    }

    function widget()
    {

        $box = $this->container_box('widget_count_content');

        echo $box['before'];

?>
        <div id="widget_count_content_<?php echo $this->id; ?>" class="row contentCount">
            <div class="col-md-4 leftCol">
                <div class="numberContent ">
                    <div>
                        <span class="numberCount" data-number="<?php echo $this->options->firstCol; ?>"></span>
                        <span class="sladNumber">/</span> <span class="contentNumber"><?= $this->options->firstColDesc; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php Template::img($this->options->bannerMidle); ?>
            </div>
            <div class="col-md-4 rightCol">
                <div class="numberContent">
                    <span class="numberCount" data-number="<?php echo $this->options->secondCol; ?>"></span>
                    <span class="sladNumber">/</span> <span class="contentNumber"><?= $this->options->secondColDesc; ?></span>
                </div>
            </div>
        </div>
        <div class="row contentBottom text-center">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <p>
                    <?= $this->options->bottomDesc; ?>
                </p>
            </div>
            <div class="col-md-2"></div>

        </div>
        <script defer>
            $(function() {

                let a = 0;

                $(window).scroll(function() {

                    let oTop = $("#widget_count_content_<?php echo $this->id; ?>").offset().top - window.innerHeight;

                    if (a == 0 && $(window).scrollTop() > oTop) {

                        $(".numberCount").each(function() {

                            let $this = $(this),

                                countTo = $this.attr("data-number");

                            $({

                                countNum: $this.text()

                            }).animate(

                                {
                                    countNum: countTo
                                },

                                {
                                    duration: 2000,

                                    easing: "swing",

                                    step: function() {
                                        //$this.text(Math.ceil(this.countNum));
                                        $this.text(
                                            Math.ceil(this.countNum)
                                        );

                                    },

                                    complete: function() {

                                        $this.text(

                                            Math.ceil(this.countNum)
                                        );

                                        //alert('finished');
                                    }
                                }

                            );

                        });

                        a = 1;
                    }
                });

                // document.querySelectorAll('#item_style_18_<?php echo $this->id; ?> .list-item').forEach((listItem) => {
                //     let items = listItem.querySelectorAll('.service-block')
                //     let angle = 360 - 90,
                //         dangle = 360 / items.length
                //     for (let i = 0; i < items.length; ++i) {
                //         let item = items[i]
                //         angle += dangle
                //         item.style.transform = `rotate(${angle}deg) translate(${listItem.clientWidth / 2}px) rotate(-${angle}deg)`
                //     }
                // });

            })
        </script>
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
            .widget_count_content .contentCount .leftCol,
            .widget_count_content .contentCount .rightCol {
                color: #fff;
                margin-top: 168px;
                font-size: 64px;
            }

            .widget_count_content .contentCount .numberCount {
                font-size: 64px;
            }

            .widget_count_content .contentCount .contentNumber {
                font-size: 17px;
            }

            .widget_count_content .sladNumber {
                color: #D9D9D9;
                font-size: 64px;
            }

            .widget_count_content .contentBottom {
                color: #fff;
            }

            /*// Large devices  Phone */
            @media (max-width: 767px) {
                .widget_count_content .numberContent {
                    margin-bottom: 20px;
                }

                .widget_count_content .contentCount .leftCol,
                .widget_count_content .contentCount .rightCol {
                    margin-top: 30px;
                    margin-left: 30px;
                }
            }
        </style>

<?php
    }
}

Widget::add('widget_count_content');
