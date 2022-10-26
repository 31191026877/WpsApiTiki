<?php

use JetBrains\PhpStorm\ArrayShape;

class Cms {
    static public function info($str = '') {
        if($str == 'version') return Cms::version();
        if($str == 'database') return Cms::databaseVersion();
        if($str == 'logo') return Cms::logo();
        return [
            'auth'      => 'Skilldo Technologies',
            'framework' => 'Codeigniter',
            'version'    => Cms::version(),
            'db_version' => Cms::databaseVersion(),
            'timezone'   => date_default_timezone_get (),
        ];
    }
    static public function version() {
        global $cmsVersion; return $cmsVersion;
    }
    static public function databaseVersion() {
        global $skd_db_version; return $skd_db_version;
    }
    static public function logo() {
        return apply_filters('cms_logo', Admin::imgTemplateLink('cms-logo.png'));
    }
    static public function config($key = '') {
        $config = Option::get('cms_config');
        if(empty($config) || !have_posts($config)) $config = [];
        $config['theme_color']       = (!empty($config['theme_color'])) ? $config['theme_color'] : '#416DEA';
        $config['menu_bg']           = (!empty($config['menu_bg'])) ? $config['menu_bg'] : '#263a53';
        $config['menu_active_bg']    = (!empty($config['menu_active_bg'])) ? $config['menu_active_bg'] : '#232F3D';
        $config['content_bg']    = (!empty($config['content_bg'])) ? $config['content_bg'] : '#E7EAEF';
        $config['admin_post_number'] = (!empty($config['admin_post_number'])) ? $config['admin_post_number'] : 20;
        $config['admin_page_number'] = (!empty($config['admin_page_number'])) ? $config['admin_page_number'] : 20;
        $config['client_post_number'] = (!empty($config['client_post_number'])) ? $config['client_post_number'] : 20;
        $config['heading'] = (!empty($config['heading'])) ? $config['heading'] : 0;
        $config['widget_cache'] = (!empty($config['widget_cache'])) ? $config['widget_cache'] : 0;
        $config['widget_time'] = (!empty($config['widget_time'])) ? $config['widget_time'] : 10;
        if(!empty($key)) return Arr::get($config, $key);
        return $config;
    }
}

class Url {
    static public function is($str = '') {
        return Str::isUrl($str);
    }
    static public function base($str = '') {
        return base_url($str);
    }
    static public function current($base64 = false) {
        $currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';

        $currentURL .= $_SERVER['SERVER_NAME'];

        if($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443'){

            $currentURL .= ':'.$_SERVER['SERVER_PORT'];
        }
        $currentURL .= $_SERVER['REQUEST_URI'];

        if($base64 == false){
            return $currentURL;
        }
        else{
            return base64_encode($currentURL);
        }
    }
    static public function ssl() {
        if(isset($_SERVER['HTTPS'])) {
            if('on' == strtolower($_SERVER['HTTPS'])) return true;
            if('1' == $_SERVER['HTTPS']) return true;
        } elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }
    static public function admin($url = '') {
        $url = Str::clear($url);
        $url = static::base().URL_ADMIN.'/'.ltrim($url,'/');
        return apply_filters('admin_url', $url) ;
    }
    static public function adminModule($url = '') {
        $class  =  static::segment(2);
        $method =  ($class != Template::getClass()) ? Template::getClass() : '';
        $result = static::base().URL_ADMIN.'/';
        $result .= (!empty($class)) ? $class.'/' : '';
        $result .= (!empty($method)) ? str_replace('_', '-', $method).'/' : '';
        $result .= trim(Str::clear($url),'/');
        return apply_filters('admin_module', $result) ;
    }
    static public function permalink($slug) {
        if(Url::is($slug)) return $slug;
        return apply_filters('get_url', $slug) ;
    }
    static public function segment($number = null) {
        if($number == null) return Str::clear(get_instance()->uri->segments);
        return Str::clear(get_instance()->uri->segment((int)$number));
    }
    static public function getYoutubeID($url) {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $matches);
        return (!empty($matches)) ? $matches[1] : null;
    }
    static public function isYoutube($url) {
        return !empty(static::getYoutubeID($url));
    }
    static public function account($full = '') {
        $my_account_url = ( $full ) ? static::base('tai-khoan') : 'tai-khoan' ;
        return apply_filters( 'my_account_url', $my_account_url );
    }
    static public function register($redirect = '') {
        $register_url = static::base(static::account().'/register');
        if ( !empty($redirect) ) {
            $redirect = urlencode( $redirect = '' );
            $register_url .= '?redirect='.$redirect;
        }
        return apply_filters( 'register_url', $register_url, $redirect );
    }
    static public function login($redirect = '') {
        $login_url = static::base( static::account().'/login');
        if ( !empty($redirect) ) {
            $redirect = urlencode( $redirect );
            $login_url .= '?redirect='.$redirect;
        }
        return apply_filters( 'login_url', $login_url, $redirect );
    }
    static public function logout($redirect = '') {
        $register_url = static::base(static::account().'/logout');
        if ( !empty($redirect) ) {
            $redirect = urlencode( $redirect );
            $register_url .= '?redirect='.$redirect;
        }
        return apply_filters( 'logout_url', $register_url, $redirect );
    }
    static public function forgot() {
        $forgot_url = static::base(static::account().'/forgot');
        return apply_filters( 'forgot_url', $forgot_url);
    }
    static public function language($langkey) {
        $segment = static::segment();
        if(!empty($segment[1])) {
            $slug  = $segment[1];
            if(in_array($slug, Language::listKey()) && !empty($segment[2])) {
                $slug  = $segment[2];
            }
        }
        else {
            $slug = URL_HOME;
        }
        $url = static::base().$langkey.'/'.$slug;
        return rtrim($url,'/');
    }
}

