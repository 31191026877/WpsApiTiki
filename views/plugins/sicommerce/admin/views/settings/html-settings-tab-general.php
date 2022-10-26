<?php
$productCurrency 		= Option::get('product_currency');
$productPriceContact 	= Option::get('product_price_contact');
$form = new FormBuilder();
$form
    ->add('product_supplier', 'switch', [
        'label' => 'Nhà sản xuất',
        'note' => 'Sử dụng nhà sản xuất',
        'after' => '<div class="col-md-3 form-group">', 'before' => '</div>'
    ], (int)Option::get('product_supplier'))
    ->add('product_brands', 'switch', [
        'label' => 'Thương hiệu',
        'note' => 'Sử dụng thương hiệu',
        'after' => '<div class="col-md-3 form-group">', 'before' => '</div>'
    ], (int)Option::get('product_brands'))
    ->add('cleart', 'html', '<div class="clearfix"></div>');

foreach (Language::listKey() as $langKey) {
    $form
        ->add('product_currency['.$langKey.'][unit]', 'text', [
            'label' => 'Đơn vị ('.$langKey.')',
            'after' => '<div class="col-md-3 form-group">', 'before' => '</div>'
        ], (isset($productCurrency[$langKey]['unit'])) ? $productCurrency[$langKey]['unit'] : 'đ')
        ->add('product_currency['.$langKey.'][position]', 'select', [
            'label' => 'Vị trí',
            'options' => ['before' => 'Trước', 'after' => 'Sau'],
            'after' => '<div class="col-md-3 form-group">', 'before' => '</div>'
        ], (isset($productCurrency[$langKey]['position'])) ? $productCurrency[$langKey]['position'] : 'after')
        ->add('cleart', 'html', '<div class="clearfix"></div>');
}

foreach (Language::listKey() as $langKey) {
    $form
        ->add('product_price_contact['.$langKey.']', 'text', [
            'label' => 'Giá liên hệ ('.$langKey.')', 'note' => 'Thay thế cho giá sản phẩm khi bằng 0',
            'after' => '<div class="col-md-6 form-group">', 'before' => '</div>'
        ], (isset($productPriceContact[$langKey])) ? $productPriceContact[$langKey] : 'Liên hệ')
        ->add('cleart', 'html', '<div class="clearfix"></div>');
}
?>
<div class="box">
	<div class="box-content" style="padding:20px;">
        <div class="row"><?php echo $form->html();?></div>
        <div class="clearfix"></div>
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$('#mainform').submit(function() {
			$('.loading').show();
			let data  = $(this).serializeJSON();
			data.action =  'Product_Admin_Setting_Ajax::save';
			$.post(ajax, data, function() {}, 'json').done(function(response) {
				$('.loading').hide();
	  			show_message(response.message, response.status);
			});
			return false;
		});
	});
</script>