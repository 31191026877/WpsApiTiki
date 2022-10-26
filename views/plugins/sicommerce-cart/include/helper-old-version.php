<?php
/*===================== ATTRIBUTE ===============================*/
function get_attribute( $args = [] ) {
    return Attributes::get($args);
}

function gets_attribute( $args = [] ) {
    return Attributes::gets($args);
}

function get_attribute_item( $args = [] ) {
    return Attributes::getItem($args);
}

function gets_attribute_item( $args = [] ) {
    return Attributes::getsItem($args);
}

function insert_attribute( $attribute = [] ) {
    return Attributes::insert($attribute);
}

function insert_attribute_item( $attribute = [] ) {
    return Attributes::insertItem($attribute);
}

function delete_attribute( $id ) {
    return Attributes::delete($id);
}

function delete_list_attribute( $attributeID ) {
    return Attributes::deleteList($attributeID);
}

function delete_attribute_item( $id ) {
    return Attributes::deleteItem($id);
}

function delete_list_attribute_item( $attributeID ) {
    return Attributes::deleteItemList($attributeID);
}

function delete_attribute_item_by_attribute( $attributeID ) {
    return Attributes::deleteItemByAttribute($attributeID);
}

if (!function_exists('insert_attribute_product')) {

    function insert_attribute_product($product_id, $attributes) {
        return Attributes::insertToProduct($product_id, $attributes);
    }
}

/*===================== VARIATION ===============================*/
if(!function_exists('gets_variations')) {
    function gets_variations( $args = [] ) {
        return Variation::gets($args);
    }
}

if(!function_exists('get_variations')) {
    function get_variations( $args = [] ) {
        return Variation::get($args);
    }
}

if(!function_exists('insert_variations_product')) {
    function insert_variations_product($product_id, $variations) {
        return Variation::insertToProduct($product_id, $variations);
    }
}
/**
 * [wcmc_order_code tạo mã đơn hàng]
 */
function wcmc_order_creat_code( $id = 0 ) {
    return Order::generateCode($id);
}

if(!function_exists('get_order')) {
    function get_order( $args = '', $detail = true, $metadata = true ) {
        return Order::get($args, $detail, $metadata);
    }
}

if(!function_exists('gets_order')) {
    function gets_order( $args = '', $detail = true, $metadata = true ) {
        return Order::gets($args, $detail, $metadata);
    }
}

if(!function_exists('count_order')) {
    function count_order($args = []) {
        return Order::count($args);
    }
}

if(!function_exists('update_order')) {
    function update_order( $data = '', $args = '' ) {
        return Order::update($data, $args);
    }
}

if(!function_exists('insert_order')) {
    function insert_order( $order = '', $metadata = '' ) {
        return Order::insert($order, $metadata);
    }
}

if(!function_exists('delete_order_by_id')) {
    function delete_order_by_id( $order_id = '' ) {
        return Order::deleteById($order_id);
    }
}

if(!function_exists('get_order_meta')) {
    function get_order_meta( $order_id, $key = '', $single = true) {
        return Order::getMeta($order_id, $key, $single);
    }
}

if(!function_exists('update_order_meta')) {
    function update_order_meta($order_id, $meta_key, $meta_value) {
        return Order::updateMeta($order_id, $meta_key, $meta_value);
    }
}

if(!function_exists('delete_order_meta')) {
    function delete_order_meta($order_id, $meta_key = '', $meta_value = '') {
        return Order::deleteMeta($order_id, $meta_key, $meta_value);
    }
}
/**
 * ORDER ITEM
 */
if(!function_exists('get_order_item' ) ) {
    function get_order_item( $args = '', $metadata = true ) {
        return Order::getItem($args, $metadata);
    }
}

if(!function_exists('gets_order_item' ) ) {
    function gets_order_item( $args = '', $metadata = true ) {
        return Order::getsItem($args, $metadata);
    }
}

if(!function_exists('delete_order_item_by' ) ) {
    function delete_order_item_by( $field = '', $data = '' ) {
        return Order::deleteItemBy($field, $data);
    }
}

