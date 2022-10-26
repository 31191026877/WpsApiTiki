<?php
if(!function_exists('getYoutubeID')){

    function getYoutubeID($youtube = '') {
        return Url::getYoutubeID($youtube);
    }
}
/**
 * CACHE
 */
if(!function_exists('cache_exists')) {
    /**
     * @since 2.0.0
     */
    function cache_exists ( $cache_id = null ) {
        return CacheHandler::has($cache_id);
    }
}
if(!function_exists('get_cache')) {
    /**
     * @since 2.0.0
     */
    function get_cache ( $cache_id = null ) {
        return CacheHandler::get($cache_id);
    }
}
if(!function_exists('save_cache')) {
    /**
     * @since 2.0.0
     */
    function save_cache ( $cache_id = null, $cache_value = null, $cache_time = TIME_CACHE ) {

        return CacheHandler::save($cache_id, $cache_value, $cache_time);
    }
}
if(!function_exists('delete_cache')) {
    /**
     * @since 2.0.0
     */
    function delete_cache ( $cache_id = null, $prefix = false ) {

        return CacheHandler::delete($cache_id, $prefix);
    }
}
/**
 * AJAX
 */
if(!function_exists('register_ajax')){
    function register_ajax( $action = null ) {
        Ajax::client($action);
    }
}
if(!function_exists('register_ajax_login')){
    /**
     * Ajax : phải đăng nhập
     * @since  2.0.0
     */
    function register_ajax_login( $action = null ) {

        Ajax::login($action);
    }
}
if(!function_exists('register_ajax_admin')){
    /**
     * Ajax : chỉ chạy ở admin và phải đăng nhập
     * @since  2.0.0
     */
    function register_ajax_admin( $action = null ) {
        Ajax::admin($action);
    }
}
/**
 * DASHBOARD
 */
if(!function_exists('add_dashboard_widget')) {
    function add_dashboard_widget( $id , $title, $content, $option = []) {
        $option['callback'] = $content;
        return Dashboard::add($id, $title, $option);
    }
}
if(!function_exists('get_dashboard_widget')) {
    function get_dashboard_widget( $id = null ) {
        return Dashboard::get($id);
    }
}
if(!function_exists('gets_dashboard_widget')) {
    function gets_dashboard_widget() {
        return Dashboard::getAll();
    }
}
if(!function_exists('remove_dashboard_widget')) {
    function remove_dashboard_widget( $id = null ) {
        return Dashboard::remove($id);
    }
}
/**
 * COMMONT
 */
if(!function_exists('removeutf8')){
    function removeutf8($value = NULL){
        return Str::ascii($value);
    }
}
if(!function_exists('slug')){
    function slug($value = NULL, $char = '-'){
        return Str::slug($value, $char);
    }
}
if(!function_exists('is_url')){
    function is_url($url){
        return Str::isUrl($url);
    }
}
if(!function_exists('str_word_cut')) {
    function str_word_cut($string, $num){
        return Str::words($string, $num);
    }
}
if(!function_exists('is_serialized')){
    /**
     * @since 2.2.0
     */
    function is_serialized( $data, $strict = true ) {
        return Str::isSerialized($data, $strict);
    }
}
if(!function_exists('send_mail')) {

    function send_mail($param = []) {

        $args = [
            'from' => '', 'name' => '', 'address' => '', 'subject' => ''
        ];

        if(!empty($param['from_email']))    $args['from'] = Str::clear($param['from_email']);
        if(!empty($param['from']))          $args['from'] = Str::clear($param['email']);
        if(!empty($param['fullname']))      $args['name'] = Str::clear($param['fullname']);
        if(!empty($param['name']))          $args['name'] = Str::clear($param['name']);
        if(!empty($param['to_email']))      $args['address'] = Str::clear($param['to_email']);
        if(!empty($param['address']))       $args['address'] = Str::clear($param['address']);
        if(!empty($param['subject']))       $args['subject'] = Str::clear($param['subject']);

        $EmailHandler = new EmailHandler();

        return $EmailHandler
            ->setVariableValues($args)
            ->setEmailTemplate($param['content'])
            ->sending();
    }
}
if(!function_exists('debug'))  {

    function debug($param = []) {
        echo '<pre>'; print_r($param); echo '</pre>';
        echo var_dump($param);
        $ci =& get_instance();
        $ci->load->library('profiler');
        echo "<html><body>";
        echo $ci->profiler->run();
        echo '</body></html>';
        die;
    }
}
/**
 * MENU
 */
if(!function_exists('register_admin_nav')) {
    function register_admin_nav( $title, $key, $slug, $position = null, $arg = []) {
        Arr::set($arg, "position", $position);
        AdminMenu::add($key, $title, $slug, $arg);
    }
}
if(!function_exists('register_admin_subnav')) {
    function register_admin_subnav( $parent_key, $title, $key, $slug, $arg = [], $position = null ) {
        if(!is_array($arg)) $arg = [];
        Arr::set($arg, "position", $position);
        AdminMenu::addSub($parent_key, $key, $title, $slug, $arg);
    }
}
if(!function_exists('remove_admin_nav')) {
    /**
     * [remove_admin_nav description]
     * singe 2.0.2
     */
    function remove_admin_nav( $key ) {

        return AdminMenu::remove($key);
    }
}
if(!function_exists('remove_admin_subnav')) {
    /**
     * [remove_admin_nav description]
     * singe 2.0.2
     */
    function remove_admin_subnav( $parent_key, $key ) {
        return AdminMenu::remove($key, $parent_key);
    }
}
/**
FRONTEND NAVIGATION
 */
