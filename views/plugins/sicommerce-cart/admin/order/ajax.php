<?php
Class Admin_Order_Ajax {
    static public function prints($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Load dữ liệu không thành công';

        if(Request::post()) {

            $id = Request::post('id');

            if(!empty($id)) {

                $orders = [];

                if(is_numeric($id)) {
                    $orders = Order::gets($id);
                }
                if(have_posts($id)) {
                    $orders = Order::gets(Qr::set()->whereIn('id', $id));
                }

                if(have_posts($orders)) {
                    ob_start();
                    foreach ($orders as $order) {
                        cart_template('admin/order/html-order-print', array('order' => $order));
                    }
                    $result['html'] = ob_get_contents();
                    ob_end_clean();
                    $result['status'] = 'success';
                    $result['message'] = 'Load dữ liệu thành công';
                }
                else $result['message'] = 'Dữ liệu không tồn tại!';
            }
        }

        echo json_encode($result);
    }
    static public function cancelled($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Load dữ liệu không thành công!';

        if(Request::post()) {

            $id  = (int)Request::post('id');

            $reason = Str::clear(Request::post('reason'));

            $order = Order::get($id);

            if(have_posts($order)) {

                do_action('admin_order_status_wc-cancelled_after', $order);

                $messages = Cart_Notice::get( 'error' );

                if( have_posts($messages) ) {
                    $result['message'] = '';
                    foreach ($messages as $message) {
                        $result['message'] .= '<p>'.Cart_Notice::printLabel( $message, 'error' ).'</p>';
                    }
                    echo json_encode( $result );
                    die;
                }

                $order_update = [
                    'id'     => $order->id,
                    'status' => ORDER_CANCELLED,
                ];

                $errors = Order::insert( $order_update );

                if( !is_skd_error( $errors ) ) {

                    $order = Order::get($id);

                    Order::updateMeta($order->id, 'time_cancelled', time());

                    Order::updateMeta($order->id, 'reason_cancelled', $reason);

                    do_action( 'admin_order_status_wc-cancelled_save', $order, $reason );

                    do_action( 'admin_order_action_wc-cancelled', $order, $reason );

                    $result['status']     = 'success';

                    $result['message'] = 'Đơn hàng đã bị hủy!';

                    $result = apply_filters('admin_order_save_result_success', $result, $id );
                }
                else {
                    foreach ($errors->errors as $error_key => $error_value) {
                        $result['message'] = $error_value[0];
                    }
                }
            }
        }

        echo json_encode( $result );
    }
    static public function loadDetailShippingList($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Cập nhật dữ liệu không thành công!';

        if(Request::post()) {
            $id = (int)Request::post('id');
            $order = Order::get($id);
            if(have_posts($order)) {
                $result['shipping_list'] 	= cart_template('admin/order/detail/shipping-list', ['order' => $order,],true);
                $result['status'] 			= 'success';
                $result['message'] 			= 'Cập nhật dữ liệu thành công!';
            }
            else {
                $result['message'] = 'Dữ liệu không tồn tại!';
            }
        }

        echo json_encode($result);
    }
    static public function updateDetailShipping($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Cập nhật dữ liệu không thành công!';

        if(Request::post()) {

            $id = (int)Request::post('id');

            $order = Order::get($id);

            if(have_posts($order)) {

                if($order->status != ORDER_WAIT && $order->status != ORDER_CONFIRM) {
                    $result['message'] = 'Đơn hàng này không thể thay đổi hình thức vận chuyển';
                    echo json_encode($result);
                    return false;
                }

                $shipping_type = Request::post('shipping_type');

                $shipping_type = explode('__', $shipping_type);

                if(!have_posts($shipping_type) || count($shipping_type) != 2) {
                    $result['message'] = 'Phương thức giao hàng chưa đúng.';
                    echo json_encode($result);
                    return false;
                }

                $ship = shipping_gateways($shipping_type[0]);

                if(!have_posts($ship)) {
                    $result['message'] = 'Phương thức giao hàng chưa đúng.';
                    echo json_encode($result);
                    return false;
                }

                if(method_exists($ship['class'],'change')) {

                    $error = call_user_func($ship['class'].'::change', $ship, $order, $shipping_type);

                    if(!is_skd_error($error)) {
                        $result['status'] = 'success';
                    }
                }
                else {

                    $result['message'] = 'Phương thức <b>'.$ship['label'].'</b> chưa hỗ trợ chuyển đổi.';

                    echo json_encode($result);

                    return false;
                }
            }
            else {

                $result['message'] = 'Dữ liệu không tồn tại!';
            }
        }

        echo json_encode($result);
    }
}

