<?php
Class Product_Roles {
    static public function group($group) {
        $group['product'] = [
            'label' => __('Sản phẩm'),
            'capabilities' => array_keys(Product_Roles::capabilities())
        ];
        return $group;
    }
    static public function label($label) {
        return array_merge($label, Product_Roles::capabilities());
    }
    static public function capabilities() {
        $label['product_list']        = 'Quản lý sản phẩm';
        $label['product_edit']        = 'Thêm / Cập nhật sản phẩm';
        $label['product_delete']      = 'Xóa sản phẩm';

        $label['product_cate_list']   = 'Quản lý danh mục sản phẩm';
        $label['product_cate_edit']   = 'Thêm / Cập nhật danh mục sản phẩm';
        $label['product_cate_delete'] = 'Xóa danh mục sản phẩm';

        $label['product_setting']     = 'Quản lý cài đặt sản phẩm';
        return $label;
    }
}
add_filter('user_role_editor_group', 'Product_Roles::group');
add_filter('user_role_editor_label', 'Product_Roles::label');