if(!function_exists('register_nav_menus')) {
    function register_nav_menus($menus = []) {
        return ThemeMenu::addLocation($menus);
    }
}
if(!function_exists('get_data_menu')) {
    function get_data_menu($id = 0) {
        return ThemeMenu::getData($id);
    }
}
if(!function_exists('add_menu_option')) {
    function add_menu_option($key, $option = []) {
        ThemeMenu::addItemOption('menu', $option);
    }
}
if(!function_exists('add_menu_option_page')) {
    function add_menu_option_page($object_type = '', $key = '', $option = []) {
        ThemeMenu::addItemOption('page', $option);
    }
}
if(!function_exists('add_menu_option_post')) {
    function add_menu_option_post($object_type = '', $key = '', $option = []) {
        $option['post_type'] = (!empty($object_type)) ? Str::clear($object_type) : 'all';
        ThemeMenu::addItemOption('post', $option);
    }
}
if(!function_exists('add_menu_option_cate')) {
    function add_menu_option_cate($object_type = '', $key = '', $option = []) {
        $option['cate_type'] = (!empty($object_type)) ? Str::clear($object_type) : 'all';
        ThemeMenu::addItemOption('post_categories', $option);
    }
}

if(!function_exists('cms_info'))  {
    function cms_info( $str = '' ) {
        return Cms::info($str);
    }
}
if(!function_exists('fullurl')){
    function fullurl($base64 = FALSE){
        return Url::current($base64);
    }
}
if(!function_exists('is_ssl')){
    function is_ssl() {
        return Url::ssl();
    }
}
if(!function_exists('admin_url')){
    function admin_url( $url ) {
        return Url::admin($url);
    }
}
if(!function_exists('get_url')){
    function get_url($slug) {
        return Url::permalink($slug);
    }
}

if(!function_exists('public_path'))  {
    function public_path($str = '', $absolute = false) {
        return Path::upload($str, $absolute);
    }
}
if(!function_exists('theme_path'))  {
    function theme_path($str = '', $absolute = false) {
        return Path::theme($str, $absolute);
    }
}
if(!function_exists('admin_path'))  {
    function admin_path($str = '', $absolute = false) {
        return Path::admin($str, $absolute);
    }
}

if(!function_exists('register_theme_option_group')) {
    function register_theme_option_group($group = []) {
        foreach ($group as $key => $item) {
            ThemeOption::addGroup($key, $item);
        }
    }
}
if(!function_exists('register_theme_option_field')) {
    function register_theme_option_field($field = []) {
        foreach ($field as $key => $item) {
            ThemeOption::addField($item['group'], $item['field'], $item['type'], $item);
        }
    }
}

if(!function_exists('is_page')) {
    function is_page( $page = '' ) {
        return Template::isPage($page);
    }
}
if(!function_exists('is_home')) {
    function is_home() {
        return Template::isPage();
    }
}

/**
=======================================================
STYLE - SCRIPT
=======================================================
 */
if(!function_exists('cle_enqueue_style'))  {
    function cle_enqueue_style() {
        do_action( 'cle_enqueue_style' );
        if(is_admin()) {
            Admin::asset()->location('header')->styles();
            Admin::asset()->location('header')->scripts();
        }
        else {
            Template::asset()->location('header')->styles();
            Template::asset()->location('header')->scripts();
        }
    }
}

if(!function_exists('cle_enqueue_script'))  {
    function cle_enqueue_script() {
        do_action( 'cle_enqueue_script' );
        if(is_admin()) {
            Admin::asset()->location('footer')->styles();
            Admin::asset()->location('footer')->scripts();
        }
        else {
            Template::asset()->location('footer')->styles();
            Template::asset()->location('footer')->scripts();
        }
    }
}

if(!function_exists('admin_register_style'))  {
    function admin_register_style($id = null, $path = null, $page = null) {
        Admin::asset()->location('header')->add($id, $path, ['page' => $page]);
    }
}

if(!function_exists('admin_register_script'))  {
    function admin_register_script($id = null, $path = null, $page = null) {
        Admin::asset()->location('footer')->add($id, $path, ['page' => $page]);
    }
}

if(!function_exists('cle_register_style'))  {
    function cle_register_style($id = null, $path = null, $page = null, $minify = false) {
        $minifyPath = [];
        if(have_posts($minify)) $minifyPath = $minify;
        Template::asset()->location('header')->add($id, $path, ['page' => $page, 'minify' => $minify, 'path' => $minifyPath]);
    }
}

if(!function_exists('cle_register_script'))  {
    function cle_register_script($id = null, $path = null, $page = null, $minify = false) {
        $minifyPath = [];
        if(have_posts($minify)) $minifyPath = $minify;
        Template::asset()->location('footer')->add($id, $path, ['page' => $page, 'minify' => $minify, 'path' => $minifyPath]);
    }
}

