<?php 
require_once(APPPATH . 'libraries/minify/src/Minify.php');
require_once(APPPATH . 'libraries/minify/src/CSS.php');
require_once(APPPATH . 'libraries/minify/src/JS.php');
require_once(APPPATH . 'libraries/minify/src/Exception.php');
require_once(APPPATH . 'libraries/minify/src/Exceptions/BasicException.php');
require_once(APPPATH . 'libraries/minify/src/Exceptions/FileImportException.php');
require_once(APPPATH . 'libraries/minify/src/Exceptions/IOException.php');
require_once(APPPATH . 'libraries/minify/src/ConverterInterface.php');
require_once(APPPATH . 'libraries/minify/src/Converter.php');
require_once(APPPATH . 'libraries/minify/src/NoConverter.php');


use MatthiasMullie\Minify;

defined('BASEPATH') OR exit('No direct script access allowed');

class skd_minify
{
	/**
	 * CodeIgniter global.
	 *
	 * @var object
	 */
	protected $ci;

	/**
	 * Css files array.
	 *
	 * @var array
	 */
	protected $css_array = [];

	/**
	 * Js files array.
	 *
	 * @var array
	 */
	protected $js_array = [];
	/**
	 * Css dir.
	 *
	 * @var string
	 */
	public $css_dir = 'views/theme-store/assets/css';
	

	/**
	 * Js dir.
	 *
	 * @var string
	 */
	public $js_dir = 'views/theme-store/assets/js';

	/**
	 * Output css file name.
	 *
	 * @var string
	 */
	public $css_file = 'styles.css';

	/**
	 * Output js file name.
	 *
	 * @var string
	 */
	public $js_file = 'scripts.js';

	/**
	 * Automatic file names.
	 *
	 * @var bool
	 */
	public $auto_names = FALSE;

	/**
	 * Compress files or not.
	 *
	 * @var bool
	 */
	public $compress = TRUE;

	/**
	 * Compression engines.
	 *
	 * @var array
	 */
	public $compression_engine = array('css' => 'minify', 'js' => 'closurecompiler');

	/**
	 * Css file name with path.
	 *
	 * @var string
	 */
	private $_css_file = '';

	/**
	 * Js file name with path.
	 *
	 * @var string
	 */
	private $_js_file = '';

	/**
	 * Last modification.
	 *
	 * @var array
	 */
	private $_lmod = array('css' => 0, 'js' => 0);

	/**
	 * Constructor
	 *
	 * @param array $config Config array
	 */
	public function __construct($config = []) {
		$this->ci = get_instance();
		if(!empty($config['css_dir'])) $this->css_dir = $config['css_dir'];
		if(!empty($config['js_dir'])) $this->js_dir = $config['js_dir'];
		$this->compression_engine = array(
			'css' => 'minify', // minify || cssmin
			'js'  => 'jsmin' // jsmin || closurecompiler || jsminplus
		);
	}
	//--------------------------------------------------------------------
	/**
	 * Declare css files list
	 *
	 * @param mixed $css   File or files names
	 * @param bool  $group Set group for files
	 *
	 * @return void
	 */
	public function css($css, $group = 'default') {
		if (is_array($css)) {
			$this->css_array[$group] = $css;
		}
		else  {
			$this->css_array[$group] = array_map('trim', explode(',', $css));
		}
		return $this;
	}

