<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Security extends  CI_Security {

    public function csrf_verify() {
		// If it's not a POST request we will set the CSRF cookie
		if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
			return $this->csrf_set_cookie();
		}

		if(isset($_SERVER["REQUEST_URI"]) && (isset($_SERVER['REQUEST_METHOD']))) {

            if (stripos($_SERVER["REQUEST_URI"],'/api/') !== false || stripos($_SERVER["REQUEST_URI"],'/webhook') !== false)  {
                unset($_POST[$this->_csrf_token_name]);
                unset($_COOKIE[$this->_csrf_cookie_name]);
                $this->_csrf_set_hash();
                $this->csrf_set_cookie();
                log_message('debug', 'CSRF token verified');
                return $this;
            }
		}

		// Do the tokens exist in both the _POST and _COOKIE arrays?
		if ( ! isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name]))
		{
			$this->csrf_show_error();
		}

		// Do the tokens match?
		if ($_POST[$this->_csrf_token_name] != $_COOKIE[$this->_csrf_cookie_name])
		{
			$this->csrf_show_error();
		}

		// We kill this since we're done and we don't want to
		// polute the _POST array
		unset($_POST[$this->_csrf_token_name]);

		// Nothing should last forever
		unset($_COOKIE[$this->_csrf_cookie_name]);

		$this->_csrf_set_hash();
		$this->csrf_set_cookie();

		log_message('debug', 'CSRF token verified');

		return $this;
	}

}