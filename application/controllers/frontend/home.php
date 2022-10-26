<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct() {
		parent::__construct('frontend');
		$this->data['module'] 		= 'home';
	}

	public function index() {
        $this->template->render();
	}

	public function close() {
		
		if(@file_exists(VIEWPATH.$this->template->name.'/cms-close.php'))  {
			$this->load->view($this->template->name.'/cms-close.php', $this->data);
		}
		else {
			$this->load->view('backend/cms-close.php', $this->data);
		}
	}

	public function password() {
		if(Request::post()) {

			$password = Str::clear(Request::post('password'));

			$cms_password = option::get('cms_password');

			if($password == $cms_password) {

				$_SESSION['cms_close_password'] = true;

				redirect();
			}
			else {

				$this->template->set_message(notice('error', 'Mật khẩu đăng nhập không chính xác.'));
			}
		}
		
		if(@file_exists(VIEWPATH.$this->template->name.'/cms-password.php'))
		{
			$this->load->view($this->template->name.'/cms-password.php', $this->data);
		}
		else {
			$this->load->view('backend/cms-password.php', $this->data);
		}
	}

	public function search() {

		$type 		= Str::clear(Request::get('type'));

		$keyword 	= Str::clear(Request::get('keyword'));

		$objects 	= [];

		if( $type == '' ) $type = 'post';

		$postType = Taxonomy::getPost( $type );

		if(have_posts($postType)) {
			$objects = Posts::gets(Qr::set('public', 1)->where('trash', 0)->where('post_type', $type)->where('title', 'like', '%'.$keyword.'%'));
		}

		$this->data['objects'] = apply_filters( 'get_search_data', $objects, $type, $keyword );

		$this->template->render();
	}

	public function page_404() {
		$this->template->error('404');
	}

    public function webhook() {

	    $connect = Request::get('connect');

        if ($connect == Option::get('api_secret_key')) {

            do_action('cms_webhook');
        }
        else {

            $this->template->error('404');
        }
    }

	public function page( $callback = '' ) {
		if(function_exists($callback)) {
            echo call_user_func( $callback, $this, model('home'));
        }
	}
}