class Path {
    static public function upload($str = '', $absolute = false) {
        $path = 'uploads/'.Str::clear($str);
        return $absolute ? FCPATH.$path : $path;
    }
    static public function theme($str = '', $absolute = false) {
        $theme = get_instance()->data['template']->name;
        if(empty($theme)) $theme = 'theme-store';
        $path = VIEWPATH.$theme.'/'.Str::clear(xss_clean($str));
        return $absolute ? FCPATH.$path : $path;
    }
    static public function admin($str = '', $absolute = false) {
        $path = VIEWPATH.'backend/'.Str::clear($str);
        return $absolute ? FCPATH.$path : $path;
    }
    static public function plugin($str = '', $absolute = false) {
        $path = VIEWPATH.'plugins/'.Str::clear($str);
        return $absolute ? FCPATH.$path : $path;
    }
}

class Admin {

    static public function template() {
        return get_instance()->template;
    }

    static public function is() {
        $admin  = Url::segment(1);
        if($admin == URL_ADMIN) {
            $ci =& get_instance();
            $class  = $ci->router->fetch_class();
            $method = $ci->router->fetch_method();
            if($class == 'home' && $method == 'ajax') {
                if(isset($_GET['client']) && $_GET['client'] == true) return false;
                if(isset($_POST['client']) && $_POST['client'] == true) return false;
                $action = '';
                if(!empty($_GET['action']))  $action = $_GET['action'];
                if(!empty($_POST['action'])) $action = $_POST['action'];
                if(!empty($action) && Ajax::isRegisterClient($action)) return false;
            }
            return true;
        }
        return false;
    }

    static public function asset() {
        return get_instance()->template->getAsset('backend');
    }

    static public function icon($action = '') {
        $icon = match ($action) {
            'add' => '<i class="fad fa-plus-circle"></i>',
            'edit' => '<i class="fad fa-pencil"></i>',
            'save' => '<i class="fad fa-hdd"></i>',
            'back' => '<i class="fad fa-reply"></i>',
            'undo' => '<i class="fad fa-undo"></i>',
            'delete' => '<i class="fad fa-trash"></i>',
            'search' => '<i class="fad fa-search"></i>',
            'cancel' => '<i class="fad fa-ban"></i>',
            'download', 'install' => '<i class="fad fa-cloud-download-alt"></i>',
            'active' => '<i class="fad fa-check-circle"></i>',
            'off' => '<i class="fad fa-power-off"></i>',
            default => '',
        };
        return apply_filters( 'admin_button_icon', $icon, $action );
    }

    static public function loading($id = '', $class = '') {
        ?>
        <div class="loading ng-star-inserted <?php echo $class;?>" id="<?php echo $id;?>">
            <div class="vs-loading__load vs-loading--default">
                <div class="lds">
                    <div class="lds__1"></div>
                    <div class="lds__2"></div>
                </div>
            </div>
        </div>
        <?php
    }

    static public function btnConfirm($args = []) {
        $label = (empty($args['icon'])) ? Admin::icon('delete') : $args['icon'];
        $label .= (!isset($args['label'])) ? '' : ' '.$args['label'];
        $args['action'] = (empty($args['action'])) ? 'delete' : $args['action'];
        $args['ajax'] = (empty($args['ajax'])) ? 'Cms_Ajax_Action::delete' : $args['ajax'];
        $args['id'] = (empty($args['id'])) ? 0 : $args['id'];
        $args['heading'] = (empty($args['heading'])) ? 'Xóa Dữ liệu' : $args['heading'];
        $args['des'] = (empty($args['des'])) ? 'Bạn chắc chắn muốn xóa dữ liệu này ?' : $args['des'];
        $args['trash'] = (empty($args['trash'])) ? 'disable' : $args['trash'];
        $args['btn'] = (empty($args['btn'])) ? 'red' : $args['btn'];
        $args['module'] = (empty($args['module'])) ? '' : $args['module'];
        $attr = '';
        if(!empty($args['attr'])) {
            if(is_string($args['attr'])) $attr = $args['attr'];
        }
        return '<button class="btn btn-'.$args['btn'].' js_btn_confirm"
                style="'.((!empty($args['style'])) ? $args['style'] :'').'"
                data-action="'.$args['action'].'"
                data-ajax="'.$args['ajax'].'"
                '.((!empty($args['id'])) ? 'data-id="'.$args['id'].'"' : '').'
                data-module="'.$args['module'].'"
                data-heading="'.$args['heading'].'"
                data-description="'.$args['des'].'"
                data-trash="'.$args['trash'].'" '.$attr.'>'.$label.'</button>';
    }

    static public function btnDelete($args = []) {
        $args['icon'] = Admin::icon('delete');
        $args['action'] = 'delete';
        $args['ajax'] = 'Cms_Ajax_Action::delete';
        $args['btn'] = 'red';
        $args['attr'] = 'data-toggle="tooltip" data-placement="top" title="Xóa"';
        return Admin::btnConfirm($args);
    }

    static public function btnRestore($args = []) {
        $args['icon'] = Admin::icon('undo');
        $args['action'] = 'restore';
        $args['ajax'] = 'Cms_Ajax_Action::restore';
        $args['btn'] = (empty($args['btn'])) ? 'green' : $args['btn'];
        $args['heading'] = (empty($args['heading'])) ? 'Khôi phục dữ liệu' : $args['heading'];
        $args['des'] = (empty($args['des'])) ? 'Bạn chắc chắn muốn khôi phục dữ liệu này ?' : $args['des'];
        $args['attr'] = 'data-toggle="tooltip" data-placement="top" title="Khôi phục"';
        return Admin::btnConfirm($args);
    }

