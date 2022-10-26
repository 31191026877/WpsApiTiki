<?php
include_once 'action-bar.php';
include_once 'popover.php';
include_once 'table.php';

Class SuppliersAdmin {
    static function page(): void {

        $view = Request::get('view');

        if(empty($view)) {

            $table = new AdminSuppliersTable([
                'items' => Suppliers::gets(),
                'table' => 'suppliers',
                'model' => model('suppliers'),
                'module'=> 'suppliers',
            ]);

            include PRODUCT_PATH.'admin/views/suppliers/html-suppliers-index.php';
        }
        else if($view == 'add') {
            Admin::creatForm('suppliers');
            include PRODUCT_PATH.'admin/views/suppliers/html-suppliers-save.php';
        }
        else if($view == 'edit') {
            $id     = (int)Request::get('id');
            $object = Suppliers::get($id);
            if(have_posts($object)) {
                Admin::creatForm('suppliers', $object);
                include PRODUCT_PATH.'admin/views/suppliers/html-suppliers-save.php';
            }
        }
    }
    static function form(FormAdmin $form): FormAdmin {
        $form->setParams('redirect', URL_ADMIN.'/plugins?page=suppliers');
        $form->lang
            ->addGroup('info','Thông Tin')
            ->addFieldLang('name', 'text', ['label' => 'Tên nhà sản xuất'])
            ->addFieldLang('excerpt', 'wysiwyg-short', ['label' => 'Mô tả']);

        $form->right
            ->addGroup('media', 'Media')
            ->addField('image', 'image', ['label' => 'Ảnh đại diện', 'display' => 'inline']);

        $form->right
            ->addGroup('information', 'Thông tin nhà sản xuất')
            ->addField('firstname', 'text', [
                'after' => '<div class="col-md-6" id="box_firstname"><label for="firstname" class="control-label">Họ</label><div class="form-group group">',
                'before' => '</div></div>'
            ])
            ->addField('lastname', 'text', [
                'after'=>'<div class="col-md-6" id="box_lastname"><label for="lastname" class="control-label">Tên</label><div class="form-group group">',
                'before' => '</div></div>'
            ])
            ->addField('email', 'email', ['label' => 'Email'])
            ->addField('phone', 'tel', ['label' => 'Số điện thoại'])
            ->addField('address', 'text', ['label' => 'Địa chỉ']);

        $form->right
            ->addGroup('seo', 'Seo')
            ->addField('slug', 'text', ['label' => 'Slug'])
            ->addField('seo_title', 'text', ['label' => 'Meta title'])
            ->addField('seo_keywords', 'text', ['label' => 'Meta Keyword'])
            ->addField('seo_description', 'text', ['label' => 'Meta Description']);
        return $form;
    }
    static function save($id, $insertData) {
        return Suppliers::insert($insertData);
    }
}
add_filter('manage_suppliers_input', 'SuppliersAdmin::form');
add_filter('form_submit_suppliers', 'SuppliersAdmin::save', 10, 2);
