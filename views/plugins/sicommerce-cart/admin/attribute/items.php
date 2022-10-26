<?php
Metabox::add('admin_attribute_items', 'Attributes list', 'admin_attribute_items', ['module' => 'attribute']);
if(!function_exists('admin_attribute_items')) {
    function admin_attribute_items($object) {

        if(have_posts($object)) {

            $attribute_items = Attributes::getsItem(['attribute' => $object->id]);

            $attribute_items_tmp = [];

            foreach ($attribute_items as $item) {

                $attribute_items_tmp['a'.$item->id] = [
                    'id'    => $item->id,
                    'title' => $item->title,
                    'color' => $item->value,
                    'image' => $item->image,
                ];
            }
        }

        include_once 'views/html-attribute-list.php';
    }
}