<?php
class Order {
    static string $table = 'order';
    static string $tableItem = 'order_detail';
    static public function handleParamsArr($args = null) {
        if(!empty($args['status'])) { $args['where']['status'] = $args['status']; unset($args['status']);}
        return $args;
    }
    static public function handleParams($args = null) {
        if(is_array($args)) {
            $args = self::handleParamsArr($args);
            $query = Qr::convert($args, 'order_metadata');
            if(!$query) return $query;
        }
        if(is_numeric($args)) $query = Qr::set(self::$table.'.id', $args);
        if($args instanceof Qr) $query = clone $args;
        return (isset($query)) ? $query : null;
    }
    static public function generateCode($id = 0) {
        return apply_filters( 'order_generate_code', (1000 + $id));
    }
    static public function get($args = [], $detail = true, $metadata = true ) {
        if(is_numeric($args)) $cacheID = 'order_'.$args;
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        if(isset($cacheID)) $cacheID .= md5(serialize(Qr::clear($args)));
        if(isset($cacheID) && CacheHandler::has($cacheID) !== false) {
            return CacheHandler::get($cacheID);
        }
        $order =  model(self::$table)->get($args);
        if(have_posts($order)) {
            //get danh sách product item
            $order->items = static::getsItem(Qr::set('order_id', $order->id));
            //get danh sách metadat
            $order_info = static::getMeta($order->id, '', false);

            if( have_posts($order_info) ) {
                $order_info->shipping_fullname =  (!empty($order_info->shipping_address))?$order_info->shipping_fullname:$order_info->billing_fullname;
                $order_info->shipping_address  =  (!empty($order_info->shipping_address))?$order_info->shipping_address:$order_info->billing_address;
                $order_info->shipping_phone    =  (!empty($order_info->shipping_phone))?$order_info->shipping_phone:$order_info->billing_phone;
                $order_info->shipping_email    =  (!empty($order_info->shipping_email))?$order_info->shipping_email:$order_info->billing_email;
            }

            $order = (object)array_merge( (array)$order_info, (array)$order);

            //lưu capche
            if( isset($cacheID) ) CacheHandler::save($cacheID, $order);
        }

        return $order;
    }
    static public function gets($args = [], $detail = true, $metadata = true ) {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        $orders =  model(self::$table)->gets($args);
        if(have_posts($orders) && ($detail || $metadata)) {
            foreach ($orders as $key => $order) {
                $orders[$key] = static::get($order->id);
            }
        }
        return $orders;
    }
    static public function count($args = []): int {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return 0;
        return model(self::$table)->count($args);
    }
    static public function max($column, $args) {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->max($column, $args));
    }
    static public function min($column, $args) {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->min($column, $args));
    }
    static public function avg($column, $args) {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->avg($column, $args));
    }
    static public function sum($column, $args) {
        $args = static::handleParams($args);
        return apply_filters('max_'.static::$table, model(static::$table)->sum($column, $args));
    }
    static public function update($data = [], $args = []): bool|int {
        $args = self::handleParams($args);
        if(!$args instanceof Qr) return false;
        return model(self::$table)->update($data, $args);
    }
    static public function insert( $order = [], $metadata = []) {
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

        $model = model(self::$table);

        if( $update ) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update($data, Qr::set($id));
            $order_id = $id;
        }
        else{
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $order_id = $model->add($data);

            $model->update(['code' => static::generateCode($order_id)], Qr::set($order_id));

            //insert product item
            foreach ($items as $item) {
                $meta_item = [];
                if(!empty($item['metadata']) && have_posts($item['metadata'])) {
                    $meta_item = $item['metadata'];
                    unset($item['metadata']);
                }
                static::insertItem($item, $order_id, $meta_item );
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
    static public function deleteById($order_id = 0) {
        if(empty($order_id)) return false;
        $model = model(self::$table);
        static::deleteItemBy('order_id', $order_id);
        static::deleteMeta($order_id);
        if($model->settable('order')->delete(Qr::set($order_id))) {
            CacheHandler::delete('order_'.$order_id, true);
            return true;
        }
        return false;
    }
    static public function delete($orderId = 0) {

        $ci =& get_instance();

        $orderId = (int)Str::clear($orderId);

        if($orderId == 0) return false;

        $model = model(self::$table);

        $order  = static::get($orderId);

        if(have_posts($order)) {

            $ci->data['module']   = self::$table;

            do_action('delete_'.self::$table, $orderId);

            if($model->delete(Qr::set($orderId))) {

                do_action('delete_'.self::$table.'_success', $orderId );

                static::deleteItemBy('order_id', $orderId);

                static::deleteMeta($orderId);

                $model->settable('order_history')->delete(Qr::set('order_id', $orderId));

                CacheHandler::delete('order_'.$orderId, true);

                return [$orderId];
            }
        }

        return false;
    }
    static public function deleteList($orderId = []) {

        if(have_posts($orderId)) {

            $model  = model(self::$table);

            $orders = static::gets(Qr::set()->whereIn('id', $orderId));

            if($model->delete(Qr::set()->whereIn('id', $orderId))) {

                do_action('delete_order_list_trash_success', $orderId);

                //delete router
                foreach ($orders as $order) {

                    static::deleteItemBy('order_id', $order->id);

                    static::deleteMeta($order->id);

                    $model->settable('order_history')->delete(Qr::set('order_id', $orderId));

                    CacheHandler::delete('order_'.$order->id, true);
                }

                return $orderId;
            }
        }

        return false;
    }
    static public function getMeta($order_id, $key = '', $single = true) {
        return Metadata::get('order', $order_id, $key, $single);
    }
    static public function updateMeta($order_id, $meta_key, $meta_value) {
        CacheHandler::delete( 'order_'.$order_id, true );
        return Metadata::update('order', $order_id, $meta_key, $meta_value);
    }
    static public function deleteMeta($order_id, $meta_key = '', $meta_value = '') {
        return Metadata::delete('order', $order_id, $meta_key, $meta_value);
    }
    static public function handleParamsItem($args = null) {
        if(is_array($args)) {
            $query = Qr::convert($args);
            if(!$query) return $query;
        }
        if(is_numeric($args)) $query = Qr::set(self::$tableItem.'.id', $args);
        if($args instanceof Qr) $query = clone $args;
        return (isset($query)) ? $query : null;
    }
    static public function getItem( $args = []) {
        $args = self::handleParamsItem($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        return model(self::$tableItem)->get($args);
    }
    static public function getsItem( $args = '', $metadata = true ) {
        $args = self::handleParamsItem($args);
        if(!$args instanceof Qr) return new Illuminate\Support\Collection();
        return model(self::$tableItem)->gets($args);
    }
    static public function deleteItemBy( $field = '', $data = '' ) {

        if (!$field) return new SKD_Error('emptyItem_field', __('ID File không được bỏ trống.'));

        $model = model(self::$tableItem);

        $items = $model->gets(Qr::set($field, $data));

        if(have_posts($items)) {
            foreach ($items as $item) {
                static::deleteItemMeta($item->id);
                $model->delete(Qr::set($item->id));
            }
            return true;
        }

        return false;
    }
    static public function insertItem($item = '', $order_id = 0, $metadata = '' ): SKD_Error|int {

        if (!empty($item['id'])) {

            $id 	 = (int) $item['id'];

            $update  = true;

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

        $image = (isset($oldItem)) ? $oldItem->image : '';

        if(!empty($item['image'])) {
            $image    = Str::clear($item['image']);
            $image    = FileHandler::handlingUrl($image);
        }

        $data = compact( 'order_id', 'image', 'title', 'product_id', 'quantity', 'price', 'subtotal', 'option');

        $data = apply_filters( 'pre_insertItem_data', $data, $update, $update ? $oldItem : null );

        $model = model(self::$tableItem);

        if($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update( $data, Qr::set($id));
            $order_item_id = $id;
        }
        else {
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $order_item_id = $model->add( $data );
        }

        if(have_posts($metadata) && $order_item_id != 0) {
            foreach ( $metadata as $meta_key => $meta_value ) {
                static::updateItemMeta($order_item_id, $meta_key, $meta_value);
            }
        }

        return $order_item_id;
    }

    static public function getItemMeta( $order_item_id, $key = '', $single = true) {
        return Metadata::get(self::$tableItem, $order_item_id, $key, $single);
    }
    static public function updateItemMeta($order_item_id, $meta_key, $meta_value) {
        return Metadata::update(self::$tableItem, $order_item_id, $meta_key, $meta_value);
    }
    static public function deleteItemMeta($order_item_id, $meta_key = '', $meta_value = '') {
        return Metadata::delete(self::$tableItem, $order_item_id, $meta_key, $meta_value);
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
        if($top) $status_options[] = 'Trạng thái đơn hàng';
        foreach ($status as $key => $item) {
            $status_options[$key] = $item['label'];
        }
        return $status_options;
    }
    static public function statusPayDefault(): string {
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
    static public function statusPayOptions($top = true): array {
        $status = static::statusPay();
        $status_options = [];
        if($top) $status_options[] = 'Trạng thái thanh toán';
        foreach ($status as $key => $item) {
            $status_options[$key] = $item['label'];
        }
        return $status_options;
    }
}

class OrderHistory extends Model{
    static string $table = 'order_history';
    static public function insert($history = []) {
        $update = false;
        if (!empty($history['id'])) {
            $id 		= (int) $history['id'];
            $update 	= true;
            $old_history = static::get($id);
            if(!$old_history) return new SKD_Error( 'invalidId', __( 'ID đơn hàng không chính xác.' ) );
        }
        if(!empty($history['order_id'])) $order_id 	= trim(Str::clear($history['order_id']));
        if(!empty($history['message']))  $message 	= base64_encode(trim($history['message']));
        if(!empty($history['action']))  $action 	= trim(Str::clear($history['action']));
        $data = compact( 'order_id', 'message', 'action');
        $data = apply_filters( 'pre_insert_order_history_data', $data, $update, $update ? (int) $id : null );
        $model = model(static::$table);
        if($update) {
            $data['updated'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $model->update( $data, Qr::set($id));
            $order_id = $id;
        }
        else{
            $data['created'] = gmdate('Y-m-d H:i:s', time() + 7*3600);
            $order_id = $model->add($data);
        }
        return $order_id;
    }
    static public function message($action = '', $args = []) {
        $message = '';
        if(empty($action)) return '';
        if($action == 'frontend-add') {
            $message = sprintf('Đơn hàng <span class="hs-orcode"><b>%s</b></span> đã được đặt.', $args['code']);
        }
        if($action == 'backend-add') {
            $message = sprintf(
                '<span class="hs-usname"><b>%s</b></span> đã tạo đơn hàng <span class="hs-orcode"><b>%s</b></span>.',
                $args['username'], $args['code']
            );
        }
        if($action == 'backend-status') {
            $message = sprintf(
                '<span class="hs-usname"><b>%s</b></span> cập nhật trạng thái đơn hàng <span class="hs-orcode"><b>%s</b></span> thành <span class="hs-orstatus"><b>%s</b></span>.',
                $args['username'], $args['code'], $args['status']
            );
        }
        if($action == 'backend-cancelled') {
            $message = sprintf(
                'Đơn hàng đã bị hủy bởi <span class="hs-usname"><b>%s</b></span>. Lý do bởi: <span>%s</span>',
                $args['username'], $args['reason']
            );
        }
        return apply_filters('order_history_message', $message, $action, $args);
    }
    static public function delete($historyID = 0) {

        $ci =& get_instance();

        $historyID = (int)Str::clear($historyID);

        if($historyID == 0) return false;

        $model = model(self::$table);

        $brands  = static::get($historyID);

        if(have_posts($brands)) {
            $ci->data['module']   = self::$table;
            do_action('delete_'.self::$table, $historyID);
            if($model->delete(Qr::set('id', $historyID))) {
                do_action('delete_'.self::$table.'_success', $historyID);
                return [$historyID];
            }
        }

        return false;
    }
    static public function deleteList($historyID = []) {

        if(have_posts($historyID)) {

            $model      = model(self::$table);

            $histories = static::gets(Qr::set()->whereIn('id', $historyID));

            if($model->delete(Qr::set()->whereIn('id', $historyID))) {

                $args = Qr::set('object_type', self::$table)->whereIn('object_id', $historyID);

                do_action('delete_history_list_trash_success', $historyID);

                //delete router
                foreach ($histories as $history) {
                    Gallery::deleteItemByObject($history->id, self::$table);
                    Metadata::deleteByMid(self::$table, $history->id);
                }

                return $historyID;
            }
        }

        return false;
    }
}

