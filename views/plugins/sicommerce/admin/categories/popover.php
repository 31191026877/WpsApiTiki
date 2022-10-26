<?php
Class Product_Category_Admin_Popover {
    static public function search($object, $keyword) {
        return ProductCategory::gets(Qr::set('name', 'like', $keyword));
    }
    static public function value($value, $data) {
        if(isset($data['image']) && $data['image'] == true) {

            if(!empty($data['value']) && is_array($data['value'])) {
                $categories = ProductCategory::gets(Qr::set()->whereIn('id', $data['value'])->select('id', 'name', 'image'));
                foreach ($categories as $category) {
                    $value[$category->id] = [
                        'label' => $category->name,
                        'image' => $category->image
                    ];
                }
            }

            if(!empty($data['value']) && is_string($data['value'])) {
                $category = ProductCategory::get(Qr::set($data['value'])->select('id', 'name'));
                if(have_posts($category)) {
                    $value[$category->id] = [
                        'label' => $category->name,
                        'image' => $category->image
                    ];
                }
            }
        }
        else {

            if(!empty($data['value']) && is_array($data['value'])) {

                foreach ($data['options'] as $op_id => $op_value ) {
                    if(in_array($op_id, $data['value']) === true) {
                        $value[$op_id] = ['label' => $op_value];
                        unset($data['value'][array_search($op_id, $data['value'])]);
                    }
                }

                if(have_posts($data['value'])) {
                    $categories = ProductCategory::gets(Qr::set()->whereIn('id', $data['value'])->select('id', 'name'));
                    foreach ($categories as $category) {
                        $value[$category->id] = ['label' => $category->name];
                    }
                }
            }

            if(!empty($data['value']) && is_string($data['value'])) {

                foreach ($data['options'] as $op_id => $op_value ) {
                    if($op_id == $data['value']) {
                        $value[$op_id] = ['label' => $op_value];
                        unset($data['value']);
                    }
                }

                if(!empty($data['value'])) {
                    $category = ProductCategory::get(Qr::set($data['value'])->select('id', 'name'));
                    if(have_posts($category)) {
                        $value[$category->id] = ['label' => $category->name];
                    }
                }
            }
        }
        return $value;
    }
}
add_filter('input_popover_products_categories_search', 'Product_Category_Admin_Popover::search', 10, 2);
add_filter('input_popover_products_categories_value', 'Product_Category_Admin_Popover::value', 10, 2);
