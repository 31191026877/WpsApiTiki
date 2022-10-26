<?php
class GalleryObjectAdmin {
    static function form() {
        if(Template::galleryIsSupport()) {
            Admin::partial('function/gallery/html/gallery-box');
        }
    }
    static function add($id, $module, $data_outside, $current_model, $ins_data) {
        $galleries = Request::post('gallery');
        if(have_posts($galleries)) {
            foreach ($galleries as $gallery) {
                $file = [];
                $file['object_id'] 		= $id;
                $file['object_type'] 	= $module;
                $file['options']        = [];
                if($module == 'post') $file['object_type'] = $module.'_'.Admin::getPostType();
                if($module == 'post_categories') $file['object_type'] = $module.'_'.Admin::getCateType();
                foreach ($gallery as $field => $value) {
                    if($field == 'value') {
                        $file['value'] 	= $value;
                        $file['type']= FileHandler::type($value);
                    }
                    if($field == 'order') {
                        $file['order'] 	= $value;
                    }
                    if($field == 'option') {
                        $file['options']  = $value;
                    }
                }
                Gallery::insertItem($file);
            }
        }
    }
    static function save($id, $module, $data_outside, $current_model, $ins_data) {
        $galleries = Request::post('gallery');
        if(have_posts($galleries)) {
            foreach ($galleries as $item_id => $item) {
                $file = [];
                $file['id'] 		= $item_id;
                $file['order'] 		= $item['order'];
                $file['value'] 		= $item['value'];
                $file['options']    = [];
                if(!empty($item['option'])) {
                    $file['options']    = $item['option'];
                }
                Gallery::insertItem($file);
            }
        }
    }
}
add_action('before_admin_form_left', 'GalleryObjectAdmin::form', 50);
add_action('save_object_add', 'GalleryObjectAdmin::add', 10, 5);
add_action('save_object_edit', 'GalleryObjectAdmin::save', 10, 5);

