<?php defined('BASEPATH') OR exit('No direct script access allowed');

class users extends MY_Controller {

	function __construct() {
		parent::__construct('frontend');
	}
	/*==================== DISPLAY ================*/
	public function index( $param1 =  '', $param2 = '' ) {

		$param1 = empty($param1) ? 'index' : Str::clear($param1);

		$param2 = Str::clear($param2);

		$view = apply_filters( 'my_account_view_'.$param1, 'user-'.$param1 );

		$layout = apply_filters( 'my_account_layout_'.$param1, 'template-user' );

		if($param1 ==  'login' ) {
			$this->login($param2);
		}
        else if($param1 ==  'forgot' ) {
            $this->forgot($param2);
        }
        else if($param1 ==  'reset' ) {
            $this->reset($param2);
        }
		else if($param1 ==  'register' ) {
			$this->register($param2);
		}
		else if($param1 ==  'index' ) {
			$this->profile($param2);
		}
		else if($param1 ==  'password' ) {
			$this->password($param2);
		}
		else if($param1 ==  'logout' ) {
			$this->logout($param2);

		} else {
			do_action('my_account_template_'.$param1, $param2 );
		}

		if(!empty($layout)) {
		    $this->template->set_layout( $layout );
        }
		if(!empty($view)) {
		    $this->template->render($view);
        }
	}

	public function login() {
		if(Request::post()) {
			$args['username'] = Request::post('username');
			$args['password'] = Request::post('password');
			$result = Auth::login($args);
			if(is_skd_error($result) ) {
				foreach ($result->errors as $error_key => $error_value) {
				    Template::setMessage(['message' => notice('error', $error_value[0]),'key' => $error_key]);
				}
			}
			else {
				$login_redirect = Request::post('redirect');
				if (!empty($login_redirect)) {
					$login_redirect = urldecode($login_redirect);
			        $login_redirect = Str::clear($login_redirect);
			    }
			    else $login_redirect = my_account_url(true);
				$login_redirect = apply_filters( 'login_redirect', my_account_url() );
				redirect( $login_redirect );
			}
		}
	}

