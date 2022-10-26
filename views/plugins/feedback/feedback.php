<?php
/**
Plugin name     : Feedback
Plugin class    : Feedback
Plugin uri      : http://sikido.vn
Description     : Tạo và quản lý ý kiến, nhận xét của khách hàng
Author          : SKDSoftware Dev Team
Version         : 2.0.0
*/
const FEEDBACK_POST_TYPE = 'feedback';

class Feedback {
    private string $name = 'Feedback';
    function __construct() {}
    public function active() {
        $this->createData();
        $this->addCap();
    }
    public function createData() {
        $count = Posts::count(Qr::set('post_type', FEEDBACK_POST_TYPE));
        if($count == 0) {
            $data['title']       = 'David Nguyen';
            $data['excerpt']     = '“It\'s easy to impress me. I don\'t need a fancy party to be happy. Just good friends, good food, and good laughs. I\'m happy. I\'m satisfied. I\'m content. Lorem Ipsum is simply dummy text of the printing and type setting industry.”';
            $data['content']     = 'Food Analyst';
            $data['post_type']   = FEEDBACK_POST_TYPE;
            $data['public']      = 0;
            Posts::insert($data);
            $data['title']       = 'Jena Vi';
            $data['excerpt']     = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.';
            $data['content']     = 'Web Developer';
            Posts::insert($data);
            $data['title']       = 'Nguyen Dora';
            $data['excerpt']     = 'Even if you can\'t afford to travel the world, you can take your children to the museum, zoo or local park. And don\'t be afraid to take them to grown-up spots. Eating out in a restaurant teaches children how to be quiet and polite and gives them the pleasure of knowing you trust them to behave.';
            $data['content']     = 'Web Designer';
            Posts::insert($data);
        }
    }
    public function addCap() {
        $caps = RoleFeedback::capabilities();
        $role_root  = Role::get('root');
        $role_admin = Role::get('administrator');
        foreach ($caps as $cap_key => $label) {
            $role_root->add_cap($cap_key);
            $role_admin->add_cap($cap_key);
        }
    }
}

if(Admin::is()) include 'admin.php';