if(!function_exists('insert_order_item' ) ) {
    function insert_order_item( $item = '', $order_id = 0, $metadata = '' ) {
        return Order::insertItem($item, $order_id, $metadata);
    }
}

if(!function_exists('get_order_item_meta') ) {
    function get_order_item_meta( $order_item_id, $key = '', $single = true) {
        return Order::getItemMeta($order_item_id, $key, $single);
    }
}

if(!function_exists('update_order_item_meta') ) {
    function update_order_item_meta($order_item_id, $meta_key, $meta_value) {
        return Order::updateItemMeta($order_item_id, $meta_key, $meta_value);
    }
}

if(!function_exists('delete_order_item_meta') ) {
    function delete_order_item_meta($order_item_id, $meta_key = '', $meta_value = '') {
        return Order::deleteItemMeta($order_item_id, $meta_key, $meta_value);
    }
}
/**
 * ORDER STATUS
 */
function order_status() {
    return Order::status();
}

function order_status_label( $key = '') {
    return Order::status($key, 'label');
}

function order_status_color( $key = '') {
    return Order::status($key, 'color');
}

function order_status_pay() {
    return Order::statusPay();
}

function order_status_pay_label($key = '') {
    return Order::statusPay($key, 'label');
}

function order_status_pay_color($key = '') {
    return Order::statusPay($key, 'color');
}
/**
 * ORDER HISTORY
 */
if( !function_exists('get_order_history') ) {
    function get_order_history( $args = '') {
        return OrderHistory::get($args);
    }
}

if( !function_exists('gets_order_history') ) {
    function gets_order_history($args = '') {
        return OrderHistory::gets($args);
    }
}

if( !function_exists('count_order_history') ) {
    function count_order_history($args = '') {
        return OrderHistory::count($args);
    }
}

if( !function_exists('insert_order_history') ) {
    function insert_order_history($history = '') {
        return OrderHistory::insert($history);
    }
}

if( !function_exists('order_history_message') ) {
    function order_history_message($action = '', $args = []) {
        return OrderHistory::message($action, $args);
    }
}

/**
 * Function hỗ trợ phiên bản nhỏ hơn 2.3.4
 */
if( ! function_exists('wcmc_get_order') ) {
    function wcmc_get_order( $args = '', $detail = true, $metadata = true ) {
        return Order::get($args, $detail, $metadata);
    }
}

if( ! function_exists('wcmc_gets_order') ) {
    function wcmc_gets_order( $args = '', $detail = true, $metadata = true ) {
        return Order::gets($args, $detail, $metadata);
    }
}

if( ! function_exists('wcmc_count_order') ) {
    /**
     * [wcmc_count_order điếm sớ lượng đơn hàng]
     * @since 2.3.1
     */
    function wcmc_count_order($args = '') {
        return Order::count($args);
    }
}

if( !function_exists('wcmc_update_order') ) {
    function wcmc_update_order( $data = '', $args = '' ) {
        return Order::update($data, $args);
    }
}

if( !function_exists('wcmc_delete_order_by_id') ) {
    function wcmc_delete_order_by_id( $order_id = '' ) {
        return Order::deleteById($order_id);
    }
}

if(!function_exists('wcmc_get_item_order' ) ) {
    function wcmc_get_item_order( $args = '', $metadata = true ) {
        return Order::getItem($args, $metadata);
    }
}

if(! function_exists('wcmc_gets_item_order' ) ) {
    function wcmc_gets_item_order( $args = '', $metadata = true ) {
        return Order::getsItem($args, $metadata);
    }
}

if(!function_exists('wcmc_delete_order_item_by' ) ) {
    function wcmc_delete_order_item_by( $field = '', $data = '' ) {
        return Order::deleteItemBy($field, $data);
    }
}

function woocommerce_order_status() {
    return apply_filters( 'woocommerce_order_status', Order::status() );
}

function woocommerce_order_status_label( $key = '') {
    return apply_filters( 'woocommerce_order_status_label', Order::status($key, 'label'), $key );
}