    static public function img($path, $args = []) {

        $type = (!empty($args['type'])) ? $args['type'] : SOURCE;

        $alt  = (!empty($args['alt'])) ? $args['alt'] : '';

        $return = (isset($args['return'])) ? (bool)$args['return'] : false;

        $url = static::imgLink($path, $type);

        /* singe 3.0.0 */
        $url = apply_filters('get_img_url', $url, ['img' => $path, 'alt' => $alt, 'params' => $args, 'type' => $type]);

        /* singe 3.0.0 */
        $params = apply_filters('get_img_params', $args, ['img' => $path, 'alt' => $alt, 'type' => $type]);

        $html = '<img src="'.$url.'" alt="'.Str::clear($alt).'" ';

        if(have_posts($args)) {
            unset($args['alt']);
            unset($args['return']);
            foreach ($args as $key => $value) {
                $html .= $key.'="'.$value.'" ';
            }
        }
        $html .=' loading="lazy" />';

        $html = apply_filters('get_img', $html, ['url' => $url, 'img' => $path, 'alt' => $alt, 'params' => $args, 'type' => $type]);

        if($return) return $html; else echo $html;
    }

    static public function imgLink($path, $type = 'source') {

        if(Url::is($path)) {

            $url = $path;
        }
        else {
            if ($type == 'source') {
                $url = SOURCE.$path;
            }
            elseif($type == 'thumbail') {
                $url = THUMBAIL.$path;
            }
            elseif($type == 'medium') {
                $url = MEDIUM.$path;
            }
            elseif($type ==  'watermark') {

                return base_url().'watermark/'.$path;
            }
            else {
                $url = $type.'/'.$path;
            }

            $url_check =  urldecode($url);

            if (!file_exists($url_check)) {
                $url        = SOURCE.$path;
                $url_check  = urldecode($url);
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
                $url_check = static::imgTemplateLink($path);

                if (file_exists($url_check)) $url = $url_check;
            }
        }

        $url = apply_filters('get_img_link', $url, array(
            'img'    => $path,
            'type'   => $type,
        ));

        return $url;
    }

    static public function imgTemplate($path, $args = []) {

        $alt    = (!empty($args['alt'])) ? $args['alt'] : '';

        $return = (isset($args['return'])) ? $args['return'] : false;

        $url = static::imgTemplateLink($path);

        $html = '<img src="'.$url.'" alt="'.Str::clear($alt).'" ';

        if(have_posts($args)) {
            unset($args['alt']);
            unset($args['return']);
            foreach ($args as $key => $value) {
                $html .= $key.'="'.$value.'" ';
            }
        }

        $html .=' loading="lazy" />';

        if($return) return $html; else echo $html;
    }

    static public function imgTemplateLink($path) {

        return Path::admin('assets/images/'.$path);
    }

    static public function isRoot($id = 0) {
        if(Auth::check() == false) return false;
        GLOBAL $superadmins;
        $listAdmin = explode(',',$superadmins);
        $id = empty($id) ? Auth::user()->id : $id;
        return (in_array($id, $listAdmin) !== false) ? true : false;
    }

    static public function getCateType() {
        return get_instance()->cateType;
    }

    static public function getPostType() {
        return get_instance()->postType;
    }

    static public function partial($views_path, $args = [], $return = false) {

        $ci =& get_instance();

        extract($ci->data);

        if (!empty($args) && is_array($args)) { extract($args);}

        $path = VIEWPATH.'backend/theme-child/'.$views_path.'.php';

        if(!file_exists($path)) {
            $path = VIEWPATH.'backend/'.$views_path.'.php';
        }

        if(file_exists($path)) {
            ob_start();
            include $path;
            $buffer = ob_get_contents();
            ob_end_clean();
            if( $return  == true) {
                return $buffer;
            } else {
                echo $buffer;
            }
        }
        else {
            echo 'Không tìm thấy file '.$path.'!';
            die;
        }
    }

    static public function creatForm($class, $object = []): void {
        $cms = &get_instance();
        $cms->creatForm(['class' => $class]);
        if(have_posts($object)) {
            $cms->setValueFields($object);
        }
    }
}

