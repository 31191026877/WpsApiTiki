<?php if(have_posts($variation)) {?>
	<?php if($variation->price == 0 && $variation->price_sale == 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo _price_none();?></span></p>
	<?php } ?>

	<?php if($variation->price != 0 && $variation->price_sale != 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo number_format($variation->price_sale);?><?php echo _price_currency();?></span></p>
	<p class="price-sale"><del id="product-detail-price-sale"><?php echo number_format($variation->price);?><?php echo _price_currency();?></del></p>
	<?php } ?>

	<?php if($variation->price != 0 && $variation->price_sale == 0) { ?>
	<p class="price"><span id="product-detail-price"><?php echo number_format($variation->price);?><?php echo _price_currency();?></span></p>
	<?php } ?>
<?php } ?>