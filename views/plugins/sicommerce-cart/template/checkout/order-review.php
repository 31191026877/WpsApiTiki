<?php if( isset($cart) && have_posts($cart)) {?>
<div class="clearfix"></div>
<div class="cart-heading">
    <div class="cart-heading__button">
        <a class="btn btn-default btn-block" href="<?php echo get_url('gio-hang');?>"><?php echo __('QUAY LẠI', 'cart_tieptucmuahang');?></a>
		<button type="submit" value="order" class="btn btn-red btn-block" style="margin-left:0"><?php echo __('ĐẶT HÀNG', 'cart_dathang');?></button>
    </div>
</div>
<?php
	//ver 2.7
    do_action('checkout_review_order_after');
?>

<div class="page-cart-box" style="padding:10px">
	<table class="table checkout-review-order-table">
		<tr class="cart-subtotal">
			<th><?php echo __('Thành Tiền', 'cart_thanhtien');?></th>
			<td data-title="Thành tiền"><span id="cart-total-price"><?php echo number_format(Scart::total()); ?></span><?php echo _price_currency();?></td>
		</tr>
		<?php
			/**
			 * woocommerce_checkout_review_order
			 * 
			 * @hook woocommerce_checkout_review_shipping - 10 - Hiển thị thông tin shipping
			 */
            //ver 2.7
			do_action('checkout_review_order');
		?>
		<tr class="total">
			<td><?php echo __('Tổng Cộng', 'cart_tong');?></td>
			<td><strong id="total"><?php echo number_format(order_total());?>₫</strong></td>
		</tr>
	</table>
</div>

<?php
    //ver 2.7
	do_action('checkout_review_order_before');
?>

<div class="clearfix"></div>

<?php
	/**
	 * checkout_after_submit
	 *
	 * @hook woocommerce_checkout_shipping - 10
	 */
	do_action('checkout_after_submit', $cart );
?>
<?php } ?>
<div class="clearfix"></div>

<br />