class Language extends Model {
    static string $table = 'language';
    static function load(): void {
        $ci =& get_instance();
        if(in_array(Url::segment(1), static::listKey())) {
            $_SESSION['language'] = Url::segment(1);
        }
        if(empty($_SESSION['language'])) {
            $_SESSION['language'] = Language::default();
        }
        static::setCurrent();
        $listTemp = Option::get('language');
        $ci->language['list'] = (!have_posts($listTemp)) ? get_instance()->language['list'] : $listTemp;
        $ci->config->set_item('language', static::current());
        $ci->lang->load('general');
    }
    static function has($key = ''): bool {
        $list = static::listKey();
        if(isset($list[$key])) return true;
        return false;
    }
    static function hasMulti(): bool {
        if(count(static::listKey()) > 1) return true;
        return false;
    }
    static function list($key = '') {
        if(empty($list)) $list = get_instance()->language['list'];
        if(empty($key)) return $list;
        if(isset($list[$key])) return $list[$key];
        return '';
    }
    static function listKey(): array {
        return array_keys(static::list());
    }
    static function current() {
        return get_instance()->language['current'];
    }
    static function setCurrent($key = ''): bool {
        if(empty($key)) {
            if(empty($_SESSION['language'])) return false;
            $key = $_SESSION['language'];
        }
        $ci =& get_instance();
        $ci->language['current'] = $key;
        return true;
    }
    static function default() {
        return get_instance()->language['default'];
    }
    static function isDefault(): bool {
        if(Language::current() != Language::default()) return false;
        return true;
    }
    static function insert($insertData = []) {

        $columns = [
            'title'       => ['string'],
            'excerpt'     => ['wysiwyg'],
            'content'     => ['wysiwyg'],
            'language'    => ['string'],
            'object_id'   => ['int'],
            'object_type' => ['string'],
        ];

        $update = false;

        if(!empty($insertData['id'])) {

            $update = true;

            $id     = (int) $insertData['id'];

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_language_id', __('ID language không chính xác.'));
        }

        $insertData = createdDataInsert($columns, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columns as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $data = compact(array_keys($columns));

        $data = apply_filters( 'pre_insert_language_data', $data, $insertData, $update ? $oldObject : null );

        $model = model(self::$table);

        if ($update) {

            $model->update($data, Qr::set('id', $id));

            $object_id = (int)$id;
        }
        else {

            $object_id = $model->add( $data );
        }

        return apply_filters('after_insert_language', $object_id, $insertData, $data, $update ? (int) $oldObject : null);
    }
    static function delete($objectID = null): array {

        if($objectID == 0) return [];

        $model = model(static::$table);

        $object = static::get(Qr::set('id', $objectID));

        if(have_posts($object)) {

            do_action('delete_'.static::$table, $object);

            if($model->delete(Qr::set('id', $objectID))) {

                do_action('delete_'.static::$table.'_success', $object);

                return [$objectID];
            }
        }

        return [];
    }
    static function deleteList($objectID = []) {
        if(have_posts($objectID)) {
            $model  = model(self::$table);
            if($model->delete(Qr::set()->whereIn('id', $objectID))) {
                do_action('delete_'.static::$table.'_list_success', $objectID);
                return $objectID;
            }
        }
        return [];
    }
    static function join(Qr $query, $table, $module = null): Qr {
        if(empty($module)) $module = $table;
        if(!Language::isDefault()) {
            $select = $query->columns;
            if(empty($select)) {
                $query->select($table.'.*', 'lg.name as name', 'lg.title as title', 'lg.excerpt as excerpt', 'lg.content as content');
            }
            else {
                foreach ($select as $keyName => $columnName) {
                    if($columnName == 'name' || $columnName == 'title' || $columnName == 'excerpt' || $columnName == 'content') {
                        $columnName = 'lg.'.$columnName;
                    }
                    else $columnName = $table.'.'.$columnName;
                    $select[$keyName] = $columnName;
                }
                $query->select(...$select);
            }
            $query->join('language as lg', $table.'.id', '=', 'lg.object_id');
            $query->where('lg.language', Language::current());
            $query->where('lg.object_type', $module);
            if(is_array($query->orders) && have_posts($query->orders)) {
                foreach ($query->orders as $key => $order) {
                    if(isset($order['column']) && !str_contains($order['column'], '.')) $query->orders[$key]['column'] = $table.'.'.$order['column'];
                }
            }
        }
        return $query;
    }
}

class Dashboard {

    public static function has($id) {
        return !((empty($id) || !isset(get_instance()->widgetDashboard[$id])));
    }

    public static function add($id, $title, $args = []) {

        if(static::has($id)) return false;

        $callback = (!empty($args['callback'])) ? Str::clear($args['callback']) : '';

        $option = array('col' => 4);

        $args   = array_merge($option, $args);

        $ci = &get_instance();

        $ci->widgetDashboard[$id] = [
            'title'     => $title,
            'callback'  => $callback,
            'option'    => $args
        ];

        return true;
    }

    public static function get($id) {

        if(!static::has($id)) return false;

        return get_instance()->widgetDashboard[$id];
    }

    public static function getAll() {

        return get_instance()->widgetDashboard;
    }

    public static function remove($id) {

        if(!static::has($id)) return false;

        $ci =& get_instance();

        unset($ci->widgetDashboard[$id]);

        return true;
    }

    public static function widget($id) {

        if(static::has($id)) {

            $widget = static::get($id);

            if(function_exists($widget['callback'])) {
                call_user_func($widget['callback'], $widget);
            }
            else {
                $callback =  explode('::', $widget['callback']);
                if(count($callback) == 2 && method_exists($callback[0], $callback[1])) {
                    call_user_func($widget['callback'], $widget);
                }
                else {
                    echo notice('warning', 'Callback of widget dashboard do\'nt exits!');
                }
            }
        }
    }

    public static function render() {

        $dashboard_sort         = option::get('dashboard_sort', []);

        $dashboard 		        = option::get('dashboard', []);

        $widgetDashboard       = [];

        $widgetDashboardTemp  = static::getAll();

        foreach ($dashboard_sort as $key => $id_dashboard) {
            if(have_posts($dashboard) && empty($dashboard[$id_dashboard])) {
                unset($widgetDashboardTemp[$id_dashboard]);
                continue;
            }
            if(!empty($widgetDashboardTemp[$id_dashboard])) {
                $widgetDashboard[$id_dashboard] = $widgetDashboardTemp[$id_dashboard];
                unset($widgetDashboardTemp[$id_dashboard]);
            }
            else {
                unset($dashboard_sort[$key]);
            }
        }

        foreach ($widgetDashboardTemp as $id_dashboard => $item) {
            if(isset($dashboard[$id_dashboard]) && $dashboard[$id_dashboard] == 0) {
                unset($widgetDashboardTemp[$id_dashboard]);
                continue;
            }
            $widgetDashboard = Arr::prepend($widgetDashboard, $item, $id_dashboard);
        }

        if(have_posts($widgetDashboard)) {

            foreach ($widgetDashboard as $id => $widget) {
                echo '<li class="list-dashboard-item col-md-'.$widget['option']['col'].'" data-id="'.$id.'">';
                echo '<div id="wg_dashboard_'.$id.'">';
                static::widget($id);
                echo '</div>';
                echo '</li>';
            }
        }
    }
}

class Option {
    static public function add($option_name, $option_value) {

        $model = model('system');

        if(!$option_name ) return false;

        do_action( "add_{$option_name}_option", $option_name, $option_value );

        if(is_array($option_value) || is_object($option_value)) $option_value = serialize($option_value);

        $data['option_name'] 	= $option_name;
        $data['option_value'] 	= $option_value;

        $mid = $model->add( array(
            'option_name' 	=> $option_name,
            'option_value' 	=> $option_value,
            'created' => gmdate('Y-m-d H:i:s', time() + 7*3600)
        ));

        if (!$mid) return false;

        CacheHandler::delete('system');

        do_action( "added_{$option_name}_meta", $mid, $option_name, $option_value );

        return $mid;
    }
    static public function update($option_name, $option_value) {

        if(!$option_name) return false;

        if(is_array($option_value) || is_object($option_value)) $option_value = serialize($option_value);

        $model = model('system');

        $option = $model->get(Qr::set('option_name', $option_name));

        if(!have_posts($option)) {
            return self::add($option_name, $option_value);
        }

        $where = Qr::set('id', $option->id)->where('option_name', $option_name);

        do_action( "update_{$option_name}_option", $option, $option_name, $option_value );

        $data['option_value'] = $option_value;

        $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);

