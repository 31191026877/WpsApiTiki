<?php
class QuestionProduct {
    public static function setting() {
        $productQuestion  = Option::get('product_question', array(
            'position' => 'sidebar',
            'data'     => 'handmade',
            'limit'    => 12,
        ));
        Plugin::partial('question-answer', 'admin/product-setting', ['productQuestion' => $productQuestion]);
    }
    public static function product($form, $object) {
        $productQuestion  = Option::get('product_question', array(
            'position' => 'sidebar',
            'data'     => 'handmade',
            'limit'    => 12,
        ));
        if(!empty($productQuestion['data']) && $productQuestion['data'] == 'handmade') {
            $question =  (have_posts($object)) ? Product::getMeta($object->id, 'product_question', true) : [];
            $form->add('product_question', 'popover-advance', [
                'label' => 'Câu hỏi liên quan',
                'search' => 'post',
                'taxonomy' => QA_KEY,
            ], $question);
        }
        return $form;
    }
    static public function productSave($product_id, $module) {
        if($module == 'products') {
            $question = Request::Post('product_question', ['type' => 'int']);
            if(is_string($question)) $question = [];
            Product::updateMeta($product_id, 'product_question', $question);
        }
    }
    public static function html($object) {

        if(Template::getData('object') === false) return;

        $productCurrent = Template::getData('object');

        $productQuestion = Option::get('product_question');

        $args = [
            'data'      => (!empty($productQuestion['data'])) ?  $productQuestion['data'] : 'handmade',
            'limit' 	=> (!empty($productQuestion['limit'])) ?  $productQuestion['limit'] : 12,
            'position' 	=> (!empty($productQuestion['position'])) ?  $productQuestion['position'] : 'sidebar',
        ];

        if($args['data'] == 'handmade') {

            $questionsData =  (have_posts($productCurrent)) ? Product::getMeta($productCurrent->id, 'product_question', true) : [];

            if(!have_posts($questionsData)) return false;

            $questionsQr = Qr::set('post_type', QA_KEY)->whereIn('id', $questionsData)->limit($args['limit'])->orderByRaw('rand()');
        }
        else {
            $questionsQr = Qr::set('post_type', QA_KEY)->limit($args['limit'])->orderByRaw('rand()');
        }

        $questions = Posts::gets($questionsQr);

        if(have_posts($questions)) {
            $args['questions']  = $questions;
            $args['heading']    = __('Câu Hỏi Liên Quan', 'product_heading_question');
            $args['id']         = 'question';
            if($args['position'] == 'sidebar') {
                Plugin::partial(QA_NAME, 'question-sidebar', $args);
            }
            if($args['position'] == 'content' || $args['position'] == 'bottom') {
                Plugin::partial(QA_NAME, 'question-content', $args);
            }
        }
    }
}

add_action('admin_product_setting_detail', 'QuestionProduct::setting', 40);
add_filter('admin_product_field_related', 'QuestionProduct::product', 40, 2);
add_action('save_object', 'QuestionProduct::productSave', 10, 2);

if(Template::isPage('products_detail')) {
    $productQuestion = Option::get('product_question');
    $productQuestion = (empty($productQuestion['position'])) ? 'sidebar' : $productQuestion['position'];
    if($productQuestion == 'sidebar') add_action('product_detail_sidebar','QuestionProduct::html', 30);
    if($productQuestion == 'content') add_action('product_detail_tabs','QuestionProduct::html', 40);
    if($productQuestion == 'bottom')  add_action('product_detail_after','QuestionProduct::html', 40);
}