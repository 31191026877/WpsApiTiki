<div class="box">
	<div class="box-content">
			<header class="order__title">
				<div class="order__title_wrap">
					<h2>Khách hàng</h2>
				</div>
			</header>
			<div class="order_cart__section">
				<h3>Thanh Toán</h3>
				<div class="order-customer-info">
					<?php do_action('before_billing_customer');?>
					<?php $billing = Admin_Page_Order_Detail::billingInfo($order);?>
					<?php foreach ($billing as $billing_key => $billing_label) {?>
						<?php 
							if($billing_key == 'billing_city')      $billing_label = Cart_Location::cities($billing_label);
							if($billing_key == 'billing_districts') $billing_label = Cart_Location::districts($billing['billing_city'], $billing_label);
							if($billing_key == 'billing_ward')      $billing_label = Cart_Location::ward($billing['billing_ward'], $billing_label);
						?>
						<p class="<?php echo $billing_key;?>"><span><?php echo $billing_label;?></span></p>
					<?php } ?>
					<?php do_action('after_billing_customer');?>
				</div>

			</div>
			<div class="order_cart__section">
				<h3>Giao nhận</h3>
				<div class="order-customer-info">
					<?php do_action('before_shipping_customer'); ?>
					<?php if( isset($order->other_delivery_address) && $order->other_delivery_address == false) { echo '<p>Giống địa chỉ giao hàng.</p>'; } else { ?>
					<?php $shipping = Admin_Page_Order_Detail::shippingInfo($order);?>
					<?php foreach ($shipping as $shipping_key => $shipping_label) { ?>
						<?php 
							if($shipping_key == 'shipping_city') 		$shipping_label = Cart_Location::cities($shipping_label);
							if($shipping_key == 'shipping_districts') 	$shipping_label = Cart_Location::districts($shipping['shipping_city'], $shipping_label);
						?>
					<p class="<?php echo $shipping_key;?>"><span><?php echo $shipping_label;?></span></p>
					<?php } ?>
					<?php } ?>
					<?php do_action('after_shipping_customer');?>
				</div>

			</div>
	</div>
</div>

<style type="text/css">
	header.order__title {
		display: block;
		padding: 20px 20px 0;
	}
	header.order__title .order__title_wrap {
		display: -webkit-box;
	    display: -webkit-flex;
	    display: -ms-flexbox;
	    display: flex;
	    margin-top: -16px;
	    /*margin-left: -16px;*/
	}
	header.order__title h2 {
		font-size: 18px; font-weight: 600; line-height: 2.4rem; margin: 0;
		-webkit-box-flex: 1;
	    -webkit-flex: 1 1 auto;
	    -ms-flex: 1 1 auto;
	    flex: 1 1 auto;
	    min-width: 0;
    	max-width: 100%;
	}
	.order_cart__section {
		padding:20px;
	}
	.order_cart__section+.order_cart__section {
	    border-top: 1px solid #dfe4e8;
	}
	.order_cart__section h3 {
		font-size: 13px;
		font-weight: 600;
	    line-height: 1.6rem;
	    text-transform: uppercase;
	    margin-top: 0;
	}
	.order-customer-info {
		color: #637381;
		word-break: break-all;
	    word-wrap: break-word;
	    white-space: normal;
	}
</style>