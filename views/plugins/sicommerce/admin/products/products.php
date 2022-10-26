<?php
include_once 'action-bar.php';
include_once 'ajax.php';
include_once 'form.php';
include_once 'popover.php';
include_once 'table.php';
include_once 'related.php';

class AdminProductAction {
    static function save($id, $insertData) {
        return Product::insert($insertData);
    }
    static function delete($res, $module, $data) {
        if(is_numeric($data)) {
            $res = Product::delete($data);
        }
        else if(have_posts($data)) {
            $res = Product::deleteList($data);
        }
        return $res;
    }

    static function logAdd($id, $module): void {

        if($module == 'products') {

            $listID = (is_numeric($id)) ? [$id] : $id;

            $objects = Product::gets(Qr::set()->whereIn('id', $listID));

            if(have_posts($objects)) {

                $user = Auth::user();

                $log = [
                    'username'     => $user->username,
                    'fullname'     => $user->firstname.' '.$user->lastname,
                    'ip'           => AdminActiveLog::getIp(),
                    'action'       => 'add',
                    'time'         => time(),
                    'agent_string' => Device::string()
                ];

                $log['message'] = 'Thêm mới sản phẩm';

                foreach ($objects as $object) {
                    $log['message'] .= ' <b>'.$object->title.'</b>,';
                }

                $log['message'] = trim($log['message'], ',');

                AdminActiveLog::writeLog($log);
            }
        }
    }
    static function logEdit($id, $module): void {
        if($module == 'products') {

            $listID = (is_numeric($id)) ? [$id] : $id;

            $objects = Product::gets(Qr::set()->whereIn('id', $listID));

            if(have_posts($objects)) {

                $user = Auth::user();

                $log = [
                    'username'     => $user->username,
                    'fullname'     => $user->firstname.' '.$user->lastname,
                    'ip'           => AdminActiveLog::getIp(),
                    'action'       => 'edit',
                    'time'         => time(),
                    'agent_string' => Device::string()
                ];

                $log['message'] = 'cập nhật sản phẩm';

                foreach ($objects as $object) {
                    $log['message'] .= ' <b>'.$object->title.'</b>,';
                }

                $log['message'] = trim($log['message'], ',');

                AdminActiveLog::writeLog($log);
            }
        }
    }
    static function logTrash($module, $id): void {
        if($module == 'products') {

            $listID = (is_numeric($id)) ? [$id] : $id;

            $objects = Product::gets(Qr::set()->whereIn('id', $listID));

            if(have_posts($objects)) {

                $user = Auth::user();

                $log = [
                    'username'     => $user->username,
                    'fullname'     => $user->firstname.' '.$user->lastname,
                    'ip'           => AdminActiveLog::getIp(),
                    'action'       => 'delete',
                    'time'         => time(),
                    'agent_string' => Device::string()
                ];

                $log['message'] = 'cho sản phẩm';

                foreach ($objects as $object) {
                    $log['message'] .= ' <b>'.$object->title.'</b>,';
                }

                $log['message'] = trim($log['message'], ',').' vào thùng rác';

                AdminActiveLog::writeLog($log);
            }
        }
    }
    static function logRestore($module, $id): void {
        if($module == 'products') {

            $listID = (is_numeric($id)) ? [$id] : $id;

            $objects = Product::gets(Qr::set()->whereIn('id', $listID));

            if(have_posts($objects)) {

                $user = Auth::user();

                $log = [
                    'username'     => $user->username,
                    'fullname'     => $user->firstname.' '.$user->lastname,
                    'ip'           => AdminActiveLog::getIp(),
                    'action'       => 'add',
                    'time'         => time(),
                    'agent_string' => Device::string()
                ];

                $log['message'] = 'khôi phục sản phẩm';

                foreach ($objects as $object) {
                    $log['message'] .= ' <b>'.$object->title.'</b>,';
                }

                $log['message'] = trim($log['message'], ',').' từ thùng rác';

                AdminActiveLog::writeLog($log);
            }
        }
    }
    static function logDelete($module, $id): void {
        if($module == 'products') {

            $listID = (is_numeric($id)) ? [$id] : $id;

            $objects = Product::gets(Qr::set()->whereIn('id', $listID));

            if(have_posts($objects)) {

                $user = Auth::user();

                $log = [
                    'username'     => $user->username,
                    'fullname'     => $user->firstname.' '.$user->lastname,
                    'ip'           => AdminActiveLog::getIp(),
                    'action'       => 'add',
                    'time'         => time(),
                    'agent_string' => Device::string()
                ];

                $log['message'] = 'xóa sản phẩm';

                foreach ($objects as $object) {
                    $log['message'] .= ' <b>'.$object->title.'</b>,';
                }

                $log['message'] = trim($log['message'], ',');

                AdminActiveLog::writeLog($log);
            }
        }
    }
}

add_filter('form_submit_products', 'AdminProductAction::save', 10, 2);
add_filter('delete_object_products', 'AdminProductAction::delete', 10, 3);

add_action('save_object_add', 'AdminProductAction::logAdd', 2, 2);
add_action('save_object_edit','AdminProductAction::logEdit', 2, 2);
add_action('ajax_trash_object_success', 'AdminProductAction::logTrash', 2, 2 );
add_action('ajax_delete_after_success', 'AdminProductAction::logDelete', 2, 2 );
add_action('ajax_restore_object_success', 'AdminProductAction::logRestore', 2, 2 );