	/**
	 * Declare js files list
	 *
	 * @param mixed $js    File or files names
	 * @param bool  $group Set group for files
	 *
	 * @return void
	 */
	public function js($js, $group = 'default') {
		if (is_array($js)) {
			$this->js_array[$group] = $js;
		}
		else  {
			$this->js_array[$group] = array_map('trim', explode(',', $js));
		}

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Declare css files list
	 *
	 * @param mixed $css   File or files names
	 * @param bool  $group Set group for files
	 *
	 * @return void
	 */
	public function add_css($css, $group = 'default') {
		if (!isset($this->css_array[$group])) {
			$this->css_array[$group] = [];
		}

		if (is_array($css)) {
			$this->css_array[$group] = array_unique(array_merge($this->css_array[$group], $css));
		}
		else  {
			$this->css_array[$group] = array_unique(array_merge($this->css_array[$group], array_map('trim', explode(',', $css))));
		}
		return $this;
	}
	//--------------------------------------------------------------------

	/**
	 * Declare js files list
	 *
	 * @param mixed $js    File or files names
	 * @param bool  $group Set group for files
	 *
	 * @return void
	 */
	public function add_js($js, $group = 'default') {
		if ( ! isset($this->js_array[$group])) {
			$this->js_array[$group] = [];
		}
		if (is_array($js)) {
			$this->js_array[$group] = array_unique(array_merge($this->js_array[$group], $js));
		}
		else  {
			$this->js_array[$group] = array_unique(array_merge($this->js_array[$group], array_map('trim', explode(',', $js))));
		}
		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Deploy and minify CSS
	 *
	 * @param bool $force     Force to rewrite file
	 * @param null $file_name File name to create
	 * @param null $group     Group name
	 *
	 * @return string
	 */
	public function deploy_css($force = TRUE, $file_name = NULL, $group = NULL) {
		$return = '';
		if (is_null($file_name)) {
			$file_name = $this->css_file;
		}
		if (is_null($group)) {
			foreach ($this->css_array as $group_name => $group_array) {
				$return .= $this->_deploy_css($force, $file_name, $group_name) . PHP_EOL;
			}
		}
		else {
			$return .= $this->_deploy_css($force, $file_name, $group);
		}
		return $return;
	}
	//--------------------------------------------------------------------
	/**
	 * Deploy and minify js
	 *
	 * @param bool $force     Force rewriting js file
	 * @param null $file_name File name
	 * @param null $group     Group name
	 *
	 * @return string
	 */
	public function deploy_js($force = FALSE, $file_name = NULL, $group = NULL) {
		$return = '';

		if (is_null($file_name))
		{
			$file_name = $this->js_file;
		}

		if (is_null($group))
		{
			foreach ($this->js_array as $group_name => $group_array)
			{
				$return .= $this->_deploy_js($force, $file_name, $group_name) . PHP_EOL;
			}
		}
		else
		{
			$return .= $this->_deploy_js($force, $file_name, $group);
		}

		return $return;
	}

	//--------------------------------------------------------------------

	/**
	 * Build and minify CSS
	 *
	 * @param bool $force     Force to rewrite file
	 * @param null $file_name File name to create
	 * @param null $group     Group name
	 *
	 * @return string
	 */
	private function _deploy_css($force = TRUE, $file_name = NULL, $group = NULL) {
		if ($this->auto_names)
		{
			$file_name = md5(serialize($this->css_array[$group])) . '.css';
		}
		else
		{
			$file_name = ($group === 'default') ? $file_name : $group . '_' . $file_name;
		}
	
		$this->_set('css_file', $file_name);

		$this->_scan_files('css', $force, $group);

		return '<link href="' . Url::base($this->_css_file) . '" rel="stylesheet" type="text/css" />';
	}

	//--------------------------------------------------------------------

	/**
	 * Build and minify js
	 *
	 * @param bool $force     Force rewriting js file
	 * @param null $file_name File name
	 * @param null $group     Group name
	 *
	 * @return string
	 */
	private function _deploy_js($force = FALSE, $file_name = NULL, $group = NULL) {
		if ($this->auto_names) {
			$file_name = md5(serialize($this->js_array[$group])) . '.js';
		}
		else {
			$file_name = ($group === 'default') ? $file_name : $group . '_' . $file_name;
		}

		$this->_set('js_file', $file_name);

		$this->_scan_files('js', $force, $group);

		return '<script type="text/javascript" src="' . base_url($this->_js_file) . '"></script>';
	}

	//--------------------------------------------------------------------

	/**
	 * construct js_file and css_file
	 *
	 * @param string $name  File type
	 * @param string $value File name
	 *
	 * @return void
	 */
	private function _set($name, $value)
	{
		switch ($name) {
			case 'js_file':
				if ($this->compress){
					if ( ! preg_match("/\.min\.js$/", $value))  {
						$value = str_replace('.js', '.min.js', $value);
					}
					$this->js_file = $value;
				}
				$this->_js_file = $this->js_dir . '/' . $value;
				if (!file_exists($this->_js_file) && ! touch($this->_js_file)) {
					throw new Exception('Can not create file ' . $this->_js_file);
				}
				else {
					$this->_lmod['js'] = filemtime($this->_js_file);
				}
				break;
			case 'css_file':
				if ($this->compress) {
					if(!preg_match("/\.min\.css$/", $value))  {
						$value = str_replace('.css', '.min.css', $value);
					}
					$this->css_file = $value;
				}
				$this->_css_file = $this->css_dir . '/' . $value;
				if(!file_exists($this->_css_file) && ! touch($this->_css_file)) {
					throw new Exception('Can not create file ' . $this->_css_file);
				}
				else {
					$this->_lmod['css'] = filemtime($this->_css_file);
				}
				break;
		}
	}


	/**
	 * scan CSS directory and look for changes
	 *
	 * @param string $type  Type (css | js)
	 * @param bool   $force Rewrite no mather what
	 * @param string $group Group name
	 */
	private function _scan_files($type, $force, $group) {
		switch ($type){
			case 'css':
				$files_array = $this->css_array[$group];
				$directory   = $this->css_dir;
				$out_file    = $this->_css_file;
				break;
			case 'js':
				$files_array = $this->js_array[$group];
				$directory   = $this->js_dir;
				$out_file    = $this->_js_file;
		}
		// if multiple files
		if (is_array($files_array))
		{
			$compile = FALSE;

			foreach ($files_array as $file)
			{
				$filename =  $file['file'];

				if (file_exists($filename))
				{
					if (filemtime($filename) > $this->_lmod[$type])
					{
						$compile = TRUE;
					}
				}
				else
				{
					throw new Exception('File ' . $filename . ' is missing');
				}
			}

			// check if this is init build
			if (file_exists($out_file) && filesize($out_file) === 0)
			{
				$force = TRUE;
			}

			if ($compile OR $force)
			{
				$this->_concat_files($files_array, $directory, $out_file, $type);
			}
		}
	}

	//--------------------------------------------------------------------

	/**
	 * add merge files
	 *
	 * @param string $file_array Input file array
	 * @param string $directory  Directory
	 * @param string $out_file   Output file
	 *
	 * @return void
	 */
	private function _concat_files($file_array, $directory, $out_file, $type) {

		if ($fh = fopen($out_file, 'w')) {

			foreach ($file_array as $file) {

				$file_name = $file['file'];

				$handle    = fopen($file_name, 'r');

                $contents = '';

                if(filesize($file_name) != 0) $contents  = fread($handle, filesize($file_name));

				if(have_posts($file['path'])) {

					foreach ($file['path'] as $folder => $path_folder) {

						if($folder == 'all') {
							$contents = str_replace('../fonts', $path_folder.'/'.'fonts', $contents);
                            $contents = str_replace('./fonts', $path_folder.'/'.'fonts', $contents);
							$contents = str_replace('../webfonts', $path_folder.'/'.'webfonts', $contents);
                            $contents = str_replace('./webfonts', $path_folder.'/'.'webfonts', $contents);
							$contents = str_replace('../images', $path_folder.'/'.'images', $contents);
							$contents = str_replace('./images', $path_folder.'/'.'images', $contents);
						}
						else {
						    $contents = str_replace('../'.$folder, $path_folder.'/'.$folder, $contents);
                            $contents = str_replace('./'.$folder, $path_folder.'/'.$folder, $contents);
                        }
					}
				}

				fclose($handle);

				fwrite($fh, $contents);
			}

			if($type == 'js') {
				if(method_exists('Theme_Style','renderJs')) {
					fwrite($fh, Theme_Style::renderJs(false));
				}
                else if(function_exists('theme_custom_script')) {
                    fwrite($fh, theme_custom_script(false));
                }
			}

			fclose($fh);
		}
		else {
			throw new Exception('Can\'t write to ' . $out_file);
		}

		if ($this->compress) {

			// read output file contest (already concated)
			$handle   = fopen($out_file, 'r');

			$contents = fread($handle, filesize($out_file));

			fclose($handle);

			// recreate file
			$handle = fopen($out_file, 'w');

			if (preg_match("/.css$/i", $out_file)) {
				$engine = '_' . $this->compression_engine['css'];
			}

			if (preg_match("/.js$/i", $out_file)) {
				$engine = '_' . $this->compression_engine['js'];
			}

			// call function name to compress file
			fwrite($handle, call_user_func(array($this, $engine), $contents));

			fclose($handle);
		}
	}

    public function _jsmin($data)  {
		$minifier = new Minify\JS($data);
		return $minifier->minify();
	}

	public function _minify($data) {
		$minifier = new Minify\CSS($data);
		return $minifier->minify();
	}

	//--------------------------------------------------------------------

	/**
	 * Perform config checks
	 *
	 * @return void
	 */
	private function _config_checks()
	{

		if (empty($this->css_dir)) {
			throw new Exception('CSS directory must be set');
		}

		if (empty($this->js_dir)) {
			throw new Exception('JS directory must be set');
		}

		if(!$this->auto_names) {
			if (empty($this->css_file)) {
				throw new Exception('CSS file name can\'t be empty');
			}
			if (empty($this->js_file)) {
				throw new Exception('JS file name can\'t be empty');
			}
		}

		if ($this->compress) {
			if (!isset($this->compression_engine['css']) OR empty($this->compression_engine['css'])) {
				throw new Exception('Compression engine for CSS is required');
			}

			if (!isset($this->compression_engine['js']) OR empty($this->compression_engine['js'])) {
				throw new Exception('Compression engine for JS is required');
			}
		}
	}
}
/* End of file Minify.php */
/* Location: ./libraries/Minify.php */