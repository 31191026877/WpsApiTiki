<?php

class widget_question_feedback extends widget
{

    public function __construct()
    {

        parent::__construct('widget_question_feedback', 'Câu hỏi & feedback', ['container' => true, 'position' => 'right']);

        add_action('theme_custom_css', array($this, 'css'), 10);

        $this->tags = ['feedback', 'question'];

        $this->author = 'SKDSoftware Dev Team';
    }

    function form($left = [], $right = [])
    {

        $this->left

            ->add('question_limit', 'number', ['label' => 'Số câu hỏi hiển thị', 'value' => 6])

            ->add('feedback_title', 'text', ['label' => 'Tiêu đề phải', 'type' => 'text'])

            ->add('time',   'number', ['label' => 'Time tự động chạy', 'value' => 2, 'after' => '<div class="col-md-6 form-group group">', 'before' => '</div>'])

            ->add('speed', 'number', ['label' => 'Time hoàn thành chạy', 'value' => 3, 'after' => '<div class="col-md-6 form-group group">', 'before' => '</div>']);



        parent::form($left, $right);
    }

    function widget()
    {

        $box = $this->container_box('widget_question_feedback');

        echo $box['before'];

?>

        <div class="row">

            <div class="col-md-6 question-box">

                <?php ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_' . $this->key . '_' . $this->id); ?>

                <div class="panel-group" id="question" role="tablist" aria-multiselectable="true">

                    <?php foreach ($this->options->questions as $key => $item) {
                        $this->itemQuestion($key, $item);
                    } ?>

                </div>

            </div>

            <div class="col-md-6 feedback-box">

                <?php ThemeWidget::heading($this->options->feedback_title, (isset($this->options->heading)) ? $this->options->heading : [], '.js_' . $this->key . '_' . $this->id); ?>

                <div class="box-content">

                    <div class="arrow_box" id="feedback_btn_<?= $this->id; ?>">

                        <div class="prev arrow"><i class="fal fa-chevron-left"></i></div>

                        <div class="next arrow"><i class="fal fa-chevron-right"></i></div>

                    </div>

                    <div id="feedback_nav_<?= $this->id; ?>" class="owl-carousel navSlick">

                        <?php foreach ($this->options->feeds as $key => $val) {
                            $this->itemAva($val);
                        } ?>

                    </div>

                    <div id="feedback_list_<?= $this->id; ?>" class="owl-carousel">

                        <?php foreach ($this->options->feeds as $key => $val) {
                            $this->itemFeed($val);
                        } ?>

                    </div>

                </div>

                <script defer>
                    $(function() {

                        let config = {

                            infinite: true,

                            dots: false,

                            autoplay: true,

                            speed: 700,
                            swipe: false,

                            loop: true,
                            //  autoplayHoverPause:true,
                            slidesToShow: 1,

                            slidesToScroll: 1,
                            rows: 1,


                            responsive: [

                                {
                                    breakpoint: 1000,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1,
                                    }
                                },

                                {
                                    breakpoint: 600,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1,
                                    }
                                }

                            ]

                        };


                        let id = <?= $this->id; ?>;

                        let sliderList = $("#feedback_list_" + id);

                        let sliderBtnNext = $('#feedback_btn_' + id + ' .next');

                        let sliderBtnPrev = $('#feedback_btn_' + id + ' .prev');

                        sliderList.slick(config);

                        sliderBtnNext.click(function() {
                            sliderList2.slick('slickNext');
                            return false;
                        });

                        sliderBtnPrev.click(function() {
                            sliderList2.slick('slickPrev');
                            return false;
                        });

                        let id2 = <?= $this->id; ?>;

                        let sliderList2 = $("#feedback_nav_" + id2);
                        $(sliderList2).slick({
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            asNavFor: sliderList,
                            dots: false,
                            // centerMode: true,
                            // focusOnSelect: true,
                            infinite: true,
                            autoplay: true,
                            speed: 700,
                            loop: true,
                            
                            responsive: [

                                {
                                    breakpoint: 1000,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1,
                                    }
                                },

                                {
                                    breakpoint: 600,
                                    settings: {
                                        slidesToShow: 1,
                                        slidesToScroll: 1,
                                    }
                                }

                            ]
                        });
                    });
                </script>


            </div>

        </div>

    <?php

        echo $box['after'];
    }

    function itemFeed($item)
    {

    ?>

        <div class="item">

            <!-- <div class="avatar" data-aos="fade-top" data-aos-duration="500">

                <?php Template::img($item->image, $item->title); ?>

            </div> -->

            <div class="title" data-aos="fade-up" data-aos-duration="800">

                <h3 class="feedback-name"><?= $item->title; ?></h3>

                <p class="feedback-office"><?= $item->content; ?></p>

                <div class="feedback-content"><?php echo Str::clear($item->excerpt); ?></div>

            </div>

        </div>

    <?php

    }
    function itemAva($item)
    {

    ?>

        <div class="item">

            <div class="avatar" data-aos="fade-top" data-aos-duration="500">

                <?php Template::img($item->image, $item->title); ?>

            </div>

            <!-- <div class="title" data-aos="fade-up" data-aos-duration="800">

                <h3 class="feedback-name"><?= $item->title; ?></h3>

                <p class="feedback-office"><?= $item->content; ?></p>

                <div class="feedback-content"><?php echo Str::clear($item->excerpt); ?></div>

            </div> -->

        </div>

    <?php

    }

    function itemQuestion($key, $item)
    {

    ?>

        <div class="panel panel-default">

            <div class="panel-heading" role="tab" id="question_heading_<?php echo $key; ?>">

                <div class="panel-title">

                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#question" href="#question_group_<?php echo $key; ?>" aria-expanded="true" aria-controls="question_group_<?php echo $key; ?>">

                        <div class="plus-main"><i class="fal fa-plus-circle"></i></div>

                        <div class="title-po"><?= str_word_cut(Str::clear($item->title), 15); ?></div>

                    </a>

                </div>

            </div>

            <div id="question_group_<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="question_heading_<?php echo $key; ?>">

                <div class="panel-body">

                    <?= str_word_cut(Str::clear($item->content), 50); ?>

                </div>

            </div>

        </div>

<?php

    }

    function default()
    {

        if ($this->name == 'Câu hỏi & feedback') $this->name = 'CÂU HỎI THƯỜNG GẶP';

        if (!isset($this->options->feedback_title)) $this->options->feedback_title = 'Ý KIẾN KHÁCH HÀNG';

        if (!isset($this->options->question_limit)) $this->options->question_limit = 6;

        if (!isset($this->options->time))  $this->options->time = 2;

        if (!isset($this->options->speed))  $this->options->speed = 3;

        if (!isset($this->options->box))   $this->options->box = 'container';

        if (Plugin::isActive('feedback')) {

            $args = Qr::set('post_type', FEEDBACK_POST_TYPE)->where('public', 0)->orderBy('order')->orderByDesc('post.created')->limit((!empty($this->options->limit)) ? $this->options->limit : 20);

            $this->options->feeds = Posts::gets($args);
        } else {

            $this->options->feeds = [

                (object)[

                    'image'     => 'https://cdn.sikido.vn/image/random/' . rand(1, 1000) . '/200x200',

                    'title'     => 'Hoàng Thùy Yến',

                    'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',

                    'excerpt'   => 'Nhân sự',

                ],

                (object)[

                    'image'     => 'https://cdn.sikido.vn/image/random/' . rand(1, 1000) . '/200x200',

                    'title'     => 'Cao Yến Vy',

                    'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',

                    'excerpt'   => 'Design',

                ],

            ];
        }



        if (Plugin::isActive('question-answer')) {

            $args = Qr::set('post_type', QA_KEY)->orderBy('order')->orderByDesc('post.created')->limit((!empty($this->options->limit)) ? $this->options->limit : 20);

            $this->options->questions = Posts::gets($args);
        } else {

            $this->options->questions = [

                (object)[

                    'title'     => 'What are some random questions to ask?',

                    'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',

                ],

                (object)[

                    'title'     => 'Do you include common questions?',

                    'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',

                ],

                (object)[

                    'title'     => 'Can I use this for 21 questions?',

                    'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',

                ],

                (object)[

                    'title'     => 'Are these questions for girls or for boys?',

                    'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',

                ],

                (object)[

                    'title'     => 'What is the next skill that you\'d like to learn really well?',

                    'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',

                ],

                (object)[

                    'title'     => 'How would you describe someone who is wealthy?',

                    'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',

                ],

            ];
        }
    }

    function css()
    {
        include_once('assets/question-feedback.css');
    }
}



Widget::add('widget_question_feedback');
