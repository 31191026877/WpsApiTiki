<?php
	do_action('cart_review_order_after');
?>
<table cellspacing="0" class="page-cart-box table shop_table_responsive">
	<tr class="cart-subtotal">
		<th><?php echo __('Thành Tiền', 'cart_thanhtien');?></th>
		<td data-title="Thành tiền"><span class="cart-total-price"><?php echo number_format(Scart::total()); ?></span><?php echo _price_currency();?></td>
	</tr>
	<?php
		do_action('cart_review_order');
	?>
	<tr class="cart-subtotal">
		<th><?php echo __('Tổng Tiền', 'cart_tong');?></th>
		<td data-title="Thành tiền"><span class="summary-cart-total-price"><?php echo number_format(order_total()); ?><span><?php echo _price_currency();?></td>
	</tr>
</table>
<?php
	do_action('cart_review_order_before');
?>