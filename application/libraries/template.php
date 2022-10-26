<?php
class Template {
    //template info
    public $name   		= '';
    public $label   	= '';
    public $description = '';
    public $version   	= '';
    public $author   	= '';
    public $screenshot  = '';

    //layout & views
    private $layout = '';
    private $view     = '';

    //class & function
    public  $class          = '';
    public  $method         = '';
    public  $support        = [];
    private $assets_path    = VIEWPATH;
    //File style script
    public $assets;
    //message
    private $message = [];

    function __construct($name = 'backend') {

        $ci =& get_instance();

        $this->name = $name;

        $this->info();

        $this->class   = $ci->router->fetch_class();

        $this->method  = $ci->router->fetch_method();

        $this->assets = new Asset();
    }

    //kiểm tra template có tồn tại hay không
    public function exist($name = null) {
        if($name == null) $name = $this->name;
        return @is_dir(VIEWPATH.$name) && file_exists(VIEWPATH.$name.'/config.php') ? true : false;
    }

    //set template
    public function setName($name = null) {
        //kiểm tra thư mục template có tồn tại không
        if($this->exist($name)) {
            $this->name     = $name;
            $this->assets_path 	.= $this->name.'/assets';
        }
        else {
            echo "Template don't exists!";
            die;
        }
    }

    public function getName() {
        return $this->name;
    }

    public function info($name = null) {
        if($name == null) $name = $this->name;
        if($this->exist($name)) {
            $string = file(VIEWPATH.$name.'/config.php');
            $count 	= 0;
            foreach ($string as $k => $val) {
                $val 		= Str::lower(trim($val));
                //Template name
                if(strpos($val,'template name',0) 	!== false) {
                    $this->label = trim(Str::after($val, ':'));
                    $count++;
                }
                //Template description
                if(strpos($val,'description',0) !== false) {
                    $this->description 	= trim(Str::after($val, ':'));
                    $count++;
                }
                //Template version
                if(strpos($val,'version',0) !== false) {
                    $this->version 	= trim(Str::after($val, ':'));
                    $count++;
                }
                //Template author
                if(strpos($val,'author',0) !== false) {
                    $this->author 	= trim(Str::after($val, ':'));
                    $count++;
                }

                if($count == 4) :
                    if(file_exists(VIEWPATH.$name.'/assets/screenshot.png')) {
                        $this->screenshot = 'views/'.$name.'/assets/screenshot.png';
                    }
                    break;
                endif;
            }
        }
    }
    /**
    lAYOUT
     */
    public function set_layout($layout = null) {

        $class   = $this->class;

        $method  = $this->method;

        $ci =& get_instance();

        if($layout ==  null) {

            /** FRONTEND */
            if($this->name != 'backend') {

                /* category page */
                if(!$this->is_home() && $this->method == 'index' &&  isset($ci->data['category']) ) {

                    $category = $ci->data['category'];

                    if( !empty($category->theme_layout) ) {

                        $this->layout = $category->theme_layout;
                    }
                    else {

                        if( !empty($category->slug) ) $this->layout = 'template-'.$this->class.'-'.$category->slug;

                        if(!file_exists( VIEWPATH.$this->name.'/'.$this->layout.'.php' ) && isset($category->cate_type)) $this->layout = 'template-'.$this->class.'-'.$category->cate_type;
                    }

                }

                /* single page */
                if(!$this->is_home() && $this->method == 'detail') {

                    if( isset($ci->data['object']) && have_posts($ci->data['object']) ) {

                        $object = $ci->data['object'];

                        if( !empty($object->theme_layout) ) {

                            $this->layout = $object->theme_layout;
                        }
                        else {

                            $this->layout = 'template-'.$this->class.'-'.$object->slug;

                            if(!file_exists( VIEWPATH.$this->name.'/'.$this->layout.'.php' ) && isset($object->post_type)) $this->layout = 'template-'.$this->class.'-'.$object->post_type;
                        }


                        if( !file_exists( VIEWPATH.$this->name.'/'.$this->layout.'.php' ) && isset($ci->data['category']->theme_layout) ) {

                            $category = $ci->data['category'];

                            if( !empty($category->theme_layout) ) {

                                $this->layout = $category->theme_layout;
                            }
                            else {

                                $this->layout = 'template-'.$this->class.'-'.$category->slug;

                                if(!file_exists( VIEWPATH.$this->name.'/'.$this->layout.'.php' ) && isset($category->cate_type)) $this->layout = 'template-'.$this->class.'-'.$category->cate_type;
                            }

                        }
                    }
                }
            }
        }
        //nếu layout tồn tại thì set layout
        else if(file_exists(VIEWPATH.$this->name.'/'.$layout.'.php')) {
            $this->layout = $layout;
        }

        //nếu không có layout thì lấy layout theo layout config
        if(!file_exists(VIEWPATH.$this->name.'/'.$this->layout.'.php') && $this->name != 'backend' && isset($ci->data['template']->default[$class][$method]['layout'])) {
            $this->layout  = $ci->data['template']->default[$class][$method]['layout'];
        }
        //sử dụng layout mặc định nếu không lấy được layout
        if(!file_exists(VIEWPATH.$this->name.'/'.$this->layout.'.php')) $this->layout = 'template-home';

        if(!file_exists(VIEWPATH.$this->name.'/'.$this->layout.'.php')) {
            echo notice('error','Layout <b>'.$this->layout.'</b> không tồn tại vui lòng kiểm tra lại template <b>'.$this->name.'</b>',true);
            die;
        }
    }

