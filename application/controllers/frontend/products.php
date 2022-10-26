<?php defined('BASEPATH') OR exit('No direct script access allowed');

class products extends MY_Controller {

	function __construct() {
		parent::__construct('frontend');
	}


	/*==================== DISPLAY ================*/
	public function index($slug = '') {
		do_action('controllers_products_index', Str::clear($slug) );
		$this->template->render();
	}

	public function detail($slug = '')
	{
		do_action('controllers_products_detail', Str::clear($slug) );
		if(have_posts($this->data['object'])) {
			$this->template->render();
		}
		else $this->template->error('404');
	}
}