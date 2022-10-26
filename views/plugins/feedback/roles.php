<?php
//Group quyền
class RoleFeedback {
    static function group($group) {
        $group['customer'] = array(
            'label' => __('Feedback                                                                                                                                                       Khách Hàng'),
            'capabilities' => array_keys(RoleFeedback::capabilities())
        );
        return $group;
    }
    static function label($label): array {
        return array_merge($label, RoleFeedback::capabilities());
    }
    static function capabilities() {
        $label['view_posts_customer']         = 'Xem danh sách ý kiến';
        $label['add_posts_customer']          = 'Thêm ý kiến';
        $label['edit_posts_customer']         = 'Sửa ý kiến';
        $label['delete_posts_customer']       = 'Xóa ý kiến';
        return apply_filters('customer_feedback_capabilities', $label );
    }
}
add_filter('user_role_editor_group', 'RoleFeedback::group', 1 );
add_filter('user_role_editor_label', 'RoleFeedback::label', 1 );
