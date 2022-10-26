<?php
include 'ajax.php';
include 'table.php';
include 'print.php';
include 'order-action.php';
include 'order-save.php';

Class Admin_Page_Order {
    static public function page($Form) {

        $views 	= Request::get('view');

        if($views == 'shop_order' || empty($views)) {

            $args = Qr::set();

            $pagination = [];

            $customer_id = (int)Request::get('customer_id');

            if($customer_id != 0) {
                $args->where('user_created', $customer_id);
            }

            $keyword = Str::clear(Request::get('name'));
            if(!empty($keyword)) {
                $args->setMetaQuery('billing_fullname', $keyword, 'like');
            }

            $phone = Str::clear(Request::get('phone'));
            if(!empty($phone)) {
                $args->setMetaQuery('billing_phone', $phone, 'like');
            }

            $status = Str::clear(Request::get('status'));

            if(!empty($status)) {
                $args->where('status', $status);
            }

            $time = Str::clear(Request::get('time'));

            if(!empty($time)) {
                $time = explode(' - ', $time);
                if(have_posts($time) && count($time) == 2) {
                    $timeStart = date('Y-m-d', strtotime($time[0])).' 00:00:00';
                    $timeEnd   = date('Y-m-d', strtotime($time[1])).' 23:59:59';
                    $args->where('created', '>=', $timeStart);
                    $args->where('created', '<=', $timeEnd);
                }
            }

            if(empty($keyword) && empty($phone) && empty($status) && (empty($timeStart) || empty($timeEnd))) {
                $orderLimit = 20;
                $args = apply_filters('admin_order_index_args_count', $args );
                $total = Order::count($args);
                //Phân trang
                $url        = Url::admin('plugins?page=order&paging={paging}');
                $pagination = pagination($total, $url, $orderLimit);
                $args->limit($orderLimit)->offset($pagination->offset())->orderByDesc('created');
            }

            $args = apply_filters('admin_order_index_args', $args);

            $orders = Order::gets($args);

            $args = array(
                'items' => $orders,
                'table' => 'order',
                'model' => model('order'),
                'module'=> 'order',
            );

            $table_list = new skd_order_list_table($args);

            cart_template('admin/order/html-order-index', array('table_list' => $table_list, 'pagination' => $pagination));
        }

        if($views == 'detail') {

            $id 	= (int)Request::get('id');

            $order 	= Order::get($id);

            $order_cancelled_reason = option::get('order_cancelled_reason', [
                'KH thay đổi / KH Hủy đơn',
                'Không liên hệ được KH',
                'Đơn hàng sai thông tin',
                'Sản phẩm không có sẳn',
            ]);

            if( Request::post() ) {

                $status = Request::post('status');

                $order_update = [];

                if(!empty($status)) {
                    $order_update = apply_filters( 'admin_order_status_'.$status.'_save',  $order_update, $status, $order );
                    do_action( 'admin_order_action_'.$status,  $order, $status );
                    do_action( 'admin_order_status_'.$status.'_action',  $order, $status );
                }

                $status_pay = Request::post('status-pay');

                if(!empty($status_pay)) {
                    $order_update = apply_filters( 'admin_order_status_pay_'.$status_pay.'_save',  $order_update, $status_pay, $order );
                    do_action( 'admin_order_status_pay_'.$status_pay.'_action',  $order, $status );
                }

                do_action( 'admin_order_action_post', $order );

                $order_update = apply_filters( 'admin_order_detail_action_save', $order_update, $order, $status );

                if(have_posts($order_update)) {

                    $order_update['id'] = $order->id;

                    Order::insert($order_update);

                    redirect( URL_ADMIN.'/plugins?page=order&view=detail&id='.$id,'refresh');
                }
            }

            cart_template('admin/order/html-order-detail', ['order' => $order, 'order_cancelled_reason' => $order_cancelled_reason]);
        }

        if($views == 'create') {
            if(Auth::hasCap('order_add'))  {
                include CART_PATH.'template/admin/order/html-order-save.php';
            }
            else {
                echo notice('error', 'Bạn không có đủ quyền để sử dụng chức năng này.');
            }
        }

        if($views == 'edit') {
            if(Auth::hasCap('order_copy'))  {
                $id 	= (int)Request::get('id');
                $order 	= Order::get($id);
                include CART_PATH.'template/admin/order/html-order-save.php';
            }
            else {
                echo notice('error', 'Bạn không có đủ quyền để sử dụng chức năng này.');
            }
        }
    }
}

