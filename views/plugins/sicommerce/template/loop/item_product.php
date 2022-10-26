<div class="item product-item <?php echo 'box-shadow-'.option::get('product_shadow', 0);?> <?php echo 'box-shadow-'.option::get('product_shadow_hover', 0).'-hover';?>">
	<?php
		/**
		 * hook product_object_info_after
		 */
		do_action( 'product_item_before', $val );
	?>
	<div class="product">
		<?php
			/**
			 * hook product_object_before_image
			 */
			do_action( 'product_object_before_image', $val );
		?>
		<a href="<?= get_url($val->slug);?>">
			<?php
				/**
				 * hook product_object_image
				 * @hook product_object_image - 10
				 */
				do_action( 'product_object_image', $val );
			?>
	    </a>
	    <?php
		    /**
			 * hook product_object_before_image
			 */
		    do_action( 'product_object_after_image', $val );
		?>
	</div>
	<div class="title">
		<?php
			/**
			 * hook product_object_info
			 * @hook product_object_title - 10
			 * @hook product_object_price - 20
			 * @hook product_object_description - 30
			 */
			do_action( 'product_object_info', $val );
		?>
	</div>
	<?php
		/**
		 * hook product_object_info_after
		 */
		do_action( 'product_item_after', $val );
	?>
</div>