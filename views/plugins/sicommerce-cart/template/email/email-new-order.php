<?php
/**
 * Email New Order
 * @version 1.8
 */
?>
<?php include 'include/email-header.php';?>
<div style="overflow: hidden;background-color: #EBF8F3; padding:30px 10px;width: 100%;">
    <div style="width: 500px; margin: 0 auto;">
        <h2 style="color: #000; text-align: center;">ĐẶT HÀNG THÀNH CÔNG</h2>
        <p style="color: #000; text-align: center;font-size: 15px;"><b><?php echo $order->billing_fullname;?></b>, Cám ơn bạn đã đặt hàng!</p>
        <p style="color: #000; text-align: center;font-size: 13px;">Chúng tôi đã nhận được đơn hàng của bạn và sẽ liên hệ với bạn trong thời gian sớm nhất. Mọi chi thiết thắc mắc xin vui lòng liên với chúng tôi</p>
    </div>
</div>
<div style="overflow: hidden;background-color: #fff; padding:30px 10px; width: 100%;">
    <h2 style="color: #000; text-align: center;"><?php echo $order->code;?></h2>
    <p style="color:#4C596B; text-align: center;"><strong>Ngày: <?php echo date('d-m-Y');?></strong></p>
</div>
<?php include 'include/email-address.php';?>
<?php include 'include/email-order-details.php';?>
<?php include 'include/email-footer.php';?>
