<?php
function product_admin_form_input(FormAdmin $form) {
    $ci =& get_instance();
    $urlParam = '?page='.((Request::get('page') != '') ? Request::get('page') : 1 );
    if(!empty(Request::get('category'))) $urlParam .= '&category='.Request::get('category');
    $form->setParams('redirect', Url::admin('products'.$urlParam));

    $form->lang
        ->addGroup('info','Thông Tin')
        ->addFieldLang('title', 'text', ['label' => 'Tiêu đề', 'note' => 'Tiêu đề được lấy làm thẻ H1'])
        ->addFieldLang('excerpt', 'wysiwyg-short', ['label' => 'Tóm tắt'])
        ->addFieldLang('content', 'wysiwyg', ['label' => 'Nội dung']);

    $form->right
        ->addGroup('category', 'Phân loại')
        ->addField('code', 'text', ['label' => 'Mã sản phẩm', 'note' => 'Nhập mã sản phẩm (SKU) nếu có.'])
        ->addField('category_id', 'popover', ['label' => 'Danh mục', 'module' => 'products_categories', 'options' => ProductCategory::gets(Qr::set()->categoryType('options'))]);

    if(Option::get('product_supplier') == 1) {
        $supplier_options = Suppliers::getsOption();
        $form->right->group('category')->addField('supplier_id', 'popover', ['label' => 'Nhà sản xuất', 'module' => 'supplier', 'multiple' => false, 'options' => $supplier_options]);
    }
    if(Option::get('product_brands') == 1) {
        $brand_options = Brands::getsOption();
        $form->right->group('category')->addField('brand_id', 'popover', ['label' => 'Thương hiệu', 'module' => 'brands', 'multiple' => false, 'options' => $brand_options]);
    }
    foreach ($ci->taxonomy['list_cat_detail'] as $taxonomy_key => $taxonomy_value) {
        if( $taxonomy_value['post_type'] == 'products') {
            $form->right->addGroup('taxonomies', 'Chuyên Mục', 'media')
                ->addField('taxonomy_'.$taxonomy_key, 'popover', ['label' => $taxonomy_value['labels']['name'], 'module' => 'post_categories']);
        }
    }

    $form->right->addGroup('price', 'Giá')
        ->addField('price', 'text', ['value' => 0, 'label' => 'Giá'])
        ->addField('price_sale', 'text', ['value' => 0, 'label' => 'Giá khuyến mãi'])
        ->addField('weight', 'number', ['value' => 0, 'label' => 'Cân nặng', 'note' => 'Đơn vị tính bằng gram']);

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


    return $form;
}
add_filter('manage_products_input', 'product_admin_form_input', 1);