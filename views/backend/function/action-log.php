<?php
class AdminActiveLog {
    static function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
    static function readLog($audit_log_file = null) {
        if($audit_log_file == null) $audit_log_file = 'audit_log_'.date('m-y');
        $path = FCPATH . VIEWPATH . 'log/'.$audit_log_file;
        $data = [];
        if (file_exists($path)) {
            $data = read_file($path);
            $data = unserialize($data);
        }
        return $data;
    }
    static function writeLog($audit_log_data = [], $audit_log_file = null) {
        if( $audit_log_file == null ) $audit_log_file = 'audit_log_'.date('m-y');
        $audit_log 		= AdminActiveLog::readLog($audit_log_file);
        $audit_log[] 	= $audit_log_data;
        $path = FCPATH . VIEWPATH . 'log/'.$audit_log_file;
        if (write_file( $path , serialize($audit_log))) {
            @chmod( $path, 0777);
            return true;
        }
        return false;
    }
    static function icon($action = null) {
        $icon = '';
        if($action == 'login')  $icon = '<i class="fal fa-sign-in"></i>';
        if($action == 'add')    $icon = '<i class="fal fa-plus"></i>';
        if($action == 'edit')   $icon = '<i class="fas fa-pencil"></i>';
        if($action == 'delete') $icon = '<i class="fas fa-trash"></i>';
        return apply_filters('get_audit_icon', $icon, $action);
    }
}