function woocommerce_order_status_color( $key = '') {
    return apply_filters('woocommerce_order_status_label', Order::status($key, 'color'), $key);
}

if (!function_exists('wcmc_get_template_cart')) {

    function wcmc_get_template_cart( $template_path = '' , $args = '', $return = false ) {

        return cart_template($template_path, $args, $return);
    }
}

if (!function_exists('wcmc_get_include_cart')) {

    function wcmc_get_include_cart( $template_path = '' , $args = '', $return = false) {

        return cart_include($template_path, $args, $return);
    }
}

function wcmc_order_total( $total = 0 ) {
    return order_total($total);
}

if (!function_exists('wcmc_shipping_states_provinces' ) ) {
    function wcmc_shipping_states_provinces($provinces_id = '', $country = 'VN') {
        return Cart_Location::cities($provinces_id, $country);
    }
}
if (!function_exists('wcmc_shipping_states_districts')) {
    function wcmc_shipping_states_districts($province_id = '', $districts_id = '', $country = 'VN') {
        return Cart_Location::districts($province_id, $districts_id, $country);
    }
}
if (!function_exists('wcmc_shipping_states_ward')) {
    function wcmc_shipping_states_ward($districts_id = '', $ward_id = '', $country = 'VN') {
        return Cart_Location::ward($districts_id, $ward_id, $country);
    }
}

function wcmc_add_notice($message, $notice_type = 'success') {
    Cart_Notice::add($message, $notice_type);
}
function wcmc_get_notices($notice_type = '') {
    return Cart_Notice::get($notice_type);
}
function wcmc_print_notice( $message, $notice_type = 'success', $return = true ) {
    return Cart_Notice::print($message, $notice_type, $return);
}
function wcmc_print_notice_label( $message, $notice_type = 'success', $return = true ) {
    return Cart_Notice::print($message, $notice_type, $return);
}

function wcmc_ajax_load_districts($ci, $model) { Cart_Ajax::loadDistricts(); }
Ajax::client('wcmc_ajax_load_districts');
function wcmc_ajax_load_ward($ci, $model) { Cart_Ajax::loadWard(); }
Ajax::client('wcmc_ajax_load_ward');

function wcmc_order_save_total( $total = 0 ) {
    return order_add_total($total);
}


