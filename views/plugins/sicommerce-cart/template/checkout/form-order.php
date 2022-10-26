<div class="page-cart-box">
	<div class="row">
	<?php foreach ($fields['order'] as $key => $field): ?>
		<?php echo _form($field, ( !empty($field['value']) ) ? $field['value'] : '');?>
	<?php endforeach ?>
	</div>
</div>