	public function register() {

		if( Request::post() ) {

			$user_meta = [];

			$error = [];

			if (!empty(Request::post('username'))) {

				$user_array['username'] = Str::clear( Request::post('username') );
			}

			if (!empty(Request::post('firstname'))) {

				$user_array['firstname'] = Str::clear( Request::post('firstname') );
			}

			if (!empty(Request::post('lastname'))) {

				$user_array['lastname'] = Str::clear( Request::post('lastname') );
			}

			if (!empty(Request::post('fullname'))) {

				$user_meta['fullname'] = Str::clear( Request::post('fullname') );
			}

			if (!empty(Request::post('phone'))) {

				$user_array['phone'] = Str::clear( Request::post('phone') );
			}

			if (!empty(Request::post('address'))) {

				$user_meta['address'] = Str::clear( Request::post('address') );
			}

			if (!empty(Request::post('email'))) {

				if(User::emailExists(Str::clear(Request::post('email')))) {

					$error = new SKD_Error( 'email_exists', __('Email này đã được sử dụng.', 'error_use_email'));
				}

				$user_array['email'] = Str::clear( Request::post('email') );
			}

			if (!empty(Request::post('password')) ) {

				$user_array['password'] = Str::clear( Request::post('password') );
			}
			else {
				if( is_skd_error($error) ) $error->add( 'empty_password', __('Mật khẩu không được bỏ trống.', 'error_empty_password') );
				else $error = new SKD_Error( 'empty_password', __('Mật khẩu không được bỏ trống.', 'error_empty_password'));
			}

			if (empty(Request::post('re_password')) ||  Request::post('re_password') != Request::post('password') ) {

				if( is_skd_error($error) ) $error->add( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.', 'error_confirm_password') );
				else $error = new SKD_Error( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.', 'error_confirm_password'));
			}

			$error = apply_filters('registration_errors', $error, $user_array, $user_meta );

			if(!is_skd_error($error)) {

				$user_array = apply_filters( 'pre_user_register', $user_array );

				$user_meta 	= apply_filters( 'pre_user_register_meta', $user_meta );

				$error = User::insert($user_array);

				if(!is_skd_error($error) && have_posts($user_meta) ) {

					foreach ($user_meta as $user_meta_key => $user_meta_value) {

						if (!empty($user_meta_value)) User::updateMeta( $error, $user_meta_key, $user_meta_value );
					}
				}
			}

			if(is_skd_error($error)) {

				foreach ($error->errors as $error_key => $error_value) {

					$this->template->set_message( notice('error', $error_value[0]), $error_key );
				}
			}
			else {
				$this->template->set_message( notice('success', __('Đăng ký tài khoản thành công.', 'register_account_success')), 'register_success' );
			}
		}
	}

	public function profile() {

		if( Request::post() ) {

			$user_obj = Auth::user();

			$error = [];

			if( have_posts( $user_obj ) ) {

				$user_array = (array)$user_obj;

				$user_meta = [];

				$error = [];

				if ( !empty(Request::post('firstname')) ) {

					$user_array['firstname'] = Str::clear( Request::post('firstname') );
				}

				if ( !empty(Request::post('lastname')) ) {

					$user_array['lastname'] = Str::clear( Request::post('lastname') );
				}

				if ( !empty(Request::post('fullname')) ) {

					$user_meta['fullname'] = Str::clear( Request::post('fullname') );
				}

				if ( !empty(Request::post('phone')) ) {

					$user_array['phone'] = Str::clear( Request::post('phone') );
				}

				if ( !empty(Request::post('address')) ) {

					$user_array['address'] = Str::clear( Request::post('address') );
				}

				if ( !empty(Request::post('birthday')) ) {

					$user_meta['birthday'] = Str::clear( Request::post('birthday') );
				}

				if ( !empty(Request::post('email')) ) {

					// if( email_exists(Str::clear( Request::get('email') )) != $user_obj->id ) {

					// 	$error = new SKD_Error( 'email_exists', __('Email này đã được sử dụng.'));
					// }

					$user_array['email'] = Str::clear( Request::post('email') );
				}

				if( !is_skd_error($error) ) {

					$user_array = apply_filters( 'pre_update_profile', $user_array, $user_obj );

					$user_meta 	= apply_filters( 'pre_update_profile_meta', $user_meta, $user_obj );

					$error = User::update( $user_array );

					if( !is_skd_error($error) && have_posts($user_meta) ) {

						foreach ($user_meta as $user_meta_key => $user_meta_value) {

							if ( !empty( $user_meta_value ) ) User::updateMeta( $user_obj->id, $user_meta_key, $user_meta_value );
						}

					}
				}

				if( is_skd_error($error) ) {

					foreach ($error->errors as $error_key => $error_value) {

						$this->template->set_message( notice('error', $error_value[0]), $error_key );
					}

				}
				else {
					$this->template->set_message( notice('success', __('Thông tin tài khoản của bạn đã được cập nhật.')), 'update_profile_success' );
				}

			}
		}

	}

	public function password() {

		if( Request::post() ) {

			$user_obj = Auth::user();

			$error = [];

			if( have_posts( $user_obj ) ) {

				$user_array = (array)$user_obj;

				$user_meta = [];

				$error = [];

				if ( empty(Request::post('old_password')) || generate_password(Request::post('old_password'), $user_obj->username, $user_obj->salt ) != $user_obj->password ) {

					$error = new SKD_Error( 'invalid_old_password', __('Mật khẩu củ không chính xác.'));
				}

				if ( strlen(Request::post('new_password')) < 6 || strlen(Request::post('new_password')) > 32 ) {
					if( is_skd_error($error) ) $error->add( 'invalid_old_password', __('Mật khẩu không được nhỏ hơn 6 hoặc lớn hơn 32 ký tự.') );
					else $error = new SKD_Error( 'invalid_old_password', __('Mật khẩu không được nhỏ hơn 6 hoặc lớn hơn 32 ký tự.'));
				}

				if ( !empty(Request::post('new_password')) ) {

					$user_array['password'] = Str::clear( Request::post('new_password') );
				}
				else {

					if( is_skd_error($error) ) $error->add( 'invalid_new_password', __('Mật khẩu mới không chính xác.') );
					else $error = new SKD_Error( 'invalid_new_password', __('Mật khẩu mới không chính xác.'));
				}

				if ( empty(Request::post('re_new_password')) ||  Request::post('re_new_password') != Request::post('new_password') ) {

					if( is_skd_error($error) ) $error->add( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.', 'error_confirm_password') );
					else $error = new SKD_Error( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.', 'error_confirm_password'));
				}

				if( !is_skd_error($error) ) {

					$user_array = apply_filters( 'pre_update_password', $user_array, $user_obj );

					$error = User::update( $user_array );

					if( !is_skd_error($error) && have_posts($user_meta) ) {

						foreach ($user_meta as $user_meta_key => $user_meta_value) {

							if ( !empty( $user_meta_value ) ) User::updateMeta( $user_obj->id, $user_meta_key, $user_meta_value );
						}

					}
				}

				if( is_skd_error($error) ) {

					foreach ($error->errors as $error_key => $error_value) {

						$this->template->set_message( notice('error', $error_value[0]), $error_key );
					}

				}
				else {
					$this->template->set_message( notice('success', __('Thông tin tài khoản của bạn đã được cập nhật.')), 'update_password_success' );
				}

			}
		}

	}

	public function logout() {

		Auth::logout();

		$redirect = Request::get('redirect');

		if ( !empty($redirect) ) {

			$redirect = urldecode($redirect);
    	
	        $redirect = Str::clear($redirect);

	    }
	    else $redirect = Url::base();

		redirect( $redirect, 'refresh' );
	}

    public function forgot() {

        if(Request::post()) {

            $error = [];

            $email = Request::post('email');

            if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $error = new SKD_Error( 'email_empty_error', __('Email không đúng định dạng'));
            }

            $error = apply_filters('user_forgot_check', $error);

            if(!is_skd_error($error)) {

                $user = User::getBy('email', $email );

                if(have_posts($user)) {

                    $activation_key = md5($email.time());

                    $time = time()+24*60*60;

                    $error = User::insert(['id' => $user->id, 'activation_key' => $activation_key, 'time' => $time]);

                    if(!is_skd_error($error)) {

                        $content = '
                        <p>Một yêu cầu lấy lại mật khẩu cho tài khoản '.$user->email.' vừa được gửi, bạn vui lòng click vào đường link bên dưới để tiến hành lấy lại mật khẩu.</p>
                        <p style="color:red">P/s: lưu ý nếu đây không phải yêu cầu của bạn vui lòng không click vào đường link bên dưới.</p>
                        <p><b><a href="'.Url::base().'tai-khoan/reset?email='.$user->email.'&key='.$activation_key.'">Click vào đây để lấy lại mật khẩu</a></b></p>
                        <p>Link trên có thời hạn đến '.date('d-m-Y h:i', $time).'</p>';

                        $error = EmailHandler::send($content, 'Tài khoản: Yêu cầu lấy lại mật khẩu cho tài khoản '.$user->email, [
                            'name'      => $user->firstname.' '.$user->lastname,
                            'from'      => Option::get('contact_mail'),
                            'address'   => $user->email,
                        ]);
                    }
                }
            }

            if(is_skd_error($error)) {
                foreach ($error->errors as $error_key => $error_value) {
                    $this->template->set_message( notice('error', $error_value[0]), $error_key );
                }
            }
            else {
                $this->template->set_message(notice('success', 'Một email đã được gửi vào email <b>'.$user->email.'</b>, nếu không nhận được email lấy mật khẩu bạn vui lòng liên hệ quản trị viên để lấy lại mật khẩu'));
            }
        }
    }

    public function reset() {
	    
        $email 		= Request::get('email');

        $activation_key = Request::get('key');

        $user = User::get(Qr::set('email', $email)->where('activation_key', $activation_key));

        $error = [];

        if(have_posts($user) ) {

            $time = time();

            if($time < $user->time) {

                if(Request::post()) {

                    $password = trim(Request::post('password'));

                    $re_password = trim(Request::post('re_password'));

                    if(strlen($password) < 6 || strlen($password) > 32 ) {

                        $error = new SKD_Error('invalid_new_password', __('Mật khẩu mới không được nhỏ hơn 6 và lớn hơn 32 ký tự.'));
                    }

                    if (empty($re_password) ||  $re_password != $password) {
                        $error = new SKD_Error( 'invalid_re_new_password', __('Nhập lại mật khẩu không trùng khớp.'));
                    }

                    if(!is_skd_error($error)) {

                        $user_array['id'] = $user->id;

                        $user_array['activation_key'] = '';

                        $user_array['time']     = 0;

                        $user_array['password'] = Auth::generatePassword($password, $user->salt);

                        $error = User::insert( $user_array );
                    }

                    if( is_skd_error($error) ) {

                        foreach ($error->errors as $error_key => $error_value) {
                            $this->template->set_message( notice('error', $error_value[0]), $error_key );
                        }

                        $error = [];
                    }
                    else {
                        $this->template->set_message( notice('success', __('Mật khẩu của bạn đã reset thành công')), 'flashdata' );

                        redirect(Url::login());
                    }
                }
            }
            else {
                $error = new SKD_Error( 'reset_error', __('Đường dẫn đã quá hạn vui lòng kiểm tra lại.'));
            }
        }
        else {
            $error = new SKD_Error( 'reset_error', __('Email hoặc Key không đúng vui lòng kiểm tra lại.'));
        }

        $this->data['error'] = $error;
    }

	public function action_links() {
		$args = array(
			'user' => array(
				'label' => __('Thông tin tài khoản', 'account_info'),
				'icon'  => '<i class="fal fa-user"></i>',
				'url'	=> Url::account(true),
			),
			'password' => array(
				'label' => __('Đổi mật khẩu', 'change_password'),
				'icon'  => '<i class="fal fa-lock-open-alt"></i>',
				'url'	=> Url::account(true).'/password',
			),
			'logout' => array(
				'label' => __('Đăng xuất'),
				'icon'  => '<i class="fal fa-sign-out"></i>',
				'url'	=> Url::logout(Url::login())
			),
		);

		return apply_filters('my_action_links', $args );
	}
}