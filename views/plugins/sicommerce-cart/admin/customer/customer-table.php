<?php
function customer_column_header( $columns ) {
    $columnsnew = [];
    foreach ($columns as $key => $column) {
        if($key == 'role') {
            $columnsnew['order_count']      = 'Đơn hàng';
            $columnsnew['order_count_now']  = 'Đơn hàng gần nhất';
            $columnsnew['order_total']      = 'Tổng chi tiêu';
        }
        $columnsnew[$key] = $column;
    }
    return $columnsnew;
}
add_filter( 'manage_user_columns', 'customer_column_header');

function customer_column_data( $column_name, $item ) {
    switch ( $column_name ) {
        case 'order_count':
            echo $item->order_count;
            break;
        case 'order_count_now':
            echo '#'.User::getMeta($item->id, 'order_recent', true);
            break;
        case 'order_total':
            echo number_format($item->order_total).' đ';
            break;
    }
}
add_action( 'manage_user_custom_column', 'customer_column_data',10,2);