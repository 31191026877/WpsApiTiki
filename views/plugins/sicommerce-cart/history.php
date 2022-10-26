<?php
/**
 * history.
 *
 * @since 2.6.0
 */
Class Order_History {
    static public function add($id) {
        $order = Order::get($id);
        if(have_posts($order)) {
            $history = [ 'order_id' => $id, 'action' => 'frontend-add', 'message'  => OrderHistory::message('frontend-add', ['code' => '#'.$order->code]) ];
            OrderHistory::insert($history);
        }
    }
    static public function adminAdd($id) {

        $user  = Auth::user();

        $order = Order::get($id);

        if(have_posts($order)) {

            $history = [
                'order_id' => $id,
                'action' => 'backend-add',
                'message'  => OrderHistory::message('backend-add', ['username' => $user->username, 'code' => '#'.$order->code])
            ];

            OrderHistory::insert($history);
        }
    }
    static public function status($order) {
        if(have_posts($order)) {

            //Trạng thái
            $status = Request::post('status');

            $status = trim(Str::clear($status));

            if( $order->status != $status ) {

                $user  	= Auth::user();

                $list_status = Order::status();

                if(!empty($list_status[$status]['label'])) {
                    $history = [
                        'order_id' => $order->id,
                        'action' => 'backend-status',
                        'message'  => OrderHistory::message('backend-status', ['username' => $user->username, 'code' => '#'.$order->code, 'status' => $list_status[$status]['label']])
                    ];
                    OrderHistory::insert($history);
                }
            }

            //Thanh toán
            $status_pay = Request::post('status-pay');

            $status_pay = trim(Str::clear($status_pay));

            if( $order->status_pay != $status_pay ) {

                $user  	= Auth::user();

                $list_status = Order::statusPay();

                if(!empty($list_status[$status_pay]['label'])) {
                    $history = [
                        'order_id' => $order->id,
                        'action' => 'backend-status',
                        'message'  => OrderHistory::message('backend-status', ['username' => $user->username, 'code' => '#'.$order->code, 'status' => $list_status[$status_pay]['label']])
                    ];
                    OrderHistory::insert($history);
                }
            }
        }
    }
    static public function cancelled($order, $reason) {

        if(have_posts($order)) {

            $user  	= Auth::user();

            $history = [
                'order_id' => $order->id,
                'action' => 'backend-cancelled',
                'message'  => OrderHistory::message('backend-cancelled', ['username' => $user->username, 'reason' => $reason])
            ];

            OrderHistory::insert($history);
        }
    }
}
add_action('checkout_order_after_save', 'Order_History::add', 1, 1 );
add_action('admin_order_add_after_save', 'Order_History::adminAdd', 1, 1 );
add_action('admin_order_action_post', 'Order_History::status', 1, 1 );
add_action('admin_order_status_'.ORDER_CANCELLED.'_save', 'Order_History::cancelled', 1, 2 );