class AdminActiveLogAction {
    static function login($user): void {
        $log = [
            'username'     => $user->username,
            'fullname'     => $user->firstname.' '.$user->lastname,
            'ip'           => AdminActiveLog::getIp(),
            'action'       => 'login',
            'message'      => '????ng nh???p h??? th???ng.',
            'time'         => time(),
            'agent_string' => Device::string()
        ];
        AdminActiveLog::writeLog($log);
    }
    static function add($id, $module): void {

        $user = Auth::user();

        $log = [
            'username'     => $user->username,
            'fullname'     => $user->firstname.' '.$user->lastname,
            'ip'           => AdminActiveLog::getIp(),
            'action'       => 'add',
            'time'         => time(),
            'agent_string' => Device::string()
        ];

        if($module == 'post') {
            $object = Posts::get($id);
            if(have_posts($object)) {
                $log['message'] = 'th??m b??i vi???t <b>'.$object->title.'</b>';
            }
        }

        if($module == 'post_categories') {
            $object = PostCategory::get($id);
            if(have_posts($object)) {
                $log['message'] = 'th??m danh m???c <b>' . $object->name . '</b>';
            }
        }

        if($module == 'page') {
            $object = Pages::get($id);
            if(have_posts($object)) {
                $log['message'] = 'th??m trang n???i dung <b>' . $object->title . '</b>';
            }
        }

        if(!empty($log['message'])) AdminActiveLog::writeLog($log);
    }
    static function edit($id, $module): void {

        $user = Auth::user();

        $log = array(
            'username'     => $user->username,
            'fullname'     => $user->firstname.' '.$user->lastname,
            'ip'           => AdminActiveLog::getIp(),
            'action'       => 'edit',
            'time'         => time(),
            'agent_string' => Device::string()
        );

        if($module == 'post') {

            $object = Posts::get($id);

            $log['message'] = 'c???p nh???t b??i vi???t <b>'.$object->title.'</b>';
        }

        if($module == 'post_categories') {

            $object = PostCategory::get($id);

            $log['message'] = 'c???p nh???t danh m???c <b>'.$object->name.'</b>';
        }

        if($module == 'page') {

            $object = Pages::get($id);

            $log['message'] = 'c???p nh???t trang n???i dung <b>'.$object->title.'</b>';
        }

        if(!empty($log['message'])) AdminActiveLog::writeLog($log);
    }
    static function trash($module, $id): void {

        $user = Auth::user();

        $log = [
            'username'     => $user->username,
            'fullname'     => $user->firstname.' '.$user->lastname,
            'ip'           => AdminActiveLog::getIp(),
            'action'       => 'delete',
            'time'         => time(),
            'agent_string' => Device::string()
        ];

        $listID = $id;

        if(is_numeric($id)) $listID = [$id];

        foreach ($listID as $key => $id) {

            $log['message'] = '';

            if($module == 'post') {
                $object = Posts::get($id);
                $log['message'] = 'cho b??i vi???t <b>'.$object->title.'</b> v??o th??ng r??c';
            }

            if($module == 'post_categories') {

                $object = PostCategory::get($id);

                $log['message'] = 'cho danh m???c <b>'.$object->name.'</b> v??o th??ng r??c';
            }

            if($module == 'page') {

                $object = Pages::get($id);

                if(have_posts($object)) $log['message'] = 'cho trang n???i dung <b>'.$object->title.'</b> v??o th??ng r??c';
            }

            if(!empty($log['message'])) AdminActiveLog::writeLog($log);
        }
    }
    static function delete($module, $id): void {

        $user = Auth::user();

        $log = array(
            'username'     => $user->username,
            'fullname'     => $user->firstname.' '.$user->lastname,
            'ip'           => AdminActiveLog::getIp(),
            'action'       => 'delete',
            'time'         => time(),
            'agent_string' => Device::string()
        );

        $listID = $id;

        if(is_numeric($id)) $listID = [$id];

        foreach ($listID as $key => $id) {

            $log['message'] = '';

            if($module == 'post') {
                $object = Posts::get($id);
                $log['message'] = 'x??a b??i vi???t <b>'.$object->title.'</b>';
            }

            if($module == 'post_categories') {
                $object = PostCategory::get($id);
                $log['message'] = 'x??a danh m???c <b>'.$object->name.'</b>';
            }

            if($module == 'page') {
                $object = Pages::get($id);
                if(have_posts($object)) $log['message'] = 'x??a trang n???i dung <b>'.$object->title.'</b>';
            }

            if(!empty($log['message'])) AdminActiveLog::writeLog($log);
        }
    }
    static function restore($module, $id): void {

        $user = Auth::user();

        $log = array(
            'username'     => $user->username,
            'fullname'     => $user->firstname.' '.$user->lastname,
            'ip'           => AdminActiveLog::getIp(),
            'action'       => 'add',
            'time'         => time(),
            'agent_string' => Device::string()
        );

        $listID = $id;

        if(is_numeric($id)) $listID = [$id];

        foreach ($listID as $key => $id) {

            $log['message'] = '';

            if($module == 'post') {

                $object = Posts::get($id);

                $log['message'] = 'kh??i ph???c b??i vi???t <b>'.$object->title.'</b> t??? th??ng r??c';
            }

            if($module == 'post_categories') {

                $object = PostCategory::get($id);

                $log['message'] = 'kh??i ph???c danh m???c <b>'.$object->name.'</b> t??? th??ng r??c';
            }

            if($module == 'page') {

                $object = Pages::get($id);

                if(have_posts($object)) $log['message'] = 'kh??i ph???c trang n???i dung <b>'.$object->title.'</b> t??? th??ng r??c';
            }

            if(!empty($log['message'])) AdminActiveLog::writeLog($log);
        }
    }
}

add_action('skd_admin_login', 'AdminActiveLogAction::login');
add_action('save_object_add', 'AdminActiveLogAction::add', 2, 2);
add_action('save_object_edit','AdminActiveLogAction::edit', 2, 2);
add_action('ajax_trash_object_success', 'AdminActiveLogAction::trash', 2, 2 );
add_action('ajax_delete_after_success', 'AdminActiveLogAction::delete', 2, 2 );
add_action('ajax_restore_object_success', 'AdminActiveLogAction::restore', 2, 2 );

class AdminSystemActiveLog {
    static public function render($ci, $tab): void {
        $logTemp = AdminActiveLog::readLog();
        $logActive = [];
        for ($i = count($logTemp) -1; $i >= 0 ; $i--) {
            $key = date('d/m/Y', $logTemp[$i]['time']);
            $logActive[$key][] = $logTemp[$i];
        }
        Admin::partial('function/system/html/log', ['logActive' => $logActive]);
    }
}