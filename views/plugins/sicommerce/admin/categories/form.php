<?php
function product_category_admin_form_input(FormAdmin $form): FormAdmin {

    $redirect = Url::admin('products/products-categories');
    if((int)Request::get('category') != 0) $redirect .= '?category='.Request::get('category');
    $form->setParams('redirect', $redirect);

    $form->lang
        ->addGroup('info','Thông Tin')
        ->addFieldLang('name', 'text', ['label' => 'Tiêu đề', 'note' => 'Tiêu đề được lấy làm thẻ H1'])
        ->addFieldLang('excerpt', 'wysiwyg-short', ['label' => 'Tóm tắt'])
        ->addFieldLang('content', 'wysiwyg', ['label' => 'Nội dung']);

    $form->right
        ->addGroup('category', 'Danh mục')
        ->addField('parent_id', 'select', ['label' => 'Danh mục cha', 'value'=> Request::get('category'), 'options' => ProductCategory::gets(Qr::set()->categoryType('options'))]);

    $form->right
        ->addGroup('media','Media')
        ->addField('image', 'image', ['label' => 'Hình ảnh']);

    $form->right
        ->addGroup('seo','Seo')
        ->addField('slug', 'text', ['label' => 'Slug'])
        ->addField('seo_title', 'text', ['label' => 'Meta title'])
        ->addField('seo_keywords', 'text', ['label' => 'Meta Keyword'])
        ->addField('seo_description', 'text', ['label' => 'Meta Description']);

    $form->right
        ->addGroup('theme','Giao Diện')
        ->addField('theme_layout', 'select', ['label' => 'Template Layout', 'options' => Template::getListLayout()])
        ->addField('theme_view', 'select', ['label' => 'Template View', 'options' => Template::getListView()]);

    if(Template::isMethod('index')) {
        $form->removeGroup(['seo', 'theme']);
        $form->removeField(['excerpt', 'content']);
    }

    return $form;
}

add_filter('manage_products_categories_input', 'product_category_admin_form_input', 1);