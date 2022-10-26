<?php
include_once (file_exists(Path::theme('theme-child/theme-setting/menu.php'))) ? Path::theme('theme-child/theme-setting/menu.php') : 'menu.php';
include_once (file_exists(Path::theme('theme-child/theme-setting/theme-ajax.php'))) ? Path::theme('theme-child/theme-setting/theme-ajax.php') : 'theme-ajax.php';
include_once (file_exists(Path::theme('theme-child/theme-setting/theme-form.php'))) ? Path::theme('theme-child/theme-setting/theme-form.php') : 'theme-form.php';
include_once (file_exists(Path::theme('theme-child/theme-setting/theme-option.php'))) ? Path::theme('theme-child/theme-setting/theme-option.php') : 'theme-option.php';
include_once (file_exists(Path::theme('theme-child/theme-setting/theme-style.php'))) ? Path::theme('theme-child/theme-setting/theme-style.php') : 'theme-style.php';
include_once (file_exists(Path::theme('theme-child/theme-setting/theme-sidebar.php'))) ? Path::theme('theme-child/theme-setting/theme-sidebar.php') : 'theme-sidebar.php';

Class Theme_Style {
    static public function renderLess(): string {
        ob_start();
        do_action('theme_custom_less');
        $css = ob_get_contents();
        ob_end_clean();
        $css = (String)Str::of($css)->replace('<style type="text/css">', '')->replace('<style type=\'text/css\'>', '')->replace('<style>', '')->replace('</style>', '');
        return Template::less($css)->getCss();
    }
    static public function renderCss(): string {
        ob_start();
        include_once (file_exists(Path::theme('theme-child/theme-setting/theme-custom-css.php'))) ? Path::theme('theme-child/theme-setting/theme-custom-css.php') : 'theme-custom-css.php';
        do_action('theme_custom_css_no_tag');
        echo self::renderLess();
        $css = ob_get_contents();
        ob_end_clean();
        $css = (String)Str::of($css)->replace('<style type="text/css">', '')->replace('<style type=\'text/css\'>', '')->replace('<style>', '')->replace('</style>', '');
        return $css;
    }
    static public function renderCssMinify(): void {
        $theme_custom_css_minify = CacheHandler::get('theme_custom_css_minify');
        if(DEBUG_LOG || DEBUG) {
            $theme_custom_css_minify = '';
        }
        if(!empty($theme_custom_css_minify)) {
            $css = $theme_custom_css_minify;
        }
        else {
            ob_start();
            echo self::renderCss();
            $css = ob_get_contents();
            ob_end_clean();
            $ci = &get_instance();
            $ci->load->library('skd_minify');
            $css = $ci->skd_minify->_minify($css);
            CacheHandler::save('theme_custom_css_minify', $css, 365*24*60*60);//Lưu cache 1 năm
        }
        echo '<style '.((Template::isAmp()) ? 'amp-custom' : 'type="text/css"').'>'.$css.'</style>';
    }
    static public function renderJs($removeTag = true,  $return = false) {
        ob_start();
        include_once (file_exists(Path::theme('theme-child/theme-setting/theme-custom-script.php'))) ? Path::theme('theme-child/theme-setting/theme-custom-script.php') : 'theme-custom-script.php';
        do_action('theme_custom_script_no_tag');
        $script = ob_get_contents();
        ob_end_clean();
        if($removeTag === false) {
            return (String)Str::of($script)
                ->replace('<script type="text/javascript">', '')
                ->replace('<script type="text/javascript" defer>', '')
                ->replace('<script type="text/javascript" async>', '')
                ->replace('<script defer type="text/javascript">', '')
                ->replace('<script async type="text/javascript">', '')
                ->replace('<script type=\'text/javascript\'>', '')
                ->replace('<script type=\'text/javascript\' defer>', '')
                ->replace('<script type=\'text/javascript\' async>', '')
                ->replace('<script defer type=\'text/javascript\'>', '')
                ->replace('<script async type=\'text/javascript\'>', '')
                ->replace('<script>', '')
                ->replace('<script defer>', '')
                ->replace('<script async>', '')
                ->replace('</script>', '');
        }
        if($return == true) return $script;
        echo $script;
    }
}

add_action('cle_header', 'Theme_Style::renderCssMinify', 999);

