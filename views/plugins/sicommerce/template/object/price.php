<div class="item-pr-price">
 	<?php if(!empty($val->price_sale)) { ?>
    	<span class="product-item-price"><?php echo Prd::price($val->price_sale);?></span>
    	<del class="product-item-price-old"><?php echo Prd::price($val->price);?></del>
  	<?php } else if($val->price == 0) { ?>
    	<span class="product-item-price"><?php echo Prd::priceNone();?></span>
  	<?php } else {?>
    	<span class="product-item-price"><?= Prd::price($val->price);?></span>
  	<?php } ?>
</div>