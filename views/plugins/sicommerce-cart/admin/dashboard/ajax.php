<?php
Class Admin_Cart_Ajax_Dashboard {

    static public function order($ci, $model) {

        $result['message'] = 'Cập nhật dữ liệu thất bại.';

        $result['status'] = 'error';

        if(Request::post()) {

            $date_format = 'Y-m-d';
            //Hôm nay
            $start = date($date_format).' 00:00:00';

            $end = date($date_format).' 23:59:59';

            $order = Order::gets(Qr::set('created', '>=', $start)->where('created', '<=', $end));

            $result['today']['total'] = number_format($order->sum('total')). 'đ';

            $result['today']['count'] = $order->count(). ' đơn hàng';

            //Hôm qua
            $start = date($date_format,strtotime("-1 days")).' 00:00:00';

            $end = date($date_format,strtotime("-1 days")).' 23:59:59';

            $cache_yesterday = 'cart_dashboard_order__yesterday_'.md5($start.$end);

            if(!CacheHandler::has($cache_yesterday)) {

                $start = date($date_format,strtotime("-1 days")).' 00:00:00';

                $end = date($date_format,strtotime("-1 days")).' 23:59:59';

                $order = Order::gets(Qr::set('created', '>=', $start)->where('created', '<=', $end));

                $result['yesterday']['total'] = number_format($order->sum('total')). 'đ';

                $result['yesterday']['count'] = $order->count(). ' đơn hàng';

                CacheHandler::save($cache_yesterday, $result['yesterday']);
            }

            //Tuần rồi
            $start = date($date_format, strtotime('monday', strtotime('last week'))) . ' 00:00:00';

            $end = date($date_format, strtotime('sunday', strtotime('last week'))) . ' 23:59:59';

            $cache_week = 'cart_dashboard_order__week_'.md5($start.$end);

            if(!CacheHandler::has($cache_week)) {

                $start = date($date_format, strtotime('monday', strtotime('last week'))) . ' 00:00:00';

                $end = date($date_format, strtotime('sunday', strtotime('last week'))) . ' 23:59:59';

                $order = Order::gets(Qr::set('created', '>=', $start)->where('created', '<=', $end));

                $result['week']['total'] = number_format($order->sum('total')). 'đ';

                $result['week']['count'] = $order->count(). ' đơn hàng';

                CacheHandler::save($cache_week, $result['week']);
            }

            //Tháng trước
            $start = date($date_format, strtotime("first day of previous month")) . ' 00:00:00';

            $end = date($date_format, strtotime("last day of previous month")) . ' 23:59:59';

            $cache_month = 'cart_dashboard_order__month_'.md5($start.$end);

            if(!CacheHandler::has($cache_month)) {

                $start = date($date_format, strtotime("first day of previous month")) . ' 00:00:00';

                $end = date($date_format, strtotime("last day of previous month")) . ' 23:59:59';

                $order = Order::gets(Qr::set('created', '>=', $start)->where('created', '<=', $end));

                $result['month']['total'] = number_format($order->sum('total')). 'đ';

                $result['month']['count'] = $order->count(). ' đơn hàng';

                CacheHandler::save($cache_month, $result['month']);
            }

            $result['yesterday'] = CacheHandler::get($cache_yesterday);

            $result['week']     = CacheHandler::get($cache_week);

            $result['month']    = CacheHandler::get($cache_month);

            $start  = date($date_format, strtotime("first day of")).' 00:00:00';

            $end    = date($date_format, strtotime("last day of")).' 23:59:59';

            $query = get_model()->query("SELECT `pr`.`title`, `pr`.`image`, `pr`.`price`, `pr`.`price_sale`, SUM(`od`.`quantity`) as `total_quantity`, SUM(`od`.`subtotal`) as `subtotal` FROM `cle_order_detail` as `od` JOIN `cle_products` as `pr` ON `pr`.`id` = `od`.`product_id` WHERE `od`.`created` >= '".$start."' AND `od`.`created` <= '".$end."' GROUP BY `od`.`product_id` ORDER BY `total_quantity` DESC LIMIT 6");

            $result['bestseller'] = [];

            foreach ($query as $item) {
                $item->price = number_format($item->price)._price_currency();
                $item->subtotal = number_format($item->subtotal)._price_currency();
                $result['bestseller'][] = $item;
            }

            $result['status'] = 'success';
        }

        echo json_encode($result);

        return true;
    }
}
Ajax::admin('Admin_Cart_Ajax_Dashboard::order');