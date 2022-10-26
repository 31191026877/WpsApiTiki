<?php
Class Admin_Cart_Ajax_Report {
    static public function reportTime() {

        $result['status'] = 'error';

        $result['message'] = 'Cập nhật dữ liệu không thành công!';

        if(Request::post()) {

            $time  = Str::clear(Request::Post('time'));

            $start_date = 0;

            $end_date = 0;

            if(!empty($time)) {
                $time = explode(' - ', $time);
                if(have_posts($time) && count($time) == 2) {
                    $start_date = strtotime(date('Y-m-d', strtotime($time[0])).' 00:00:00');
                    $end_date   = strtotime(date('Y-m-d', strtotime($time[1])).' 23:59:59');
                }
            }

            if(empty($start_date) && empty($end_date)) {

                $time = time();

                $start_date = strtotime('monday this week', $time);

                $end_date   = strtotime('sunday this week', $time);

                $start_date = strtotime(date('d-m-Y', $start_date).' 00:00:00');

                $end_date   = strtotime(date('d-m-Y', $end_date).' 23:59:59');
            }

            $heading = 'từ '.date('d/m/Y', $start_date).' đến '.date('d/m/Y', $end_date);

            $date_format = 'Y-m-d';
            //Hôm nay
            $start  = date($date_format, $start_date).' 00:00:00';
            $end    = date($date_format, $end_date).' 23:59:59';

            $result['revenue']  = number_format(Order::gets(Qr::set('created','>=', $start)->where('created','<=', $end)->where('status_pay', 'paid'))->sum('total'));

            $result['new']      = Order::count(Qr::set('created','>=', $start)->where('created','<=', $end));

            $result['refunded'] = Order::count(Qr::set('created','>=', $start)->where('created','<=', $end)->where('status_pay', 'refunded'));

            $result['cancel']   = Order::count(Qr::set('created','>=', $start)->where('created','<=', $end)->where('status', ORDER_CANCELLED));

            $result['list']     = [];

            for($i = $start_date; $i <= $end_date; $i += 24*60*60) {
                $result['list'][$i] = ['time' => date('d-m-Y', $i), 'count' => 0, 'total' => 0, 'shipping' => 0, 'cancel' => 0, 'revenue' => 0];
                $start  = date($date_format, $i).' 00:00:00';
                $end    = date($date_format, $i).' 23:59:59';
                $orders = Order::gets(Qr::set('created','>=', $start)->where('created','<=', $end));
                if(have_posts($orders)) {
                    foreach ($orders as $order) {
                        $result['list'][$i]['count']++;
                        $result['list'][$i]['total'] += $order->total;
                        if(!empty($order->_shipping_price)) {
                            $result['list'][$i]['total'] -= $order->_shipping_price;
                            $result['list'][$i]['shipping'] += $order->_shipping_price;
                        }
                        if($order->status_pay == 'paid' && $order->status != ORDER_CANCELLED) {
                            $result['list'][$i]['revenue'] += $order->total;
                        }
                        if($order->status == ORDER_CANCELLED) {
                            $result['list'][$i]['cancel'] += $order->total;
                        }
                    }
                }
                $result['list'][$i]['total'] = number_format($result['list'][$i]['total']);
                $result['list'][$i]['shipping'] = number_format($result['list'][$i]['shipping']);
                $result['list'][$i]['cancel'] = number_format($result['list'][$i]['cancel']);
                $result['list'][$i]['revenue'] = number_format($result['list'][$i]['revenue']);
            }

            $result['status'] = 'success';
            $result['heading'] = $heading;
        }

        echo json_encode($result);
    }
    static public function reportProduct() {

        $result['status'] = 'error';

        $result['message'] = 'Cập nhật dữ liệu không thành công!';

        if(Request::post()) {

            $time  = Str::clear(Request::Post('time'));

            $start_date = 0;

            $end_date = 0;

            if(!empty($time)) {
                $time = explode(' - ', $time);
                if(have_posts($time) && count($time) == 2) {
                    $start_date = strtotime(date('Y-m-d', strtotime($time[0])).' 00:00:00');
                    $end_date   = strtotime(date('Y-m-d', strtotime($time[1])).' 23:59:59');
                }
            }

            if(empty($start_date) && empty($end_date)) {

                $time = time();

                $start_date = strtotime('monday this week', $time);

                $end_date   = strtotime('sunday this week', $time);

                $start_date = strtotime(date('d-m-Y', $start_date).' 00:00:00');

                $end_date   = strtotime(date('d-m-Y', $end_date).' 23:59:59');
            }

            $heading = 'từ '.date('d/m/Y', $start_date).' đến '.date('d/m/Y', $end_date);

            $date_format = 'Y-m-d';
            //Hôm nay
            $start  = date($date_format, $start_date).' 00:00:00';

            $end    = date($date_format, $end_date).' 23:59:59';

            $query = get_model()->query("SELECT `pr`.`title`, `pr`.`image`, `pr`.`price`, `pr`.`price_sale`, SUM(`od`.`quantity`) as `total_quantity`, SUM(`od`.`subtotal`) as `subtotal` FROM `cle_order_detail` as `od` JOIN `cle_products` as `pr` ON `pr`.`id` = `od`.`product_id` WHERE `od`.`created` >= '".$start."' AND `od`.`created` <= '".$end."' GROUP BY `od`.`product_id` ORDER BY `total_quantity` DESC LIMIT 6");

            $result['list'] = [];

            foreach ($query as $item) {
                $item->price    = number_format($item->price)._price_currency();
                $item->subtotal = number_format($item->subtotal)._price_currency();
                $result['list'][] = $item;
            }

            $result['status'] = 'success';

            $result['heading'] = $heading;
        }

        echo json_encode($result);
    }
}
Ajax::admin('Admin_Cart_Ajax_Report::reportTime');
Ajax::admin('Admin_Cart_Ajax_Report::reportProduct');