if (!Device::isGoogleSpeed()) {
    function header_script() {
        echo Option::get('header_script');
    }
    add_action('cle_header', 'header_script');
    function footer_script() {
        echo Option::get('footer_script');
    }
    add_action('cle_footer', 'footer_script');
}

Class Theme_Layout {
    static $layout;
    function __construct() {
        $this::$layout = get_theme_layout();

        if(isset(self::$layout['banner'])) {

            if(self::$layout['banner'] == 'full-width' || self::$layout['banner'] == 'in-container') {
                add_action('template_wrapper_before', 'Theme_Layout::layoutBanner', 10);
            }
            else if(self::$layout['banner'] == 'full-width' || self::$layout['banner'] == 'in-container') {
                add_action('template_wrapper_before', 'Theme_Layout::layoutBanner', 10);
            }
            else if(self::$layout['banner'] == 'in-content') {
                add_action('view_post_index_before', 'Theme_Layout::layoutBanner', 10);
                add_action('view_products_index_before', 'Theme_Layout::layoutBanner', 10);
            }

            add_action('breadcrumb_render', 'Theme_Layout::layoutBreadcrumb', 10);
        }
        else {
            add_action('template_'.Template::getPage().'_before', 'Theme_Layout::layoutBreadcrumb', 10);
        }
    }
    static function layoutBanner() {
        if(self::$layout['banner'] == 'in-container') {
            echo '<div class="container">';
            Template::partial('include/banner');
            echo '</div>';
        }
        else {
            Template::partial('include/banner');
        }
    }
    static function layoutBreadcrumb() {
        echo '<div class="breadcrumb-box"><div class="container">';
        $breadcrumb = Template::breadcrumb();
        echo Breadcrumb($breadcrumb);
        echo '</div></div>';
    }
}
Class Theme_Post_Index_Layout {
    function __construct() {
        if(!isset(Theme_Layout::$layout['banner'])) {
            add_action('template_post_index_before', 'Theme_Layout::layoutBreadcrumb', 10);
            add_action('view_post_index_before', 'Theme_Post_Index_Layout::title', 20);
        }
        add_action('view_post_index_after', 'Theme_Post_Index_Layout::pagination', 40, 3);
    }
    static function title($category) {
        ?>
        <h1 class="header text-left"><?= $category->name;?></h1>
        <style>h1.header { text-align:left; margin:0 0 20px 0; font-size: 30px; }</style>
        <?php
    }
    static function pagination($category, $object, $pagination) {
        if($pagination instanceof Pagination) {
            ?><nav class="text-center"><?php echo $pagination->html();?></nav><?php
        }
    }
}
Class Theme_Post_Detail_Layout {
    function __construct() {
        if(!isset(Theme_Layout::$layout['banner'])) {
            add_action('template_post_detail_before', 'Theme_Layout::layoutBreadcrumb', 10);
            add_action('view_post_detail_before', 'Theme_Post_Detail_Layout::title', 20);
        }
        add_action('view_post_detail_after', 'Theme_Post_Detail_Layout::share', 20);
        add_action('view_post_detail_after', 'Theme_Post_Detail_Layout::related', 20);
    }
    static function title($object) {
        ?>
        <h1 class="header text-left"><?= $object->title;?></h1>
        <style>h1.header { text-align:left; margin:0 0 20px 0; font-size: 20px; }</style>
        <?php
    }
    static function share($object) {
        ?>
        <div class="td-post-sharing td-post-sharing-bottom td-with-like">
            <span class="td-post-share-title">Chia sẻ</span>
            <div class="td-default-sharing">
                <a class="td-social-sharing-buttons td-social-facebook" href="http://www.facebook.com/sharer.php?u=<?= Url::current();?>" onclick="window.open(this.href, 'mywin','left=50%,top=50%,width=600,height=350,toolbar=0'); return false;">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="td-social-sharing-buttons td-social-twitter" href="https://twitter.com/intent/tweet?text=<?php echo $object->title;?>&amp;url=<?= Url::current();?>" onclick="window.open(this.href, 'mywin','left=50%,top=50%,width=600,height=350,toolbar=0'); return false;">
                    <i class="fab fa-twitter"></i>
                </a>
                <a class="td-social-sharing-buttons td-social-pinterest" href="http://pinterest.com/pin/create/button/?url=<?= Url::current();?>&amp;media=<?php echo Template::imgLink($object->image);?>&amp;description=<?php echo Str::clear($object->excerpt);?>&amp;" onclick="window.open(this.href, 'mywin','left=50,top=50,width=600,height=350,toolbar=0'); return false;">
                    <i class="fab fa-pinterest-p"></i>
                </a>
            </div>
        </div>
        <style>
            .td-post-sharing-bottom {
                border: 1px solid #ededed;
                padding: 10px 26px;
                margin-bottom: 40px;
                margin-top: 40px;
            }
            .td-post-share-title {
                font-weight: 700;
                font-size: 14px;
                position: relative;
                margin-right: 20px;
                vertical-align: middle;
            }
            .td-default-sharing {
                display: inline-block;
                vertical-align: middle;
            }
            .td-social-sharing-buttons {
                font-size: 11px;
                color: #fff;
                margin-right: 10px;
                text-align: center;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                height: 32px; line-height: 32px;
                width: 32px;
                display: inline-block;
            }
            .td-social-sharing-buttons:hover {color: #fff;}
            .td-social-sharing-buttons img { height:30px!important;}

            [class*="td-icon-"] {
                line-height: 1;
                text-align: center;
                display: inline-block;
            }

            .td-social-facebook .td-icon-facebook {
                font-size: 14px;
                position: relative;
                top: 1px;
            }
            .td-social-zalo {
                padding-top:0!important;
            }

            .td-social-facebook {
                background-color: #516eab;
            }

            .td-social-twitter {
                background-color: #29c5f6;
            }
            .td-social-pinterest {
                background-color: #ca212a;
                margin-right: 0;
            }
        </style>
        <?php
    }
    static function related($object) {
        $args = Qr::set('public', 1)
            ->where('trash', 0)
            ->where('post_type', $object->post_type)
            ->limit(6);

        $relatedPost = Posts::getsRelated($object, $args);

        $taxonomy = Taxonomy::getPost($object->post_type);
		?>
        <div class="related_post post">
            <div class="header-title"><h3 class="header text-left" style="text-align: left;"><?php echo $taxonomy['labels']['singular_name'];?> liên quan</h3></div>
            <div class="row">
                <?php foreach ($relatedPost as $key => $related) {
                    echo '<div class="col-md-4 col-sm-6 col-xs-6">';
                    Template::partial('include/loop/item_post_horizontal', ['val' => $related]);
                    echo '</div>';
                } ?>
            </div>
        </div>
        <?php
    }
}
Class Theme_Page_Detail_Layout {
    function __construct() {
        if(!isset(Theme_Layout::$layout['banner'])) {
            add_action('template_page_detail_before', 'Theme_Layout::layoutBreadcrumb', 10);
            add_action('view_page_detail_before', 'Theme_Post_Detail_Layout::title', 20);
        }
    }
    static function title($object) {
        ?>
        <h1 class="header text-left"><?= $object->title;?></h1>
        <style>h1.header { text-align:left; margin:0 0 20px 0; font-size: 20px; }</style>
        <?php
    }
}
Class Theme_Product_Index_Layout {
    function __construct() {
        if(!isset(Theme_Layout::$layout['banner'])) {
            add_action('template_products_index_before', 'Theme_Layout::layoutBreadcrumb', 10);
            add_action('view_products_index_before', 'Theme_Product_Index_Layout::title', 20);
        }
        add_action('view_products_index_after', 'Theme_Product_Index_Layout::pagination', 40, 3);
    }
    static function title($category): void {
        $name = 'Sản Phẩm';
        if(have_posts($category)) { $name = $category->name; }
        ?>
        <h1 class="header text-left"><?= $name;?></h1>
        <style>h1.header { text-align:left; margin:0 0 20px 0; font-size: 30px; }</style>
        <?php
    }
    static function pagination($category, $object, $pagination): void {
        ?><nav class="text-center"><?php echo (isset($pagination))?$pagination->html():'';?></nav><?php
    }
}

new Theme_Layout();
new Theme_Post_Index_Layout();
new Theme_Product_Index_Layout();
new Theme_Post_Detail_Layout();
new Theme_Page_Detail_Layout();