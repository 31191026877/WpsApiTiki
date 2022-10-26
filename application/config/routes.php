<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

use Illuminate\Database\Capsule\Manager as DB;

Class SKD_Route {

    public $language = ['vi' => 'vi'];

    public $slug = '';

    public $routes = [];

    function __construct() {
        $language = $this->getLanguage();
        if(!empty($language)) {
            $this->language 	= unserialize($language);
            $this->language 	= array_keys($this->language);
        }
        $this->setSlug();
        $this->routeDefault();
        $this->routeAccount();
        $this->routeSlug();
        $this->routeLanguage();
        $this->routeBackend();
        return $this;
    }

    function setSlug() {

        $uri = $_SERVER['REQUEST_URI'];

        if (str_starts_with($uri, $_SERVER['SCRIPT_NAME'])) {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        }
        elseif (str_starts_with($uri, dirname($_SERVER['SCRIPT_NAME']))) {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        if (strncmp($uri, '?/', 2) === 0) {
            $uri = substr($uri, 2);
        }

        $parts = preg_split('#\?#i', $uri, 2);

        $uri   = $parts[0];

        $uri   = str_replace(array('//', '../'), '/', trim($uri, '/'));

        $slug  = explode( '/', $uri );

        if(count($slug)  && is_array($slug)) {
            if(in_array($slug[0], $this->language) !== false) {
                if(!empty($slug[1])) {
                    if($slug[1] == 'amp') {
                        $slug = (!empty($slug[2])) ? $slug[2] : 'trang-chu';
                    }
                    else {
                        $slug = $slug[1];
                    }
                }
                else $slug = 'trang-chu';
            }
            else {
                if(!empty($slug[0])) {
                    if($slug[0] == 'amp') {
                        $slug = (!empty($slug[1])) ? $slug[1] : 'trang-chu';
                    }
                    else {
                        $slug = $slug[0];
                    }
                }
                else $slug = 'trang-chu';
            }
        }

        $slug = trim($slug,'?');

        $slug = explode('?', $slug);

        $slug = array_shift($slug);

        $this->slug = $slug;
    }

    function routeDefault() {
        if($this->slug == 'admin') return false;
        $this->setRoute(URL_PRODUCT, 'frontend/products/index');
        $this->setRoute('search', 'frontend/home/search');
        $this->setRoute('close', 'frontend/home/close');
        $this->setRoute('password', 'frontend/home/password');
        $this->setRoute('404', 'frontend/home/page_404');
        $this->setRoute('webhook', 'frontend/home/webhook');
        $this->setRoute('watermark/(:any)', 'frontend/home/watermark/$1');
        $this->setRoute('page/(:any)', 'frontend/home/page/$1');
        $this->setRoute(URL_HOME, 'frontend/home/index');
        $this->setRoute(URL_HOME.'/([a-zA-Z0-9+-]+)', 'frontend/home/$1');
        $this->setRoute(URL_HOME.'/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)', 'frontend/home/$1/$2');
        $this->setRoute(URL_HOME.'/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)', 'frontend/home/$1/$2/$3');
    }

    function routeAccount() {
        if($this->slug == 'admin') return false;
        $this->setRoute('tai-khoan', 'frontend/users/index');
        $this->setRoute('tai-khoan/([a-zA-Z0-9+-]+)', 'frontend/users/index/$1');
        $this->setRoute('tai-khoan/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)', 'frontend/users/index/$1/$2');
    }

    function routeSlug() {

        if($this->slug == 'admin') return false;

        $cachePath = FCPATH.'views/cache/routes-'.$this->slug;

        if(file_exists($cachePath)) {
            $cache = @unserialize(file_get_contents($cachePath));
            if(isset($cache['data'])) { $row = $cache['data']; }
        }

        if(!isset($row)) {

            $row = DB::table('routes')->where('slug', $this->slug)->first();

            $contents = array('time' => time(), 'ttl' => TIME_CACHE, 'data'	=> $row);

            if ($fp = @fopen($cachePath, FOPEN_WRITE_CREATE_DESTRUCTIVE)) {
                flock($fp, LOCK_EX);
                fwrite($fp, serialize($contents));
                flock($fp, LOCK_UN);
                fclose($fp);
                @chmod($cachePath, 0777);
            }
        }

        if(!empty($row->slug) && !isset($this->routes[$row->slug])) {
            if( $row->callback == null && !isset($route[$row->slug]) ) {
                $this->setRoute($row->slug, $row->controller.$row->slug);
                $this->setRoute($row->slug.'/([a-zA-Z0-9+-]+)', $row->controller.$row->slug.'/$1');
                $this->setRoute($row->slug.'/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)', $row->controller.$row->slug.'/$1/$2');
                $this->setRoute('amp/'.$row->slug, $row->controller.$row->slug);
                $this->setRoute('amp/'.$row->slug.'/([a-zA-Z0-9+-]+)', $row->controller.$row->slug.'/$1');
            }
            else {
                $this->setRoute($row->slug, $row->controller.$row->callback);
                $this->setRoute($row->slug.'/([a-zA-Z0-9+-]+)', $row->controller.$row->callback.'/$1');
                $this->setRoute($row->slug.'/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)', $row->controller.$row->callback.'/$1/$2');
                $this->setRoute('amp/'.$row->slug, $row->controller.$row->callback);
                $this->setRoute('amp/'.$row->slug.'/([a-zA-Z0-9+-]+)', $row->controller.$row->callback.'/$1');
                $this->setRoute('amp/'.$row->slug.'/([a-zA-Z0-9+-]+)/([a-zA-Z0-9+-]+)', $row->controller.$row->callback.'/$1/$2');
            }
        } else {
            if(!isset($this->routes[$this->slug])) {
                $this->setRoute($this->slug, 'frontend/post/detail/'.$this->slug);
            }
        }
    }

    function routeLanguage(): void {
        if(count($this->language) >= 2) {
            $routes = $this->getRoute();
            foreach ($this->language as $key_lang => $lang) {
                foreach ($routes as $url => $controller)  {
                    $this->setRoute($lang.'/'.$url, $controller);
                }
            }
        }
    }

    function routeBackend(): void {
        $modules = ['home', 'users', 'theme' => ['menu','widgets'], 'plugins', 'page' , 'post' => ['post_categories'], 'products' => ['products_categories'], 'galleries'];
        $this->setRoute(URL_ADMIN,          'backend/home/index');
        $this->setRoute(URL_ADMIN.'/ajax',  'backend/home/ajax');
        $this->setRoute(URL_ADMIN.'/system','backend/home/system');
        $this->setRoute(URL_ADMIN.'/system/([a-zA-Z0-9+-_]+)','backend/home/system/$1');
        $this->setRoute(URL_ADMIN.'/login', 'backend/users/login');
        foreach ($modules as $key => $moduleName) {
            $moduleKey = (is_array($moduleName)) ? $key : $moduleName;
            if(is_array($moduleName)) {
                foreach ($moduleName as $moduleSub) {
                    $moduleUrl  = URL_ADMIN.'/'.str_replace('_', '-', $moduleKey).'/'.str_replace('_', '-', $moduleSub);
                    $modulePath = 'backend/'.$moduleSub;
                    $this->setRoute($moduleUrl,  $modulePath.'/index');
                    $this->setRoute($moduleUrl.'/([a-zA-Z0-9+-_]+)', $modulePath.'/$1');
                    $this->setRoute($moduleUrl.'/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)',$modulePath.'/$1/$2');
                    $this->setRoute($moduleUrl.'/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)',$modulePath.'/$1/$2/$3');
                }
            }
            $moduleUrl = URL_ADMIN.'/'.str_replace('_', '-', $moduleKey);
            $modulePath = 'backend/'.$moduleKey;
            $this->setRoute($moduleUrl,  $modulePath.'/index');
            $this->setRoute($moduleUrl.'/([a-zA-Z0-9+-_]+)',  $modulePath.'/$1');
            $this->setRoute($moduleUrl.'/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)',  $modulePath.'/$1/$2');
            $this->setRoute($moduleUrl.'/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)/([a-zA-Z0-9+-_]+)',$modulePath.'/$1/$2/$3');
        }
    }

    function setRoute($route, $controller): void {
        $this->routes[$route] = $controller;
    }

    function getRoute(): array {
        return $this->routes;
    }

    function getLanguage() {
        $cache = FCPATH.'views/cache/system';
        if(file_exists($cache)) {
            $system = @unserialize(file_get_contents($cache));
            if(isset($system['data']['language'])) return $system['data']['language'];
        }
        $system = DB::table('system')->select('option_value')->where('option_name', 'language')->first();
        return (!empty($system)) ? $system->option_value : [];
    }
}

$routeSKD = new SKD_Route();

$routePath = VIEWPATH.'theme-store/theme-setting/theme-router.php';

if(file_exists($routePath)) {
    include_once $routePath;
}

$route = $routeSKD->getRoute();

$route['404_override']                   = '';

$route['default_controller']             = 'frontend/home/index';
/* End of file routes.php */
/* Location: ./application/config/routes.php */