Class Admin_Page_Order_List {
    function __construct() {
        add_filter( 'admin_order_index_search', [$this, 'searchField'], 10);
        add_action( 'manage_orders_custom_column', [$this, 'columnShippingData'], 10, 2);
    }
    public function searchField($Form) {
        $Form->add('name','text', ['after' => '<div class="form-group">', 'before' => '</div>', 'placeholder' => 'Tên khách hàng',], Request::get('name'));
        $Form->add('phone','text',['after' => '<div class="form-group">', 'before' => '</div>', 'placeholder' => 'Số điện thoại'],  Request::get('phone'));
        $Form->add('status','select',['after' => '<div class="form-group">', 'before' => '</div>', 'options' => Order::statusOptions(), 'placeholder' => 'Trạng thái đơn hàng'], Request::get('status'));
        $Form->add('time','daterange',['after' => '<div class="form-group">', 'before' => '</div>', 'placeholder' => ''], Request::get('time'));
        return $Form;
    }
    public function columnShippingData( $column_name, $item ) {
        switch ( $column_name ) {
            case 'shipping_online':
                if(!empty($item->_shipping_type)) {
                    echo $item->_shipping_label;
                    do_action('shipping_online_table_column', $item );
                }
                break;
        }
    }
}
new Admin_Page_Order_List();

Class Admin_Page_Order_Detail {

    function __construct() {
        add_action( 'order_detail_header_action', [$this, 'addButtonAction'] );

        add_action( 'order_detail_sections_primary', [$this, 'renderContent'], 10);
        add_action( 'order_detail_sections_primary', [$this, 'renderNote'], 20);
        add_action( 'order_detail_sections_primary', [$this, 'renderShipping'], 30);
        add_action( 'order_detail_sections_primary', [$this, 'renderHistory'], 30);

        add_action( 'order_detail_sections_secondary', [$this, 'renderAction'], 10);
        add_action( 'order_detail_sections_secondary', [$this, 'renderCustomerInfo'], 20);
    }

    public function addButtonAction($order) {

        if(Auth::hasCap('order_copy')) {
            echo '<a href="'.Url::admin(sicommerce_cart::url('order').'&view=edit&id=' . $order->id).'" class="btn btn-default"><i class="fal fa-clone"></i> Đặt lại</a>';
        }

        if($order->status != ORDER_CANCELLED && $order->status != ORDER_COMPLETED) {
            echo '<a href="#" class="btn btn-default js_order__btn_cancelled" data-id="'.$order->id.'"><i class="fal fa-times"></i> Hủy đơn hàng</a>';
        }
    }

    public function renderContent($order) {
        cart_template('admin/order/detail/content', array('order' => $order));
    }
    public function renderNote($order) {
        cart_template('admin/order/detail/note', array('order' => $order));
    }
    public function renderShipping($order) {
        cart_template('admin/order/detail/shipping', array('order' => $order));
    }
    public function renderHistory($order) {

        $histories = OrderHistory::gets(Qr::set('order_id', $order->id));
        cart_template('admin/order/detail/history', array('order' => $order, 'histories' => $histories));
    }

    public function renderAction($order) {
        cart_template('admin/order/detail/sidebar-action', array('order' => $order));
    }
    public function renderCustomerInfo($order) {
        cart_template('admin/order/detail/sidebar-customer', array('order' => $order));
    }

    static public function billingInfo($order) {
        $billing = get_checkout_fields_billing();
        $temp = [];
        foreach ($billing as $key => $field) {
            if(isset($order->{$field['field']})) $temp[$field['field']] = $order->{$field['field']};
        }
        return apply_filters( 'order_detail_billing_info', $temp );
    }
    static public function shippingInfo($order) {
        $shipping = get_checkout_fields_shipping();
        $temp = [];
        foreach ($shipping as $key => $field) {
            if(isset($order->{$field['field']})) $temp[$field['field']] = $order->{$field['field']};
        }
        return apply_filters( 'order_detail_shipping_info', $temp );
    }
}
new Admin_Page_Order_Detail();