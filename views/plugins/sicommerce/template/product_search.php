<?php
	$category_row_count        = Option::get('category_row_count');
	$category_row_count_tablet = Option::get('category_row_count_tablet');
	$category_row_count_mobile = Option::get('category_row_count_mobile');

	$lg = ($category_row_count == 5) ? 15 : 12/$category_row_count;
	$sm = ($category_row_count_tablet == 5) ? 15 : 12/$category_row_count_tablet;
	$xs = ($category_row_count_mobile == 5) ? 15 : 12/$category_row_count_mobile;

	$col = 'col-'.$xs.' col-xs-'.$xs.' col-sm-'.$sm.' col-md-'.$lg.' col-lg-'.$lg;
?>

<div class="product-slider-horizontal" style="margin-top: 10px;">
	<div class="list-item-product row">
	<?php foreach ($objects as $key => $val): ?>
		<div class="<?php echo $col;?>">
		<?php echo Prd::template('loop/item_product', array('val' =>$val));?>
		</div>
	<?php endforeach ?>
	</div>
</div>