Ajax::admin('Admin_Order_Ajax::loadDetailShippingList');
Ajax::admin('Admin_Order_Ajax::updateDetailShipping');
Ajax::admin('Admin_Order_Ajax::cancelled');
Ajax::admin('Admin_Order_Ajax::prints');

Class Admin_Order_Ajax_Add {
    static public function loadCustomer($ci, $model) {

        $result['type'] = 'error';

        $result['message'] = 'Cập nhật dữ liệu không thành công!';

        if(Request::post()) {

            $id = (int)Request::post('id');

            $customer = User::get($id);

            if(have_posts($customer)) {

                $result['customer_review'] 	= cart_template('admin/order/save/customer-infomation', [
                    'customer' 	=> $customer,
                ],true);

                $result['type'] 			= 'success';

                $result['message'] 			= 'Cập nhật dữ liệu thành công!';

            }
            else {
                if($id == 0) {

                    $result['customer_review'] 	= cart_template('admin/order/save/customer-infomation', ['customer' 	=> []],true);

                    $result['type'] 			= 'success';
                }
                else $result['message'] = 'Dữ liệu không tồn tại!';
            }
        }

        echo json_encode($result);
    }
    static public function loadReview($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Load dữ liệu không thành công!';

        if(Request::post()) {

            $data = Request::post();

            $order_provisional = 0;

            $shipping          = (int)Request::post('_shipping_price');

            if(isset($data['line_items'])) {
                foreach ($data['line_items'] as $key => $productData) {
                    if($productData['productPriceSale'] != 0) $productData['productPrice'] = $productData['productPriceSale'];
                    $order_provisional += $productData['productPrice']*$productData['productQuantity'];
                }
            }

            $order_total = $order_provisional + $shipping;

            $result['status']         = 'success';

            $result['order_review'] = cart_template('admin/order/save/amount-review',[
                'order_provisional' => $order_provisional,
                'shipping'          => $shipping,
                'total'				=> $order_total
            ],true);
            $result = apply_filters('wcmc_ajax_order_save_review', $result, $data );
        }
        echo json_encode( $result );
    }
    static public function addOrder($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Load dữ liệu không thành công!';

        if(Request::post()) {

            $data 			= Request::post();

            $rules = [];

            $billings 	= get_checkout_fields_billing();

            $shippings 	= get_checkout_fields_shipping();

            $inputs 	= array_merge($billings, $shippings);

            $product_items = Request::post('line_items');

            /**
             * KIỂM TRA CÁC ĐIỀU KIỆN INPUT
             */
            foreach ($inputs as $key => $input) {

                if(!empty($input['rules'])) $rules[] = array( 'field'   => $key, 'label' => ( isset($input['label_error']) ) ? $input['label_error'] : $input['label'], 'rules' => $input['rules']);
            }

            $rules = apply_filters('admin_order_add_rules', $rules);

            $ci->form_validation->set_rules($rules);

            if( $ci->form_validation->run() == false )  {

                foreach($rules as $row) {

                    $field = $row['field'];

                    $error = form_error($field);

                    if($error) Cart_Notice::add(  $error, 'error' );
                }
            }

            do_action('cart_checkout_process');

            /**
             * KIỂM TRA ĐIỀU KIỆN SẢN PHẨM
             */
            if(!have_posts($product_items)) {

                Cart_Notice::add(  'Đơn hàng chưa có sản phẩm nào.', 'error' );
            }

            $messages = Cart_Notice::get( 'error' );

            if(have_posts($messages)) {
                $result['message'] = '';
                foreach ($messages as $message) {
                    $result['message'] .= '<p>'.Cart_Notice::printLabel( $message, 'error' ).'</p>';
                }
                echo json_encode( $result );
                die;
            }

            $order 			= [];

            $metadata_order = [];

            foreach ($inputs as $key => $input) {

                if(isset( $data[$key])) $metadata_order[$key] = Str::clear( $data[$key] );
            }

            $order['total'] 			= 0;

            $order['user_created'] 		= Str::clear($data['customer_id']);

            $metadata_order['quantity'] = 0;

            foreach ($product_items as $key => $item) {
                $product = Product::get($item['productID']);
                if(have_posts($product)) {
                    $order_detail = [
                        'product_id' => $product->id,
                        'title'		 => $product->title,
                        'quantity'   => (int)$item['productQuantity'],
                        'image'      => $product->image,
                        'price'      => $item['productPrice'],
                        'subtotal'   => $item['productQuantity']*$item['productPrice'],
                        'metadata'   => array(
                            'weight' => $product->weight
                        ),
                    ];
                    if($item['productVariation'] != 0) {

                        $order_detail['metadata']['variable'] = (int)$item['productVariation'];

                        $variation = Variation::get($item['productVariation']);

                        $attr_name = [];

                        foreach ($variation->items as $attr_id) {

                            $attr = Attributes::getItem($attr_id);

                            if( have_posts($attr)) $attr_name[] = $attr->title;
                        }

                        if(have_posts($attr_name)) {

                            $order_detail['option'] 				= serialize($attr_name);

                            $order_detail['metadata']['attribute']  = $attr_name;

                        }
                    }
                    $order_detail = apply_filters('checkout_item_before_save', $order_detail, $item);
                    $order['items'][] 			 = $order_detail;
                    $order['total'] 			+= $order_detail['subtotal'];
                    $metadata_order['quantity'] += $order_detail['quantity'];
                }
            }

            if(!empty($data['shipping_type'])) {

                $shipping_type 			= Str::clear($data['shipping_type']);

                $shipping 			= shipping_gateways($shipping_type);

                $metadata_order['_shipping_type'] 	= $shipping_type;

                $metadata_order['_shipping_price']  = Request::Post('_shipping_price');

                if(have_posts($shipping)) {
                    $metadata_order['_shipping_label'] 	= $shipping['label'];
                }
            }

            if(!empty($data['_payment'])) {

                $payment_key    = Str::clear($data['_payment']);

                $metadata_order['_payment']         = $payment_key;

                $payment        = payment_gateways($payment_key);

                if(have_posts($payment)) {
                    $metadata_order['_payment_label']   = $payment['title'];
                }
            }

            if(!empty($metadata_order['_shipping_price']) && is_numeric($metadata_order['_shipping_price'])) {

                $order['total'] = $order['total'] + $metadata_order['_shipping_price'];
            }

            $order = apply_filters('checkout_order_before_save', $order, $metadata_order, $data);

            $id = Order::insert( $order, $metadata_order );

            if(!is_skd_error($id)) {

                do_action('admin_order_add_after_save', $id, $data);

                $customer = User::get($data['customer_id']);

                if(have_posts($customer)) {

                    $order = Order::get($id);

                    $customer->order_count += 1;

                    User::insert((array)$customer);

                    User::updateMeta( $customer->id, 'order_recent', $order->code);
                }

                $result['id'] = $id;

                $result['status'] = 'success';

                $result['message'] = 'Lưu đơn hàng thành công!';

                $result = apply_filters('admin_order_add_result_success', $result, $id );
            }
            else {
                foreach ($error->errors as $error_key => $error_value) {
                    $result['message'] = $error_value[0];
                }
            }
        }

        echo json_encode( $result );
    }
}
Ajax::admin('Admin_Order_Ajax_Add::loadCustomer');
Ajax::admin('Admin_Order_Ajax_Add::loadReview');
Ajax::admin('Admin_Order_Ajax_Add::addOrder');