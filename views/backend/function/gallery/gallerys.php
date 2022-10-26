<?php 
/* TEMPLATE *********************************************************/
if(!function_exists('admin_object_gallery_form')) {
    function admin_object_gallery_form() {
       if(Template::galleryIsSupport()) {
           Admin::partial('function/gallery/html/gallery-box');
       }
    }
}
add_action( 'before_admin_form_left', 'admin_object_gallery_form', 50);
/* PROCESS DATA *********************************************************/
if(!function_exists('object_gallery_process_data')) {
    function object_gallery_process_data($data) {
        $ci =& get_instance();
        if(isset($ci->data['form_data_post']['gallery'])) {
            $data['gallery'] = $ci->data['form_data_post']['gallery'];
            unset($ci->data['form_data_post']['gallery']);
        }
        return $data;
    }
    add_filter( 'skd_form_process_data', 'object_gallery_process_data', 10, 2);
}
if(!function_exists('object_gallery_from_add') ) {
    function object_gallery_from_add($id, $module, $data_outside, $current_model, $ins_data) {

        $ci =& get_instance();

        if(isset($data_outside['gallery']) && count($data_outside['gallery'])) {

            foreach ($data_outside['gallery'] as $key => $gallery) {

                $file = [];

                $file['object_id'] 		= $id;

                $file['object_type'] 	= $ci->data['module'];

                $file['options']        = [];

                if($ci->data['module'] == 'post') $file['object_type'] = $ci->data['module'].'_'.$ci->postType;

                if($ci->data['module'] == 'post_categories') $file['object_type'] = $ci->data['module'].'_'.$ci->cateType;

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
    add_action( 'save_object_add', 'object_gallery_from_add', 10, 5);
}
if( !function_exists('object_gallery_from_edit') ) {
    /**
     * @ Save gallery item cho object
     * */
    function object_gallery_from_edit($id, $module, $data_outside, $current_model, $ins_data) {

        if(isset($data_outside['gallery']) && count($data_outside['gallery'])) {

            foreach ($data_outside['gallery'] as $item_id => $item) {

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
    add_action( 'save_object_edit', 'object_gallery_from_edit', 10, 5);
}

