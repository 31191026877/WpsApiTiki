<?php
$user 		= Auth::user();
$order_id 	= (int)Request::get('code');
$order 		= Order::get(Qr::set($order_id)->where('user_created', $user->id));
$histories = OrderHistory::gets(Qr::set('order_id', $order->id));
$step = [
    ['finish' => true, 'label' => __('Đơn hàng đã đặt'), 'time' => $order->created],
    ['finish' => false, 'label' => __('Đã xác nhận'), 'time' => ''],
    ['finish' => false, 'label' => __('Đang đóng gói'), 'time' => ''],
    ['finish' => false, 'label' => __('Đang giao hàng'), 'time' => ''],
    ['finish' => false, 'label' => __('Hoàn thành'), 'time' => ''],
];
$step_go = 0;
switch ($order->status) {
    case ORDER_CONFIRM:
        $step_go = 0.25;
        $step[1] = ['finish' => true, 'label' => __('Đã xác nhận'), 'time' => ''];
    break;
    case ORDER_PROCESSING:
        $step_go = 0.5;
        $step[1] = ['finish' => true, 'label' => __('Đã xác nhận'), 'time' => ''];
        $step[2] = ['finish' => true, 'label' => __('Đang đóng gói'), 'time' => ''];
    break;
    case ORDER_SHIPPING:
        $step_go = 0.75;
        $step[1] = ['finish' => true, 'label' => __('Đã xác nhận'), 'time' => ''];
        $step[2] = ['finish' => true, 'label' => __('Đã đóng gói'), 'time' => ''];
        $step[3] = ['finish' => true, 'label' => __('Đang vận chuyển'), 'time' => ''];
    break;
    case ORDER_SHIPPING_FAIL:
        $step_go = 0.75;
        $step[1] = ['finish' => true, 'label' => __('Đã xác nhận'), 'time' => ''];
        $step[2] = ['finish' => true, 'label' => __('Đã đóng gói'), 'time' => ''];
        $step[3] = ['finish' => true, 'label' => __('Giao hàng thất bại'), 'time' => ''];
    break;
    case ORDER_COMPLETED:
        $step_go = 1;
        $step[1] = ['finish' => true, 'label' => __('Đã xác nhận'), 'time' => ''];
        $step[2] = ['finish' => true, 'label' => __('Đã đóng gói'), 'time' => ''];
        $step[3] = ['finish' => true, 'label' => __('Giao hàng thành công'), 'time' => ''];
        if($order->status_pay == 'paid') {
            $step[4] = ['finish' => true, 'label' => __('Hoàn Thành'), 'time' => ''];
        }
        break;
    case ORDER_CANCELLED:
        $step_go = 1;
        $step[1] = ['finish' => true, 'label' => __('Đã xác nhận'), 'time' => ''];
        $step[2] = ['finish' => true, 'label' => __('Đã đóng gói'), 'time' => ''];
        $step[3] = ['finish' => true, 'label' => __('Giao hàng thất bại'), 'time' => ''];
        $step[4] = ['finish' => true, 'label' => __('Đã hủy'), 'time' => ''];
        break;
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="order-header">
            <h1>Đơn hàng #<?php echo $order->code;?>, <span class="order-time-created"><?php echo __('Đặt lúc');?> — <?php echo date('d/m/Y, H:s', strtotime($order->created));?></span></h1>
        </div>
    </div>
    <div class="clearfix"></div>
    <br />
    <div class="_1AsWWl"></div>
    <br />
    <div class="col-md-12">
        <div class="order-ui order__step">
            <div class="stepper">
                <div class="stepper__step <?php echo ($step[0]['finish']) ? 'stepper__step--finish' : '';?>">
                    <div class="stepper__step-icon <?php echo ($step[0]['finish']) ? 'stepper__step-icon--finish' : '';?>">
                        <svg enable-background="new 0 0 32 32" viewBox="0 0 32 32" x="0" y="0" class="shopee-svg-icon icon-order-order">
                            <g>
                                <path d="m5 3.4v23.7c0 .4.3.7.7.7.2 0 .3 0 .3-.2.5-.4 1-.5 1.7-.5.9 0 1.7.4 2.2 1.1.2.2.3.4.5.4s.3-.2.5-.4c.5-.7 1.4-1.1 2.2-1.1s1.7.4 2.2 1.1c.2.2.3.4.5.4s.3-.2.5-.4c.5-.7 1.4-1.1 2.2-1.1.9 0 1.7.4 2.2 1.1.2.2.3.4.5.4s.3-.2.5-.4c.5-.7 1.4-1.1 2.2-1.1.7 0 1.2.2 1.7.5.2.2.3.2.3.2.3 0 .7-.4.7-.7v-23.7z" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"></path>
                                <g>
                                    <line fill="none" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" x1="10" x2="22" y1="11.5" y2="11.5"></line>
                                    <line fill="none" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3" x1="10" x2="22" y1="18.5" y2="18.5"></line>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="stepper__step-text"><?php echo $step[0]['label'];?></div>
                    <div class="stepper__step-date"><?php echo $step[0]['time'];?></div>
                </div>
                <div class="stepper__step <?php echo ($step[1]['finish']) ? 'stepper__step--finish' : '';?>">
                    <div class="stepper__step-icon <?php echo ($step[1]['finish']) ? 'stepper__step-icon--finish' : '';?>">
                        <svg enable-background="new 0 0 32 32" viewBox="0 0 32 32" x="0" y="0" class="shopee-svg-icon icon-order-paid">
                            <g>
                                <path clip-rule="evenodd" d="m24 22h-21c-.5 0-1-.5-1-1v-15c0-.6.5-1 1-1h21c .5 0 1 .4 1 1v15c0 .5-.5 1-1 1z" fill="none" fill-rule="evenodd" stroke-miterlimit="10" stroke-width="3"></path>
                                <path clip-rule="evenodd" d="m24.8 10h4.2c.5 0 1 .4 1 1v15c0 .5-.5 1-1 1h-21c-.6 0-1-.4-1-1v-4" fill="none" fill-rule="evenodd" stroke-miterlimit="10" stroke-width="3"></path>
                                <path d="m12.9 17.2c-.7-.1-1.5-.4-2.1-.9l.8-1.2c.6.5 1.1.7 1.7.7.7 0 1-.3 1-.8 0-1.2-3.2-1.2-3.2-3.4 0-1.2.7-2 1.8-2.2v-1.3h1.2v1.2c.8.1 1.3.5 1.8 1l-.9 1c-.4-.4-.8-.6-1.3-.6-.6 0-.9.2-.9.8 0 1.1 3.2 1 3.2 3.3 0 1.2-.6 2-1.9 2.3v1.2h-1.2z" stroke="none"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="stepper__step-text"><?php echo $step[1]['label'];?></div>
                    <div class="stepper__step-date"><?php echo $step[1]['time'];?></div>
                </div>
                <div class="stepper__step <?php echo ($step[2]['finish']) ? 'stepper__step--finish' : '';?>">
                    <div class="stepper__step-icon <?php echo ($step[2]['finish']) ? 'stepper__step-icon--finish' : '';?>">
                        <svg enable-background="new 0 0 32 32" viewBox="0 0 32 32" x="0" y="0" class="shopee-svg-icon icon-order-received">
                            <g>
                                <polygon fill="none" points="2 28 2 19.2 10.6 19.2 11.7 21.5 19.8 21.5 20.9 19.2 30 19.1 30 28" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"></polygon>
                                <polyline fill="none" points="21 8 27 8 30 19.1" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"></polyline>
                                <polyline fill="none" points="2 19.2 5 8 11 8" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"></polyline>
                                <line fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3" x1="16" x2="16" y1="4" y2="14"></line>
                                <path d="m20.1 13.4-3.6 3.6c-.3.3-.7.3-.9 0l-3.6-3.6c-.4-.4-.1-1.1.5-1.1h7.2c.5 0 .8.7.4 1.1z" stroke="none"></path>
                            </g>
                        </svg>
                    </div>
                    <div class="stepper__step-text"><?php echo $step[2]['label'];?></div>
                    <div class="stepper__step-date"><?php echo $step[2]['time'];?></div>
                </div>
                <div class="stepper__step <?php echo ($step[3]['finish']) ? 'stepper__step--finish' : '';?>">
                    <div class="stepper__step-icon <?php echo ($step[3]['finish']) ? 'stepper__step-icon--finish' : '';?>">
                        <svg enable-background="new 0 0 32 32" viewBox="0 0 32 32" x="0" y="0" class="shopee-svg-icon icon-order-shipping">
                            <g>
                                <line fill="none" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3" x1="18.1" x2="9.6" y1="20.5" y2="20.5"></line>
                                <circle cx="7.5" cy="23.5" fill="none" r="4" stroke-miterlimit="10" stroke-width="3"></circle>
                                <circle cx="20.5" cy="23.5" fill="none" r="4" stroke-miterlimit="10" stroke-width="3"></circle>
                                <line fill="none" stroke-miterlimit="10" stroke-width="3" x1="19.7" x2="30" y1="15.5" y2="15.5"></line>
                                <polyline fill="none" points="4.6 20.5 1.5 20.5 1.5 4.5 20.5 4.5 20.5 18.4" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"></polyline>
                                <polyline fill="none" points="20.5 9 29.5 9 30.5 22 24.7 22" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"></polyline>
                            </g>
                        </svg>
                    </div>
                    <div class="stepper__step-text"><?php echo $step[3]['label'];?></div>
                    <div class="stepper__step-date"><?php echo $step[3]['time'];?></div>
                </div>
                <div class="stepper__step <?php echo ($step[4]['finish']) ? 'stepper__step--finish' : '';?>">
                    <div class="stepper__step-icon <?php echo ($step[4]['finish']) ? 'stepper__step-icon--finish' : '';?>">
                        <svg enable-background="new 0 0 32 32" viewBox="0 0 32 32" x="0" y="0" class="shopee-svg-icon icon-order-rating">
                            <polygon fill="none" points="16 3.2 20.2 11.9 29.5 13 22.2 19 24.3 28.8 16 23.8 7.7 28.8 9.8 19 2.5 13 11.8 11.9" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"></polygon>
                        </svg>
                    </div>
                    <div class="stepper__step-text"><?php echo $step[4]['label'];?></div>
                    <div class="stepper__step-date"><?php echo $step[4]['time'];?></div>
                </div>
                <div class="stepper__line">
                    <div class="stepper__line-background" style="background: rgb(224, 224, 224);"></div>
                    <div class="stepper__line-foreground" style="width: calc((100% - 140px) * <?php echo $step_go;?>); background: rgb(45, 194, 88);"></div></div>
            </div>
        </div>
        <style>
            .stepper {
                position: relative;
                -webkit-box-pack: justify;
                -webkit-justify-content: space-between;
                -moz-box-pack: justify;
                -ms-flex-pack: justify;
                justify-content: space-between;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                -webkit-flex-wrap: nowrap;
                -ms-flex-wrap: nowrap;
                flex-wrap: nowrap;
                display: -webkit-box;
                display: -webkit-flex;
                display: -moz-box;
                display: -ms-flexbox;
                display: flex;
            }
            .stepper__step {
                width: 140px;
                text-align: center;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                cursor: default;
                z-index: 1;
            }
            .stepper__step-icon {
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                display: -webkit-box;
                display: -webkit-flex;
                display: -moz-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-orient: vertical;
                -webkit-box-direction: normal;
                -webkit-flex-direction: column;
                -moz-box-orient: vertical;
                -moz-box-direction: normal;
                -ms-flex-direction: column;
                flex-direction: column;
                -webkit-box-pack: center;
                -webkit-justify-content: center;
                -moz-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                -webkit-box-align: center;
                -webkit-align-items: center;
                -moz-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                position: relative;
                margin: auto;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                font-size: 1.875rem;
                border: 4px solid #e0e0e0;
                color: #e0e0e0;
                background-color: #fff;
                -webkit-transition: background-color .3s cubic-bezier(.4,0,.2,1) .7s,border-color .3s cubic-bezier(.4,0,.2,1) .7s,color .3s cubic-bezier(.4,0,.2,1) .7s;
                transition: background-color .3s cubic-bezier(.4,0,.2,1) .7s,border-color .3s cubic-bezier(.4,0,.2,1) .7s,color .3s cubic-bezier(.4,0,.2,1) .7s;
            }
            .stepper__step-icon--finish {
                border-color: #2dc258;
                color: #2dc258;
            }
            .shopee-svg-icon {
                display: inline-block;
                width: 1em;
                height: 1em;
                fill: currentColor;
                stroke: currentColor;
                position: relative;
                overflow: hidden;
            }
            .stepper__step-text {
                text-transform: capitalize;
                font-size: 12px;
                color: rgba(0,0,0,.8);
                line-height: 20px;
                margin: 1.25rem 0 .25rem;
            }
            .stepper__step-date {
                font-size: 10px;
                color: rgba(0,0,0,.26);
                height: .875rem;
            }
            .stepper__line {
                position: absolute;
                top: 29px;
                height: 4px;
                width: 100%;
            }
            .stepper__line-background, .stepper__line-foreground {
                position: absolute;
                width: -webkit-calc(100% - 140px);
                width: calc(100% - 140px);
                margin: 0 70px;
                height: 100%;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
        </style>
    </div>
    <div class="clearfix"></div>
    <br />
    <div class="_1AsWWl"></div>
    <style>
        ._1AsWWl {
            height: .1875rem;
            width: 100%;
            background-position-x: -1.875rem;
            background-size: 7.25rem .1875rem;
            background-image: repeating-linear-gradient(
                    45deg
                    ,#6fa6d6,#6fa6d6 33px,transparent 0,transparent 41px,#f18d9b 0,#f18d9b 74px,transparent 0,transparent 82px);
        }
    </style>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 order_cart__section"><header class="order__title"><div class="order__title_wrap"><h2>Địa Chỉ Nhận Hàng</h2></div></header></div>
            <div class="col-md-4 order_cart__section">
                <div class="order-box-info">
                    <?php if($order->other_delivery_address == 0) { ?>
                        <?php if(!empty($order->billing_ward)) $order->billing_address .= Cart_Location::ward($order->billing_ward, $order->billing_districts);
                        if(!empty($order->billing_districts)) $order->billing_address .= ', '.Cart_Location::districts($order->billing_city, $order->billing_districts);
                        if(!empty($order->billing_city)) $order->billing_address .= ', '.Cart_Location::cities($order->billing_city);
                        ?>
                        <p class="name"><span><?php echo $order->billing_fullname;?></span></p>
                        <p><span><?php echo $order->billing_phone;?></span></p>
                        <p><span><?php echo $order->billing_address;?></span></p>
                        <p><span><?php echo $order->billing_email;?></span></p>
                    <?php } else { ?>
                        <?php if(!empty($order->shipping_ward)) $order->shipping_address .= Cart_Location::ward($order->shipping_ward, $order->shipping_districts);
                        if(!empty($order->shipping_districts)) $order->shipping_address .= ', '.Cart_Location::districts($order->shipping_city, $order->shipping_districts);
                        if(!empty($order->shipping_city)) $order->shipping_address .= ', '.Cart_Location::cities($order->shipping_city);
                        ?>
                        <p class="name"><span><?php echo $order->shipping_fullname;?></span></p>
                        <p><span><?php echo $order->shipping_phone;?></span></p>
                        <p><span><?php echo $order->shipping_address;?></span></p>
                        <p><span><?php echo $order->shipping_email;?></span></p>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box order_cart__section" id="order_history">
                    <div class="box-content">
                        <div class="order_cart__section order-box-info">
                            <div class="timeline-container_new">
                                <div class="timeline-new__wrapper__content--body">
                                    <?php foreach ($histories as $key => $his) { ?>
                                        <div class="timeline-container_new--position">
                                            <div class="timeline-event-contentnew__icon"></div>
                                            <div class="timeline-item-new--border--padding">
                                                <div class="timeline-new__infomation">
                                                    <div>
                                                        <span class="timeline-new__infomation__name"></span>
                                                        <span class="timeline-new__infomation__time"><?php echo date('H:i d/m/Y', strtotime($his->created));?></span>
                                                        <span class="timeline-new__infomation__message"><?php echo base64_decode($his->message);?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <style>
                    .timeline-container_new {
                        border-radius: 3px;
                    }
                    .timeline-container_new .timeline-container_new--position {
                        position: relative;
                        margin-left: 30px;
                        word-break: break-all;
                    }
                    .timeline-container_new .timeline-container_new--position:before {
                        content: "";
                        width: 12px;
                        height: 12px;
                        border: 3px solid #ebeef0;
                        border-radius: 50%;
                        position: absolute;
                        top: 17px;
                        left: -3.9px;
                        background-color: #ebeef0;
                        z-index: 2;
                    }
                    .timeline-container_new .timeline-container_new--position .timeline-event-contentnew__icon {
                        position: absolute;
                        top: 21px;
                        left: -11px;
                        z-index: 2;
                    }
                    .timeline-container_new .timeline-item-new--border--padding {
                        border-left: 3px solid #ebeef0;
                        padding: 10px 0px 10px 0;
                    }
                    .timeline-container_new .timeline-container_new--position .timeline-new__infomation {
                        margin-left: 15px;
                    }
                    .timeline-container_new .timeline-container_new--position .timeline-new__infomation__name {
                        font-weight: 600;
                        font-size: 14px;
                        color: #212121;
                    }
                    .timeline-container_new .timeline-container_new--position .timeline-new__infomation__time {
                        color: #6c798f;
                        font-size: 14px;
                    }
                    .timeline-container_new .timeline-container_new--position .timeline-new__infomation__message {
                        font-size: 14px;
                        margin-left: 10px;
                    }
                </style>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box" id="order_items">
            <div class="box-content">
                <header class="order__title"><div class="order__title_wrap"><h2>Chi Tiết Đơn Hàng</h2></div></header>
                <div class="order-box-info order_cart__section">
                    <table class="order_items" style="width:100%">
                        <?php foreach ($order->items as $key => $val): ?>
                            <tr class="item">
                                <td><?php Template::img($val->image);?></td>
                                <td>
                                    <?php echo $val->title;?>
                                    <?php $val->option = (is_serialized($val->option))?@unserialize($val->option):$val->option ;?>
                                    <?php if(isset($val->option) && have_posts($val->option)) {
                                        $attributes = '';
                                        foreach ($val->option as $key => $attribute): $attributes .= $attribute.' / '; endforeach;
                                        $attributes = trim(trim($attributes), '/' );
                                        echo '<p class="variant-title" style="color:#999;">'.$attributes.'</p>';
                                    } ?>
                                </td>
                                <td><?= number_format($val->price);?><?php echo _price_currency();?> x <b><?= $val->quantity;?></b></td>
                                <td class="text-right"><?= number_format($val->price*$val->quantity);?><?php echo _price_currency();?></td>
                            </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
            <div class="box-content wc-order-data-row order-box-info">
                <table class="wc-order-totals">
                    <tbody>
                    <?php $totals = get_order_item_totals( $order ); ?>
                    <?php foreach ($totals as $total): ?>
                        <tr>
                            <td class="label"><?php echo (!empty($total['label'])) ? $total['label'] : '';?></td>
                            <td width="1%"></td>
                            <td class="total">
                                <span class="woocommerce-Price-amount amount"><?php echo $total['value'];?></span>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 box" id="order_note">
                <div class="box-content">
                    <header class="order__title">
                        <div class="order__title_wrap">
                            <h2>Ghi chú</h2>
                        </div>
                    </header>

                    <div class="order_cart__section">
                        <?php echo $order->order_note;?>
                    </div>
                </div>
            </div>
            <?php
            $payments = payment_gateways();
            if(have_posts($payments) && !empty($order->_payment) ) {?>
                <div class="box" id="order_payment">
                    <div class="box-content">
                        <header class="order__title">
                            <div class="order__title_wrap">
                                <h2>Hình thức thanh toán</h2>
                            </div>
                        </header>

                        <div class="order_cart__section">
                            <?php
                            foreach ($payments as $key => $payment) {
                                if($order->_payment == $key) {
                                    echo (!empty($payment['title'])) ? $payment['title'] : $key ;
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<style type="text/css">
	.order__title {
		display: block;
		padding: 20px 0px 10px 0;
	}
	.order__title h2 {
		font-size: 16px; font-weight: bold; line-height: 2.4rem; margin: 0;
		-webkit-box-flex: 1;
	    -webkit-flex: 1 1 auto;
	    -ms-flex: 1 1 auto;
	    flex: 1 1 auto;
	    min-width: 0;
    	max-width: 100%;
	}
    .order-header { margin-bottom: 10px; }
    .order-header h1 {
        font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
        font-weight: 600;
        margin-right: .8rem;
        overflow: hidden;
        overflow-wrap: break-word;
        word-wrap: break-word;
        white-space: normal;
        font-size: 30px;
        line-height: 3.4rem;
    }
    .order-header h1 span.order-time-created {
        font-size: 20px;
        color: #798c9c;
        font-weight: 400;
        padding-left: 0;
        -webkit-box-flex: 0;
        -webkit-flex: 0 1 auto;
        -ms-flex: 0 1 auto;
        flex: 0 1 auto;
        -webkit-align-self: flex-end;
        -ms-flex-item-align: end;
        align-self: flex-end;
        line-height: 2.5rem;
    }
    .order-box-info p.name {
        font-size: 14px; line-height: 20px; margin-bottom: 0; color: #000;
    }
    .order-box-info p {
        font-size: 12px; line-height: 15px; margin-bottom: 0; color: rgba(0, 0, 0, 0.54);
    }
	.order_cart__section h3 {
		font-size: 13px;
		font-weight: 600;
	    line-height: 1.6rem;
	    text-transform: uppercase;
	    margin-top: 0;
	}
    .order_cart__section .order_items .item img {
        height: 50px;
    }
    .order_cart__section .order_items .item td {
        vertical-align: middle;
    }

	#order_items .wc-order-data-row::after, #order_items .wc-order-data-row::before {
	    content: ' ';
	    display: table;
	}
	#order_items .wc-order-data-row  .wc-order-totals {
	    float: right;
	    width: 50%;
	    margin: 0;
	    padding: 0;
	    text-align: right;
	}
	#order_items .wc-order-data-row  .wc-order-totals .label{
	    color:#333;
	    font-size: 15px;
	}

	table.order_detail tr {
		border-bottom: 1px dotted #ccc;
	}
	table.order_detail td {
		padding:10px;
	}
	.loading, .success, .error, #order_cancel_alert {
		display: none;
	}
    html table tr td, body table tr td {
        border:0;
    }
    strong, b { font-weight: bold; }
</style>