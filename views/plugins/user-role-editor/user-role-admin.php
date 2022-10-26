<?php
class AdminRole {
    static function registerSystem($tabs) {
        if(Auth::hasCap('role_editor')) {
            $tabs['role'] = [
                'label' => 'Phân quyền',
                'description' => 'Quản lý các cấp bật quyền hạng thành viên',
                'callback' => 'AdminRole::render',
                'icon' => '<i class="fa-duotone fa-user-lock"></i>',
                'form' => false,
            ];
        }
        return $tabs;
    }
    static function render() {

        $user = Auth::user();

        $role = skd_roles()->get_names();

        if(is_super_admin($user->id)) $role_name_default = 'root';
        else $role_name_default = 'administrator';

        $role_name 		= (Request::get('role') == '') ? $role_name_default : Request::get('role');

        $role_current 	= Role::get( $role_name )->capabilities;

        $role_label = RoleEditor::label();

        $role_group = RoleEditor::group();

        if(!is_super_admin() && $role['root'] ) unset($role['root']);

        include 'html/user_role_editor.php';
    }
    static function registerTab($args) {
        if(Auth::hasCap('role_editor_user') ) {
            $args['role'] = array(
                'label' => __('Phân Quyền'),
                'callback' => 'AdminRole::userTab'
            );
        }
        return $args;
    }
    static function userTab($user) {

        $role 			= skd_roles()->get_names();

        $role_name 		= user_role($user->id);

        $role_name 		=  array_pop($role_name);

        if(is_super_admin()) {

            $role_name_default = 'root';
        }
        else $role_name_default = 'administrator';

        $role_all 		= Role::get( $role_name_default )->capabilities;

        $role_default   = Role::get( $role_name )->capabilities;

        $role_current 	= get_role_caps( $user->id );

        $role_label 	= RoleEditor::label();

        $role_group     = RoleEditor::group();

        if($role_name_default == 'administrator') {

            foreach ($role_group as &$role_group_value) {

                foreach ($role_group_value['capabilities'] as $key => $cap) {

                    if(empty($role_all[$cap])) unset($role_group_value['capabilities'][$key]);
                }
            }
        }

        if( !is_super_admin() && $role['root'] ) unset($role['root']);

        include 'html/user_role_tab.php';
    }
}
add_filter('skd_system_tab', 'AdminRole::registerSystem', 50);
add_filter('admin_my_action_links', 'AdminRole::registerTab');