class UpdateOrderV1 {
    static public function generateCode($id = 0) {
        return apply_filters( 'order_generate_code', (1000 + $id));
    }
    static public function get( $args = [], $detail = true, $metadata = true ) {
        $model = get_model('products')->settable('wcmc_order');
        $args = ['where' => array('id' => (int)$args)];
        if(!have_posts($args)) $args = [];
        if(!empty($args['status'])) { $args['where']['status'] = $args['status']; unset($args['status']);}
        $args = array_merge(['where' => [], 'params' => []], $args );
        if(!empty($args['operator'])) {
            $where 	= $args['where'];
            $col = $args['operator']['col'];
            $operator = (!empty($args['operator']['operator']))?$args['operator']['operator']:'sum';
            $orders = $model->operatorby($where, $col, $operator);
            $order = (have_posts($orders))?$orders->$col:0;
        }
        else {
            $order =  $model->get_data($args, 'order');
            if( have_posts($order) ) {
                //get danh sách product item
                $order->items = static::getsItem($order->id);
                //get danh sách metadat
                $order_info = static::getMeta($order->id, '', false);
                if( have_posts($order_info) ) {
                    $order_info->shipping_fullname =  (!empty($order_info->shipping_address))?$order_info->shipping_fullname:$order_info->billing_fullname;
                    $order_info->shipping_address  =  (!empty($order_info->shipping_address))?$order_info->shipping_address:$order_info->billing_address;
                    $order_info->shipping_phone    =  (!empty($order_info->shipping_phone))?$order_info->shipping_phone:$order_info->billing_phone;
                    $order_info->shipping_email    =  (!empty($order_info->shipping_email))?$order_info->shipping_email:$order_info->billing_email;
                }
                $order = (object)array_merge( (array)$order_info, (array)$order );
                //lưu capche
                if( isset($cache_id) ) CacheHandler::save($cache_id, $order);
            }
        }
        return $order;
    }
    static public function gets( $args = [], $detail = true, $metadata = true ) {
        $model = get_model('products')->settable('wcmc_order')->settable_metabox('wcmc_order_metadata');
        if(is_numeric($args)) $args = [ 'where' => ['id' => (int)$args]];
        if(!have_posts($args)) $args = [];
        if(!empty($args['status'])) { $args['where']['status'] = $args['status']; unset($args['status']);}
        $args = array_merge(['where' => [], 'params' => [] ], $args);
        if(!empty($args['operator'])) {
            $where 	= $args['where'];
            $col = $args['operator']['col'];
            $operator = (!empty($args['operator']['operator']))?$args['operator']['operator']:'sum';
            $orders = $model->operatorby($where, $col, $operator);
            $orders = (have_posts($orders))?$orders->$col:0;
        }
        else $orders =  $model->gets_data($args, 'order');
        if(have_posts($orders) && ($detail == true || $metadata == true)) {
            foreach ($orders as &$order) {
                $order = static::get($order->id);
            }
        }
        return $orders;
    }
    static public function count($args = []) {
        $model = get_model()->settable('wcmc_order');
        if(is_numeric($args)) $args = [ 'where' => ['id' => (int)$args]];
        if(!have_posts($args)) $args = [];
        if(!empty($args['status'])) { $args['where']['status'] = $args['status']; unset($args['status']);}
        $args = array_merge(['where' => [], 'params' => []], $args);
        $orders = $model->count_data($args, 'order');
        return $orders;
    }
    static public function insert( $order = [], $metadata = []) {

        $model = get_model()->settable('wcmc_order');
        $user = Auth::user();
        if(!empty($order['id'])) {
            $id 			= (int) $order['id'];
            $update 	   = true;
            $old_order = static::get($id, false, false);
            if (!$old_order) return new SKD_Error( 'invalidId', __( 'ID đơn hàng không chính xác.' ) );
            $user_updated = (have_posts($user)) ? $user->id : 0;
            $user_created = $old_order->user_created;
            $order['total']         = (!empty($order['total'])) ? (int)$order['total'] : $old_order->total;
            $order['status']        = (!empty($order['status'])) ? Str::clear($order['status']) : $old_order->status;
            $order['status_pay']    = (!empty($order['status_pay'])) ? Str::clear($order['status_pay']) : $old_order->status_pay;
            $order['code']          = (!empty($order['code'])) ? Str::clear($order['code']) : $old_order->code;
        }
        else {
            $update = false;
            $user_updated = 0;
            $user_created = ( have_posts($user) ) ? $user->id : 0;
            if(empty($order['items']) || !have_posts($order['items'])) {
                return new SKD_Error( 'emptyItem', __('Đơn hàng không có sản phẩm nào.') );
            }
        }

        if(!empty($order['user_created'])) $user_created = (int)$order['user_created'];

        $items      =  (!empty($order['items'])) ? $order['items'] : [];

        $total       = (!empty($order['total'])) ? (int)$order['total'] : 0;

        $status      = (!empty($order['status'])) ? Str::clear($order['status']) : static::statusDefault();

        $status_pay  = (!empty($order['status_pay'])) ? Str::clear($order['status_pay']) : static::statusPayDefault();

        $code        = (!empty($order['code'])) ? Str::clear($order['code']) : '';

        $data = compact( 'code', 'total', 'status', 'status_pay', 'user_created', 'user_updated');

        $data = apply_filters( 'pre_insert_order_data', $data, $order, $metadata, $update ? $old_order : null );

        if( $update ) {
            $model->settable('wcmc_order')->update( $data, Qr::set($id));
            $order_id = $id;
        }
        else{
            $order_id = $model->settable('order')->add( $data );
            $model->update(['code' => static::generateCode($order_id)], Qr::set($order_id));

            //insert product item
            foreach ($items as $item) {
                $meta_item = [];
                if(!empty($item['metadata']) && have_posts($item['metadata'])) {
                    $meta_item = $item['metadata'];
                    unset($item['metadata']);
                }
                static::insertItem( $item, $order_id, $meta_item );
            }

            //insert metada
            if(have_posts($metadata)) {
                foreach ($metadata as $meta_key => $meta_value) {
                    if(is_string($meta_value)) $meta_value = Str::clear($meta_value);
                    static::updateMeta( $order_id, $meta_key, $meta_value );
                }
            }
        }

        if(!is_skd_error($order_id) && $update) CacheHandler::delete('order_'.$order_id, true);

        return $order_id;
    }
    static public function getMeta($order_id, $key = '', $single = true) {
        return Metadata::get('wcmc_order', $order_id, $key, $single);
    }
    static public function updateMeta($order_id, $meta_key, $meta_value) {
        CacheHandler::delete( 'wcmc_order_'.$order_id, true );
        return Metadata::update('wcmc_order', $order_id, $meta_key, $meta_value);
    }
    static public function deleteMeta($order_id, $meta_key = '', $meta_value = '') {
        return Metadata::delete('wcmc_order', $order_id, $meta_key, $meta_value);
    }
    static public function getItem( $args = [], $metadata = true ) {

        $model = model('wcmc_order_detail');

        if(is_numeric($args)) $args = Qr::set($args);

        if(is_array($args)) $args = Qr::convert($args);

        $item =  $model->get($args);

        return $item;
    }
    static public function getsItem( $args = '', $metadata = true ) {

        $model = get_model('products')->settable('wcmc_order_detail');

        if(is_numeric($args)) $args = array('where' => ['order_id' => (int)$args]);

        if(!have_posts($args)) $args = [];

        $args = array_merge(['where' => [], 'params' => [] ], $args);

        $where 	= $args['where'];

        $params = $args['params'];

        $items =  $model->gets_where($where, $params );

        $model->settable('products');

        return $items;
    }
    static public function deleteItemBy( $field = '', $data = '' ) {

        if (!$field) return new SKD_Error('emptyItem_field', __('ID File không được bỏ trống.'));

        $model = get_model('products')->settable('wcmc_order_detail');

        $items = $model->gets_where([$field => $data]);

        if(have_posts($items)) {
            foreach ($items as $item) {
                static::deleteItemMeta($item->id);
                $model->delete_where( array( 'id' => $item->id ) );
            }
            return true;
        }
        return 	false;
    }

