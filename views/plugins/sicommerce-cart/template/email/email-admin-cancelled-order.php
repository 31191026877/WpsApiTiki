<?php
/**
 * Customer new order email
 * @version 1.8
 */
include_once 'include/email-header.php';
?>
<div style="overflow: hidden;background-color: #FFC1C1; padding:30px 10px;width: 100%;">
    <div style="width: 500px; margin: 0 auto;">
        <h2 style="color: #000; text-align: center;">XÁC NHẬN HỦY ĐƠN HÀNG</h2>
        <p style="color: #000; text-align: center;font-size: 15px;"><b><?php echo $order->billing_fullname;?></b>, Cám ơn bạn đã đặt hàng!</p>
        <p style="color: #000; text-align: center;font-size: 13px;">Chúng tôi rất tiếc khi đơn hàng của bạn đã bị hủy. Mọi chi thiết thắc mắc xin vui lòng liên hệ với chúng tôi</p>
    </div>
</div>
<div style="overflow: hidden;background-color: #fff; padding:30px 10px; width: 100%;">
    <h2 style="color: #000; text-align: center;"><?php echo $order->code;?></h2>
    <p style="color:#4C596B; text-align: center;"><strong>Ngày: <?php echo date('d-m-Y');?></strong></p>
</div>
<div style="font-family: &quot;Arial&quot;,Helvetica Neue,Helvetica,sans-serif; line-height: 18pt;background-color: #fff;padding:30px 10px; width: 100%;">
    <div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
            <tr>
                <th
                    style="border-left: 1px solid #d7d7d7; border-right: 1px solid #d7d7d7; border-top: 1px solid #d7d7d7; padding: 5px 10px; text-align: left;">
                    <strong>Thông tin người mua</strong>
                </th>
                <th
                    style="border-left: 1px solid #d7d7d7; border-right: 1px solid #d7d7d7; border-top: 1px solid #d7d7d7; padding: 5px 10px; text-align: left;">
                    <strong>Thông tin người nhận</strong>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="border-left: 1px solid #d7d7d7; border-right: 1px solid #d7d7d7; border-bottom: 1px solid #d7d7d7; padding: 5px 10px;">
                    <table style="width: 100%;">
                        <tbody>
                        <tr>
                            <td>Họ tên:</td>
                            <td><?php echo $order->billing_fullname;?></td>
                        </tr>
                        <tr>
                            <td>Điện thoại:</td>
                            <td><?php echo $order->billing_phone;?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo $order->billing_email;?></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ:</td>
                            <td><?php echo $order->billing_address;?></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td style="border-left: 1px solid #d7d7d7; border-right: 1px solid #d7d7d7; border-bottom: 1px solid #d7d7d7; padding: 5px 10px;">
                    <table style="width: 100%;">
                        <tbody>
                        <tr>
                            <td>Họ tên:</td>
                            <td><?php echo $order->shipping_fullname;?></td>
                        </tr>
                        <tr>
                            <td>Điện thoại:</td>
                            <td><?php echo $order->shipping_phone;?></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><?php echo $order->shipping_email;?></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ:</td>
                            <td><?php echo $order->shipping_address;?></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <p>Cám ơn Anh/chị đã đặt mua hàng tại <strong><?php echo Option::get('general_label');?></strong>.</p>
</div>
<?php
include_once 'include/email-footer.php';