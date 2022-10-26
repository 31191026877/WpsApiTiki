<?php
class QA_Activator {

    public static function activate() {
        self::addRole();
        self::randomData();
        self::templateFile();
    }

    public static function randomData() {

        $questions = [
            [
                'title'     => 'What are some random questions to ask?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'Do you include common questions?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'Can I use this for 21 questions?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'Are these questions for girls or for boys?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'What is the next skill that you\'d like to learn really well?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
            [
                'title'     => 'How would you describe someone who is wealthy?',
                'content'   => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            ],
        ];

        foreach ($questions as $question) {
            $question['post_type'] = QA_KEY;
            Posts::insert($question);
        }

        Pages::insert(['title' => 'FAQ']);

        $product_question = Option::get('product_question');

        if(empty($product_question)) {
            Option::update('product_question', array(
                'position' => 'sidebar',
                'data'     => 'handmade',
                'limit'    => 12,
            ));
        }
    }

    public static function templateFile() {
        $template  = [
            'page-faq.php'                    => QA_NAME.'/template/page-faq.php',
            'template-page-faq.php'           => QA_NAME.'/template/template-page-faq.php',
        ];
        foreach ($template as $file_name => $file_path) {
            $file_new  = Path::theme($file_name, true);
            $file_path = Path::plugin($file_path, true);
            if(file_exists($file_new)) continue;
            if(file_exists($file_path)) {
                $handle     = file_get_contents($file_path);
                $file_new   = fopen($file_new, "w");
                fwrite($file_new, $handle);
                fclose($file_new);
            }
        }
    }

    public static function addRole() {
        // Add caps for Root role
        $role = Role::get('root');
        $role->add_cap('view_question_answer');
        $role->add_cap('add_question_answer');
        $role->add_cap('edit_question_answer');
        $role->add_cap('delete_question_answer');

        $role->add_cap('view_question');
        $role->add_cap('delete_question');

        // Add caps for Administrator role
        $role = Role::get('administrator');
        $role->add_cap('view_question_answer');
        $role->add_cap('add_question_answer');
        $role->add_cap('edit_question_answer');
        $role->add_cap('delete_question_answer');

        $role->add_cap('view_question');
        $role->add_cap('delete_question');
    }
}