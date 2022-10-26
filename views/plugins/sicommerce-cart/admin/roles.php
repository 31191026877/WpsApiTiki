<?php
Class Cart_Roles {
    static public function group( $group ) {
        $group['order'] = [
            'label' => __('Đơn hàng'),
            'capabilities' => array_keys(Cart_Roles::capabilitiesOrder())
        ];

        $group['customer'] = [
            'label' => __('Khách hàng'),
            'capabilities' => array_keys(Cart_Roles::capabilitiesCustomer())
        ];

        if(isset($group['product'])) {

            $group['product']['capabilities'] = array_merge(
                $group['product']['capabilities'],
                array_keys(Cart_Roles::capabilitiesProductAttributes())
            );
        }
        return $group;
    }
    static public function label( $label ) {
        $label = array_merge($label, Cart_Roles::capabilitiesOrder() );
        $label = array_merge($label, Cart_Roles::capabilitiesCustomer() );
        return array_merge($label, Cart_Roles::capabilitiesProductAttributes() );
    }
    static public function capabilitiesOrder() {
        $label['order_list']   = 'Quản lý đơn hàng';
        $label['order_add']    = 'Thêm đơn hàng mới';
        $label['order_copy']   = 'Nhân bản đơn hàng';
        $label['order_edit']   = 'Cập nhật đơn hàng';
        $label['order_delete'] = 'Xóa đơn hàng';
        $label['order_setting'] = 'Quản lý cài đặt đơn hàng';
        return $label;
    }
    static public function capabilitiesCustomer() {
        $label['customer_list']           = 'Quản lý khách hàng';
        $label['customer_active']         = 'Kích hoạt tài khoản khách hàng';
        $label['customer_add']            = 'Thêm khách hàng mới';
        $label['customer_edit']           = 'Cập nhật thông tin khách hàng';
        $label['customer_reset_password'] = 'Reset mật khẩu khách hàng';
        $label['customer_block']  = 'Block khách hàng';
        return $label;
    }
    static public function capabilitiesProductAttributes() {
        $label['attributes_list']   = 'Quản lý tùy chọn';
        $label['attributes_add']    = 'Thêm tùy chọn';
        $label['attributes_edit']   = 'Cập nhật tùy chọn';
        $label['attributes_delete'] = 'Xóa tùy chọn';
        return $label;
    }
}

add_filter('user_role_editor_group', 'Cart_Roles::group' );
add_filter('user_role_editor_label', 'Cart_Roles::label' );