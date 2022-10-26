<?php
do_action('cart_email_before_order_table', $order ); ?>
<div style="overflow: hidden;background-color: #fff; padding:10px; width: 100%;">
	<table style="width: 100%;">
		<tr style="background-color: #F7F7F7;">
			<th style="text-align: left; padding:15px 10px; border:0;">Tên sản phẩm</th>
			<th style="text-align: left; padding:15px 10px; border:0;">Số lượng</th>
			<th style="text-align: left; padding:15px 10px; border:0;">Đơn giá</th>
			<th style="text-align: left; padding:15px 10px; border:0;">Thành tiền</th>
		</tr>
		<?php
			include 'email-order-items.php';
		?>
	</table>
</div>
<?php $totals = get_order_item_totals( $order ); ?>

<?php foreach ($totals as $total): ?>
<div style="overflow: hidden;background-color: #fff; padding:5px 10px; width: 100%;">
	<div style="float: left; width: 60%; min-height: 5px;"> </div>
	<div style="float: left; width: 20%;"><?php echo $total['label'];?></div>
	<div style="float: left; width: 20%; text-align: right"><?php echo $total['value'];?></div>
</div>
<?php endforeach ?>
<?php do_action('cart_email_after_order_table', $order ); ?>