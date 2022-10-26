<?php
Class Order_Email {
    static public function templateNewOrder($order, $email_id) {
        return cart_template('email/email-new-order', array( 'order' => $order, 'email_id' => $email_id ), true );
    }
    static public function templateCancelledOrder($order, $email_id) {
        return cart_template('email/email-admin-cancelled-order', array( 'order' => $order, 'email_id' => $email_id ), true );
    }
    static public function sendNewOrder($id) {

        $email = Option::get('cart_email');

        if(empty($email)) {
            $email = [
                'customer_order_new' 	=> 'on',
                'admin_order_new' 		=> 'on',
            ];
        }

        $order = Order::get( $id );

        $domain = Url::base();

        $domain = str_replace('https://', '', $domain);
        $domain = str_replace('http://', '', $domain);
        $domain = str_replace('www.', '', $domain);
        $domain = trim($domain, '/');

        if($email['customer_order_new'] == 'on' && !empty($order->billing_email)) {
            //Gửi cho khách hàng
            EmailHandler::send(Order_Email::templateNewOrder($order, 'new_order'), 'Đơn hàng mới '.$order->code, [
                'name'      => $order->billing_fullname,
                'from'      => 'noreply@'.$domain,
                'address'   => $order->billing_email,
            ]);
        }

        if($email['admin_order_new'] == 'on') {
            //Gửi cho admin
            EmailHandler::send(Order_Email::templateNewOrder($order, 'new_order'), 'Xác nhận đơn hàng '.$order->code, [
                'name'      => $order->billing_fullname,
                'from'      => 'noreply@'.$domain,
                'address'   => Option::get('contact_mail'),
            ]);
        }
    }
    static public function sendCancelledOrder($order) {
        $SendNotificationEmail = (int)get_instance()->input->post('SendNotificationEmail');
        if($SendNotificationEmail == 1) {
            $domain = Url::base();

            $domain = str_replace('https://', '', $domain);
            $domain = str_replace('http://', '', $domain);
            $domain = str_replace('www.', '', $domain);
            $domain = trim($domain, '/');
            EmailHandler::send(Order_Email::templateCancelledOrder($order, 'admin_cancelled'), 'Xác nhận hủy đơn hàng #'.$order->code, [
                'name'      => $order->billing_fullname,
                'from'      => 'noreply@'.$domain,
                'address'   => $order->billing_email,
            ]);
        }
    }
}
//Gửi Email khi đặt hàng thành công
add_action('checkout_after_success', 'Order_Email::sendNewOrder', 10, 1 );
//Gửi email khi hủy đơn hàng
add_action('admin_order_status_wc-cancelled_save', 'Order_Email::sendCancelledOrder', 10, 1);