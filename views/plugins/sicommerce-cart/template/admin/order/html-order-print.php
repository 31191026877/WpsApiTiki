<table style="width:100%;">
    <tr>
        <td class="logo" style="width:20%;">
            <img src="<?php echo Url::base().SOURCE.Option::get('logo_header');?>" height="50">
        </td>
        <td class="info-company" style="width:80%;padding-left: 20px; text-align:right">
            <p style="text-align:right; line-height: 10px"><b><?php echo option::get('general_label');?></b></p>
            <p style="text-align:right; line-height: 10px; font-size:12px"><?php echo option::get('contact_address');?></p>
            <p style="text-align:right; line-height: 10px; font-size:12px">Hotline:<?php echo option::get('contact_phone');?></p>
        </td>
    </tr>
</table>
<p style="line-height: 10px; font-size:14px;"><strong>Đơn hàng số</strong> <span>#<?php echo $order->code;?></span></p>
<p style="line-height: 10px; font-size:14px;"><strong>Ngày đặt</strong> <span><?php echo date('d-m-Y', strtotime($order->created));?></span></p>
<?php
$payments = payment_gateways();
if( have_posts($payments) && !empty($order->_payment) ) { ?>
    <p style="line-height: 10px; font-size:14px;"><strong>Thanh toán</strong> <span><?php foreach ($payments as $key => $payment) { if($order->_payment == $key) { echo ( !empty($payment['woocommerce_'.$key.'_title']) ) ? $payment['woocommerce_'.$key.'_title'] : $key ; } } ?></span></p>
<?php } ?>
<br />
<table class="customer-info" style="width:100%">
    <tr>
        <td style="width:50%">
            <h4 style="margin-bottom:10px;"><b>Đặt hàng</b></h4>
            <?php $billing = Admin_Page_Order_Detail::billingInfo($order);?>
            <?php
            if(!empty($billing['billing_ward'])) $billing['billing_address'] .= Cart_Location::ward($billing['billing_ward'], $billing['billing_districts']);
            if(!empty($billing['billing_districts'])) $billing['billing_address'] .= ', '.Cart_Location::districts($billing['billing_city'], $billing['billing_districts']);
            if(!empty($billing['billing_city'])) $billing['billing_address'] .= ', '.Cart_Location::cities($billing['billing_city']);
            ?>
            <p style="line-height: 10px; font-size:12px"><span><?php echo $billing['billing_fullname'];?>   <?php echo $billing['billing_phone'];?></span></p>
            <p style="line-height: 10px; font-size:12px"><span><?php echo $billing['billing_email'];?></span></p>
            <p style="line-height: 10px; font-size:12px"><span><?php echo $billing['billing_address'];?></span></p>
        </td>
        <td style="width:50%">
            <h4 style="margin-bottom:10px;"><b>Nhận hàng</b></h4>
            <?php $shipping = Admin_Page_Order_Detail::shippingInfo($order);?>
            <?php
            if(!empty($shipping['shipping_ward'])) $shipping['shipping_address'] .= Cart_Location::ward($shipping['shipping_ward'], $shipping['shipping_districts']);
            if(!empty($shipping['shipping_districts'])) $shipping['shipping_address'] .= ', '.Cart_Location::districts($shipping['shipping_city'], $shipping['shipping_districts']);
            if(!empty($shipping['shipping_city'])) $shipping['shipping_address'] .= ', '.Cart_Location::cities($shipping['shipping_city']);
            ?>
            <p style="line-height: 10px; font-size:12px"><span><?php echo $shipping['shipping_fullname'];?> - <?php echo $shipping['shipping_phone'];?></span></p>
            <p style="line-height: 10px; font-size:12px"><span><?php echo $shipping['shipping_email'];?></span></p>
            <p style="line-height: 10px; font-size:12px"><span><?php echo $shipping['shipping_address'];?></span></p>
        </td>
    </tr>
</table>
<br />
<br />
<table class="order_items" style="width:100%;">
    <tr class="item" style="background-color:#A3A3A3;">
        <td style="padding:5px;background-color:#A3A3A3;">Thông tin</td>
        <td style="padding:5px;background-color:#A3A3A3;">Đơn giá VND</td>
        <td style="padding:5px;background-color:#A3A3A3;">Số lượng</td>
        <td style="padding:5px;background-color:#A3A3A3;">Thành tiền</td>
    </tr>
    <?php foreach ($order->items as $key => $val): ?>
        <tr class="item">
            <td style="width:50%;padding:5px;border:1px solid #ccc">
                <?= $val->title;?>
                <?php $val->option = @unserialize($val->option) ;?>
                <?php if(isset($val->option) && have_posts($val->option)) {
                    $attributes = '';
                    foreach ($val->option as $key => $attribute): $attributes .= $attribute.' / '; endforeach;
                    $attributes = trim( trim($attributes), '/' );
                    echo '<span class="variant-title" style="color:#999;">'.$attributes.'</span>';
                } ?>
            </td>
            <td style="width:20%;padding:5px;border:1px solid #ccc"><?= number_format($val->price);?> đ</td>
            <td style="width:10%;padding:5px;border:1px solid #ccc"><?= $val->quantity;?></td>
            <td style="width:20%;padding:5px;border:1px solid #ccc"><?= number_format($val->price*$val->quantity);?> đ</td>
        </tr>
    <?php endforeach ?>
</table>
<br />
<table class="wc-order-totals" style="width:100%">
    <tbody>
        <?php $totals = get_order_item_totals( $order ); ?>
        <?php foreach ($totals as $total): ?>
        <tr style="padding-bottom:10px">
            <td style="width:50%;padding:5px;"></td>
            <td style="width:5%;padding:5px;"></td>
            <td style="width:25%;padding:5px; text-align:right; font-size:13px;" class="label"><b><?php echo $total['label'];?></b></td>
            <td style="width:20%;padding:5px; text-align:right" class="total"> <b><?php echo $total['value'];?></b></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>
<div class="break-page-print"></div>
<style>
    @media print {
        body{
            -webkit-print-color-adjust:exact;
            font-family: "Times New Roman", Courier, monospace;
        }
        img{
            display: inline;
            visibility: visible;
            -webkit-print-color-adjust: exact;
            height: 60px; width: auto;
        }
        table tr td {
            font-family: "Times New Roman", Courier, monospace;
        }
        .customer-info { width:100%;}
        /*.order_items tr { border:1px solid #ccc }*/
        .order_items tr td { font-size:13px; }
        .break-page-print {page-break-after: always;}
    }
</style>