        $result = $model->update( $data, $where );

        if(!$result) return false;

        if(CacheHandler::has('system')) {

            $system_cache = CacheHandler::get('system');

            $system_cache = (array)$system_cache;

        } else {
            $system_cache = [];
        }

        $system_cache[$option_name] = $option_value;

        CacheHandler::save('system', $system_cache);

        return true;
    }
    static public function delete($option_name) {

        $model = model('system');

        if (!$option_name) return false;

        $where = Qr::set('option_name', $option_name);

        do_action( "delete_{$option_name}_option", $where, $option_name);

        $count =  $model->delete($where);

        if (!$count) return false;

        CacheHandler::delete('system');

        do_action("deleted_{$option_name}_option", $where, $option_name);

        return true;
    }
    static public function get($option_name  = '', $value = '') {

        if (!$option_name) return false;

        $ci =& get_instance();

        if(!empty($ci->system->theme_option) && Str::isSerialized($ci->system->theme_option)) {
            $ci->system->theme_option = unserialize($ci->system->theme_option);
            $ci->system = (object)array_merge((array)$ci->system, (array)$ci->system->theme_option);
        }

        if(!isset($ci->system->$option_name) && CacheHandler::has('system')) {
            $ci->system  = (object)CacheHandler::get('system');
        }

        if(isset($ci->system->$option_name)) return (Str::isSerialized($ci->system->$option_name)) ? unserialize($ci->system->$option_name) : $ci->system->$option_name;

        if(isset($ci->system->theme_option)) {

            $themeOptions = @unserialize($ci->system->theme_option);

            if(isset($themeOptions[$option_name])) {
                return (Str::isSerialized($themeOptions[$option_name])) ? unserialize($themeOptions[$option_name]) : $themeOptions[$option_name];
            }
        }

        $result 	= model()::table('system')->where('option_name', $option_name)->get();

        $system = [];

        if(count($result) != 0) $system = $result[0];

        if(CacheHandler::has('system')) {

            $system_cache = CacheHandler::get('system');

            if(is_object($system_cache)) $system_cache = (array)$system_cache;

        } else {

            $system_cache = [];
        }

        if(have_posts($system)) {

            $system_cache[$option_name] = $system->option_value;

            $value = (Str::isSerialized($system->option_value))?unserialize($system->option_value):$system->option_value;
        }
        else {

            if(is_object($system_cache)) $system_cache = (array)$system_cache;

            $system_cache[$option_name] = $value;
        }

        CacheHandler::save('system', $system_cache);

        $ci->system   = (object)CacheHandler::get('system');

        return $value;
    }
    static public function getAll() {

        if(CacheHandler::has('system')) return CacheHandler::get('system');

        $_system = model('system')->get(Qr::set()->select('option_name', 'option_value'));

        if(have_posts($_system)) {

            $system = (object)[];

            foreach ($_system as $value) {
                $system->{$value->option_name} =  $value->option_value;
            }

            CacheHandler::save('system', $system);

            return $system;
        }

        return [];
    }
}

class ThemeOption {
    static public function addGroup($key, $args = []) {

        if(empty($key)) return false;

        if(!have_posts($args)) return false;

        $ci =& get_instance();

        $themeOptions = $ci->themeOptions['group'];

        $args =  array_merge(['label' => '', 'icon' => '', 'position' => 0], $args);

        if(isset($themeOptions[$key])) return false;

        $themeOptions[$key] = $args;

        $themeOptionsPosition = [];

        foreach ($themeOptions as $key => $item) {
            $themeOptionsPosition[$key] = $item['position'];
        }

        asort($themeOptionsPosition);

        foreach ($themeOptionsPosition as $key => $item) {
            $themeOptionsPosition[$key] = $themeOptions[$key];
        }

        $ci->themeOptions['group'] = $themeOptionsPosition;

        return true;
    }
    static public function addGroupSub($parentKey, $subKey, $args) {
        if(empty($parentKey)) return false;
        if(empty($subKey)) return false;
        $ci =& get_instance();
        $themeOptions = $ci->themeOptions['group'];
        if(!isset($themeOptions[$parentKey])) return false;
        if(empty($themeOptions[$parentKey]['sub'])) {
            $themeOptions[$parentKey]['sub'] = [];
        }
        $name = '';
        if(!empty($args['name'])) $name = $args['name'];
        if(is_string($args)) $name = $args;
        if(empty($name)) return false;
        $themeOptions[$parentKey]['sub'][$subKey] = ['label' => $name];
        $ci->themeOptions['group'] = $themeOptions;
        return true;
    }
    static public function addField($group, $name, $type, $field = []) {

        if(empty($group)) return false;

        if(!have_posts($field)) return false;

        $field['group'] = $group;
        $field['field'] = $name;
        $field['type']  = $type;

        $ci = &get_instance();

        $ci->themeOptions['option'][] = $field;

        return true;
    }
    static public function is($key = '') {

        if(empty($key)) return false;

        $ci =& get_instance();

        $themeOptions = $ci->themeOptions['group'];

        if(isset($themeOptions[$key])) {
            return true;
        }

        return false;
    }
}

class Sidebar {