if(!function_exists('is_admin'))  {
    function is_admin() {
        return Admin::is();
    }
}
if(!function_exists('set_template_default')) {
    function set_template_default($class, $method, $layout = '', $view = '') {
        Template::setLayout($class.'_'.$method ,$layout, $view);
    }
}
if(!function_exists('admin_button_icon')){
    /**
     * [admin_button_icon]
     * @singel 2.3.5
     */
    function admin_button_icon( $action ) {

        return Admin::icon($action);
    }
}
if(!function_exists('admin_loading_icon')){
    /**
     * [admin_loading_icon]
     * @singel 3.0.0
     */
    function admin_loading_icon( $id = '', $class = '' ) {
        echo Admin::loading($id, $class);
    }
}

if(!function_exists('check_file_type')) {

    function get_file_type($path) {
        return FileHandler::type($path);
    }
}

if(!function_exists('check_url_video')) {

    function get_url_web_video($path) {
        return FileHandler::type($path);
    }
}

if(!function_exists('process_file')) {
    function process_file($field) {
        return FileHandler::handlingUrl($field);
    }
}

if(!function_exists('form_add_group')) {

    function form_add_group($groups = [], $key = '', $name = '', $position = 0) {

        $form = [];

        if($position === 0) {

            $form = $groups;

            $form[$key] = $name;
        }
        else {
            foreach ($groups as $k => $value) {

                if($k == $position) $form[$key] = $name;

                $form[$k] = $value;
            }

            if(!isset($form[$key])) $form[$key] = $name;
        }

        return $form;
    }
}

if(!function_exists('template_support_action')) {

    function template_support_action($remove_group = '', $remove_field = '', &$form = [], $class = '') {

        $ci 		=& get_instance();

        $template 	= (isset($ci->data['template'])) ? $ci->data['template'] : [];

        $support_group = [];

        $support_field = [];

        if(isset($template->support[$class]['group']) && have_posts($template->support[$class]['group'])) {
            $support_group = $template->support[$class]['group'];
        }

        if(isset($template->support[$class]['field']) && have_posts($template->support[$class]['field'])) {
            $support_field = $template->support[$class]['field'];
        }

        //remove group các chức năng
        if(have_posts($support_group)) {
            foreach ($support_group as $key => $group) { $remove_group = str_replace($group,'',$remove_group); }
        }
        $remove_group = trim($remove_group);
        $remove_group = trim($remove_group,',');
        if($remove_group != '') $form = form_remove_group($remove_group, $form);

        //remove các field các chức năng
        if(have_posts($support_field)) {
            foreach ($support_field as $key => $group) { $remove_field = str_replace($group,'',$remove_field); }
        }
        $remove_field = trim($remove_field);
        $remove_field = trim($remove_field,',');
        if($remove_field != '') $form = form_remove_field($remove_field, $form);
    }
}
/**
=======================================================
TEMPLATE SUPPORT
=======================================================
 */
if(!function_exists('template_support')) {
    function template_support($class = '', $group = [], $field = []) {
        Template::support($class, $group, $field);
    }
}
if(!function_exists('_form')) {

    function _form($param, $value = null) {

        $input = new InputBuilder($param, $value);

        return $input->render();
    }
}

if(!function_exists('form_remove_group')) {

    function form_remove_group($groups = [], $form = []) {

        $groups = trim($groups,',');

        $groups = explode(',', $groups);

        foreach ($form as $key => $group) {

            if(have_posts($group)) {

                foreach ($group as $k => $value) {

                    if(in_array($k, $groups, true) !== false) {

                        unset($form[$key][$k]);
                    }
                }
            }
        }

        return $form;
    }
}

if(!function_exists('form_add_field')) {

    function form_add_field( $form = [], $param = [], $position = null) {

        if( have_posts($param) ) {

            $id = $param['field'];

            if( isset($param['lang']) ) {

                $id = str_replace( $param['lang'].'[', '', $id );

                $id = $param['lang'].'_'.str_replace(']', '', $id );
            }

            if( $position == 'title' || $position == 'name' || $position == 'excerpt' || $position == 'content' ) {

                if( isset($param['lang']) ) $position = $param['lang'].'_'.$position;
                else $position = 'vi_'.$position;
            }

            if( $position === null) {

                $form[ $id ] = $param;

            }
            else {

                $temp = [];

                foreach ( $form as $k => $value) {

                    if( $k == $position ) {

                        $temp[ $id ] = $param;

                    }

                    $temp[$k] = $value;
                }

                $form = $temp;
            }
        }

        return $form;
    }
}

if(!function_exists('form_remove_field')) {

    function form_remove_field($fields = [], $form = []) {

        $fields = trim($fields,',');

        $fields = explode(',', $fields);

        foreach ($form['field'] as $key => $value) {

            if(in_array($value['field'], $fields, true) !== false) unset($form['field'][$key]);
            else if(isset($value['lang'])) {

                $value['field'] = str_replace($value['lang'].'[', '', $value['field']);

                $value['field'] = str_replace(']', '', $value['field']);

                if(in_array($value['field'], $fields, true) !== false) unset($form['field'][$key]);
            }
        }
        return $form;
    }
}

