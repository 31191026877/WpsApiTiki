<div class="price-detail">
	<?php if($object->price == 0 && $object->price_sale == 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo Prd::priceNone();?></span></p>
	<?php } ?>

	<?php if($object->price != 0 && $object->price_sale != 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo Prd::price($object->price_sale);?></span></p>
	<p class="price-sale">
        <del id="product-detail-price-sale">
            <?php echo Prd::price($object->price);?>
        </del>
        <span>Giảm <?php echo ceil(($object->price - $object->price_sale)*100/$object->price);?>%</span>
    </p>
	<?php } ?>

	<?php if($object->price != 0 && $object->price_sale == 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo Prd::price($object->price);?></span></p>
	<?php } ?>
</div>