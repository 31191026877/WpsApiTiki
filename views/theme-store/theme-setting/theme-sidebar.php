<?php
Class ThemeSidebar {

    static public function postNew($config) {

        if(empty($config['sidebar']['new']['toggle'])) return false;

        $category = get_object_current('category');

        if(have_posts($category)) {
            $categoryTaxonomy = Taxonomy::getCategory($category->cate_type);
            if(is_string($categoryTaxonomy['post_type']) && $categoryTaxonomy['post_type'] != 'post') {
                return false;
            }
        }

        $config = $config['sidebar']['new'];

        $args = Qr::set('post_type', 'post')->orderBy('order')->orderByDesc('created')->limit((int)$config['limit']);

        if(($config['data'] == 'post-category-current' || $config['data'] == 0) && isset($category) && have_posts($category)) {
            $args->whereByCategory($category);
        }
        else if(is_numeric($config['data']) && !empty($config['data'])) {
            $category = PostCategory::gets($config['data']);
            if(have_posts($category)) $args->whereByCategory($category);
        }

        if(!empty($category)) {
            $config['title'] = $category->name._(' mới');
        }

        $post = Posts::gets($args);
        ?>
        <div class="widget widget_box_post_sidebar">
            <div class="sidebar-title"><?php echo ThemeSidebar::heading($config['title']);?></div>
            <div class="sidebar-content">
                <?php foreach ($post as $val):
                    Template::partial('include/loop/item_post_sidebar', ['val' => $val]);
                endforeach ?>
            </div>
        </div>
        <?php
    }

    static public function postHot($config) {

        if(empty($config['sidebar']['hot']['toggle'])) return false;

        $category = get_object_current('category');

        if(have_posts($category)) {
            $categoryTaxonomy = Taxonomy::getCategory($category->cate_type);
            if(is_string($categoryTaxonomy['post_type']) && $categoryTaxonomy['post_type'] != 'post') {
                return false;
            }
        }

        $config = $config['sidebar']['hot'];

        $args = Qr::set('post_type', 'post')->where('status', 1)->orderBy('order')->orderByDesc('created')->limit((int)$config['limit']);

        if(($config['data'] == 'post-category-current' || $config['data'] == 0) && isset($category) && have_posts($category)) {
            $args->whereByCategory($category);
        }
        else if(is_numeric($config['data']) && !empty($config['data'])) {
            $category = PostCategory::gets($config['data']);
            if(have_posts($category)) $args->whereByCategory($category);
        }

        if(!empty($category)) {
            $config['title'] = $category->name._(' nổi bật');
        }

        $post = Posts::gets($args);
        ?>
        <div class="widget widget_box_post_sidebar widget_not_border">
            <div class="sidebar-title"><?php echo ThemeSidebar::heading($config['title']);?></div>
            <div class="sidebar-content">
                <?php foreach ($post as $val):
                    Template::partial('include/loop/item_post_sidebar', ['val' => $val]);
                endforeach ?>
            </div>
        </div>
        <?php
    }

    static public function postRelated($config) {

        if(empty($config['sidebar']['related']['toggle'])) return false;

        $config = $config['sidebar']['related'];

        $object = get_object_current();

        if(have_posts($object)) {

            $args = Qr::set('post_type', 'post')->orderBy('order')->orderByDesc('created')->limit((int)$config['limit']);

            $args->related = $object->id;

            $post = Posts::gets($args);

            if(have_posts($post)){
                ?>
                <div class="widget widget_box_post_sidebar">
                    <div class="sidebar-title"><?php echo ThemeSidebar::heading($config['title']);?></div>
                    <?php foreach ($post as $key => $val):
                        Template::partial('include/loop/item_post_sidebar', ['val' => $val]);
                    endforeach ?>
                </div>
                <?php
            }
        }
    }

    static public function sidebar($config) {

        if(empty($config['sidebar']['sidebar']['toggle'])) return false;

        $config = $config['sidebar']['sidebar'];

        Sidebar::render($config['data']);
    }

    static public function registerHeading($key = '') {
        $heading = apply_filters('theme_sidebar_heading', []);
        return (!empty($key)) ? Arr::get($heading, $key) : $heading;
    }

    static public function heading($name, $options = []) {
        if(empty($name)) return false;
        $sidebar = (empty($options['style']) || $options['style'] == 'none') ? Option::get('sidebar_heading_style') : $options['style'];
        if(empty($sidebar) || $sidebar == 'none') {
            self::headingHtmlDefault($name);
        }
        else {
            $style = self::registerHeading($sidebar.'.class');
            if(!empty($style) && class_exists($style)) $style::html($name, $options);
        }
    }

    static public function headingCss($style = '', $options = [], $id = '.sidebar .widget'): void {
        $sidebar = (empty($style) || $style == 'none') ? Option::get('sidebar_heading_style') : $style;
        $style = self::registerHeading($sidebar.'.class');
        $options = Option::get('sidebar_heading_option');
        if(!empty($style) && class_exists($style)) $style::css($options, $id);
    }

    static public function headingHtmlDefault($name): void {
        ?><div class="header-title"><p class="header"><?= $name;?></p></div><?php
    }
}

add_action('init', 'ThemeSidebar::registerHeading', 10);
add_action('theme_custom_css', 'ThemeSidebar::headingCss', 10);

if(Template::isPage('post_index')) {
    add_action('theme_sidebar', 'ThemeSidebar::postNew', 10);
    add_action('theme_sidebar', 'ThemeSidebar::postHot', 20);
    add_action('theme_sidebar', 'ThemeSidebar::sidebar', 40);
}

if(Template::isPage('post_detail')) {
    add_action('theme_sidebar', 'ThemeSidebar::postNew', 10);
    add_action('theme_sidebar', 'ThemeSidebar::postHot', 20);
    add_action('theme_sidebar', 'ThemeSidebar::postRelated', 30);
    add_action('theme_sidebar', 'ThemeSidebar::sidebar', 40);
}