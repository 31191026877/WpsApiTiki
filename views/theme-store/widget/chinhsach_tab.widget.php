<?php
class widget_chinhsach_tab extends widget
{
    function __construct()
    {
        parent::__construct('widget_chinhsach_tab', 'widget_chinhsach_tab', ['container' => true, 'position' => 'left']);
        add_action('theme_custom_css', array($this, 'css'), 10);
    }
    function form($left = [], $right = [])
    {
        $this->left
            ->add('item', 'repeater', ['label' => 'Danh sách chi nhánh', 'fields' => [

                ['name' => 'title', 'type' => 'text',  'label' => __('Tiêu đề'), 'col' => 6, 'language' => true],

                ['name' => 'image', 'type' => 'image',  'label' => __('Image'), 'col' => 6],

                ['name' => 'description', 'type' => 'wysiwyg', 'label' => __('Mô tả'), 'col' => 12, 'language' => true],

                ['name' => 'url', 'type' => 'text', 'label' => __('Liên kết'), 'col' => 6],

            ]]);
        $this->right
            ->add('imageBottom', 'image', ['label' => 'banner Dưới']);
        parent::form($left, $right);
    }
    function widget()
    {
        $box = $this->container_box('widget_chinhsach_tab');
        echo $box['before'];

?>
        <?php ThemeWidget::heading($this->name, (isset($this->options->heading)) ? $this->options->heading : [], '.js_' . $this->key . '_' . $this->id); ?>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <?php $active = "active";
                foreach ($this->options->item as $key => $item) : ?>
                    <button class="nav-link <?= $active; ?>" id="nav-<?= $key; ?>-tab" data-bs-toggle="tab" data-bs-target="#nav-<?= $key; ?>" type="button" role="tab" aria-controls="nav-<?= $key; ?>" aria-selected="true"><?= $item['title']; ?></button>
                <?php
                    $active = "";
                endforeach; ?>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <?php $show = "show active";
            foreach ($this->options->item as $key => $item) : ?>
                <div class="tab-pane fade <?= $show; ?>" id="nav-<?= $key; ?>" role="tabpanel" aria-labelledby="nav-<?= $key; ?>-tab">
                    <div class="row contentTab">
                        <div class="col-md-6 rowLeft">
                            <div class="title">
                                <?= $item['title']; ?>
                            </div>
                            <div class="desc">
                                <?= $item['description']; ?>
                            </div>
                            <a href="<?= $item['url']; ?>" class="btn btn-effect-default btn-theme">Xem thêm</a>
                        </div>
                        <div class="col-md-6 rowRight">
                            <div class="img">
                                <?php Template::img($item['image']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                $show = "";
            endforeach; ?>
        </div>

        <style>
            .widget_chinhsach_tab .nav-tabs {
                justify-content: center;
                gap: 30px;
                border-bottom: none;
                margin-bottom: 40px;
            }

            .widget_chinhsach_tab .tab-content {
                position: relative;
            }

            .widget_chinhsach_tab .contentTab .rowRight img {
                width: 100%;
            }

            .widget_chinhsach_tab .contentTab {
                /* align-items: center; */
                padding-left: 10px;
            }

            .widget_chinhsach_tab .contentTab .rowLeft {
                background-color: #fff;
                padding: 73px 20px;
            }

            .widget_chinhsach_tab .contentTab .rowRight {
                padding-left: 0;
            }

            .widget_chinhsach_tab .contentTab .title {
                font-weight: 800;
                font-size: 40px;
                line-height: 47px;
                font-family: 'AbhayaLibre-ExtraBold';
                position: relative;
            }

            .widget_chinhsach_tab .contentTab .title::after {
                content: '';
                width: 180px;
                height: 2px;
                background-color: #000;
                position: absolute;
                bottom: -14px;
                left: 0;
            }

            .widget_chinhsach_tab .contentTab .desc {
                font-weight: 800;
                font-size: 24px;
                line-height: 28px;
                font-family: 'AbhayaLibre-ExtraBold';
                margin-top: 30px;
            }

            .widget_chinhsach_tab .contentTab .btn-theme {
                padding: 5px 30px;
                border-radius: 0;
                margin-top: 35px;
                font-size: 19px;
            }

            .widget_chinhsach_tab .nav-tabs .nav-link {
                padding: 5px 30px;
                background: #FFFFFF;
                border: 1px solid #8E1E18;
                border-radius: 150px;
                font-weight: 800;
                font-size: 20px;
                font-family: 'AbhayaLibre-ExtraBold';
                color: var(--theme-color);
            }
            .widget_chinhsach_tab .nav-tabs .nav-link:hover{
                background-color: var(--theme-color);
                color: #fff;
            }

            .widget_chinhsach_tab .nav-tabs .nav-item.show .nav-link,
            .widget_chinhsach_tab .nav-tabs .nav-link.active {
                color: #fff;
                background-color: var(--theme-color);
            }

            .widget_chinhsach_tab .header-title .header {
                width: fit-content;
                margin: 0 auto !important;
                border-bottom: 4px solid var(--theme-color);
                padding-bottom: 10px !important;
            }
            /*// Large devices  Phone */
            @media (max-width: 767px) {
                
                .widget_chinhsach_tab .contentTab .rowRight{
                    padding-right: 0px;
                }
                .widget_chinhsach_tab .contentTab{
                    padding-right: 10px;
                }
            }

            /*// Large devices  Ipad */
            @media (min-width: 768px) and (max-width: 991.99px) {
                .widget_chinhsach_tab .contentTab .rowLeft{
                    padding: 0px 20px;
                }
                .widget_chinhsach_tab .contentTab .title{
                    padding-top: 20px;
                }
            }
        </style>
        <?php
        echo $box['after'];
        ?>
        <div class="bannerBottom">
            <div class="img">
                <?php Template::img($this->options->imageBottom); ?>
            </div>
        </div>
        <style>
            .bannerBottom .img img {
                width: 100%;
            }

            .bannerBottom {
                margin-top: -140px;
            }

            /*// Large devices  Phone */
            @media (max-width: 767px) {
                .bannerBottom{
                    margin-top: -50px;
                }
            }

            /*// Large devices  Ipad */
            @media (min-width: 768px) and (max-width: 991.99px) {
                .bannerBottom{
                    margin-top: -50px;
                }

            }
        </style>
<?php
    }
    function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
    function default()
    {
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
    }
}
Widget::add('widget_chinhsach_tab');