    static public function add($key = '', $name = '') {
        if(empty($key)) return false;
        $ci =& get_instance();
        if(is_string($key)) {
            $ci->sidebar[$key] = [ 'id' => $key, 'name' => $name ];
        }
        if(have_posts($key)) {
            $ci->sidebar[$key['id']] = $key;
        }
        return true;
    }

    /**
     * @throws Exception
     */
    static public function render($index) {

        $html = '';

        if(Cms::config('widget_cache') == 1) {
            $html = CacheHandler::get('sidebar_widget_'.$index);
        }
        if(Device::isGoogleSpeed()) {
            $html = CacheHandler::get('sidebar_google_speed_'.$index);
        }

        if(empty($html)) {

            ob_start();

            $ci =& get_instance();

            $model = model('widget');

            $cache_sidebar_id = 'sidebar_' . md5($index . '_' . $ci->data['template']->name);

            if (!CacheHandler::has($cache_sidebar_id)) {
                $sidebar = $model->gets(Qr::set('sidebar_id', $index)->where('template', $ci->data['template']->name)->orderBy('order'));
                CacheHandler::save($cache_sidebar_id, $sidebar);
            }
            else {
                $sidebar = CacheHandler::get($cache_sidebar_id);
            }

            if (have_posts($sidebar)) {
                foreach ($sidebar as $key => $value) {
                    $widget = $ci->template->getWidget($value->widget_id);
                    if (have_posts($widget)) {
                        if (Device::isGoogleSpeed() && $key == 3) break;
                        $widget->form();
                        $widget->id = $value->id;
                        $widget->name = $value->name;
                        if(Str::isSerialized($value->options)) {
                            $widget->options = (object)unserialize($value->options);
                        }
                        else {
                            $widget->options = (object)[];
                        }
                        if (Language::current() != Language::default()) {
                            if (isset($widget->options->{'title_' . Language::current()})) $widget->name = $widget->options->{'title_' . Language::current()};
                        }
                        if(method_exists($widget, 'default')) {
                            $widget->default();
                        }
                        $widget->widget($widget->options);
                    }
                }
            } else {
                $widget = new Widget('widget_none', 'widget none');
                $widget->widgetNone($index);
            }

            $html = ob_get_contents();

            ob_clean();

            ob_end_flush();

            if (Device::isGoogleSpeed()) {
                CacheHandler::save('sidebar_google_speed_'.$index, $html, 30*60);
            }
            else {
                CacheHandler::save('sidebar_widget_'.$index, $html, Cms::config('widget_time')*60);
            }
        }

        echo $html;
    }

    static public function gets() {
        return get_instance()->sidebar;
    }
}

class Device {

    public function getAgent() {
        $ci = get_instance();
        $ci->load->library('user_agent');
        return new CI_User_agent();
    }

    static public function string(): ?string {
        return (new Device)->getAgent()->agent_string();
    }

    static public function isBrowser($key = null) {
        return (new Device)->getAgent()->is_browser($key);
    }

    static public function getBrowser() {
        return (new Device)->getAgent()->browser();
    }

    static public function isMobile($key = null) {
        return (new Device)->getAgent()->is_mobile($key);
    }

    static public function getMobile() {
        return (new Device)->getAgent()->mobile();
    }

    static public function getPlatform() {
        return (new Device)->getAgent()->platform();
    }

    static public function isGoogleSpeed() {
        if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "google page speed insights") || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "chrome-lighthouse")) {
            return true;
        }
        return false;
    }
}

class CacheHandler {

    public static function library() {
        if(!class_exists('cache')) {
            $ci = &get_instance();
            $ci->load->driver('cache', array('adapter' => 'file'));
        }
    }

    public static function has($cache_id) {

        if(empty($cache_id)) return false;

        static::library();

        $path = FCPATH.get_instance()->config->item('cache_path').$cache_id;

        $data = '';

        if(!file_exists($path)) return false;

        if (function_exists('file_get_contents')) {
            $data = file_get_contents($path);
        }
        else {
            $fp = @fopen($path, FOPEN_READ);

            if(!$fp) return false;

            flock($fp, LOCK_SH);

            if (filesize($path) > 0) {
                $fread = fread($fp, filesize($path));
                $data =& $fread;
            }

            flock($fp, LOCK_UN);

            fclose($fp);
        }

        if(!Str::isSerialized($data)) return false;

        $data = unserialize($data);

        if (time() >  $data['time'] + $data['ttl']) {

            unlink($path);

            return false;
        }

        return true;
    }

    public static function get($cache_id, $default= '') {

        if(empty($cache_id) ) return false;

        static::library();

        $items = get_instance()->cache->get($cache_id);

        return (!empty($items)) ? $items : $default;
    }

    public static function save($cache_id, $cache_value, $cache_time = TIME_CACHE) {

        if(empty($cache_id)) return false;

        static::library();

        $ci = &get_instance();

        $ci->cache->save($cache_id, $cache_value, $cache_time );

        return true;
    }

    public static function delete($cache_id, $prefix = false) {

        static::library();

        $ci = &get_instance();

        if(!$prefix) {
            if(CacheHandler::has($cache_id)) $ci->cache->delete($cache_id);
        }

        if($prefix) {

            $list_cache = scandir(VIEWPATH.'cache');

            foreach ($list_cache as $key => $value) {

                if( $value == 'index.html' ) continue;

                if( $value == '.htaccess' ) continue;

                if(!empty($cache_id) && Str::is($cache_id.'*', $value) !== false) {
                    unlink( VIEWPATH.'cache/'.$value);
                }
            }
        }

        return true;
    }

    public static function flush(): bool {
        $list_cache = scandir(VIEWPATH.'cache');
        foreach ($list_cache as $key => $value) {
            if( $value == 'index.html' ) continue;
            if( $value == '.htaccess' ) continue;
            if( $value == '.' ) continue;
            if( $value == '..' ) continue;
            if(file_exists(FCPATH.VIEWPATH.'cache/'.$value)) {
                unlink( VIEWPATH.'cache/'.$value );
            }
        }
        return true;
    }
}

