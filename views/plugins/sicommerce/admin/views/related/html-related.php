<?php
$FormBuilder = new FormBuilder();

$product_selling_setting = option::get('product_selling');

if(empty($product_selling_setting['position']) || $product_selling_setting['position'] != 'disabled') {
    if(empty($product_selling_setting['type']) || $product_selling_setting['type'] != 'auto') {
        $FormBuilder->add('product_selling', 'popover-advance', ['label' => 'Sản phẩm bán chạy', 'search' => 'products'], $product_selling);
    }
}

$FormBuilder->add('product_related', 'popover-advance', [ 'label' => 'Sản phẩm liên quan', 'search' => 'products',
    'note' => 'Nếu không chọn sản phẩm liên quan sẽ được lấy từ sản phẩm cùng danh mục.',
], $product_related);

$FormBuilder->add('post_related', 'popover-advance', ['label'=> 'Tin tức liên quan', 'search' => 'post',], $post_related);

$FormBuilder = apply_filters('admin_product_field_related', $FormBuilder, $object);

$FormBuilder->html(false);