if(!function_exists('form_rename_field')) {

    function form_rename_field($fields = [], $form = []) {

        if(have_posts($fields)) {

            foreach ($form['field'] as $key => &$value) {

                if(!isset($fields) || !have_posts($fields)) break;
                foreach ($fields as $field => $label) {

                    if(isset($value['lang'])) {

                        $field = $value['lang'].'['.$field.']';

                    }
                    if($value['field'] == $field) {

                        $value['label'] = $label;

                        unset($fields[$field]);

                        break;
                    }
                }
            }
        }
        return $form;
    }
}

if(!function_exists('get_page')) {
    /**
     * [get_page Lấy dữ liệu trang nội dung]
     * @since 2.5.0
     */
    function get_page( $args = [] ) {
        return Pages::get($args);
    }
}


if(!function_exists('gets_page')) {
    /**
     * [gets_page]
     * @since [version] 2.5.0
     * @return array|mixed
     */
    function gets_page( $args = [] ) {
        return Pages::gets($args);
    }
}

if(!function_exists('count_page')) {
    function count_page( $args = [] ) {
        return Pages::count($args);
    }
}

if(!function_exists('insert_page')) {
    /**
     * @since  2.5.0
     */
    function insert_page($page = []) {
        return Pages::insert($page);
    }
}

if( !function_exists('delete_page') ) {
    /**
     * @since  2.5.0
     */
    function delete_page( $pageID = 0, $trash = false ) {
        return Pages::delete($pageID, $trash);
    }
}

if( !function_exists('delete_list_page') ) {
    /**
     * @since  3.0.0
     */
    function delete_list_page( $pageID = [], $trash = false ) {
        return Pages::deleteList($pageID, $trash);
    }
}

if( !function_exists('get_page_meta') ) {
    function get_page_meta( $page_id, $key = '', $single = true) {
        return Pages::getMeta($page_id, $key, $single);
    }
}

if( !function_exists('update_page_meta') ) {
    function update_page_meta($page_id, $meta_key, $meta_value) {
        return Pages::updateMeta($page_id, $meta_key, $meta_value);
    }
}

if( !function_exists('delete_page_meta') ) {
    function delete_page_meta($page_id, $meta_key = '', $meta_value = '') {
        return Pages::deleteMeta($page_id, $meta_key, $meta_value);
    }
}

if(!function_exists('get_post') ) {

    function get_post( $args = [] ) {
        return Posts::get($args);
    }
}
if(!function_exists('get_post_by') ) {
    /**
     * [get_post_by]
     * @since  [version] 2.3.4
     */
    function get_post_by( $field, $value, $params = [] ) {
        return Posts::getBy($field, $value, $params);
    }
}
if(!function_exists('gets_post') ) {

    function gets_post( $args = [] ) {
        return Posts::gets($args);
    }
}
if(!function_exists('gets_post_by') ) {
    /**
     * @since  [version] 2.3.4
     */
    function gets_post_by( $field, $value, $params = [] ) {
        return Posts::getsBy($field, $value, $params);
    }
}
if(!function_exists('count_post') ) {
    function count_post( $args = [] ) {
        return Posts::count($args);
    }
}
if( !function_exists('insert_post') ) {
    /**
     * @since  2.0.0
     * @since  2.3.5 custom new
     */
    function insert_post($post = []) {
        return Posts::insert($post);
    }
}
if( !function_exists('delete_post') ) {
    /**
     * @since  2.0.0
     */
    function delete_post( $postID = 0, $trash = false ) {
        return Posts::delete($postID, $trash);;
    }
}
if( !function_exists('delete_list_post') ) {
    /**
     * @since  3.0.0
     */
    function delete_list_post( $postID = [], $trash = false ) {
        return Posts::deleteList($postID, $trash);
    }
}
if( !function_exists('get_post_meta') ) {

    function get_post_meta( $post_id, $key = '', $single = true) {
        return Posts::getMeta($post_id, $key, $single);
    }
}
if( !function_exists('update_post_meta') ) {

    function update_post_meta($post_id, $meta_key, $meta_value) {

        return Posts::updateMeta($post_id, $meta_key, $meta_value);
    }
}
if( !function_exists('delete_post_meta') ) {

    function delete_post_meta($post_id, $meta_key = '', $meta_value = '') {

        return Posts::deleteMeta($post_id, $meta_key, $meta_value);
    }
}
if(!function_exists('get_post_category')) {
    function get_post_category($args = []) {
        return PostCategory::get($args);
    }
}
if(!function_exists('gets_post_category')) {
    function gets_post_category($args = []) {
        return PostCategory::gets($args);
    }
}
if(!function_exists('count_post_category')) {
    function count_post_category($args = []) {
        return PostCategory::count($args);
    }
}
if(!function_exists('insert_category')) {
    /**
     * @since  2.0.5
     */
    function insert_category( $postarr = [], $outsite = [] ) {
        return PostCategory::insert($postarr, $outsite);
    }
}
if(!function_exists('delete_category')) {
    /**
     * @since  2.0.5
     */
    function delete_category( $cate_ID = 0 ) {
        return PostCategory::delete($cate_ID);
    }
}
if(!function_exists('delete_list_category')) {
    /**
     * @since  3.0.0
     */
    function delete_list_category( $cate_ID = [] ) {
        return PostCategory::deleteList($cate_ID);
    }
}
if(!function_exists('get_post_category_meta')) {
    function get_post_category_meta( $cateID, $key = '', $single = true) {
        return PostCategory::getMeta($cateID, $key, $single);
    }
}
if(!function_exists('update_post_category_meta')) {
    function update_post_category_meta($cateID, $meta_key, $meta_value) {
        return PostCategory::updateMeta($cateID, $meta_key, $meta_value);
    }
}
if(!function_exists('delete_post_category_meta')) {
    function delete_post_category_meta($cateID, $meta_key = '', $meta_value = '') {
        return PostCategory::deleteMeta($cateID, $meta_key, $meta_value);
    }
}