class Response {
    public function download($url, $path): bool {
        # open file to write
        $fp = fopen ($path, 'w+');
        # start curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url );
        # set return transfer to false
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false );
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
        # increase timeout to download big file
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10 );
        # write data to local file
        curl_setopt($ch, CURLOPT_FILE, $fp );
        # execute curl
        curl_exec($ch);
        # close curl
        curl_close($ch);
        # close local file
        fclose( $fp );
        if (ob_get_length()) ob_end_clean();
        if (filesize($path) > 0) return true;
        return false;
    }
    public function getHeaders($url){
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_NOBODY, true );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
        curl_setopt( $ch, CURLOPT_HEADER, false );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt( $ch, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'] . "/".SPATH."uploads/cacert.pem");
        curl_exec( $ch );
        $headers = curl_getinfo( $ch );
        curl_close( $ch );
        return $headers;
    }
}

class Request {
    static public function get($key = '', $args = ['clear' => true]) {

        if(empty($key)) return get_instance()->input->get();

        $clear = (!empty($args['clear'])) ? (bool)$args['clear'] : '';

        $type  = (!empty($args['type'])) ? $args['type'] : 'none';

        $result = get_instance()->input->get($key);

        $function_clear = 'Str::clear';

        if(!$clear) $function_clear = 'xss_clean';

        if(is_string($result)) {

            $result =  $function_clear($result);

            if($type == 'int') {
                $result =  (int)$result;
            }
        }
        else {

            if(have_posts($result)) {

                foreach ($result as $key => $item) {

                    if(is_string($item)) {

                        $result[$key] = $function_clear($item);

                        if($type == 'int') {

                            $result[$key] =  (int)$result[$key];
                        }
                    }
                }
            }
        }

        return $result;
    }
    static public function post($key = '', $args = ['clear' => true]) {

        if(empty($key)) return get_instance()->input->post();

        $clear = (!empty($args['clear'])) ? (bool)$args['clear'] : '';

        $type  = (!empty($args['type'])) ? $args['type'] : 'none';

        $result = get_instance()->input->post($key);

        $function_clear = 'Str::clear';

        if(!$clear) $function_clear = 'xss_clean';

        if(is_string($result)) {

            $result =  $function_clear($result);

            if($type == 'int') {
                $result =  (int)$result;
            }
        }
        else {

            if(have_posts($result)) {

                foreach ($result as $key => $item) {

                    if(is_string($item)) {

                        $result[$key] = $function_clear($item);

                        if($type == 'int') {

                            $result[$key] =  (int)$result[$key];
                        }
                    }
                }
            }
        }

        return $result;
    }
}

class Routes extends Model{
    static string $table = 'routes';
    static function insert($insertData = []) {

        $columnsTable = [
            'slug'         => ['Str::ascii'],
            'object_type'  => ['string'],
            'directional'  => ['string'],
            'controller'   => ['string'],
            'object_id'    => ['int'],
            'callback'     => ['string'],
        ];

        $columnsTable = apply_filters('columns_db_routes', $columnsTable);

        $update = false;

        if(!empty($insertData['id'])) {

            $update        = true;

            $id             = (int) $insertData['id'];

            $oldObject = static::get($id);

            if (!$oldObject) return new SKD_Error('invalid_page_id', __('ID router không chính xác.'));
        }

        $insertData = createdDataInsert($columnsTable, $insertData, (isset($oldObject)) ? $oldObject : null);

        foreach ($columnsTable as $columnsKey => $columnsValue ) {
            ${$columnsKey}  = $insertData[$columnsKey];
        }

        $data = compact(array_keys($columnsTable));

        $data = apply_filters( 'pre_insert_routes_data', $data, $insertData, $update ? $oldObject : null );

        $model = model(self::$table);

        if (!empty($oldObject)) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set('id', $id));

            CacheHandler::delete('routes-'.$oldObject->slug);
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $id = $model->add($data);
        }

        return apply_filters('after_insert_routes', $id, $insertData, $data, $update ? (int) $oldObject : null);
    }
    static function update($update, $args) {

        if(!have_posts($update)) {
            return new SKD_Error('invalid_update', __('Không có trường dữ liệu nào được cập nhật.'));
        }

        if(!have_posts($args)) {
            return new SKD_Error( 'invalid_update', __('Không có điều kiện cập nhật.'));
        }

        if(is_array($args)){

            $qr = Qr::set();

            foreach ($args as $keyWhere => $valueWhere) {
                $keyWhere = explode(' ', $keyWhere);
                if(count($keyWhere) == 2) {
                    $qr->where($keyWhere[0], $keyWhere[1], $valueWhere);
                }
                else {
                    $qr->where($keyWhere[0], $valueWhere);
                }
            }

            $args = $qr;
        }

        $routes = self::gets($args);

        if(!have_posts($routes)) return 0;

        foreach ($routes as $route) {
            CacheHandler::delete('routes-'.$route->slug);
        }
        $update['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
        return apply_filters('update_routes', model(self::$table)->update($update, $args), $update, $args);
    }
    static function delete(int $objectID): array {

        if($objectID == 0) return [];

        $model = model(self::$table);

        $object = static::get(Qr::set('id', $objectID));

        if(have_posts($object)) {

            do_action('delete_routes', $object);

            if($model->delete(Qr::set('id', $objectID))) {

                do_action('delete_routes_success', $object );

                CacheHandler::delete( 'routes-'.$object->slug);

                return [$objectID];
            }
        }

        return [];
    }
    static function deleteList($objectID = []) {

        if(have_posts($objectID)) {

            $model  = model(self::$table);

            if($model->delete(Qr::set()->whereIn('id', $objectID))) {

                do_action('delete_routes_list_success', $objectID );

                return $objectID;
            }
        }

        return [];
    }
    static function slug($slug, $module, $id = 0): string {

        $slug 	= Str::slug($slug);

        $model = model(self::$table);

        $args = Qr::set('slug', $slug);

        if(!empty($id)) {
            $args->where('object_id', '<>', $id);
        }

        $count 	= $model->count($args);

        $temp 	= $slug;

        if($count != 0) {
            $i = 1;
            while ($count != 0) {
                $slug 	= $temp.'-'.$i;
                $args->removeWhere('slug')->where('slug', $slug);
                $count 	= $model->count($args); $i++;
            }
        }

        return $slug;
    }
}

