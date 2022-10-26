<div class="products-detail">
	<?php
	/**
	 * product_detail_before hook.
	 *
	 * @product_detail_before - 10 - Tạo breadcrumb
	 */
	do_action( 'product_detail_before', $object );
	?>
	<div class="row">
		<div class="col-12 col-xs-12 col-md-6" id="surround">
			<?php
                /**
                 * products_detail_slider hook.
                 * @hooked product_slider_vertical - 10 - Slider ảnh sản phẩm
                 */
                do_action('product_detail_slider', $object );
			?>
		</div>
		<div class="col-12 col-xs-12 col-md-6">
			<?php
                /**
                 * products_detail_info hook.
                 *
                 * @hooked product_detail_brand 		- 3 - hiển thị thương hiệu sản phẩm
                 * @hooked product_detail_title 		- 5 - hiển thị tên sản phẩm
                 * @hooked product_detail_price 		- 10 - hiển thị giá sản phẩm
                 * @hooked product_detail_description  - 20 - hiển thụ mô tả sản phẩm
                 * @hooked product_detail_social        - 30 - hiển thị chia sẻ sản phẩm
                 * @plugin sicommerce_cart - @hook product_add_cart - 40 - Hiển thị button add cart
                 */
                do_action( 'product_detail_info', $object );
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-12 col-xs-12 col-md-9">
			<?php
			/**
			 * product_detail_tabs hook.
			 * @hooked product_detail_display_tabs 		- 10 - Hiển thị tabs thông tin sản phẩm
			 * @hooked product_page_detail_related  - 20 - Hiển thị sản phẩm liên quan
			 */
			do_action( 'product_detail_tabs', $object );
			?> </div>
		<div class="col-12 col-xs-12 col-md-3 sidebar">
			<?php
			/**
			 * product_detail_sidebar hook.
			 */
			do_action( 'product_detail_sidebar', $object );
			?>
		</div>
	</div>
	<?php
	/**
	 * products_detail_after hook.
	 */
	do_action( 'product_detail_after', $object );
	?>
</div>