if(!function_exists('get_gallery')) {
    /**
     *  Get add item of object
     * */
    function get_gallery( $params = [], $group_id = null, $object_id = 0, $object_type = null, $type = null ) {

        $ci =  &get_instance();

        $cache_id = '';

        $where = [];

        if ( 0 !== $object_id ) {

            $cache_id .= '_object_'.$object_id;

            $where['object_id'] = $object_id;
        }

        if ( null !== $group_id ) {

            $cache_id .= '_group_'.$group_id;

            $where['group_id'] = $group_id;
        }

        if ( null !== $object_type ) {

            $cache_id .= '_object_type_'.$object_type;

            $where['object_type'] = $object_type;
        }

        if ( null !== $type ) {

            $cache_id .= '_type_'.$type;

            $where['type'] = $type;
        }


        if(have_posts($params)) {
            $cache_id = 'gallery_'.md5($cache_id.serialize($params));
        } else $cache_id = 'gallery_'.md5($cache_id);

        if( CacheHandler::has($cache_id) == false ) {

            $model = model('galleries');

            $gItem = $model->gets_where($where, $params);

            foreach ($gItem as $key => &$items) {

                $items->options = @unserialize($items->options);
            }

            CacheHandler::save($cache_id, $gItem);

            return $gItem;

        }
        else return CacheHandler::get($cache_id);
    }
}
if(!function_exists('_get_gallery')) {
    function _get_gallery( $args = [] ) {
        return Gallery::getItem($args);
    }
}
if(!function_exists('gets_gallery')) {
    function gets_gallery( $args ) {
        return Gallery::getsItem($args);
    }
}
if(!function_exists('count_gallery')) {
    function count_gallery($args) {
        return Gallery::countItem($args);
    }
}
if(!function_exists('insert_gallery')) {
    function insert_gallery( $gallery_arr ) {
        return Gallery::insertItem($gallery_arr);
    }
}
if(!function_exists('delete_gallery')) {
    function delete_gallery( $id ) {
        return Gallery::deleteItem($id);
    }
}
if(!function_exists('delete_gallery_by_object')) {
    function delete_gallery_by_object( $id, $object_type ) {
        return Gallery::deleteItemByObject($id, $object_type);
    }
}

if(!function_exists('get_gallery_meta')) {
    function get_gallery_meta( $gallery_id, $key = '', $single = true) {
        return Gallery::getItemMeta($gallery_id, $key, $single);
    }
}
if(!function_exists('update_gallery_meta')) {
    function update_gallery_meta($gallery_id, $meta_key, $meta_value) {
        return Gallery::updateItemMeta($gallery_id, $meta_key, $meta_value);
    }
}
if(!function_exists('delete_gallery_meta')) {
    function delete_gallery_meta($gallery_id, $meta_key = '', $meta_value = '') {
        return Gallery::deleteItemMeta($gallery_id, $meta_key, $meta_value);
    }
}
if(!function_exists('gallery_template_support') ) {
    function gallery_template_support( $object_type = [] ) {
        Template::gallerySupport($object_type);
    }
}
if(!function_exists('gallery_template_support_cate_type') ) {
    function gallery_template_support_cate_type( $cate_type = [] ) {
        Template::gallerySupport('post_categories', $cate_type);
    }
}
if(!function_exists('gallery_template_support_post_type') ) {
    function gallery_template_support_post_type( $postType = [] ) {
        Template::gallerySupport('post', $postType);
    }
}
/* OPTION *********************************************************/
if( !function_exists('add_option_gallery') ) {
    function add_option_gallery ($type = null, $object = null, $input = [] , $position = 1) {
        Gallery::addOption($object, $input, $position);
    }
}
if( !function_exists('add_option_gallery_object') ) {
    function add_option_gallery_object ( $args = [] ) {
        if(!empty($args['type'])) $args['input']['object_type'] = $args['type'];
        Gallery::addOption($args['object'], $args['input']);
    }
}
if( !function_exists('remove_option_gallery_object') ) {
    function remove_option_gallery_object ( $id = null, $object = null, $type = null ) {
        Gallery::removeItemOption($id, $object, $type);
    }
}
if(!function_exists('get_template_file')) {
    function get_template_file($views_path, $args = '', $return = false) {
        Template::partial($views_path, $args, $return);
    }
}

/**
=======================================================
HIỂN THỊ HÌNH ẢNH
=======================================================
 */