    static public function getItemMeta( $order_item_id, $key = '', $single = true) {
        return Metadata::get('wcmc_order_detail', $order_item_id, $key, $single);
    }
    static public function updateItemMeta($order_item_id, $meta_key, $meta_value) {
        return Metadata::update('wcmc_order_detail', $order_item_id, $meta_key, $meta_value);
    }
    static public function deleteItemMeta($order_item_id, $meta_key = '', $meta_value = '') {
        return Metadata::delete('wcmc_order_detail', $order_item_id, $meta_key, $meta_value);
    }

    static public function statusDefault() {
        return ORDER_WAIT;
    }
    static public function status($key = '', $type = '') {
        $status = [
            ORDER_WAIT => [
                'label' => __('Chờ xác nhận', ORDER_WAIT),
                'color' => '#DFE4E8',
            ],
            ORDER_CONFIRM => [
                'label' => __('Đã tiếp nhận', ORDER_CONFIRM),
                'color' => '#b4dfff',
            ],
            ORDER_PROCESSING => [
                'label' => __('Đang đóng gói', ORDER_PROCESSING),
                'color' => '#57d616',
            ],
            ORDER_SHIPPING => [
                'label' => __('Đang vận chuyển', ORDER_SHIPPING),
                'color' => '#ffc58b',
            ],
            ORDER_SHIPPING_FAIL => [
                'label' => __('Giao hàng thất bại', ORDER_SHIPPING_FAIL),
                'color' => '#ff9d83',
            ],
            ORDER_COMPLETED => [
                'label' => __('Hoàn thành', ORDER_COMPLETED),
                'color' => '#BAE0BD',
            ],
            ORDER_CANCELLED => [
                'label' => __('Đã hủy', ORDER_CANCELLED),
                'color' => '#ffc1c1',
            ],
        ];
        $status = apply_filters( 'order_status', $status);
        if(!empty($key) && !empty($type) && isset($status[$key])) {
            if(!empty($status[$key][$type])) return apply_filters('order_status_'.$type, $status[$key][$type], $key, $type);
            return $status;
        }
        return $status;
    }
    static public function statusOptions($top = true) {
        $status = static::status();
        $status_options = [];
        if($top == true) $status_options[] = 'Trạng thái đơn hàng';
        foreach ($status as $key => $item) {
            $status_options[$key] = $item['label'];
        }
        return $status_options;
    }
    static public function statusPayDefault() {
        return 'unpaid';
    }
    static public function statusPay($key = '', $type = '') {
        $status = [
            'unpaid' => [
                'label' => __('Chờ thanh toán', 'unpaid'),
                'color' => '#FFEA8A',
            ],
            'paid' => [
                'label' => __('Đã thanh toán', 'paid'),
                'color' => '#BAE0BD',
            ],
            'refunded' => [
                'label' => __('Đã hoàn tiền', 'refunded'),
                'color' => '#ffc1c1',
            ],
        ];
        if(!empty($key) && !empty($type) && isset($status[$key])) {
            if(!empty($status[$key][$type])) return apply_filters('order_status_pay_'.$type, $status[$key][$type], $key, $type);
            return apply_filters( 'order_status_pay', $status[$key], $key, $type);
        }
        return apply_filters( 'order_status_pay', $status, $key);
    }
    static public function statusPayOptions($top = true) {
        $status = static::statusPay();
        $status_options = [];
        if($top == true) $status_options[] = 'Trạng thái thanh toán';
        foreach ($status as $key => $item) {
            $status_options[$key] = $item['label'];
        }
        return $status_options;
    }

