<div class="item">
	<div class="img">
		<a href="<?= Url::permalink($val->slug);?>">
			<?php Template::img($val->image);?>
		</a>
	</div>
	<div class="info">
		<h3><a href="<?= Url::permalink($val->slug);?>" title="<?= $val->title;?>"><?= $val->title;?></a></h3>
		<p class="price view_price">
			<?php if(!empty($val->price_sale)) { ?>
				<span class="price-new"><?= Prd::price($val->price_sale);?></span>
				<del class="price-old"><?= Prd::price($val->price);?></del>
	      	<?php } else if($val->price == 0) { ?>
	        	<span class="price-new"><?php echo Prd::priceNone();?></span>
	      	<?php } else {?>
	        	<span class="price-new"><?= Prd::price($val->price);?></span>
	      	<?php } ?>
		</p>
	</div>
</div>