    public function get_layout() {
        return $this->layout;
    }
    /**
    VIEWS
     */
    public function set_view($view = null) {

        $ci =& get_instance();

        $class   = $this->class;

        $method  = $this->method;

        $default = (isset($ci->data['template']->default)) ? $ci->data['template']->default : '';

        if($view ==  null) {
            /** FRONTEND */
            if($this->name != 'backend') {

                /* category page */
                if(!$this->is_home() && $this->method == 'index' && isset($ci->data['category']) ) {

                    $category = $ci->data['category'];

                    if( !empty($category->theme_view) ) {

                        $this->view = $category->theme_view;
                    }
                    else {

                        if( !empty($category->slug) ) $this->view = $this->class.'-'.$category->slug;

                        if(!file_exists( VIEWPATH.$this->name.'/'.$this->view.'.php' ) && isset($category->cate_type)) $this->view = $this->class.'-'.$category->cate_type;
                    }
                }

                /* single page */
                if(!$this->is_home() && $this->method == 'detail') {

                    if( isset($ci->data['object']) && have_posts($ci->data['object']) ) {

                        $object = $ci->data['object'];

                        if( !empty($object->theme_view) ) {

                            $this->view = $object->theme_view;
                        }
                        else {

                            $this->view = $this->class.'-'.$object->slug;

                            if(!file_exists( VIEWPATH.$this->name.'/'.$this->view.'.php' ) && isset($object->post_type)) $this->view = $this->class.'-'.$object->post_type;
                        }

                        if( !file_exists( VIEWPATH.$this->name.'/'.$this->view.'.php' ) && isset($ci->data['category']->theme_view) ) {

                            $category = $ci->data['category'];

                            if( !empty($category->theme_view) ) {

                                $this->view = $category->theme_view;
                            }
                            else {

                                $this->view = $this->class.'-'.$category->slug;

                                if(!file_exists( VIEWPATH.$this->name.'/'.$this->view.'.php' ) && isset($category->cate_type)) $this->view = $this->class.'-'.$category->cate_type;
                            }

                        }
                    }
                    else {
                        $this->view = '404-error';
                    }
                }

                if( $this->class == 'user' ) {
                    $this->view = 'user-'.$this->method;
                }
            }
        }
        //nếu view tồn tại thì set view
        elseif(file_exists(VIEWPATH.$this->name.'/'.$view.'.php')) {
            $this->view = $view;
        }

        if(!file_exists($view.'.php')) {
            //nếu không có layout thì lấy layout theo view config
            if(!file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php') && $this->name != 'backend' && isset($default[$class][$method]['view']) && !empty($default[$class][$method]['view'])) {
                $this->view = $default[$class][$method]['view'];
            }

            if(!file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php') && file_exists(VIEWPATH.$this->name.'/'.$class.'-'.$method.'.php')) {
                $this->view = $class.'-'.$method;
            }

            //sử dụng view mặc định nếu không lấy được view
            if(!file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php')) $this->view = 'home-index';

            if(!file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php')) {
                echo notice('error','View <b>'.$this->view.'</b> không tồn tại vui lòng kiểm tra lại template <b>'.$this->name.'</b>',true);
                die;
            }
        }
        else { $this->view = $view; }
    }

    public function get_view($param = TRUE) {
        return ($param == TRUE)?$this->name.'/'.$this->view:$this->view;
    }

    public function render($view = null, $layout = null) {

        $ci =& get_instance();

        $this->set_layout($layout);

        $this->set_view($view);

        do_action( 'template_redirect' );
        /**
         * @since 3.0.8 Thêm filters template_layout_render
         */
        if(self::isAmp()) {
            $this->layout = 'amp/'.$this->layout;
            $this->view = 'amp/'.$this->view;
        }

        $template_render = $this->name.'/theme-child/'.$this->layout;

        if(!file_exists(Path::theme('theme-child/'.$this->layout.'.php'))) {
            $template_render = $this->name.'/'.$this->layout;
        }

        $template_render = apply_filters('template_layout_render', $template_render);

        if(file_exists($template_render.'.blade.php')) {
            $template_render = explode('/', trim($template_render, '/'));
            $templateFile    = array_pop($template_render);
            $template_render = implode('/', $template_render);
            $views = FCPATH.'views/'.$template_render;
            $cache = FCPATH.'views/cache';
            $blade = new BladeOne($views,$cache,BladeOne::MODE_DEBUG);
            echo $blade->run($templateFile, $ci->data);
        }
        else {
            $ci->load->view($template_render, $ci->data);
        }

        if(DEBUG == TRUE) $ci->output->enable_profiler(TRUE);
    }

    public function render_view($type = false) {

        $file_render = null;
        /**
         * @since 3.0.8 Thêm filters template_view_render
         */
        if(@file_exists(VIEWPATH.$this->name.'/theme-child/'.$this->view.'.php')) {
            $file_render = apply_filters('template_view_render', $this->name.'/theme-child/'.$this->view);
        }
        else if(@file_exists(VIEWPATH.$this->name.'/'.$this->view.'.php')) {
            $file_render = apply_filters('template_view_render', $this->name.'/'.$this->view);
        }
        else if($this->view != null) {
            $file_render = apply_filters('template_view_render', $this->view);
        }
        if($file_render != null) {
            get_instance()->load->view($file_render, get_instance()->data, $type);
        }
    }

    public function error($type = '404') {

        $this->set_layout('template-full-width');

        $this->render($type.'-error');
    }

    //xuất INCLUDE ra views
    public function render_include($views_path = null, $data = NULL, $type = false) {
        $ci =& get_instance();
        if($type == true) return $ci->load->view($this->name.'/include/'.$views_path, (have_posts($data))?$data:$ci->data, $type);
        $ci->load->view($this->name.'/include/'.$views_path, (have_posts($data))?$data:$ci->data, $type);
    }

    public function render_file($views_path = null, $args = NULL, $type = false) {

        $ci =& get_instance();

        extract($ci->data);

        if ( ! empty( $args ) && is_array( $args ) ) {
            extract( $args );
        }

        $path = VIEWPATH.$this->name.'/'.$views_path.'.php';

        if(file_exists($path)) {
            include $path;
        }
        else {
            echo 'File not found!';
            die;
        }
    }

    //check page
    public function is_page($page = 'home_index') {

        if($page == $this->class.'_'.$this->method) return true;
        return false;
    }

    public function is_home() {
        if('home_index' == $this->class.'_'.$this->method) return true;
        return false;
    }

    public function get_page() {
        return $this->class.'_'.$this->method;
    }

    //style - script
    public function get_assets() {
        return Url::base().VIEWPATH.$this->name.'/assets/';
    }

    public function getAsset($name = '') {
        $this->assets->name = (!empty($this->name)) ? $this->name : $name;
        return $this->assets;
    }

    public function minify_clear($type = '') {

        $path = FCPATH.VIEWPATH.$this->name.'/assets/'.$type;

        if($type == 'css') $minify = $path.'/styles.min.css';

        if($type == 'js')  $minify = $path.'/script.min.js';

        if(isset($minify) && file_exists($minify)) {
            unlink($minify);
            return true;
        }

        if(empty($type)) {

            $minify_css = $path.'/css/styles.min.js';

            $minify_js = $path.'/js/script.min.js';

            if(file_exists($minify_css)) unlink($minify_css);

            if(file_exists($minify_js)) unlink($minify_js);

            return true;
        }

        return false;
    }
    /**
     * MESSAGE
     * @param string $message NỘI DUNG THÔNG BÁO
     * @param string $type LOẠI THÔNG BÁO
     */
    public function set_message($message = '', $type = 'str') {

        $def = array( 'name' => 'all', 'type' => 'str', );

        if( is_array($type) ) {

            $type = array_merge($def, $type);

        }
        else {

            if( $type == 'str') $def['type'] 	= $type;

            if( $type != 'str') $def['name'] 	= $type;

            $type 			= $def;
        }

        if( $type['type'] == 'str' ) {

            $this->message[$type['name']] = $message;

        } else {

            $_SESSION['template_message'] = $message;
        }
    }

    public function get_message( $key = null, $return = false) {

        if( $key != null && isset($this->message[$key])) {

            if( $return ) return $this->message[$key];

            echo $this->message[$key];

            unset($this->message[$key]);
        }
        else {

            $mess = $this->message;

            if(isset($_SESSION['template_message'])) {

                $mess[] = $_SESSION['template_message'];

                unset($_SESSION['template_message']);
            }

            if( have_posts($mess) ) {

                if( $return ) return $mess;

                foreach ($mess as $key => $val) {

                    if(is_array($val)) show_r($val); else echo $val;
                }

            }

        }
    }

    public function getWidget($index = null) {

        $ci =& get_instance();

        if($index == null) {
            foreach ($ci->widget as $widgetKey => $widgetItem) {
                if(is_string($widgetItem)) $ci->widget[$widgetKey] = new $widgetItem();
            }
            return $ci->widget;
        }
        else {
            if(isset($ci->widget[$index])) {
                if(is_string($ci->widget[$index])) $ci->widget[$index] = new $ci->widget[$index]();
                return $ci->widget[$index];
            }
            return null;
        }
    }
    /**
    STATIC FUNCTION
     */
    static public function setMessage($args = []) {

        $message = (!empty($args['message'])) ? $args['message'] : '';

        $type    = (!empty($args['type'])) ? $args['type'] : 'str';

        $ci = &get_instance();

        $ci->template->set_message( $message, $type );
    }
    static public function displayMessage() {
        echo get_instance()->template->get_message();
    }
    static public function isPage($page = 'home_index') {
        return get_instance()->template->is_page($page);
    }
    static public function getPage() {
        return get_instance()->template->get_page();
    }
    static public function asset() {
        return get_instance()->data['template']->getAsset();
    }
    static public function setLayout($page, $layout, $view = '') {
        if(!empty($page)) {
            $page = explode('_', $page);
            $class = (!empty($page[0])) ? trim($page[0]) : '';
            $method = (!empty($page[1])) ? trim($page[1]) : '';
            if(!empty($class) && !empty($method)) {
                $ci =& get_instance();
                if(!empty($layout)) $ci->data['template']->default[$class][$method]['layout']   = $layout;
                if(!empty($view))   $ci->data['template']->default[$class][$method]['view']     = $view;
            }
        }
    }
    static public function support($class = '', $group = [], $field = []) {
        $ci =& get_instance();
        if(!empty($class)) {
            if(have_posts($group)) $ci->data['template']->support[$class]['group'] = $group;
            if(have_posts($field)) $ci->data['template']->support[$class]['field'] = $field;
        }
    }
    static public function gallerySupport($type, $object = []) {
        $ci =& get_instance();
        if(is_array($type)) {
            foreach ($type as $key => $value) {
                $ci->gallery_support[$value] = true;
            }
        }
        if(is_string($type)) {
            foreach ($object as $key => $value) {
                $ci->gallery_support[$type][$value] = true;
            }
        }
    }
    static public function galleryIsSupport() {

        $ci = &get_instance();

        $page = $ci->router->fetch_class();

        if(!isset($ci->gallery_support[$page])) return false;

        if($page == 'page') {

            if(!empty($ci->gallery_support['page'])) return true;
        }
        else if($page == 'post') {

            if(!empty($ci->gallery_support['post'][$ci->postType])) return true;
        }
        else if($page == 'post_categories') {

            if(!empty($ci->gallery_support['post_categories'][$ci->cateType])) return true;
        }
        else if(!empty($ci->gallery_support[$page])) return true;

        return false;
    }
    static public function getListLayout() {

        $ci =& get_instance();

        $layout = ['Mặc định'];

        $dir = Path::theme('', true);

        $ci->load->helper('directory');

        $layouts = directory_map($dir, FALSE, TRUE);

        if(have_posts($layouts)) {
            foreach ($layouts as $key => $path) {
                if(is_numeric($key)) {
                    $content = file($dir.'/'.$path);
                    foreach ($content as $line) {
                        $line 		= Str::lower(trim($line));
                        if(Str::startsWith($line, 'layout-name') !== false) {
                            $layout[str_replace('.php','',$path)] = trim(Str::of($line)->after(':')->title());
                            break;
                        }
                    }
                }
            }
        }

        return $layout;
    }
    static public function getListView() {

        $ci =& get_instance();

        $view = ['Mặc định'];

        $dir = Path::theme('', true);

        $ci->load->helper('directory');

        $views = directory_map($dir, FALSE, TRUE);

        if(have_posts($views)) {
            foreach ($views as $key => $path) {
                if(is_numeric($key)) {
                    $content = file($dir.'/'.$path);
                    foreach ($content as $line) {
                        $line 		= Str::lower(trim($line));
                        if(Str::startsWith($line, ['view name', 'view-name']) !== false) {
                            $view[str_replace('.php','',$path)] = trim(Str::of($line)->after(':')->title());
                            break;
                        }
                    }
                }
            }
        }

        return $view;
    }
    static public function partial($viewsPath, $args = [], $return = false) {

        $ci =& get_instance();

        extract($ci->data);

        if (!empty($args) && is_array($args)) { extract($args);}

        $extension  = '.php';

        if(str_ends_with($viewsPath, '.php') || str_ends_with($viewsPath, '.less') || str_ends_with($viewsPath, '.css')) {
            $extension = '';
        }

        $path = VIEWPATH.$ci->data['template']->name.'/theme-child/'.$viewsPath.$extension;

        if(!file_exists($path)) {
            $path = VIEWPATH.$ci->data['template']->name.'/'.$viewsPath.$extension;
        }

        if(file_exists($path)) {
            ob_start();
            include $path;
            $buffer = ob_get_contents();
            ob_end_clean();
            if($return) {
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
    static public function img($path, $alt = '', $args = []) {

        $type = (!empty($args['type'])) ? $args['type'] : SOURCE;

        $alt  = (empty($alt) && !empty($args['alt'])) ? $args['alt'] : Str::clear($alt);

        if(empty($alt)) {
            $obj = static::getData('object');
            if(!empty($obj->title)) {
                $alt = $obj->title;
            }
            else if(!empty($obj->name)) {
                $alt = $obj->name;
            }
            else if($obj = static::getData('category') !== false ) {
                if(!empty($obj->name)) { $alt = $obj->name; }
            }
        }

        if(empty($alt)) $alt = Option::get('general_label');

        $return = isset($args['return']) && $args['return'];

        $url = static::imgLink($path, $type);

        /* singe 3.0.0 */
        $url = apply_filters('get_img_url', $url, ['img' => $path, 'alt' => $alt, 'params' => $args, 'type' => $type]);

        /* singe 3.0.0 */
        $params = apply_filters('get_img_params', $args, ['img' => $path, 'alt' => $alt, 'type' => $type]);

        if(!empty($args['lazy'])) {
            $args['lazy'] = ($args['lazy'] == 'default') ? Url::base().Path::theme('assets/images/preloader.gif') : $args['lazy'];
            $lazy = $url;
            $url = $args['lazy'];
            unset($args['lazy']);
        }

        if(self::isAmp()) {
            $html = '<amp-img src="'.$url.'" alt="'.$alt.'" title="'.$alt.'" ';
        }
        else {
            $html = '<img src="'.$url.'" alt="'.$alt.'" title="'.$alt.'" ';
        }

        if(isset($lazy)) {
            $html .=' data-src="'.$lazy.'" ';
        }

        if(have_posts($args)) {
            unset($args['alt']);
            unset($args['return']);
            unset($args['type']);
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
            if(Url::isYoutube($path)) $path = 'https://img.youtube.com/vi/'.Url::getYoutubeID($path).'/0.jpg';
            $url = $path;
        }
        else if(Str::is('data:image/png;base64*', Str::limit($path, 100)) !== false) {
            $url = $path;
        }
        else {
            if(Device::isGoogleSpeed()) {
                $url = THUMBAIL.$path;
            }
            else {
                if ($type == 'source') {
                    $url = SOURCE.$path;
                }
                elseif($type == 'thumbnail') {
                    $url = THUMBAIL.$path;
                }
                elseif($type == 'medium') {
                    $url = MEDIUM.$path;
                }
                elseif($type == 'large') {
                    $url = 'uploads/large/'.$path;
                }
                elseif($type ==  'watermark') {
                    $fileType = pathinfo(SOURCE.$path,PATHINFO_EXTENSION);
                    $allowTypes = array('jpg','png','jpeg');
                    if(in_array($fileType, $allowTypes)) return Url::base().'watermark/'.$path;
                    return SOURCE.$path;
                }
                else {
                    $url = $type.'/'.$path;
                }
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

                if (file_exists($url_check)) {
                    $url = $url_check;
                }
                else {
                    $url = Admin::imgLink('no-images.jpg');
                }
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
        return Path::theme('assets/images/'.$path);
    }
    static public function getClass() {
        return get_instance()->template->class;
    }
    static public function isClass($class) {
        return $class == get_instance()->template->class;
    }
    static public function getMethod() {
        return get_instance()->template->method;
    }
    static public function isMethod($method) {
        return $method == get_instance()->template->method;
    }
    static public function getData($key) {
        $ci = get_instance();
        return (isset($ci->data[$key])) ? $ci->data[$key] : false;
    }
    static public function fonts() {
        $font_family = [
            [
                'key' 	=> 'arial,helvetica,sans-serif',
                'type' 	=> 'default',
                'load' 	=> '',
                'label' => 'Arial',
            ],
            [
                'key' 	=> 'arial,helvetica,sans-serif',
                'type' 	=> 'default',
                'load' 	=> '',
                'label' => 'Arial Black',
            ],
            [
                'key' 	=> 'courier new,courier',
                'type' 	=> 'default',
                'load' 	=> '',
                'label' => 'Courier New',
            ],
            [
                'key' 	=> 'helvetica,sans-serif',
                'type' 	=> 'default',
                'load' 	=> '',
                'label' => 'Helvetica',
            ],
            [
                'key' 	=> 'tahoma,arial,helvetica,sans-serif',
                'type' 	=> 'default',
                'load' 	=> '',
                'label' => 'Tahoma',
            ],
            [
                'key' 	=> 'times new roman,times, sans-serif',
                'type' 	=> 'default',
                'load' 	=> '',
                'label' => 'Times New Roman',
            ],
            [
                'key' 	=> 'UTMAvo,sans-serif',
                'type' 	=> 'theme',
                'load' 	=> '',
                'label' => 'UTM Avo',
            ],
            [
                'key' 	=> 'UTMAvoBold,sans-serif',
                'type' 	=> 'theme',
                'load' 	=> '',
                'label' => 'UTM Avo Bold',
            ],
            [
                'key' 	=> 'UTMCafeta,sans-serif',
                'type' 	=> 'theme',
                'load' 	=> '',
                'label' => 'UTM Cafeta',
            ],
            [
                'key' 	=> 'Roboto, Geneva, sans-serif',
                'type' 	=> 'google',
                'load' 	=> 'Roboto',
                'label' => 'Roboto',
            ],
            [
                'key' 	=> 'Roboto Condensed, Geneva, sans-serif',
                'type' 	=> 'google',
                'load' 	=> 'Roboto+Condensed',
                'label' => 'Roboto Condensed',
            ],
            [
                'key' 	=> 'Roboto Slab, Geneva, sans-serif',
                'type' 	=> 'google',
                'load' 	=> 'Roboto+Slab',
                'label' => 'Roboto Slab',
            ],
            [
                'key' 	=> 'Lobster, Geneva, sans-serif',
                'type' 	=> 'google',
                'load' 	=> 'Lobster',
                'label' => 'Lobster',
            ],
            [
                'key' 	=> 'Open Sans, Geneva, sans-serif',
                'type' 	=> 'google',
                'load' 	=> 'Open+Sans',
                'label' => 'Open Sans',
            ],
            [
                'key' 	=> 'Open Sans Condensed, Geneva, sans-serif',
                'type' 	=> 'google',
                'load' 	=> 'Open+Sans+Condensed:700|Open+Sans+Condensed:300',
                'label' => 'Open Sans Condensed',
            ],


        ];

        $fonts = Option::get('tinymce_config_font_family', $font_family);

        return (have_posts($fonts)) ? $fonts : [];
    }
    static public function breadcrumb() {

        $page = self::getPage();

        $id = 0;

        $breadcrumbTable = 'categories';

        if($page == 'post_index') {

            $category = get_object_current('category');

            if(have_posts($category)) $id = $category->id;
        }

        if($page == 'page_detail' || $page == 'post_detail') {

            $object = get_object_current('object');

            if(have_posts($object)) $id = $object->id;
        }

        $id = apply_filters('theme_breadcrumb_id_'.$page, $id);

        $id = apply_filters('theme_breadcrumb_id', $id, $page);

        $breadcrumbTable = apply_filters('theme_breadcrumb_table_'.$page, $breadcrumbTable);

        $breadcrumbTable = apply_filters('theme_breadcrumb_table', $breadcrumbTable, $page);

        $cache_id = 'breadcrumb_'.$page.'_'.$id.'_'.Language::current();

        if(CacheHandler::has($cache_id)) return CacheHandler::get($cache_id);

        $breadcrumb = [];

        $category = get_object_current('category');

        if(have_posts($category)) {

            $args = Qr::set('public', 1)->select('id', 'name', 'slug')->where('lft', '<=', $category->lft)->where('rgt', '>=', $category->rgt)->orderBy('level');

            if(!Language::isDefault()) {

                $module = self::getClass();

                if(Template::isPage('page_detail')) {
                    $module = 'page';
                }
                if(Template::isPage('post_index') || Template::isPage('post_detail')) {
                    $module = 'post_categories';
                }
                if(Template::isPage('products_index') || Template::isPage('products_detail')) {
                    $module = 'products_categories';
                }

                $args->select($breadcrumbTable.'.id', 'lg.name', $breadcrumbTable.'.slug');
                $args->join('language as lg', $breadcrumbTable.'.id', '=', 'lg.object_id');
                $args->where('lg.language', Language::current());
                $args->where('lg.object_type', $module);
            }

            $breadcrumb = model($breadcrumbTable)->gets($args);
        }

        $object = get_object_current('object');

        if(have_posts($object)) {
            $breadcrumb[] = (object)['slug' => $object->slug, 'name' => $object->title];
        }

        $breadcrumb = apply_filters('theme_breadcrumb_data_'.$page, $breadcrumb);

        $breadcrumb = apply_filters('theme_breadcrumb_data', $breadcrumb, $page);

        if(have_posts($breadcrumb)) CacheHandler::save($cache_id, $breadcrumb);

        return $breadcrumb;
    }
    static public function isAmp() {
        if(in_array(Url::segment(1), Language::listKey()) !== false) {
            if(Url::segment(2) == 'amp') return true;
        }
        else {
            if(Url::segment(1) == 'amp') return true;
        }
        return false;
    }
    static public function cssBg($background): string {
        $color = (!empty($background['color'])) ? $background['color'] : '';
        $image = (!empty($background['image'])) ? $background['image'] : '';
        $gradientUse = (empty($background['gradientUse'])) ? 0 : 1;

        //CSS
        $css = '';

        if(!empty($color)) $css .= 'background-color:'.$color.';';

        //background gradient
        if(!empty($gradientUse)) {
            $gradientColor1 = (empty($background['gradientColor1'])) ? '' : $background['gradientColor1'];
            $gradientColor2 = (empty($background['gradientColor2'])) ? '' : $background['gradientColor2'];
            $gradientType = (empty($background['gradientType'])) ? 'linear-gradient' : $background['gradientType'].'-gradient';
            $gradientPositionStart = (empty($background['gradientPositionStart'])) ? 0 : $background['gradientPositionStart'];
            if($gradientType == 'linear-gradient') {
                $gradientRadialDirection = (empty($background['gradientRadialDirection2'])) ? '180deg' : $background['gradientRadialDirection2'].'deg';
            }
            else {
                $gradientRadialDirection = 'circle at '.((empty($background['gradientRadialDirection1'])) ? 'center' : $background['gradientRadialDirection1']);
            }
            $gradientPositionEnd = (empty($background['gradientPositionEnd'])) ? 100 : $background['gradientPositionEnd'];
            $gradient = $gradientType.'('.$gradientRadialDirection.','.$gradientColor1.' '.$gradientPositionStart.'%, '.$gradientColor2.' '.$gradientPositionEnd.'%)';
        }

        //background image
        if(!empty($image)) {
            $imageSize = (!empty($background['imageSize'])) ? $background['imageSize'] : 'cover';
            $imagePosition = (!empty($background['imagePosition'])) ? $background['imagePosition'] : 'center center';
            $imageRepeat = (!empty($background['imageRepeat'])) ? $background['imageRepeat'] : 'no-repeat';
            $css .= 'background-image:url(\''.Template::imgLink($image).'\')'.((!empty($gradient)) ? ','.$gradient.';' : ';');
            $css .= 'background-size:'.$imageSize.';background-repeat: '.$imageRepeat.'; background-position: '.$imagePosition.';background-blend-mode: color-burn;';
        }
        else if(!empty($gradient)) {
            $css .= 'background:'.$gradient.';';
        }
        return $css;
    }
    static public function cssText($style, $default = []) {
        $style['txt'] = (!empty($style['txt'])) ? $style['txt'] : Arr::get($default, 'txt');
        $style['color'] = (!empty($style['color'])) ? $style['color'] : Arr::get($default, 'color');
        $style['fontWeight'] = (!empty($style['fontWeight'])) ? $style['fontWeight'] : Arr::get($default, 'fontWeight');
        $style['fontSize']   = (int)((!empty($style['fontSize'])) ? $style['fontSize'] : Arr::get($default, 'fontSize'));
        $style['fontFamily'] = (!empty($style['fontFamily'])) ? $style['fontFamily'] : Arr::get($default, 'fontFamily');
        $style['lineHeight'] = (int)(!empty($style['lineHeight'])) ? $style['lineHeight'] : Arr::get($default, 'lineHeight');
        if(empty($style['lineHeight']) && !empty($style['fontSize'])) {
            $style['lineHeight'] = $style['fontSize']+ceil($style['fontSize']*0.3);
        }
        $css = '';

        if(!empty($style['color'])) {
            $css .= 'color:'.$style['color'].';';
        }
        if(!empty($style['fontFamily'])) {
            $css .= 'font-family:'.$style['fontFamily'].';';
        }
        if(!empty($style['fontSize'])) {
            $css .= 'font-size:'.$style['fontSize'].'px;';
        }
        if(!empty($style['fontWeight'])) {
            $css .= 'font-weight:'.$style['fontWeight'].';';
        }
        if(!empty($style['lineHeight'])) {
            $css .= 'line-height:'.$style['lineHeight'].'px;';
        }

        $style['css'] = $css;

        return $style;
    }
    static public function less($css = null) {
        $parser = new Less_Parser();
        if($css != null) $parser->parse($css);
        return $parser;
    }
}

class Asset {

    public $location = [];

    public $name;

    function __construct($name = '') {
        if(empty($name)) $name = Option::get('theme_current', 'theme-store');
        $this->name = $name;
        $this->location['footer'] = new AssetPosition('footer', $name );
        $this->location['header'] = new AssetPosition('header', $name );
    }

    function location($key = '') {
        if(empty($this->location[$key])) {
            $this->location[$key] = new AssetPosition($key, $this->name );
        }
        $this->location[$key]->template = $this->name;
        return $this->location[$key];
    }
}

class AssetPosition {
    public $name;
    public $template;
    public $style   = [];
    public $script  = [];
    public $asset;
    function __construct($name = '', $template = ''){
        $this->name     = $name;
        $this->template = $template;
        $this->asset    = 'views/'.$template.'/assets';
    }
    public function add($id, $file, $args = []) {
        $minify     = false;
        $screens    = [];
        $path       = [];
        $page       = [];
        if(isset($args['minify'])) {
            $minify = (bool)$args['minify']; unset($args['minify']);
        }
        if(!empty($args['screens']) && Arr::accessible($args['screens'])) {
            $screens = $args['screens']; unset($args['screens']);
        }
        if(!empty($args['path']) && Arr::accessible($args['path'])) {
            $path = $args['path']; unset($args['path']);
        }
        if(!empty($args['page']) && Arr::accessible($args['page'])) {
            $page = $args['page']; unset($args['page']);
        }
        $extension = FileHandler::extension($file);
        if($extension == 'css') {
            if(!isset($this->style[$id])) $this->style[$id] = [];
            $this->style[$id][$file]['file']       = $file;
            $this->style[$id][$file]['minify']     = $minify;
            $this->style[$id][$file]['screens']    = $screens;
            $this->style[$id][$file]['path']       = $path;
            $this->style[$id][$file]['page']       = $page;
            $this->style[$id][$file]['attributes'] = array_merge(
                ['rel' => 'stylesheet'], $args
            );
        }
        if($extension == 'js') {
            if(!isset($this->script[$id])) $this->script[$id] = [];
            $this->script[$id][$file]['file']      = $file;
            $this->script[$id][$file]['minify']    = $minify;
            $this->script[$id][$file]['screens']   = $screens;
            $this->script[$id][$file]['path']      = $path;
            $this->script[$id][$file]['page']      = $page;
            $this->script[$id][$file]['attributes'] = array_merge(
                ['type' => 'text/javascript'], $args
            );
        }
        return $this;
    }
    public function getAttributes($attributes) {
        $attr = '';
        foreach ($attributes as $key => $value) {
            if(is_string($value)) $attr .= ' '.$key .'="'.$value.'"';
        }
        return $attr;
    }

    public function styles() {
        $minify = [];
        $styles = [];
        foreach ($this->style as $id => $group) {
            foreach ($group as $key => $file) {
                if ($file['minify'] == true && file_exists($file['file'])) {
                    $minify[$key] = $file;
                }
                else {
                    if(!have_posts($file['page']) || in_array(Template::getPage(), $file['page']) === true ) {
                        $styles[$key] = $file;
                    }
                }
            }
            unset($this->style[$id]);
        }
        echo $this->renderStyles($styles);
        echo $this->renderStylesMinify($minify);
        return true;
    }
    public function renderStylesMinify($styles = []) {
        if(!have_posts($styles) && !have_posts($this->style)) return '';
        if(!have_posts($styles)) $styles = $this->style;
        $css = '';
        $css .= '<style type="text/css">';
        $css .= $this->minify($styles);
        $css .= '</style>';
        return $css;
    }
    public function renderStyles($styles = []) {
        if(!have_posts($styles) && !have_posts($this->style)) return '';
        if(!have_posts($styles)) $styles = $this->style;
        $css = '';
        foreach ($styles as $id => $file) {
            $attr   = $this->getAttributes($file['attributes']);
            $css    .= '<link href="'.$file['file'].'"'.$attr.'>';
        }
        return $css;
    }
    public function scripts() {
        $minify = [];
        $scripts = [];
        foreach ($this->script as $id => $group) {
            foreach ($group as $key => $file) {
                if ($file['minify'] == true && file_exists($file['file'])) {
                    $minify[$key] = $file;
                }
                else {
                    if(!have_posts($file['page']) || in_array(Template::getPage(), $file['page']) === true ) {
                        $scripts[$key] = $file;
                    }
                }
            }
            unset($this->script[$id]);
        }
        echo $this->renderScripts($scripts);
        echo $this->renderScriptsMinify($minify);
        return true;
    }
    public function renderScriptsMinify($scripts = []) {
        $review = '';
        if(!have_posts($scripts) && !have_posts($this->script)) return '';
        if(!have_posts($scripts)) $scripts = $this->script;
        if(Request::Get('builder') == 'review') $review = '?v='.time();
        return '<script type="text/javascript" defer src="'.$this->minify($scripts, 'js').$review.'" charset="utf-8"></script>';
    }
    public function renderScripts($scripts = []) {

        if(!have_posts($scripts) && !have_posts($this->script)) return '';

        if(!have_posts($scripts)) $scripts = $this->script;

        $script = '';

        foreach ($scripts as $id => $file) {

            $attr   = $this->getAttributes($file['attributes']);

            $script    .= '<script src="'.$file['file'].'"'.$attr.'></script>';

        }

        return $script;
    }

    public function minify($files, $type = 'css') {

        if(!have_posts($files)) $files = $this->style;

        $this->asset    = 'views/'.$this->template.'/assets';

        $path           = VIEWPATH.$this->template.'/assets/';

        $file_min       = ($type == 'css') ? 'styles.min.css' : 'script.min.js';

        $path_min       = $path.$type.'/'.$file_min;

        $createMinFile = false;

        if(!file_exists($path_min)) $createMinFile = true;

        $files_temp = [];
        foreach ($files as $key => &$value) {
            $files_temp[$key]['path'] = (!empty($value['path']) && Arr::accessible($value['path']))? $value['path'] : [];
            $files_temp[$key]['file'] = Str::before(str_replace(Url::base(),'',$value['file']),'?v=');
        }
        $files = $files_temp;
        if(!$createMinFile) {
            $btime = filemtime($path_min);
            foreach ($files as $key => $value) {
                $time = filemtime($value['file']);
                if($time > $btime){
                    unlink($path_min);
                    $createMinFile = true;
                    break;
                }
            }
        }

        if($type == 'css') {
            return $this->minifyCss($files, $createMinFile, $path_min, $file_min);
        }
        else {
            return $this->minifyJs($files, $createMinFile, $path_min, $file_min);
        }
    }
    public function minifyCss($files, $createMinFile, $path_min, $file_min) {
        if($createMinFile) {
            $ci = &get_instance();
            $ci->load->library('skd_minify', ['css_dir' => $this->asset.'/css']);
            $ci->skd_minify->css($files);
            $ci->skd_minify->deploy_css(TRUE, $file_min);
        }
        $sContentCss = str_replace('../fonts',$this->asset.'/fonts', concatenate_files(array($path_min)));
        $sContentCss = str_replace('../images',$this->asset.'/images', $sContentCss);
        return $sContentCss;
    }
    public function minifyJs($files, $createMinFile, $path_min, $file_min) {
        if($createMinFile) {
            $ci = &get_instance();
            $ci->load->library('skd_minify', ['js_dir' => $this->asset.'/js']);
            $ci->skd_minify->js($files);
            $ci->skd_minify->deploy_js(TRUE, $file_min);
        }
        return $path_min;
    }
}