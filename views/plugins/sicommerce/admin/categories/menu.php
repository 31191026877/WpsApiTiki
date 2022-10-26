<?php
function product_category_admin_menu($list_object) {
    $list_object['products_categories'] = [
        'label' => 'Danh mục sản phẩm', 'type' => 'products_categories', 'data' => []
    ];
    $list_object['products_categories']['data'] = [];
    $list_object['products_categories']['data'][0] = (object)array('id' => 0, 'name' => '<b>Sản phẩm</b>');
    $data = ProductCategory::gets(Qr::set()->categoryType('options'));
    if(have_posts($data)) {
        foreach ($data as $key => $datum) {
            if(is_string($datum) && is_numeric($key)) {
                if($key == 0) continue;
                $list_object['products_categories']['data'][$key] = (object)array('id' => $key, 'name' => $datum);
            }
            else {
                if(!isset($datum->id)) continue;
                $list_object['products_categories']['data'][$datum->id] = (object)array('id' => $datum->id, 'name' => $datum->name);
            }
        }
    }
    return $list_object;
}
add_filter('admin_menu_list_object',  'product_category_admin_menu', 10);