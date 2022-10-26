<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax {

    static public function getFunction($action) {
        if(is_string($action)) return Str::clear($action);
        return false;
    }

    public static function client($action) {
        $ci =& get_instance();
        $function = self::getFunction($action);
        if(!self::isRegister($function))  {
            $ci->ajax['nopriv'][$function] = $action;
        }
    }

    public static function login($action) {
        $ci =& get_instance();
        $function = self::getFunction($action);
        if(!self::isRegister($function))  {
            $ci->ajax['login'][$function] = $action;
        }
    }

    public static function admin($action) {
        $ci =& get_instance();
        $function = self::getFunction($action);
        if(!self::isRegister($function))  {
            $ci->ajax['admin'][$function] = $action;
        }
    }

    public static function getClient($function) {
        if(empty($function)) return false;
        if(self::isRegisterClient($function))  {
            return get_instance()->ajax['nopriv'][$function];
        }
        return false;
    }

    public static function getLogin($function) {
        if(empty($function)) return false;
        if(self::isRegisterLogin($function))  {
            return get_instance()->ajax['login'][$function];
        }
        return false;
    }

    public static function getAdmin($function) {
        if(empty($function)) return false;
        if(self::isRegisterAdmin($function))  {
            return get_instance()->ajax['admin'][$function];
        }
        return false;
    }

    public static function isRegister($action, $type = '') {
        if($type == 'client') return Ajax::isRegisterClient($action);
        if($type == 'login') return Ajax::isRegisterLogin($action);
        if($type == 'admin') return Ajax::isRegisterAdmin($action);
        if(empty($type)) {
            $ci =& get_instance();
            if(!empty($ci->ajax['nopriv'][$action]) !== false) return true;
            if(!empty($ci->ajax['login'][$action]) !== false) return true;
            if(!empty($ci->ajax['admin'][$action]) !== false) return true;
        }
        return false;
    }

    public static function isRegisterClient($action) {
        $ci =& get_instance();
        return !empty($ci->ajax['nopriv'][$action]);
    }

    public static function isRegisterLogin($action) {
        $ci =& get_instance();
        return !empty($ci->ajax['login'][$action]);
    }

    public static function isRegisterAdmin($action) {
        $ci =& get_instance();
        return !empty($ci->ajax['admin'][$action]);
    }

    public static function remove($action) {

        $ci =& get_instance();

        //kiểm tra function có trong list chưa
        $key = array_search($action,  $ci->ajax['nopriv']);

        if( $key !== false) unset($ci->ajax['nopriv'][$key]);

        $key = array_search($action,  $ci->ajax['login']);

        if( $key !== false) unset($ci->ajax['login'][$key]);

        $key = array_search($action,  $ci->ajax['admin']);

        if( $key !== false) unset($ci->ajax['admin'][$key]);
    }
}