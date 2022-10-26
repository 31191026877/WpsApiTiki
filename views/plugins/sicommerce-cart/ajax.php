<?php
Class Cart_Ajax {
    static public function loadDistricts() {

        $result['status']     = 'error';

        $result['message']  = 'Lưu dữ liệu thất bại';

        if(Request::post()) {

            $post = Request::post();

            $result['status'] = 'success';

            $result['data'] = '<option value="">Chọn quận huyện</option>';

            if( $post['province_id'] != '') {

                $province_id = Str::clear( $post['province_id'] );

                if(empty($province_id)) {
                    echo json_encode( $result );
                    return false;
                }

                $district_id = '';

                if(!empty($post['district_id'])) $district_id = Str::clear( $post['district_id'] );

                $districts   = Cart_Location::districts($province_id);

                if(have_posts($districts)) {
                    ksort($districts);
                    foreach ($districts as $key => $name ) {
                        $result['data'] .= '<option value="'.$key.'" '.(($district_id == $key)?'selected':'').'>'.$name.'</option>';
                    }
                }

            }
        }
        echo json_encode($result);
        return false;
    }
    static public function loadWard() {
        $result['status']     = 'error';
        $result['message']  = 'Lưu dữ liệu thất bại';
        if(Request::post()) {
            $post = Request::post();
            $result['status'] = 'success';
            $result['data'] = '<option value="">Chọn phường xã</option>';
            if( $post['district_id'] != '') {
                $district_id = Str::clear( $post['district_id'] );
                if(empty($district_id)) {
                    echo json_encode( $result );
                    return false;
                }
                $ward_id = '';
                if(!empty($post['ward_id'])) $ward_id = Str::clear( $post['ward_id'] );
                $ward   = Cart_Location::ward($district_id);
                if( have_posts( $ward ) ) {
                    ksort($ward);
                    foreach ($ward as $key => $name ) {
                        $result['data'] .= '<option value="'.$key.'" '.(($ward_id == $key)?'selected':'').'>'.$name.'</option>';
                    }
                }

            }
        }
        echo json_encode($result);
        return false;
    }
    static public function loadPrice() {

        $result['status'] = 'error';

        $result['message'] = 'Lưu dữ liệu không thành công!';

        if(Request::post()) {

            $product_id 	= (int)Request::post('product_id');

            $data 			= Request::post();

            unset($data['action']);

            $variations = Variation::gets(Qr::set('parent_id', $product_id));

            if(have_posts($variations) && count($data['option'])) {

                $metadata = [];

                foreach ($variations as $variation) {

                    if(count($variation->items) != count($data['option'])  ) continue;

                    if(!have_posts(array_diff( $variation->items, $data['option'])) ) {
                        $result['variation'] 	= $variation;
                        $metadata = $variation; break;
                    }
                }

                if( have_posts($metadata) ) {
                    $result['data'] 		= apply_filters('product_detail_price_template', scmc_template('detail/price', ['object' => $metadata], true), $metadata);
                    $result['status'] 		= 'success';
                }
            }
            else {
                $result['status'] 		= 'warning';
            }
        }

        echo json_encode($result);
    }
    static public function addCart() {

        $result['status'] = 'error';

        $result['message'] = 'Thêm sản phẩm vào giỏ hàng không thành công!';

        if(Request::post()) {

            $product_id 	= (int)Request::post('product_id');

            $data 			= Request::post();

            unset($data['action']);

            unset($data['product_id']);

            $product = Product::get($product_id);
            //kiểm tra sản phẩm có tồn tại
            if( !have_posts($product) ) {
                $result['status'] 	= 'error';
                $result['message'] 	= 'Sản phẩm không tồn tại';
                $result['data'] = notice('error', $result['message'], false);
                echo json_encode($result);
                return false;
            }

            $option['product_id'] 		= $product->id;

            $option['product_image'] 	= $product->image;

            $option['weight'] 	        = $product->weight;

            if(Language::hasMulti()) {
                $products_language = Language::gets(Qr::set('object_type', 'products')->where('object_id', $product->id));
                foreach (Language::listKey() as $index => $item) {
                    if($index == Language::default()) continue;
                    foreach ( $products_language as $i => $pr) {
                        if($pr->language == $index) {
                            $option['title_'.$index] = $pr->title; unset($products_language[$i]); break;
                        }
                    }
                }
            }

            $cart = [
                'id'     => $product->id,
                'qty'    => $data['qty'],
                'price'  => (!empty($product->price_sale)) ? $product->price_sale : $product->price,
                'name'   => $product->title,
                'option' => $option,
                'weight' => $product->weight
            ];

            unset($data['qty']);

            $variations = Variation::getsByProduct($product_id);

            $options    = Product::getMeta($product_id, 'attributes', true);

            foreach ($variations as $variation_key => $variation) {

                if(!isset($variation->items)) continue;

                foreach ($variation->items as $key => $item) {
                    if($item == 0) unset($variation->items[$key]);
                }

                if(count($variation->items) != count($options)) {
                    unset($variations[$variation_key]);
                }
            }

            //có biến thể nhưng không chọn
            if(have_posts($variations) && ( !isset($data) || !have_posts($data) ) ) {
                $result['status'] 	= 'error';
                $result['message'] 	= 'Bạn chưa chọn tùy chọn cho sản phẩm';
                $result['data'] = notice('error', $result['message'], false);
                echo json_encode($result);
                return false;
            }

            //có tùy chọn biến thể
            if(have_posts($variations)) {

                //kiểm tra đã chọn đủ tùy chọn chưa
                if((have_posts($options) && !isset($data['option'])) || (count($options) != count($data['option'])) ) {
                    $result['status'] 		= 'warning';
                    $result['message'] 		= 'Bạn chưa chọn đầy đủ tùy chọn cho sản phẩm.';
                    $result['data'] 		= notice($result['status'] , $result['message'], false);
                    echo json_encode($result);
                    return false;
                }

                //lấy thông tin sản phẩm tùy biến nếu có
                foreach ($variations as $key => $variable) {
                    $metadata = [];
                    if( !have_posts(array_diff( $variable->items, $data['option'])) ) {
                        $metadata = $variable;
                        $cart['variable'] = $variable->id;
                        $cart['weight']   = $variable->weight;
                        $cart['id'] 	  = $cart['id'].'_'.$variable->id;
                        break;
                    }
                }

                //chọn đúng 1 trong các tùy chọn biến thể
                if(!have_posts($metadata)) {
                    $result['message'] 	= 'Shop hiện không kinh doanh sản phẩm này!';
                    $result['data'] = notice('error', $result['message'], false);
                    echo json_encode($result);
                    return false;
                }

                $cart['option']['attribute'] = [];

                foreach ($data['option'] as $key => $value) {
                    $attribute = Attributes::getItem($value);
                    $img = Product::getMeta($product->id, 'variations_img', true);
                    if(!empty($img[$attribute->id])) {
                        $cart['option']['product_image'] = $img[$attribute->id];
                    }
                    $cart['option']['attribute'][] = $attribute->title;
                }

                $cart['name'] = trim($cart['name'], ',');

                $cart['price'] = (!empty($metadata->price_sale))?$metadata->price_sale:$metadata->price;

                $cart['option']['product_image'] = (!empty($metadata->image))?$metadata->image:$cart['option']['product_image'];

                $cart = apply_filters( 'cart_add_variations', $cart, Request::post(), $product, $metadata );
            }
            //sản phẩm bình thường không có tùy biến
            else {

                $cart['option']['attribute'] = [];

                if(isset($data['option']) && have_posts($data['option']) ) {
                    foreach ($data['option'] as $key => $value) {
                        $attribute = Attributes::getItem($value);
                        $img = Product::getMeta($product->id, 'variations_img', true);
                        if(!empty($img[$attribute->id])) {
                            $cart['option']['product_image'] = $img[$attribute->id];
                        }
                        $cart['option']['attribute'][] = $attribute->title;
                        $cart['id'] .= '_'.$attribute->id;
                    }
                }

                $cart = apply_filters( 'cart_add_no_variations', $cart, Request::post(), $product );
            }

            $list_cart = Scart::getItems();

            foreach ($list_cart as $item) {
                if($item['id'] == $cart['id']) $cart['qty'] += $item['qty'];
            }

            do_action('checkout_add_to_cart', $cart, $product, $variations);

            $errors = Cart_Notice::get();

            if(have_posts($errors)) {
                $result['message'] = [];
                foreach ($errors as $key_error => $list_error) {
                    foreach ($list_error as $message) {
                        if(empty($result['message'])) $result['message'] = '';
                        if($key_error == 'error' ) $result['message'] .= $message;
                        else $result['message'] = $message;
                    }
                }
                echo json_encode( $result );
                die;
            }

            $cart = apply_filters( 'cart_add', $cart, Request::post(), $product, $variations );

            if(Scart::insert($cart)) {
                $result['total_items']  = Scart::totalQty();
                $result['total']        = Scart::total();
                $result['total_label']  = number_format(Scart::total());
                ob_start();
                foreach (Scart::getItems() as $item) { $item = (object)$item;
                    echo cart_template('cart/cart-items', array('item' => $item));
                }
                $result['items'] = ob_get_contents();
                ob_end_clean();
                $result['status']       = 'success';
                $result['message'] 	    = 'Sản phẩm <strong>'.$product->title.'</strong> với số lượng '.Scart::totalQty(). ' đã được thêm vào giỏ hàng!';
                $result = apply_filters('cart_add_success_response', $result, $cart, $product);
            }
        }
        $result['data'] = notice($result['status'], $result['message']);
        echo json_encode($result);
        return false;
    }
    static public function updateQuantity() {

        $result['status'] = 'error';

        $result['message'] = 'Cập nhật dữ liệu thất bại.';

        if(Request::post()) {

            $rowid 	= Str::clear(Request::post('rowid'));

            $qty 	= Str::clear(Request::post('qty'));

            if(is_numeric($qty)) {

                $data = ['rowid' => $rowid, 'qty' => $qty];

                $item = Scart::getItem($rowid);

                if(have_posts($item)) {

                    Scart::update($data);

                    //ver 2.7.0
                    do_action('cart_update_quantity', $rowid, $qty);
                    //ver 2.8.0
                    do_action('cart_update_quantity_success', $item, $qty);

                    $errors = Cart_Notice::get();

                    if( have_posts($errors) ) {

                        $result['message'] = [];

                        foreach ($errors as $key_error => $list_error) {

                            foreach ($list_error as $message) {

                                if(empty($result['message'][$key_error])) $result['message'][$key_error] = '';

                                if($key_error == 'error' ) $result['message'][$key_error] .= Cart_Notice::print( $message, 'error' );
                                else $result['message'][$key_error] .= $message;
                            }
                        }

                        echo json_encode( $result );

                        die;
                    }

                    $result['status'] = 'success';

                    $item = Scart::getItem($rowid);

                    $result['qty'] 		= $qty;

                    $result['price'] 	= (!empty($item['price'])) ? $item['price'] : 0;

                    $result['total'] 	    = number_format(Scart::total());

                    $result['summary_total'] = number_format(order_total());
                }
            }
        }

        echo json_encode($result);
    }
    static public function saveCheckout($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Đặt hàng không thành công!';

        if(Request::post()) {

            $data 			= Request::post();

            $cart 			= Scart::getItems();

            $billings 	= get_checkout_fields_billing();

            $shippings 	= get_checkout_fields_shipping();

            $orders 	= get_checkout_fields_order();

            $inputs 	= array_merge($billings, $shippings);

            $inputs 	= array_merge($inputs, $orders);

            $rules 		= checkout_fields_rules();

            $rules 		= apply_filters('cart_checkout_rules', $rules);

            if(isset($rules['billings']) && have_posts($rules['billings'])) {
                $ci->form_validation->set_rules($rules['billings']);
            }

            $show_form_shipping = Str::clear(Request::post('show-form-shipping'));

            if($show_form_shipping == 'on' && isset($rules['shippings']) && have_posts($rules['shippings'])) {
                $ci->form_validation->set_rules($rules['shippings']);
            }

            if(!$ci->form_validation->run()) {
                $errors = $ci->form_validation->error_array();
                foreach($errors as $error_key => $error_value) {
                    Cart_Notice::add( $error_value, $error_key );
                }
            }

            if(!have_posts($cart)) {
                Cart_Notice::add(__('Không có sản phẩm nào trong giỏ hàng', 'checkout_cart_empty'), 'error');
            }

            do_action('cart_checkout_process'); //ver 2.7.0

            $errors = Cart_Notice::get();

            if(have_posts($errors)) {
                $result['message'] = [];
                foreach ($errors as $key_error => $list_error) {
                    foreach ($list_error as $message) {
                        if(empty($result['message'][$key_error])) $result['message'][$key_error] = '';
                        if($key_error == 'error' ) $result['message'][$key_error] .= Cart_Notice::print( $message, 'error' );
                        else $result['message'][$key_error] .= $message;
                    }
                }
                echo json_encode( $result );
                die;
            }

            if($ci->form_validation->run()) {

                $metadata_order = [];

                foreach ($inputs as $key => $input) {
                    if( isset( $data[$key]) ) $metadata_order[$key] = Str::clear( $data[$key] );
                }

                if(!empty($data['shipping_type'])) {

                    $shipping_type 		= Str::clear($data['shipping_type']);

                    $shipping 			= shipping_gateways($shipping_type);

                    $metadata_order['_shipping_type'] 	= $shipping_type;

                    $metadata_order['_shipping_price']  = 0;

                    if(have_posts($shipping)) {

                        if(class_exists($shipping['class']) && method_exists($shipping['class'], 'check')) {

                            $shipping['class']::check($shipping);

                            $errors = Cart_Notice::get();

                            if( have_posts($errors) ) {
                                $result['message'] = [];
                                foreach ($errors as $key_error => $list_error) {
                                    foreach ($list_error as $message) {
                                        if(empty($result['message'][$key_error])) $result['message'][$key_error] = '';
                                        if($key_error == 'error' ) $result['message'][$key_error] .= Cart_Notice::print( $message, 'error' );
                                        else $result['message'][$key_error] .= $message;
                                    }
                                }
                                echo json_encode( $result );
                                die;
                            }
                        }

                        $key_temp = str_replace( '-', '_', $shipping_type);

                        $metadata_order['_shipping_label'] 	= $shipping['label'];

                        if(method_exists($shipping['class'], 'calculate')) {

                            $metadata_order['_shipping_price'] = $shipping['class']::calculate($data);
                        }
                        //Ver 4.0.0
                        $metadata_order['_shipping_price'] 	= apply_filters('checkout_shipping_price_'.$key_temp, $metadata_order['_shipping_price'] );
                    }
                }

                if(!empty($data['_payment'])) {

                    $payment_key    = Str::clear($data['_payment']);

                    $metadata_order['_payment']         = $payment_key;

                    $payment        = payment_gateways($payment_key);

                    if(have_posts($payment)) {

                        $metadata_order['_payment_label']   = $payment['title'];

                        if(class_exists($payment['class']) && method_exists($payment['class'], 'check')) {

                            $payment['class']::check($payment);

                            $errors = Cart_Notice::get();

                            if( have_posts($errors) ) {
                                $result['message'] = [];
                                foreach ($errors as $key_error => $list_error) {
                                    foreach ($list_error as $message) {
                                        if(empty($result['message'][$key_error])) $result['message'][$key_error] = '';
                                        if($key_error == 'error' ) $result['message'][$key_error] .= Cart_Notice::print( $message, 'error' );
                                        else $result['message'][$key_error] .= $message;
                                    }
                                }
                                echo json_encode( $result );
                                die;
                            }
                        }
                    }
                }

                $order['total'] = Scart::total();

                if(!empty($metadata_order['_shipping_price']) && is_numeric($metadata_order['_shipping_price'])) {

                    $order['total'] = $order['total'] + $metadata_order['_shipping_price'];
                }

                $metadata_order['quantity'] = 0;

                $metadata_order['other_delivery_address'] = false;

                if($show_form_shipping == 'on') $metadata_order['other_delivery_address'] = true;

                foreach ($cart as $key => $item) {

                    $order_detail = [
                        'product_id' => $item['option']['product_id'],
                        'title'      => $item['name'],
                        'quantity'   => $item['qty'],
                        'image'      => $item['option']['product_image'],
                        'price'      => $item['price'],
                        'subtotal'   => $item['subtotal'],
                        'metadata'   => array(
                            'weight' => $item['weight']
                        ),
                    ];

                    unset($item['option']['product_id']);

                    unset($item['option']['product_image']);

                    if( isset($item['option']['attribute']) ) {

                        $order_detail['option'] = serialize($item['option']['attribute']);

                        $order_detail['metadata']['attribute'] = $item['option']['attribute'];
                    }

                    if( !empty($item['variable']) ) {
                        $order_detail['product_id'] = (int)$item['variable'];
                        $order_detail['metadata']['variable'] = (int)$item['variable'];
                    }

                    //ver 4
                    $order_detail = apply_filters('checkout_item_before_save', $order_detail, $item);

                    $order['items'][] = $order_detail;

                    $metadata_order['quantity'] += $order_detail['quantity'];
                }
                //ver 4.0.0
                $order  = apply_filters('checkout_order_before_save', $order, $metadata_order, $data, $cart);

                //ver 4.0.0
                $metadata_order = apply_filters('checkout_order_metadata_before_save', $metadata_order, $order, $data, $cart);

                $id = Order::insert( $order, $metadata_order);

                if(!is_skd_error($id)) {
                    $order = Order::get($id);
                    //ver 4
                    do_action('checkout_order_after_save', $id, $data, $order);
                    /**
                     * Thêm khách hàng nếu khách hàng chưa tồn tại
                     *  */
                    if(!Auth::check()) {

                        $customer = User::getby('email', $metadata_order['billing_email']);

                        if(!have_posts($customer)) {
                            $customer = User::getby('phone', $metadata_order['billing_phone']);
                        }

                        if(have_posts($customer)) {
                            $customer->order_count += 1;
                            User::insert((array)$customer);
                            User::updateMeta($customer->id, 'order_recent', $order->code);
                        }
                        else {
                            //Thêm mới user
                            $lastname = (strpos($metadata_order['billing_fullname'], ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $metadata_order['billing_fullname']);

                            $firstname = trim(preg_replace('#'.$lastname.'#', '', $metadata_order['billing_fullname'] ) );

                            $customer = [
                                'firstname' 	=> $firstname,
                                'lastname'  	=> $lastname,
                                'email'			=> (!empty($metadata_order['billing_email'])) ? $metadata_order['billing_email'] : '',
                                'phone'			=> $metadata_order['billing_phone'],
                                'order_total' 	=> 0,
                                'order_count' 	=> 1,
                                'status' 	    => 'public',
                                'customer' 	    => 1,
                                'role'          => 'customer'
                            ];

                            $model->settable('users');

                            $user_id = $model->add($customer);

                            user_set_role( $user_id, 'customer');

                            User::updateMeta( $user_id, 'order_recent', $order->code);

                            User::updateMeta( $user_id, 'city', 		$order->billing_city);

                            User::updateMeta( $user_id, 'districts', 	$order->billing_districts);

                            User::updateMeta( $user_id, 'address', 		$order->billing_address);

                            Order::insert([
                                'id' => $id,
                                'user_created' => $user_id
                            ]);

                            CacheHandler::delete( 'user_', true );
                        }
                    }
                    else {

                        $order = Order::get($id);

                        $customer = Auth::user();

                        $customer->order_count += 1;

                        if($customer->customer == 0) $customer->customer = 2;
                        else $customer->customer = 1;

                        User::insert((array)$customer);

                        User::updateMeta($customer->id, 'order_recent', $order->code);

                        if(!empty($order->billing_city)) 		User::updateMeta($customer->id, 'city', $order->billing_city);

                        if(!empty($order->billing_districts)) 	User::updateMeta($customer->id, 'districts', $order->billing_districts);

                        User::updateMeta($customer->id, 'address', $order->billing_address);
                    }

                    /**
                     * Tạo token trả về trang thanh toán thành công
                     */
                    $token = md5(time());

                    $_SESSION['token'] = $token;

                    $url = get_url('don-hang').'?id='.$id.'&token='.$token;

                    //ver 4
                    do_action('checkout_after_success', $id);

                    $result['status'] 	= 'success';

                    $result['message'] 	= 'Đặt hàng thành công!';

                    $result['url'] 		= $url;

                    if(!empty($payment) && have_posts($payment)) {

                        if(class_exists($payment['class']) && method_exists($payment['class'], 'process')) {
                            $result = $payment['class']::process($order, $result, $payment);
                        }
                    }

                    if($result['status'] == 'success') {
                        Scart::empty();
                    }

                    //ver 4
                    $result = apply_filters('checkout_result_success', $result, $id );
                }
                else {
                    foreach ($id->errors as $error_key => $error_value) {
                        $result['message']['error'] = $error_value[0];
                    }
                }
            }
        }

        echo json_encode( $result );
    }
    static public function loadCheckoutReview($ci, $model) {

        $result['type'] = 'error';

        $result['message'] = 'Load dữ liệu không thành công!';

        if(Request::post()) {

            $data                   = Request::post();

            $data['cart']           = Scart::getItems();

            if(!empty($data['shipping_type'])) {

                $shipping_type = Str::clear($data['shipping_type']);

                $shipping 		= shipping_gateways();

                foreach ($shipping as $key => $ship) {

                    if(empty($ship['enabled']) || $ship['enabled']  == false) continue;

                    $key_temp = str_replace( '-', '_', $key);

                    $data['shipping_price_'.$key_temp] = 0;

                    if(method_exists($ship['class'], 'calculate')) $data['shipping_price_'.$key_temp] = $ship['class']::calculate($data);

                    $data['shipping_price_'.$key_temp] = apply_filters('shipping_price_'.$key_temp, $data['shipping_price_'.$key_temp] );

                    if($key == $shipping_type) {

                        $data['shipping_price'] = $data['shipping_price_'.$key_temp];
                    }
                }
            }

            $ci->data['wcmc_cart_checkout'] = $data;

            $result['type']         = 'success';

            $result['order_review'] = cart_template('checkout/order-review', $data,true);

            //ver 4.0.0
            $result = apply_filters('checkout_ajax_order_review', $result, $data );
        }

        echo json_encode( $result );
    }
}

Ajax::client('Cart_Ajax::loadDistricts');
Ajax::client('Cart_Ajax::loadWard');
Ajax::client('Cart_Ajax::loadPrice');
Ajax::client('Cart_Ajax::addCart');
Ajax::client('Cart_Ajax::updateQuantity');
Ajax::client('Cart_Ajax::saveCheckout');
Ajax::client('Cart_Ajax::loadCheckoutReview');


