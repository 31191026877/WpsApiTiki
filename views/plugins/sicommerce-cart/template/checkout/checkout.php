<?php
$cart 	= Scart::getItems();

$fields = get_checkout_fields();

do_action('checkout_before', $cart);

if(have_posts($cart)) {
?>
<div class="">
	<form name="checkout" class="page-checkout page-cart-content" method="post">

		<?php echo form_open();?>

		<div class="row">

			<div class="col-md-8">
				<div class="page-cart-left">
					<?php do_action('checkout_content', $cart);?>
				</div>
				<div class="page-cart-center">
					<div class="wcm-box-more">
						<?php echo cart_template('checkout/order-more');?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="page-cart-right">
					<div class="page-checkout-review">
						<?php echo cart_template('checkout/order-review');?>
					</div>
				</div>
			</div>

		</div>
	</form>
</div>
<?php } else { echo cart_template('empty'); } ?>

<style>
	.object-detail { border:0; background-color: transparent; overflow: inherit; margin-top: 100px; }
	.box-bg-top { display: none; }
	.table-striped > tbody > tr:nth-of-type(odd) { background-color: transparent; }
	body .wrapper {
        margin-top:10px!important;
        padding-top:10px;
		min-height:100vh;
		background-color:#F0F2F5!important;
	}
	h1, header, footer, .btn-breadcrumb { display:none; }
    .page-cart-content .form-control {
        padding:0 10px;
    }
</style>