<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Galleries extends MY_Controller {

    function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->data['galleries'] = Gallery::gets();
		$this->template->render();
	}
}