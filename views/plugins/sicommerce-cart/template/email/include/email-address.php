<div style="overflow: hidden;background-color: #fff; padding:10px 10px; width: 100%;">
	<div style="float: left; width: 40%">
		<h4 style="margin: 10px 0;">Địa chỉ thanh toán</h4>
		<p style="font-size: 13px;color:#616060!important; margin: 0px;"><?php echo $order->billing_fullname;?></p>
		<p style="font-size: 13px;color:#616060!important; margin: 0px;"><?php echo $order->billing_address;?></p>
		<p style="font-size: 13px;color:#616060!important; margin: 0px;"><?php echo $order->billing_phone;?></p>
		<p style="font-size: 13px;color:#616060!important; margin: 0px;"><?php echo $order->billing_email;?></p>
	</div>
	<div style="float: left; width: 20%; min-height: 50px;"></div>
	<div style="float: left; width: 40%; text-align: right">
		<h4 style="margin: 10px 0;">Địa chỉ nhận hàng</h4>
		<p style="font-size: 13px;color:#616060!important; margin: 0px;"><?php echo $order->shipping_fullname;?></p>
		<p style="font-size: 13px;color:#616060!important; margin: 0px;"><?php echo $order->shipping_address;?></p>
		<p style="font-size: 13px;color:#616060!important; margin: 0px;"><?php echo $order->shipping_phone;?></p>
		<p style="font-size: 13px;color:#616060!important; margin: 0px;"><?php echo $order->shipping_email;?></p>
	</div>
</div>