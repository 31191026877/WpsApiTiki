<?php defined('BASEPATH') OR exit('No direct script access allowed');

class page extends MY_Controller {

	function __construct() {
		parent::__construct('frontend');
	}

	public function detail($slug = '') {
        $args  = apply_filters('page_controllers_detail_args', Qr::set('slug', $slug)->select('id', 'title', 'slug', 'excerpt', 'content', 'image', 'theme_layout', 'theme_view', 'seo_title', 'seo_description', 'seo_keywords', 'trash'));
        $this->data['object']    = apply_filters('page_controllers_detail_objects', Pages::get($args),  $args );
		$this->template->render();
	}
}