<div id="widget_products_<?php echo $id;?>" class="row">
	<?php foreach ($products as $key => $val): ?>
		<div class="col-12 col-xs-12 col-md-<?php echo ( $columns != 5) ? 12/$columns : 15;?>">
			<?php echo Prd::template('loop/item_product', array('val' =>$val));?>
		</div>
	<?php endforeach ?>
</div>