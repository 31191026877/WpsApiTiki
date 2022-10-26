<?php
class widget_feedback_style_1 extends widget {
    function __construct() {
        parent::__construct('widget_feedback_style_1', 'Feedback (style 1)', [ 'container' => true, 'position' => 'right' ]);
        add_action('theme_custom_css', array( $this, 'css'), 10);
        add_action('theme_custom_script', array( $this, 'script'), 10);
        $this->tabs = ['feedback'];
        $this->author = 'SKDSoftware Dev Team';
    }
    function form( $left = [], $right = []) {
        $this->left
            ->add('limit', 'number', ['value' => 10, 'label'=> 'Số Item lấy ra', 'note'=>'Để 0 để lấy tất cả', 'after' => '<div class="col-md-6 form-group group">', 'before'=> '</div>'])
            ->add('time', 'number', ['label' =>'Time tự động chạy',  'value' => 2, 'after' => '<div class="col-md-6 form-group group">', 'before'=> '</div>']);
        parent::form($left, $right);
    }
    function widget() {
        $box = $this->container_box('widget_feedback_style_1');
        echo $box['before'];
        ?>
        <?php ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_'.$this->key.'_'.$this->id);?>
        <div class="box-content">
            <div class="arrow_box" id="feedback_btn_<?= $this->id;?>">
                <div class="prev arrow"><i class="fal fa-chevron-left"></i></div>
                <div class="next arrow"><i class="fal fa-chevron-right"></i></div>
            </div>
            <div class="swiper" id="feedback_list_<?= $this->id;?>" data-time="<?= $this->options->time;?>" data-id="<?= $this->id;?>">
                <div class="swiper-wrapper">
                    <?php foreach ($this->options->feeds as $key => $val) { $this->item($val); } ?>
                </div>
            </div>
        </div>
        <?php
        echo $box['after'];
    }
    function item($item) {
        ?>
        <div class="swiper-slide">
            <div class="item">
                <div class="avatar" data-aos="fade-top" data-aos-duration="500"><?php Template::img($item->image, $item->title);?></div>
                <div class="title" data-aos="fade-up" data-aos-duration="800">
                    <p class="feedback-name"><?= $item->title;?></p>
                    <p class="feedback-office"><?= $item->content;?></p>
                    <div class="feedback-content"><?php echo Str::clear($item->excerpt);?></div>
                </div>
            </div>
        </div>
        <?php
    }
    function default() {
        if($this->name == 'Feedback (style 1)') $this->name = 'Ý KIẾN KHÁCH HÀNG';
        if(!isset($this->options->time))  $this->options->time = 1;
        if(!isset($this->options->box))   $this->options->box = 'container';
        if(Plugin::isActive('feedback')) {
            $args = Qr::set('post_type', FEEDBACK_POST_TYPE)
                ->where('public', 0)
                ->orderBy('order')->orderByDesc('created')
                ->limit((!empty($this->options->limit)) ? $this->options->limit : 20);
            $this->options->feeds = Posts::gets($args);
        }
        else {
            $this->options->feeds = [
                (object)[
                    'id'        => 1,
                    'image'     => 'https://cdn.sikido.vn/image/random/'.rand(1,1000).'/200x200',
                    'title'     => 'Hoàng Thùy Yến',
                    'excerpt'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'content'   => 'Nhân sự',
                ],
                (object)[
                    'id'        => 2,
                    'image'     => 'https://cdn.sikido.vn/image/random/'.rand(1,1000).'/200x200',
                    'title'     => 'Cao Yến Vy',
                    'excerpt'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'content'   => 'Design',
                ],
                (object)[
                    'id'        => 3,
                    'image'     => 'https://cdn.sikido.vn/image/random/'.rand(1,1000).'/200x200',
                    'title'     => 'NGuyễn Văn Cường',
                    'excerpt'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                    'content'   => 'Design',
                ],
            ];
        }
    }
    function css() { include_once('assets/feedback-style-1.css'); }
    function script() { include_once('assets/feedback-script-1.js'); }
}

Widget::add('widget_feedback_style_1');
