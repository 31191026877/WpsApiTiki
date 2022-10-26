<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*---------------------------
 * Kiểm tra dữ liệu dạng object, array
 *---------------------------*/
if(!function_exists('have_posts')) {
	function have_posts($object, $type = null): bool {
        if($object instanceof Illuminate\Support\Collection) {
            if(!$object->isEmpty()) {
                return true;
            }
            return false;
        }
		if((is_array($object) || is_object($object)) && count((array)$object)) {
            if($type == 'array') return is_array($object);
            if($type == 'object') return is_object($object);
            return true;
		}
		return false;
	}
}
// ------------------------------------------------------------------------
/**
 * CodeIgniter Security Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/security_helper.html
 */
// ------------------------------------------------------------------------
/**
 * XSS Filtering
 *
 * @access	public
 * @param	string
 * @param	bool	whether or not the content is an image file
 * @return	string
 */
if (!function_exists('xss_clean')) {
    function xss_clean($str, $is_image = FALSE) {
        return get_instance()->security->xss_clean($str, $is_image);
    }
}
// ------------------------------------------------------------------------
/**
 * Sanitize Filename
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('sanitize_filename')) {
    function sanitize_filename($filename){
        return get_instance()->security->sanitize_filename($filename);
    }
}
// --------------------------------------------------------------------
/**
 * Hash encode a string
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('do_hash')) {
    function do_hash($str, $type = 'sha1') {
        if ($type == 'sha1') {
            return sha1($str);
        }
        else {
            return md5($str);
        }
    }
}
// ------------------------------------------------------------------------
/**
 * Strip Image Tags
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('strip_image_tags')) {
    function strip_image_tags($str){
        $str = preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
        return preg_replace("#<img\s+.*?src\s*=\s*(.+?).*?\>#", "\\1", $str);
    }
}
// ------------------------------------------------------------------------
/**
 * Convert PHP tags to entities
 *
 * @access	public
 * @param	string
 * @return	string
 */
if (!function_exists('encode_php_tags')) {
    function encode_php_tags($str) {
        return str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);
    }
}

