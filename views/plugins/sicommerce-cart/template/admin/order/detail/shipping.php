<?php
$shipping = shipping_gateways();
if(have_posts($shipping)) {?>
    <div class="box" id="order_shipping" style="position: relative">
        <div class="box-content">
            <header class="order__title">
                <div class="order__title_wrap"> <h2>Hình thức vận chuyển</h2> </div>
            </header>
            <div class="order_cart__section">
                <?php
                if(!empty($order->_shipping_type)) {
                    foreach ($shipping as $key => $payment) {
                        if($order->_shipping_type == $key) {
                            echo (!empty($payment['title'])) ? $payment['title'] : $key ;
                        }
                    }
                }
                ?>
            </div>
            <?php echo Admin::loading();?>
            <?php if(empty($order->waybill_code) && $order->status == ORDER_WAIT || $order->status == ORDER_CONFIRM) { ?>
                <div id="js_order_shipping_list"></div>
                <div class="order_cart__section text-right">
                    <button type="button" class="btn btn-blue js_order_btn__shipping_show_list" data-id="<?php echo $order->id;?>">Thay đổi hình thức vận chuyển</button>
                    <button type="button" class="btn btn-red js_order_btn__shipping_change" style="display: none;" data-id="<?php echo $order->id;?>">Xác nhận</button>
                    <button type="button" class="btn btn-white js_order_btn__shipping_cancel" style="display: none;" data-id="<?php echo $order->id;?>">Hủy</button>
                </div>
            <?php } ?>
        </div>
    </div>
    <script>
        $(function () {
            $('.js_order_btn__shipping_show_list').click(function () {
                $('#order_shipping .loading').show();
                let data = {
                    'action': 'Admin_Order_Ajax::loadDetailShippingList',
                    'id': $(this).attr('data-id')
                };
                $jqxhr = $.post(ajax, data, function () {}, 'json');
                $jqxhr.done(function (response) {
                    $('#order_shipping .loading').hide();
                    if (response.status === 'success') {
                        $('#js_order_shipping_list').html(response.shipping_list);
                        $('.js_order_btn__shipping_show_list').hide();
                        $('.js_order_btn__shipping_change').show();
                        $('.js_order_btn__shipping_cancel').show();
                    }
                    else {
                        show_message(response.message, response.status);
                    }
                });
                return false;
            });
            $('.js_order_btn__shipping_cancel').click(function () {
                $('#js_order_shipping_list').html('');
                $('.js_order_btn__shipping_show_list').show();
                $('.js_order_btn__shipping_change').hide();
                $('.js_order_btn__shipping_cancel').hide();
                return false;
            });
            $('.js_order_btn__shipping_change').click(function () {
                let data = $(':input', '#js_order_shipping_list').serializeJSON();
                data.action = 'Admin_Order_Ajax::updateDetailShipping';
                data.id = $(this).attr('data-id');
                $.post(ajax, data, function () {}, 'json').done(function (response) {
                    show_message(response.message, response.status);
                    if (response.status === 'success') {
                    }
                });
                return false;
            });
        });
    </script>
<?php } ?>