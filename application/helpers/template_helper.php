<?php
if(!function_exists('notice'))  {
	function notice($status = 'success', $mess = '', $css_inser = false, $heading = '') {
        $icon = '';
        $header = '';
		if($status == 'info') {
			$icon = '<i class="fad fa-info"></i>';
			$header = __('Thông tin', 'notice_info');
		}
		if($status == 'success') {
			$icon = '<i class="fad fa-check"></i>';
			$header = __('Thành công', 'notice_success');
		}
		if($status == 'experimental') {
			$icon = '<svg style="height:1.5em; width:1.5em;" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M14.38 14.59L11 7V3h1v-1H3v1h1v4L0.63 14.59c-0.3 0.66 0.19 1.41 0.91 1.41h11.94c0.72 0 1.2-0.75 0.91-1.41zM3.75 10l1.25-3V3h5v4l1.25 3H3.75z m4.25-2h1v1h-1v-1z m-1-1h-1v-1h1v1z m0-3h1v1h-1v-1z m0-3h-1V0h1v1z" /></svg>';
			$header = __('Chú ý', 'notice_experimental');
		}
		if($status == 'warning') {
			$icon = '<i class="fad fa-lightbulb"></i>';
			$header = __('Lưu ý', 'notice_warning');
		}
		if($status == 'danger' || $status == 'error') {
			$icon = '<i class="fad fa-times"></i>';
			$header = __('Lỗi', 'notice_error');
		}

		if(!empty($heading)) $header = $heading;

		$messages  = ($css_inser == true) ? '<link rel="stylesheet" href="'.Path::admin('assets/less/notice.css').'">':'';

		$messages .= "
        <div class=\"alert alert_$status\">
            <div class=\"alert_icon\">$icon</div>
            <div class=\"alert_wrapper\"><b>$header</b> $mess</div>
            <a class=\"close\" href=\"#\"><i class=\"icon-cancel\"></i></a>
        </div>";

		return $messages;
	}
}
if(!function_exists('admin_notices')) {
	function admin_notices() {
		$ci =& get_instance();
		$ci->template->get_message();
		do_action('admin_notices');
	}
}
if(!function_exists('the_content'))  {
	function the_content( $content = '' ): void {
		if(empty($content)) {
            $object = get_object_current('object');
            if(!empty($object->content)) $content = $object->content;
        }
        $content = htmlspecialchars_decode($content);
		$content = do_shortcode($content);
		$content = apply_filters( 'the_content', $content );
    	$content = str_replace( ']]>', ']]&gt;', $content );
    	echo $content;
	}
}
if(!function_exists('__')) {
    function __( $str = '', $key = '' ) {
        $ci = &get_instance();
        $lang = apply_filters('lang_line', $ci->lang->line($str));
        if(empty($lang)) {
        	$lang = $ci->lang->line( $key );
        }
        return !empty($lang)?$lang:$str;
    }
}
if(!function_exists('breadcrumb')){
	function breadcrumb($data = []) {
		$bre = null;
		if(have_posts($data)) {
			$count = count($data);
            $bre .= apply_filters('breadcrumb_open', '<div class="btn-group btn-breadcrumb">');
			$bre .= apply_filters('breadcrumb_first', '<a href="'.base_url().'" class="btn btn-default">'.__('Trang chủ','trang-chu').'</a>');
			foreach ($data as $key => $val) {
				$bre .= apply_filters('breadcrumb_icon', '<a class="btn btn-default btn-next"><i class="fal fa-caret-right"></i></a>');
				if( $key == ($count -1) ) {
					$bre .= apply_filters('breadcrumb_item_last', '<a class="btn btn-default">'.$val->name.'</a>', $val, $key);
				} else {
					$bre .= apply_filters('breadcrumb_item', '<a href="'.get_url($val->slug).'" class="btn btn-default">'.$val->name.'</a>', $val, $key);
				}
			}
			$bre .= apply_filters('breadcrumb_close', '</div>');
		}
	  	return $bre;
	}
}
if(!function_exists('pagination'))  {
    function pagination($total = 10, $url = '', $limit = 10, $page = null): Pagination {
        $httpGet = Request::Get();
        if($page == null) {
            if((int)Request::Get('paging') != 0) {
                $page = (int)Request::Get('paging');
                unset($httpGet['paging']);
            }
            else {
                $page = (int)Request::Get('page');
                unset($httpGet['page']);
            }
        }
        if(have_posts($httpGet)) {
            $url .= http_build_query($httpGet);
        }
        $config  = array (
            'currentPage'   => ($page != 0) ? $page : 1, // Trang hiện tại
            'totalRecords'  => $total, // Tổng số record
            'limit'		    => $limit,
            'url'           => $url,
        );
        return new Pagination($config);
    }
}
if(!function_exists('concatenate_files')){
	function concatenate_files($files) {
		$buffer = '';
		foreach($files as $file) {
			$buffer .= file_get_contents($file);
		}
		return $buffer;
	}
}
if(!function_exists('template')){
    function template() {
        return get_instance()->data['template'];
    }
}