<?php
if(Admin::is()) {

    Class Theme_Store_System {

        function __construct() {

            add_filter( 'skd_system_tab' , [$this, 'registerTab'], 10);

            function skd_system_theme_social($ci, $tab) { Theme_Store_System::tabSocial($ci, $tab); }
            function skd_system_theme_gallery($ci, $tab) { Theme_Store_System::tabGallery($ci, $tab); }
            function skd_system_theme_seo($ci, $tab) { Theme_Store_System::tabSeo($ci, $tab); }

            add_action('system_tab_theme_seo_render','Theme_Store_System_Seo::page',10);
            add_filter('system_theme_seo_save','Theme_Store_System_Seo::save',10,2);

            add_filter('system_theme_social_save',[$this, 'tabSocialSave'],10,2);
            add_filter('system_theme_gallery_save',[$this, 'tabGallerySave'],10,2);
        }

        public function registerTab($tabs) {
            $tabs['theme-social']   = ['label' => 'Mạng xã hội', 	'callback' => 'skd_system_theme_social',    'icon' => '<i class="fal fa-users"></i>'];
            $tabs['theme-gallery']  = ['label' => 'Gallery',        'callback' => 'skd_system_theme_gallery',   'icon' => '<i class="fal fa-images"></i>', 'root' => true];
            $tabs['theme-seo']      = ['label' => 'Seo',            'callback' => 'skd_system_theme_seo',   'icon' => '<i class="fal fa-megaphone"></i>'];
            return $tabs;
        }

        public static function tabSocial($ci, $tab) {
            $socials = get_theme_social();
            include 'html/system-theme-social.php';
        }

        public static function tabGallery($ci, $tab) {

            $categories = Taxonomy::getCategory();

            $posts      = Taxonomy::getPost();

            $gallery_support = option::get('gallery_template_support', []);

            if(!is_array($gallery_support)) $gallery_support = [];

            $gallery_support['page'] = (isset($gallery_support['page'])) ? $gallery_support['page'] : 0;

            foreach ($categories as $key => $cateType) {
                $gallery_support['category'][$cateType] = (isset($gallery_support['category'][$cateType])) ? $gallery_support['category'][$cateType] : 0;
            }

            foreach ($posts as $key => $postType) {
                $gallery_support['post'][$postType] = (isset($gallery_support['post'][$postType])) ? $gallery_support['post'][$postType] : 0;
            }

            include 'html/system-theme-gallery.php';
        }

        public static function tabSeo($ci, $tab) {
            do_action('system_tab_theme_seo_render', $tab);
        }

        public function tabSocialSave($result, $data) {

            $socials = get_theme_social();

            foreach ($socials as $key => $social) {

                $field = $social['field'];

                option::update( $field , (isset($data[$field])) ? Str::clear($data[$field]) : '' );
            }

            return $result;
        }

        public function tabGallerySave($result, $data) {

            option::update( 'gallery_template_support' , $data['gallery_template_support'] );

            return $result;
        }
    }

    Class Theme_Store_System_Seo {
        public static function page() {
            $seo_input = get_theme_seo_input();
            $Form = new FormBuilder();
            foreach ($seo_input as $key => $input) {
                $Form->add($input['field'], $input['type'], $input, Option::get($input['field']));
            }
            include 'html/system-theme-seo.php';
        }
        public static function save($result, $data) {

            $seo_input = get_theme_seo_input();

            foreach ($seo_input as $key => $input) {

                $field = $input['field'];

                option::update( $field , (isset($data[$field])) ? $data[$field] : '' );
            }

            return $result;
        }
    }

    new Theme_Store_System();
}