if(!function_exists('get_img')) {

    function get_img($img = '', $alt = '',$params = [], $type ='source' , $return = false) {

        $url = get_img_link($img, $type);

        /* singe 3.0.0 */
        $url  	 = apply_filters('get_img_url', $url, array('img' => $img, 'alt' => $alt, 'params' => $params, 'type' => $type));

        /* singe 3.0.0 */
        $params  = apply_filters('get_img_params', $params, array('img' => $img, 'alt' => $alt, 'type' => $type));

        $html = '<img src="'.$url.'" alt="'.Str::clear($alt).'" ';

        if(have_posts($params)) {
            foreach ($params as $key => $value)
                $html .= $key.'="'.$value.'" ';
        }
        $html .=' loading="lazy" />';

        $html = apply_filters('get_img', $html, array('url' => $url, 'img' => $img, 'alt' => $alt, 'params' => $params, 'type' => $type));

        if($return) return $html; else echo $html;
    }
}

if(!function_exists('get_img_link')) {

    /**
     * [get_img_link tạo đường dẫn hình ảnh]
     * @param  string  $img    [đường dẫn, tên hình ảnh]
     * @param  string  $type   [nguồn ảnh từ thư mục upload: sourc, medium, thumb]
     * @param  boolean $return [true nếu muốn trả về kết quả và false nếu muốn in kết quả ra]
     * @return [type]          [đường link ảnh]
     */
    function get_img_link($img = '', $type = 'source', $return = true) {

        $ci =& get_instance();
        //kiểm tra có phải url img không
        if(is_url($img)) $url = $img;
        //nếu không phải get
        else {
            if($type == 'source') 		 $url = SOURCE.$img;
            else if($type == 'thumbail') $url = THUMBAIL.$img;
            else if($type == 'medium') 	 $url = MEDIUM.$img;
            else 						 $url = $type.'/'.$img;

            if($type ==  'watermark') return base_url().$url;

            $url_check =  urldecode($url);

            if (!file_exists($url_check)) {

                $url = SOURCE.$img;

                $url_check =  urldecode($url);
            }

            //get nếu file không tồn tại
            if (!file_exists($url_check)) {
                stream_context_set_default( [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);
                //kiểm tra template
                $url = get_img_template_link($img);

                //$file_headers = @get_headers($url);

                //if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                //    $url = $ci->template->get_assets().'images/no-images.png';
                //}
            }
        }

        $ch = curl_init();

        $url = apply_filters('get_img_link', $url, array(
            'img'    => $img,
            'type'   => $type,
        ));

        if($return) return $url; else echo $url;
    }
}

if(!function_exists('get_img_template')) {
    function get_img_template($img = '', $alt = '',$params = [], $return = true) {
        $ci =& get_instance();
        $html = '<img src="';
        $html .= $ci->template->get_assets().'images/'.$img.'"';
        $html .= ' alt="'.$alt.'" ';
        if(have_posts($params)) {
            foreach ($params as $key => $value) {
                $html .= $key.'="'.$value.'" ';
            }
        }
        $html .='/>';
        if($return) return $html; else echo $html;
    }
}

if(!function_exists('get_img_template_link')) {
    function get_img_template_link($img = '', $return = true)
    {
        $ci =& get_instance();
        $url = $ci->template->get_assets().'images/'.$img;
        if($return) return $url; else echo $url;
    }
}

if(!function_exists('get_img_fontend')) {
    function get_img_fontend($img = '', $alt = '',$params = [], $return = false)
    {
        $url = get_img_fontend_link($img);

        $html = '<img src="'.$url.'" alt="'.Str::clear($alt).'" ';
        if(have_posts($params)) {
            foreach ($params as $key => $value)
                $html .= $key.'="'.$value.'" ';
        }
        $html .='/>';
        if($return) return $html; else echo $html;
    }
}

if(!function_exists('get_img_fontend_link')) {

    function get_img_fontend_link($img = '', $return = true)
    {
        $ci =& get_instance();
        //kiểm tra có phải url img không
        if(is_url($img)) $url = $img;
        //nếu không phải get
        else {
            $url = SOURCE.$img;

            $url_check =  urldecode($url);

            //get nếu file không tồn tại
            if (!file_exists($url_check)) {
                stream_context_set_default( [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);
                //kiểm tra template
                $url = base_url().'views/'.$ci->data['template']->name.'/assets/images/'.$img;
                $file_headers = @get_headers($url);

                if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                    $url = $ci->data['template']->get_assets().'images/no-images.png';
                }
            }
        }

        if($return) return $url; else echo $url;
    }
}

if(!function_exists('get_img_plugin')) {
    function get_img_plugin($name, $img = '', $alt = '',$params = [], $return = false) {
        $url = get_img_plugin_link($name, $img);
        $html = '<img src="'.$url.'" alt="'.Str::clear($alt).'" ';
        if(have_posts($params)) {
            foreach ($params as $key => $value)
                $html .= $key.'="'.$value.'" ';
        }
        $html .='/>';
        if($return) return $html; else echo $html;
    }
}

if(!function_exists('get_img_plugins_link')) {
    function get_img_plugin_link($name, $img = '', $return = true)
    {
        $ci =& get_instance();
        $url = $ci->plugin->get_path($name).$img;
        if($return) return $url; else echo $url;
    }
}

function add_option($option_name, $option_value) {
    return Option::add($option_name, $option_value);
}
function update_option($option_name, $option_value) {
    return Option::update($option_name, $option_value);
}
function delete_option($option_name) {
    return Option::delete($option_name);
}
function get_option($option_name = '', $value = '') {
    return Option::get($option_name, $value);
}
function get_option_all() {
    return Option::getAll();
}

if(!function_exists('superadmin')) {
    function superadmin($id = 0) {
        return Admin::isRoot($id);
    }
}

if( !function_exists('get_user') ) {
    function get_user( $args = [] ) {
        return User::get();
    }
}
if( !function_exists('get_user_by') ) {
    function get_user_by( $field, $value ) {
        return User::getBy($field, $value);
    }
}
if( !function_exists('gets_user') ) {
    function gets_user( $args = [] ) {
        return User::get($args);
    }
}
if(!function_exists('count_user') ) {
    function count_user( $args = [] ) {
        return User::count($args);
    }
}
if(!function_exists('insert_user')) {
    function insert_user( $userdata ) {
        return User::insert($userdata);
    }
}
if(!function_exists('get_user_meta') ) {
    function get_user_meta( $user_id, $key = '', $single = true) {
        return User::getMeta($user_id, $key, $single);
    }
}
if(!function_exists('update_user_meta')) {
    function update_user_meta($user_id, $meta_key, $meta_value) {
        return User::updateMeta($user_id, $meta_key, $meta_value);
    }
}
if(!function_exists('delete_user_meta')) {
    function delete_user_meta($user_id, $meta_key, $meta_value = '') {
        return User::deleteMeta($user_id, $meta_key, $meta_value);
    }
}
if (!function_exists('is_user_logged_in')) {
    function is_user_logged_in() {
        return Auth::check();
    }
}
if( !function_exists('skd_signon') ) {
    function skd_signon( $credentials = [] ) {
        return Auth::login($credentials);
    }
}
if(!function_exists('skd_authenticate_username_password')) {
    function skd_authenticate_username_password($user, $username, $password) {
        return Auth::loginUsingUsername($username, $password, $user);
    }
}
if(!function_exists('skd_authenticate_email_password')) {
    function skd_authenticate_email_password( $user, $email, $password) {
        return Auth::loginUsingEmail($email, $password, $user);
    }
}
if(!function_exists('skd_check_password')) {
    function skd_check_password($password, $user ) {
        return Auth::passwordConfirm($password, $user);
    }
}
if(!function_exists('generate_password')) {
    function generate_password( $password, $username, $salt ) {
        return Auth::generatePassword($password, $salt);
    }
}
if(!function_exists('generate_password_old')) {
    function generate_password_old( $password, $username, $salt ) {
        return Auth::generatePasswordOld($password, $username, $salt);
    }
}
if (!function_exists('skd_set_auth_cookie')) {
    function skd_set_auth_cookie( $user, $secure = '', $token = '' ) {
        Auth::setCookie($user);
    }
}
if (!function_exists('user_logout')) {
    function user_logout() {
        Auth::logout();
    }
}
if(!function_exists('username_exists')) {
    function username_exists( $username ) {
        return User::usernameExists($username);
    }
}
if(!function_exists('email_exists')) {
    function email_exists( $email ) {
        return User::usernameExists($email);
    }
}
if(!function_exists('get_current_user_id')) {
    function get_current_user_id() {
        return Auth::userID();
    }
}
if(!function_exists('get_user_current')) {
    function get_user_current() {
        return Auth::user();
    }
}
if(!function_exists('delete_user')) {
    function delete_user( $id, $reassign = null ) {
        return User::delete($id, $reassign);
    }
}
if(!function_exists('update_user')) {
    function update_user($userdata) {
        return User::update($userdata);
    }
}
if(!function_exists('get_userdata')) {
    function get_userdata( $user_id ) {
        return User::getData($user_id);
    }
}
if(!function_exists('user_has_cap') ) {
    function user_has_cap( $user_id, $cap ) {
        return User::hasCap($user_id, $cap);
    }
}
if(!function_exists('current_user_can') ) {
    function current_user_can($capability) {
        return Auth::hasCap($capability);
    }
}
function is_super_admin( $user_id = false ) {
    if (!$user_id || $user_id == get_current_user_id() )
        $user = get_user_current();
    else
        $user = get_userdata( $user_id );
    if ( !have_posts( $user ) ) return false;
    if ( user_has_cap( $user->id, 'delete_users' ) ) return true;
    return false;
}
if(!function_exists('get_role_caps') ) {
    function get_role_caps( $user_id, $cap = 'capabilities' ) {
        return User::getCap($user_id, $cap);
    }
}
if(!function_exists('get_user_current_roles') ) {
    function get_user_current_roles() {
        return Auth::getCap();
    }
}
if(!function_exists('user_role')) {
    function user_role( $user_id ) {
        return User::getRole($user_id);
    }
}
if(!function_exists('user_set_role') ) {
    function user_set_role( $user_id, $role ) {
        return User::setRole($user_id, $role);
    }
}
if(!function_exists('user_add_role') ) {
    function user_add_role( $user_id, $role ) {
        return User::addRole($user_id, $role);
    }
}
function my_account_url( $full = false ) {
    return Url::account($full);
}
function register_url( $redirect = '') {
    return Url::register($redirect);
}
function login_url($redirect = '') {
    return Url::login($redirect);
}
function logout_url($redirect = '') {
    return Url::logout($redirect);
}

function add_role( $role, $display_name, $capabilities = [] ) {
    return Role::add($role, $display_name, $capabilities);
}
function remove_role( $role ) {
    return Role::remove($role);
}
function get_role( $role ) {
    return Role::get($role);
}
function add_cap( $role, $cap, $grant = true ) {
    return Role::addCap( $role, $cap, $grant );
}
function remove_cap( $role, $cap ) {
    return Role::removeCap( $role, $cap);
}

if( !function_exists('add_meta_box') ) {
    function add_meta_box ( $id, $title, $callback, $module = null, $position = 1, $content = 'leftb', $content_box = '') {

        Metabox::add($id, $title, $callback, [
            'module' => $module,
            'position' => $position,
            'content' => $content,
            'content_box' => $content_box,
        ]);

    }
}

if( !function_exists('remove_meta_box') ) {
    function remove_meta_box ( $id ) {
        Metabox::remove($id);
    }
}

if(!function_exists('register_widget')) {
    function register_widget( $key ) {
        Widget::add($key);
    }
}

if(!function_exists('register_sidebar')) {
    function register_sidebar($args = []) {
        return Sidebar::add($args['id'], $args['name']);
    }
}

if(!function_exists('dynamic_sidebar'))  {
    function dynamic_sidebar( $index ) {
        Sidebar::render($index);
    }
}

if(!function_exists('add_metadata')) {
    function add_metadata($object_type, $object_id, $meta_key, $meta_value) {
        return Metadata::add($object_type, $object_id, $meta_key, $meta_value);
    }
}
if(!function_exists('update_metadata')) {
    function update_metadata($object_type, $object_id, $meta_key, $meta_value) {
        return Metadata::update($object_type, $object_id, $meta_key, $meta_value);
    }
}
if(!function_exists('delete_metadata')) {
    function delete_metadata($object_type, $object_id, $meta_key = '', $meta_value = '', $delete_all = false) {
        return Metadata::delete($object_type, $object_id, $meta_key, $meta_value, $delete_all);
    }
}
if(!function_exists('delete_all_metadata')) {
    function delete_all_metadata($object_type, $meta_key = '') {
        return Metadata::deleteAll($object_type, $meta_key);
    }
}
if(!function_exists('delete_metadata_by_mkey')) {
    function delete_metadata_by_mkey($object_type, $meta_key = '') {
        return Metadata::deleteByMkey($object_type, $meta_key);
    }
}
if(!function_exists('delete_metadata_by_mid')) {
    function delete_metadata_by_mid( $object_type, $mid ) {
        return Metadata::deleteByMid($object_type, $mid);
    }
}
if(!function_exists('get_metadata')) {
    function get_metadata($object_type, $object_id = '', $meta_key = '', $single = false) {
        return Metadata::get($object_type, $object_id, $meta_key, $single);
    }
}

if(!function_exists('get_cate_type')){
    function get_cate_type($cateType = null) {
        return Taxonomy::getCategory($cateType);
    }
}
if(!function_exists('isset_cate_type')){
    function isset_cate_type($cateType = null) {
        return Taxonomy::hasCategory($cateType);
    }
}
if(!function_exists('register_cate_type')){
    function register_cate_type($cateType, $postType, $arg) {
        if(isset($arg['capibilitie'])) $arg['capabilities'] = $arg['capibilitie'];
        return Taxonomy::addCategory($cateType, $postType, $arg);
    }
}
if(!function_exists('remove_cate_type')){
    function remove_cate_type($cateType, $postType) {
        return Taxonomy::removeCategory($cateType, $postType);
    }
}
if(!function_exists('register_post_type')) {
    function register_post_type($postType, $arg) {
        if(isset($arg['capibilitie'])) $arg['capabilities'] = $arg['capibilitie'];
        return Taxonomy::addPost($postType, $arg);
    }
}
if(!function_exists('remove_post_type')){
    function remove_post_type($postType) {
        return Taxonomy::removePost($postType);
    }
}
if(!function_exists('isset_post_type')) {
    function isset_post_type($postType = null) {
        return Taxonomy::hasPost($postType);
    }
}
if(!function_exists('get_post_type')){
    function get_post_type($postType = null) {
        return Taxonomy::getPost($postType);
    }
}
if(!function_exists('get_post_type_detail')){
    function get_post_type_detail() {
        return Taxonomy::getPostDetail();
    }
}
if(!function_exists('get_cate_type_detail')){
    function get_cate_type_detail() {
        return Taxonomy::getCategoryDetail();
    }
}
if(!function_exists('get_object_taxonomies')){
    function get_object_taxonomies( $object, $output = 'names' ) {
        return Taxonomy::getCategoryByPost($object, $output);
    }
}
function get_font_family() {
    return Template::fonts();
}

if(!function_exists('cle_nav_menu')) {
    function cle_nav_menu($args = []) {
        ThemeMenu::render($args);
    }
}

if(!function_exists('removeHtmlTags')) {
    function removeHtmlTags($str = '') {
        return Str::clear($str);
    }
}

if (!function_exists( 'plugin_get_include')) {
    function plugin_get_include($plugin_name, $template_path = '' , $args = '', $return = false) {
        if($return == true) return Plugin::partial($plugin_name, $template_path, $args, $return);
        Plugin::partial($plugin_name, $template_path, $args, $return);
    }
}

function plugin_dir_path( $name = '') {
    return Path::plugin($name).'/';
}