    static public function insertItem( $item = '', $order_id = 0, $metadata = '' ) {

        $model = get_model('products')->settable('wcmc_order_detail');

        if (!empty($item['id'])) {
            $id 			= (int) $item['id'];
            $update 	    = true;
            $oldItem = static::getItem($id, false, false);
            if(!$oldItem) return new SKD_Error('invalidItem_id', __('ID item đơn hàng không chính xác.'));
        }
        else {
            $update = false;
        }

        if(empty($order_id)) return new SKD_Error( 'emptyId', __( 'ID Đơn hàng không chính xác.' ) );

        if(empty($item['product_id'])) return new SKD_Error( 'empty_product_id', __( 'ID sản phẩm không chính xác.' ) );

        if(empty($item['title'])) return new SKD_Error( 'empty_product_title', __( 'ID tiêu đề sản phẩm không được để trống.' ) );

        $product_id    	= (int)$item['product_id'];

        $title    		= Str::clear($item['title']);

        $price   		= empty($item['price']) ? 0 : (int)$item['price'];

        $quantity   	= empty($item['quantity']) ? 0 : (int)$item['quantity'];

        $subtotal 		= empty($item['subtotal']) ? $price*$quantity : (int)$item['subtotal'];

        $option    		= empty($item['option']) ? '' : Str::clear($item['option']);

        $option    		= (is_array($option) || is_object($option)) ? serialize($option) : Str::clear($option);

        if(!empty($item['image'])) {
            $image    = Str::clear($item['image']);
            $image    = FileHandler::handlingUrl($image);
        }
        $data = compact( 'order_id', 'image', 'title', 'product_id', 'quantity', 'price', 'subtotal', 'option');
        $data = apply_filters( 'pre_insertItem_data', $data, $update, $update ? $oldItem : null );
        if($update) {
            $model->settable('order_detail')->update( $data, Qr::set($id));
            $order_item_id = $id;
        }
        else {
            $order_item_id = $model->settable('wcmc_order_detail')->add( $data );
        }

        if(have_posts($metadata) && $order_item_id != 0) {
            foreach ( $metadata as $meta_key => $meta_value ) {
                static::updateItemMeta($order_item_id, $meta_key, $meta_value);
            }
        }

        return $order_item_id;
    }
}