if(!function_exists('show_r'))  {
    function show_r($param = []): void {
        echo '<pre>'; print_r($param); echo '</pre>';
    }
}

if(!function_exists('get_object_current')){
    function get_object_current($key = null) {
        $ci =& get_instance();
        if(Admin::is()) return false;
        if(Template::isClass('post') || Template::isClass('products') || Template::isClass('page')) {
            if(Template::isMethod('index') && $key === null) $key = 'category';
            if(Template::isMethod('detail') && $key === null) $key = 'object';
        }
        if(!empty($key) && !empty($ci->data[$key])) return $ci->data[$key];
        return [];
    }
}

if(!function_exists('is_skd_error')){
    /**
     * @since 2.2.0
     */
    function is_skd_error( $thing ) {
        return ( $thing instanceof SKD_Error );
    }
}

if(!function_exists('add_magic_quotes')){
    /**
     * @since 2.2.0
     */
    function add_magic_quotes($array) {
        foreach ((array) $array as $k => $v) {
            if (is_array($v)) {
                $array[$k] = add_magic_quotes($v);
            } else {
                $array[$k] = (!empty($v)) ? addslashes($v) : $v;
            }
        }
        return $array;
    }
}

if(!function_exists('process_data')) {
    function process_data($data = [], $rules = []) {
        foreach ($rules as $val) {
            if(isset($val['type']) && $val['type'] == 'image' && isset($data[$val['field']])) {
                $data[$val['field']] = FileHandler::handlingUrl($data[$val['field']]);
            }
            if(isset($val['type']) && $val['type'] == 'file' && isset($data[$val['field']])) $data[$val['field']] = FileHandler::handlingUrl($data[$val['field']]);
            if(isset($val['type']) && $val['type'] == 'video') $data[$val['field']] = getYoutubeID($data[$val['field']]);
        }
        return $data;
    }
}
if(!function_exists('deleteDirectory')) {

    function deleteDirectory($dir): bool {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
}

if(!function_exists('createdDataInsert')) {

    function createdDataInsert($columns = [], $insertData = [], $oldObject = []) {

        foreach ($columns as $columnDataKey => $columnDataValue ) {

            $columnDataType = $columnDataValue[0];

            $columnDataDefault = (isset($columnDataValue[1])) ? $columnDataValue[1] : '';

            if($columnDataType == 'int' && empty($columnDataDefault)) {
                $columnDataDefault = 0;
            }

            if($columnDataType == 'string') {
                $insertData[$columnDataKey]  = (!empty($insertData[$columnDataKey])) ? Str::clear($insertData[$columnDataKey]) : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
            }
            else if($columnDataType == 'slug') {
                $insertData[$columnDataKey]  = (!empty($insertData[$columnDataKey])) ? Str::clear($insertData[$columnDataKey]) : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
                $insertData[$columnDataKey] = Str::slug($insertData[$columnDataKey]);
            }
            else if($columnDataType == 'int' || is_numeric($columnDataKey)) {
                $insertData[$columnDataKey]  = (isset($insertData[$columnDataKey])) ? (int)$insertData[$columnDataKey] : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
            }
            else if($columnDataType == 'wysiwyg') {
                $insertData[$columnDataKey]  = (!empty($insertData[$columnDataKey])) ? $insertData[$columnDataKey] : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
            }
            else if($columnDataType == 'xss_clean') {
                $insertData[$columnDataKey]  = (!empty($insertData[$columnDataKey])) ? xss_clean($insertData[$columnDataKey]) : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
            }
            else if($columnDataType == 'price') {
                $insertData[$columnDataKey]  = (!empty($insertData[$columnDataKey])) ? Str::price($insertData[$columnDataKey]) : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
            }
            else if($columnDataType == 'image') {
                $insertData[$columnDataKey]  = (isset($insertData[$columnDataKey])) ? $insertData[$columnDataKey] : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
                $insertData[$columnDataKey]  = FileHandler::handlingUrl($insertData[$columnDataKey]);
            }
            else if($columnDataType == 'file') {
                $insertData[$columnDataKey]  = (isset($insertData[$columnDataKey])) ? $insertData[$columnDataKey] : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
                $insertData[$columnDataKey]  = FileHandler::handlingUrl($insertData[$columnDataKey]);
            }
            else if($columnDataType == 'array') {

                $columnDataDefault = [];

                $insertData[$columnDataKey]  = (isset($insertData[$columnDataKey])) ? $insertData[$columnDataKey] : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);

                if(is_array($insertData[$columnDataKey]) || is_object($insertData[$columnDataKey])) {
                    $insertData[$columnDataKey] = serialize($insertData[$columnDataKey]);
                }
                else if(!Str::isSerialized($insertData[$columnDataKey])) {
                    $insertData[$columnDataKey] = serialize([]);
                }
            }
            else {
                if(function_exists($columnDataType)) {
                    $insertData[$columnDataKey]  = (isset($insertData[$columnDataKey])) ? $insertData[$columnDataKey] : ((have_posts($oldObject)) ? $oldObject->$columnDataKey : $columnDataDefault);
                    $insertData[$columnDataKey] = $columnDataType($insertData[$columnDataKey]);
                }
            }
        }

        return $insertData;
    }
}

if(!function_exists('response')) {
    function response(): Response {
        return new Response();
    }
}