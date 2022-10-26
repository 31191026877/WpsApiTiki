<?php
//suppliers
class AdminSuppliersTable extends skd_object_list_table {

    function get_columns() {
        $this->_column_headers = [];
        $this->_column_headers['cb']        = 'cb';
        $this->_column_headers['name']      = 'Tên nhà sản xuất';
        $this->_column_headers['email']     = 'Email';
        $this->_column_headers['address']   = 'Địa chỉ';
        $this->_column_headers['action']   = 'Hành động';
        return apply_filters( "manage_suppliers_columns", $this->_column_headers );
    }

    function column_default($column_name, $item, $global) {
       do_action( 'manage_suppliers_custom_column', $column_name, $item, $global);
    }

    function column_name($item, $column_name, $module, $table) {
        echo $item->name;
    }

    function column_email($item, $column_name, $module, $table) {
        echo $item->email;
    }

    function column_address($item, $column_name, $module, $table) {
        echo $item->address;
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $class .= ' text-center';
        echo '<td class="'.$class.'">';
        echo '<a href="'.Url::admin('plugins?page=suppliers&view=edit&id='.$item->id).'" class="btn-blue btn">'.Admin::icon('edit').'</a>';
        echo Admin::btnDelete(['trash' => 'disable', 'id' => $item->id, 'module' => 'Suppliers', 'des' => 'Bạn chắc chắn muốn xóa nhà sản xuất <b>'.Str::clear($item->name).'</b> ?']);
        echo '</td>';
    }

    function search_left() {
        echo Admin::btnDelete(['trash' => 'disable', 'module' => 'Suppliers', 'style' => 'display: none;']);
    }

    function search_right(){}
}