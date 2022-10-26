<?php
include 'roles.php';

Taxonomy::addPost(FEEDBACK_POST_TYPE,
    array(
        'labels' => array(
            'name'          => 'Feedback',
            'singular_name' => 'Feedback',
        ),
        'public' => false,
        'show_admin_column'  => false,
        'capabilities' => array(
            'view'      => 'view_posts_customer',
            'add'       => 'add_posts_customer',
            'edit'      => 'edit_posts_customer',
            'delete'    => 'delete_posts_customer',
        ),
    )
);

class AdminFeedback {
    static function navigation(): void {
        AdminMenu::add(FEEDBACK_POST_TYPE, 'Feedback', 'post?post_type='.FEEDBACK_POST_TYPE, [
            'position' => 'post',
            'icon' => '<img src="'.Path::plugin('feedback').'/icon-customer.png" />'
        ]);
    }
    static function tableHeader($columns): array {
        $columnsnew['cb']   	= 'cb';
        $columnsnew['thumb'] 	= 'Ảnh đại diện';
        $columnsnew['title'] 	= 'Họ và Tên';
        $columnsnew['content'] 	= 'Chức vụ';
        $columnsnew['excerpt'] 	= 'Feedback';
        $columnsnew['action'] 	= 'Hành động';
        $columns = $columnsnew;
        return $columns;
    }
    static function columnData( $column_name, $item ): void {
        switch ($column_name) {
            case 'thumb':
                echo Template::img($item->image, $item->title, array('style'=>'width:50px;'));
                break;
            case 'excerpt':
                echo $item->excerpt;
                break;
            case 'content':
                echo $item->content;
                break;
        }
    }
    static function formInput($form) {
        $info = $form->lang->group('info');
        foreach (Language::listKey() as $key) {
            $info->field[$key.'_content']['type'] = 'text';
            $info->field[$key.'_content']['label'] = 'Chức vụ';
        }
        $form->removeGroup(['seo', 'theme']);
        $info->renameField(['title' => 'Họ và Tên', 'excerpt' => 'Bình Luận']);
        return $form;
    }
    static function formSave($ins_data) {
        if(get_instance()->data['module'] == 'post' && Admin::getPostType() == FEEDBACK_POST_TYPE) {
            $ins_data['public'] = false;
        }
        return $ins_data;
    }
}
add_action('init', 'AdminFeedback::navigation');
add_filter('manage_post_'.FEEDBACK_POST_TYPE.'_columns', 'AdminFeedback::tableHeader');
add_action('manage_post_'.FEEDBACK_POST_TYPE.'_custom_column', 'AdminFeedback::columnData',10,2);
add_filter('manage_post_'.FEEDBACK_POST_TYPE.'_input', 'AdminFeedback::formInput');
add_filter('save_object_before', 'AdminFeedback::formSave');