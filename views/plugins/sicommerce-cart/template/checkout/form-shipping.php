<?php $From = new FormBuilder();?>
<div class="page-cart-box" id="checkout_shipping_form" style="display: none;">
	<h2 class="cart__title"><?php echo __('Thông Tin Giao Hàng', 'cart_thongtinthanhtoan');?></h2>
	<?php
        do_action('checkout_before_shipping_form');
    ?>
    <div class="row">
        <?php
        foreach ($fields['shipping'] as $key => $field) {
            $From->add($field['field'], $field['type'], $field,  (!empty($field['value'])) ? $field['value'] : '');
        }
        $From = apply_filters('checkout_shipping_form', $From, $fields);
        $From->html(false);
        ?>
    </div>
    <?php
        do_action('checkout_after_shipping_form');
    ?>
</div>
<script type="text/javascript">
	$(function(){
		$('input[name="show-form-shipping"]').click(function(){ $('#checkout_shipping_form').toggle(); })
	})
</script>