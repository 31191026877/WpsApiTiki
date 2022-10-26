<?php $From = new FormBuilder();?>
<div class="page-cart-box">
	<h2 class="cart__title"><?php echo __('Thông Tin Thanh Toán', 'cart_thongtinthanhtoan');?></h2>
	<?php
        do_action('checkout_before_billing_form');
	?>
	<div class="row">
		<?php
            foreach ($fields['billing'] as $key => $field) {
                $From->add($field['field'], $field['type'], $field,  (!empty($field['value'])) ? $field['value'] : '');
            }
            $From = apply_filters('checkout_billing_form', $From, $fields);
            $From->html(false);
		?>
	</div>
	<?php
        do_action( 'checkout_after_billing_form');
    ?>
	<div class="checkbox">
		<label>
			<input type="checkbox" name="show-form-shipping">
			<span><?php echo __('Giao Hàng Tới Địa Chỉ Khác', 'cart_giaohangdiachikhac');?> ?</span>
		</label>
	</div>
</div>