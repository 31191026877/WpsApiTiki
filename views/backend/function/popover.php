<?php
function popover_post_categories_value($value, $data) {
    if(isset($data['image']) && $data['image'] == true) {

        if(!empty($data['value']) && is_array($data['value'])) {
            $categories = PostCategory::gets(Qr::set()->whereIn('id', $data['value'])->select('id', 'name', 'image'));
            foreach ($categories as $category) {
                $value[$category->id] = [
                    'label' => $category->name,
                    'image' => $category->image
                ];
            }
        }

        if(!empty($data['value']) && is_string($data['value'])) {
            $category = PostCategory::get(Qr::set($data['value'])->select('id', 'title'));
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
                $categories = PostCategory::gets(Qr::set()->whereIn('id', $data['value'])->select('id', 'name'));
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
                $category = PostCategory::get(Qr::set($data['value'])->select('id', 'title'));
                if(have_posts($category)) {
                    $value[$category->id] = ['label' => $category->name];
                }
            }
        }
    }
    return $value;
}
add_filter('input_popover_post_categories_value', 'popover_post_categories_value', 10, 2);

function popover_post_value($value, $data) {

    if(isset($data['image']) && $data['image'] == true) {

        if(!empty($data['value']) && is_array($data['value'])) {
            $posts = Posts::gets(Qr::set()->whereIn('id', $data['value'])->select('id', 'title', 'image'));
            foreach ($posts as $post) {
                $value[$post->id] = [
                    'label' => $post->title,
                    'image' => $post->image
                ];
            }
        }

        if(!empty($data['value']) && is_string($data['value'])) {
            $post = Posts::get(Qr::set($data['value'])->select('id', 'title'));
            if(have_posts($post)) {
                $value[$post->id] = [
                    'label' => $post->title,
                    'image' => $post->image
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
                $posts = Posts::gets(Qr::set()->whereIn('id', $data['value'])->select('id', 'title'));
                foreach ($posts as $post) {
                    $value[$post->id] = ['label' => $post->title];
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
                $post = Posts::get(Qr::set($data['value'])->select('id', 'title'));
                if(have_posts($post)) {
                    $value[$post->id] = ['label' => $post->title];
                }
            }
        }
    }

    return $value;
}
add_filter('input_popover_post_value', 'popover_post_value', 10, 2);



