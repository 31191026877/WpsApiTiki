<?php
include_once 'action-bar.php';
include_once 'popover.php';
include_once 'table.php';

Class BrandsAdmin {
    static function page(): void {

        $view = Request::get('view');

        if(empty($view)) {

            $table = new AdminBrandsTable([
                'items' => Brands::gets(),
                'table' => 'brands',
                'model' => model('brands'),
                'module'=> 'brands',
            ]);

            include PRODUCT_PATH.'admin/views/brands/html-brands-index.php';
        }
        else if($view == 'add') {
            Admin::creatForm('brands');
            include PRODUCT_PATH.'admin/views/brands/html-brands-save.php';
        }
        else if($view == 'edit') {
            $id     = (int)Request::get('id');
            $object = Brands::get($id);
            if(have_posts($object)) {
                Admin::creatForm('brands', $object);
                include PRODUCT_PATH.'admin/views/brands/html-brands-save.php';
            }
        }
    }
    static function form(FormAdmin $form): FormAdmin {
        $form->setParams('redirect', URL_ADMIN.'/plugins?page=brands');
        $form->lang
            ->addGroup('info','Thông Tin')
            ->addFieldLang('name', 'text', ['label' => 'Tiêu đề', 'note' 	=> 'Tiêu đề bài viết được lấy làm thẻ H1'])
            ->addFieldLang('excerpt', 'wysiwyg-short', ['label' => 'Mô tả']);
        $form->right
            ->addGroup('media', 'Hình Ảnh')
            ->addField('image', 'image', ['label' => 'Ảnh đại diện', 'display' => 'inline']);

        $form->right
            ->addGroup('seo', 'Seo')
            ->addField('slug', 'text', ['label' => 'Slug'])
            ->addField('seo_title', 'text', ['label' => 'Meta title'])
            ->addField('seo_keywords', 'text', ['label' => 'Meta Keyword'])
            ->addField('seo_description', 'text', ['label' => 'Meta Description']);
        return $form;
    }
    static function save($id, $insertData) {
        return Brands::insert($insertData);
    }
    static function menu($listObject) {
        $listObject['brands'] = array ( 'label' => 'Thương hiệu', 'type' => 'brands', 'data' => []);
        $listObject['brands']['data'] = [];
        $data = Brands::gets();
        if(have_posts($data)) {
            foreach ($data as $key => $datum) {
                if(!isset($datum->id)) continue;
                $listObject['brands']['data'][$datum->id] = (object)array('id' => $datum->id, 'name' => $datum->name);
            }
        }
        return $listObject;
    }
}
add_filter('manage_brands_input', 'BrandsAdmin::form');
add_filter('form_submit_brands', 'BrandsAdmin::save', 10, 2);
if(!empty(Option::get('product_brands'))) {
    add_filter('admin_menu_list_object',  'BrandsAdmin::menu', 10);
}