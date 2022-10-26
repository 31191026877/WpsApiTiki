<?php defined('BASEPATH') OR exit('No direct script access allowed');

class widgets extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->data['group'] = 'theme';
	}

	public function index() {
		$this->template->render();
	}
}