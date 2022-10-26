<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class plugin {

	public $name;

	public $label   	= '';

	public $class   	= '';

	public $description = '';

	public $version   	= '';

    public $author   	= '';

    public $screenshot  = '';

    public $active 		= 0;

    function __construct($name = NULL) {

		$this->dir 	= VIEWPATH.'plugins';

		$this->name = $name;

		$this->info();

		if($this->isActive($this->name)) $this->active = 1;
	}

	public function is_plugin($name = null) {
		if($name == null) $name = $this->name;
		$dir  = FCPATH.$this->dir;
		return @is_dir($dir.'/'.$name) && file_exists($dir.'/'.$name.'/'.$name.'.php') ? true : false;
	}

	/**
	@ kiểm tra plugin name đã cài đặt hay chưa
	*/
	public function is_setup($name = '') {
		if($name == null) $name = $this->name;
		if(!isset(get_instance()->plugins['active']) || !have_posts(get_instance()->plugins['active'])) return false;
		$key = array_keys(get_instance()->plugins['active']);
		if(in_array($name, $key) !== false) return true;
		return false;
	}

	public function info($name = '') {
		if($name == null) $name = $this->name;
		if($this->is_plugin($name)) {
			$dir 			= FCPATH.$this->dir.'/'.$name;
			$plugin_info 	= file($dir.'/'.$name.'.php');
			$count = 0;
			foreach ($plugin_info as $k => $val) {
				$val 		= trim($val);
				$pos_start  = stripos($val,':')+1;
				$pos_end    = strlen($val);
				//plugin name
				if(strpos($val,'Plugin Name',0) 	!== false ||
					strpos($val,'plugin Name',0) 	!== false ||
					strpos($val,'Plugin name',0) 	!== false ||
					strpos($val,'plugin name',0) 	!== false) {
					$this->label 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//plugin class
				if(strpos($val,'Plugin Class',0) 	!== false ||
					strpos($val,'plugin Class',0) 	!== false ||
					strpos($val,'Plugin class',0) 	!== false ||
					strpos($val,'plugin class',0) 	!== false) {
					$this->class = trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//plugin description
				if(strpos($val,'Description',0) 	!== false ||
					strpos($val,'description',0) 	!== false) {
					$this->description 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//plugin version
				if(strpos($val,'Version',0) 	!== false ||
				strpos($val,'version',0) 	!== false) {
					$this->version 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}
				//plugin author
				if(strpos($val,'Author',0) 	!== false ||
				strpos($val,'author',0) 	!== false) {
					$this->author 	= trim(substr($val, $pos_start, $pos_end));
					$count++;
				}

				if($count == 5) :
					if(file_exists($dir.'/screenshot.png')) {
						$this->screenshot = $this->dir.'/'.$name.'/screenshot.png';
					}
					break;
				endif;
			}
		}
	}

	public function get_path($name = '') {
		return $this->dir.'/'.$name.'/';
	}

    public function include($name = null) {
        if($name == null) $name = $this->name;
        require_once($this->dir.'/'.$name.'/'.$name.'.php');
    }

	public function load() {
	    $ci =& get_instance();
		if(have_posts($ci->plugins['active'])) {
			foreach ($ci->plugins['active'] as $key => $active) {
				if($this->is_plugin($key) && $active == true) {
                    if($key == null) $key = $this->name;
                    $pluginPath = $this->dir.'/'.$key.'/'.$key.'.php';
                    if(file_exists($pluginPath)) require_once($pluginPath);
                }
				else unset($ci->plugins['active'][$key]);
			}
		}
	}

    static public function gets($name = null) {
        $ci =& get_instance();
        $info = [];
        if(empty($plugin_name)) {
            $ci->load->helper('directory');
            $path 		= FCPATH.$ci->plugin->dir;
            $plugins 	= directory_map($path,true);
            $plugin 	= null;
            foreach ($plugins as $key => $plugin_name) {
                $pl = new plugin($plugin_name);
                if($pl->is_plugin($plugin_name) == false) continue;
                $info[$plugin_name]['name']        = $pl->name;
                $info[$plugin_name]['description'] = $pl->description;
                $info[$plugin_name]['version']     = $pl->version;
                $info[$plugin_name]['author']      = $pl->author;
                $info[$plugin_name]['active']      = $pl->active;
                $info[$plugin_name]['label']       = $pl->label;
            }
        }
        else {
            $pl = new plugin( $plugin_name );
            if($pl->is_plugin($plugin_name) == false) return $info;
            $info['name'] 			= $pl->name;
            $info['description'] 	= $pl->description;
            $info['version'] 		= $pl->version;
            $info['author'] 		= $pl->author;
            $info['active'] 		= $pl->active;
            $info['label'] 			= $pl->label;
        }
        return $info;
    }

    static public function has($name = null) {

	    if(empty($name)) return false;

        $dir  = trim(Path::plugin(''), '/');

        return @is_dir($dir.'/'.$name) && file_exists($dir.'/'.$name.'/'.$name.'.php') ? true : false;
    }

    static public function download($pl) {

        $url = $pl->file;

        $dir = Path::plugin();

        $temp_filename = basename( $url );

        $temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

        $temp_filename  = $dir . $temp_filename . '.zip';

        $headers 		= response()->getHeaders($url);

        if ($headers['http_code'] === 200) {

            if (response()->download($url, $temp_filename)) {
                return true;
            }
        }

        return false;
    }

    static public function extract($pl) {

        $url = $pl->file;

        $dir = Path::plugin();

        $temp_filename = basename( $url );

        $temp_filename = preg_replace( '|\.[^.]*$|', '', $temp_filename );

        $temp_filename  = $dir . $temp_filename . '.zip';

        $zip = new ZipArchive;

        if ($zip->open($temp_filename) === TRUE) {

            $zip->extractTo( $dir );

            $zip->close();

            unlink( $temp_filename );

            return true;

        } else {

            return false;
        }
    }

    static public function partial( $plugin_name, $template_path = '' , $args = '', $return = false) {
        $ci =& get_instance();
        extract($ci->data);
        if (!empty( $args ) && is_array( $args ) ) {
            extract( $args );
        }
        $path = VIEWPATH.$ci->data['template']->name.'/'.$plugin_name.'/'.$template_path.'.php';
        if(!file_exists($path))  $path 	= $ci->plugin->dir.'/'.$plugin_name.'/template/'.$template_path.'.php';
        if(!file_exists($path))  $path 	= $ci->plugin->dir.'/'.$plugin_name.'/'.$template_path.'.php';
        if(!file_exists($path)) {
            echo notice('error', $path);
        }
        ob_start();
        if(file_exists($path)) {
            include $path;
        }
        if ($return === true) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }
        ob_end_flush();
    }

    static public function isActive($name = '') {

	    if(empty($name)) return false;

        if(!isset(get_instance()->plugins['active']) || !have_posts(get_instance()->plugins['active'])) return false;

        $key = array_keys(get_instance()->plugins['active']);

        if(in_array($name, $key) !== false) return true;

        return false;
    }
}