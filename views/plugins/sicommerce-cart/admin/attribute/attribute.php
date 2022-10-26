<?php
include 'ajax.php';
include 'table.php';
include 'items.php';

Class Admin_Attribute_Page {
    static public function page($ci, $model) {
        $view 	= Str::clear(Request::get('view'));
        $id 	= (int)Request::get('id');
        if(empty($view)) {
            $args = array(
                'items' => Attributes::gets(),
                'table' => 'attribute',
                'model' => model('attribute'),
                'module' => 'attributes',
            );
            $table_list = new skd_attribute_list_table($args);
            include 'views/html-attribute.php';
        }

        if($view == 'add') {
            Admin::creatForm('attribute');
            include 'views/html-attribute-save.php';
        }

        if($view == 'edit') {
            $object = Attributes::get($id);
            if(have_posts($object)) {
                Admin::creatForm('attribute', $object);
                include 'views/html-attribute-save.php';
            }
            else {
                echo notice('error', 'Trang bạn yêu cầu không tồn tại. Liên kết bạn vừa nhấn không còn được sử dụng. Trang này có thể đã được chuyển sang vị trí khác. Có thể có lỗi xảy ra. Bạn không được cấp quyền để có thể truy cập trang này', 'Không tìm thấy trang yêu cầu');
            }
        }
    }
    static public function form($form) {
        $form->lang
            ->addGroup('info','Thông Tin')
            ->addFieldLang('title', 'text', ['label' => 'Tên thuộc tính', 'note' 	=> 'Tiêu đề bài viết được lấy làm thẻ H1']);

        $form->right
            ->addGroup('information', 'Loại thuộc tính')
            ->addField('option_type', 'select', ['label' => 'Loại options', 'options' => [
                'label' => 'Chữ (Label)',
                'color' => 'Màu (Color)',
                'image' => 'Hình ảnh (Image)'
            ]]);

        return $form;
    }
    static public function logDelete($module, $id): void {

        if($module == 'Attributes') {

            $listID = (is_numeric($id)) ? [$id] : $id;

            $objects = Attributes::gets(Qr::set()->whereIn('id', $listID));

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

                $log['message'] = 'Xóa nhóm thuộc tính';

                foreach ($objects as $object) {
                    $log['message'] .= ' <b>'.$object->title.'</b>,';
                }

                AdminActiveLog::writeLog($log);
            }
        }
    }
}

add_filter('manage_attribute_input', 'Admin_Attribute_Page::form');
add_action('ajax_delete_after_success', 'Admin_Attribute_Page::logDelete', 2, 2);
