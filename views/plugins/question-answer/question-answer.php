<?php
/**
Plugin name     : Question Answer
Plugin class    : question_answer
Plugin uri      : http://sikido.vn
Description     : Built Question Answer site for your website
Author          : SKDSoftware Dev Team
Version         : 1.2.0
 */
const QA_NAME = 'question-answer';
const QA_KEY = 'question-answer';
define('QA_PATH', Path::plugin(QA_NAME));
class Question_Answer {

    private $name = 'question_answer';

    function __construct() {
        $this->loadDependencies();
        new QA_Taxonomy();
        if(Admin::is()) new Question_Answer_Roles();
    }

    public function active() {
        QA_Activator::activate();
    }

    public function uninstall() {}

    private function loadDependencies() {
        require_once QA_PATH.'/includes/product.php';
        require_once QA_PATH.'/includes/active.php';
        require_once QA_PATH.'/includes/taxonomy.php';
        require_once QA_PATH.'/includes/schema.php';
        require_once QA_PATH.'/includes/roles.php';
    }
}

new Question_Answer();

if (!function_exists('ajax_question_save')) {
    function ajax_question_save($ci, $model) {

        $result['message'] = 'Cập nhật dữ liệu thất bại.';

        $result['status'] = 'error';

        if (Request::post()) {

            $name  = trim(Request::post('name'));

            if(empty($name)) {
                $result['message'] = 'Họ tên của bạn chưa được nhập.';
                echo json_encode($result);
                return true;
            }

            $email = trim(Request::post('email'));

            if(empty($email)) {
                $result['message'] = 'Email của bạn chưa được nhập.';
                echo json_encode($result);
                return true;
            }

            $question = trim(Request::post('question'));

            if(empty($question)) {
                $result['message'] = 'Câu hỏi của bạn chưa được nhập.';
                echo json_encode($result);
                return true;
            }

            if(Str::length($question) <= 10) {
                $result['message'] = 'Câu hỏi của bạn quá ngắn.';
                echo json_encode($result);
                return true;
            }

            $data = [
                'title'     => $name,
                'excerpt'   => $email,
                'content'   => $question,
                'public'    => 0,
                'status'    => 1,
                'post_type' => 'question'
            ];

            $res  = Posts::insert($data);

            if(!is_skd_error($res)) {
                $result['status'] 	= 'success';
                $result['message'] 	= 'Cám ơn bạn đã gửi câu hỏi cho chúng tôi.';
            }
        }

        echo json_encode($result);

        return true;
    }
    Ajax::client('ajax_question_save');
}


