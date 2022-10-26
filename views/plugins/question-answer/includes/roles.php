<?php
Class Question_Answer_Roles
{
    public function __construct()
    {
        add_filter('user_role_editor_group', array($this, 'group'), 10);
        add_filter('user_role_editor_label', array($this, 'label'), 10, 2);
    }

    public function group($group) {
        $group['question'] = array(
            'label' => __('Hỏi & Đáp'),
            'capabilities' => array_keys(static::capabilities())
        );
        return $group;
    }

    public function label($label) {
        return array_merge($label, static::capabilities());
    }

    static public function capabilities()
    {
        $label['view_question_answer'] = 'Xem danh sách câu hỏi & trả lời';
        $label['add_question_answer'] = 'Thêm câu hỏi & trả lời';
        $label['edit_question_answer'] = 'Sửa câu hỏi & trả lời';
        $label['delete_question_answer'] = 'Xóa câu hỏi & trả lời';
        $label['view_question'] = 'Xem danh sách câu hỏi khách hàng';
        $label['delete_question'] = 'Xóa câu hỏi khách hàng';
        return apply_filters('Question_Answer_Roles', $label);
    }
}