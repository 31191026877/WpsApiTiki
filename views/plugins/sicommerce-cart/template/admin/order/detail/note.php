<?php
$note = Order::getMeta($order->id, 'order_note', true);
if(!empty($note)) { ?>
<div class="box" id="order_note">
	<div class="box-content">
		<header class="order__title">
			<div class="order__title_wrap">
				<h2>Ghi chú</h2>
			</div>
		</header>

		<div class="order_cart__section">
			<?php echo Order::getMeta($order->id, 'order_note', true);?>
		</div>
	</div>
</div>
<?php } ?>

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