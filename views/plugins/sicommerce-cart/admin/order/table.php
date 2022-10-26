<?php
class skd_order_list_table extends SKD_list_table {

    function get_columns() {
        $this->_column_headers = array(
            'cb'               => 'cb',
            'code'             => 'Đơn hàng',
            'created'          => 'Ngày tạo',
            'billing_fullname' => 'Khách hàng',
            'billing_phone'    => 'Điện thoại',
            'status'           => 'Tình trạng',
            'status_pay'       => 'Thanh toán',
            'shipping_online'  => 'Vận chuyển',
            'total'            => 'Tổng tiền',
            'action'           => 'Thao tác',
        );
        if(!have_posts(shipping_gateways())) unset($this->_column_headers['shipping_online']);
        $this->_column_headers = apply_filters( "manage_woocomerce_order_columns", $this->_column_headers );
        $this->_column_headers = apply_filters( "manage_order_columns", $this->_column_headers );
        $this->_column_headers['action'] = 'Hành Động';
        return $this->_column_headers;
    }

    public function single_row( $item ) {
        echo '<tr class="tr_'.$item->id.' '.$item->status.'">';
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    function _column_code($item, $column_name, $module, $table, $class) {
        $url = Url::admin('plugins?page=order&view=detail&id='.$item->id);
        $class .= '';
        echo '<td class="'.$class.'">';
        echo '<a href="'.$url.'" style="font-weight:bold;">#'.$item->code.'</a>';
        echo "</td>";
    }

    function _column_created($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo date('d-m-Y', strtotime($item->created));
        echo "</td>";
    }

    function _column_billing_fullname($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo '<p>'.$item->billing_fullname.'</p>';
        echo '<p>'.$item->billing_email.'</p>';
        echo "</td>";
    }

    function _column_billing_phone($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo $item->billing_phone;
        echo "</td>";
    }

    function _column_status($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo '<span style="background-color:'.Order::status($item->status, 'color').'; border-radius:20px; padding:3px 15px; font-size:12px; display:inline-block;color:#000;">'.Order::status($item->status, 'label').'</span>';
        echo "</td>";
    }

    function _column_status_pay($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo '<span style="background-color:'.Order::statusPay($item->status_pay, 'color').'; border-radius:20px; padding:3px 15px; font-size:12px; display:inline-block;color:#000;">'.Order::statusPay($item->status_pay, 'label').'</span>';
        echo "</td>";
    }

    function _column_total($item, $column_name, $module, $table, $class) {
        $class .= '';
        echo '<td class="'.$class.'">';
        echo number_format($item->total)._price_currency().'</b>';
        echo "</td>";
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url = Url::admin('plugins?page=order&view=detail&id='.$item->id);
        $class .= ' text-center';
        echo '<td class="'.$class.'" style="width:200px;">';
        echo '<a href="'.$url.'" class="btn btn-blue">'.Admin::icon('edit').'</a>';
        do_action('admin_order_table_column_action', $item);
        if(Auth::hasCap('order_delete') ) {
            echo Admin::btnDelete([
                'id' => $item->id,
                'heading' => 'Xác nhận xóa đơn hàng',
                'des' => 'Đơn hàng '.$item->code.' không thể khôi phục khi đã xóa.',
                'trash' => 'disable',
                'module' => 'order'
            ]);
        }
        echo "</td>";
    }

    function column_default($column_name, $item, $global) {
        do_action('manage_orders_custom_column', $column_name, $item, $global);
        do_action('manage_order_custom_column', $column_name, $item, $global);
    }
}