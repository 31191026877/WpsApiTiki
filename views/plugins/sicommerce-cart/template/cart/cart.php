<?php
$cart = Scart::getItems();
$productsId = [];
foreach ($cart as $item) {
    $temp = Product::getMeta($item['option']['product_id'], 'product_related', true);
    if(have_posts($temp)) $productsId = array_merge($productsId, $temp);
}
$products = [];

if(isset($_SESSION['viewed_product']) && have_posts($_SESSION['viewed_product'])) {
    $productsId = array_merge($productsId, $_SESSION['viewed_product']);
}

if(have_posts($productsId)) {
    $products = Product::gets(Qr::set()->whereIn('id', $productsId)->limit(10));
}
else {
    $products = Product::gets(Qr::set()->limit(10)->orderByRaw('rand()'));
}
?>
<div class="page-cart page-cart-content">
	<?php do_action( 'cart_before', $cart); ?>
	<?php if(have_posts($cart)) { ?>
	<div class="row">
		<div class="col-md-8">
            <?php echo Admin::loading();?>
            <div class="cart-error"></div>
			<div class="page-cart-box">
				<?php do_action('cart_before_table', $cart); ?>
				<form method="post" class="" id="page_cart_form">
					<?php echo form_open();?>
					<div class="page-cart-tbody">
						<?php
                            do_action('cart_before_contents', $cart);
                            foreach ($cart as $item) { $item = (object)$item;
                                echo cart_template('cart/cart-items', array('item' => $item));
                            }
                            do_action('cart_after_contents', $cart);
						?>
					</div>
				</form>
				<?php do_action('cart_after_table', $cart); ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="cart-collaterals page-cart-right">
				<?php echo cart_template('cart/cart-total');?>
			</div>
		</div>
        <div class="col-md-12">
            <?php if(have_posts($products)) {?>
            <div class="box product-slider-horizontal">
                <div class="title-header"><h3 class="header"><?php echo __('Có thể bạn quan tâm', 'page_cart_title_interested');?></h3></div>
                <div class="box-content">
                    <div id="cart_products_related">
                        <div class="arrow_box">
                            <div class="prev arrow"><i class="fal fa-chevron-left"></i></div>
                            <div class="next arrow"><i class="fal fa-chevron-right"></i></div>
                        </div>
                        <div class="swiper">
                            <div class="swiper-wrapper list-product">
                                <?php foreach ($products as $key => $val) {
                                    echo '<div class="swiper-slide">';
                                    echo scmc_template('loop/item_product', array('val' =>$val));
                                    echo '</div>';
                                } ?>
                            </div>
                        </div>

                    </div>
                    <script defer>
                        $(document).ready(function(){
                            let productList     = '#cart_products_related .swiper';
                            let productBtnNext  = $('#cart_products_related .next');
                            let productBtnPrev  = $('#cart_products_related .prev');
                            function shouldBeEnabled(carousel, numberShow) {
                                const slidesCount = carousel.find('.swiper-slide').length;
                                if (slidesCount < numberShow) {
                                    return {loop: false, };
                                }
                                return {loop: true,};
                            }
                            let config = {
                                ...shouldBeEnabled($(productList), 5),
                                autoplay: {
                                    delay: 2000
                                },
                                speed:500,
                                slidesPerView: 5,
                                spaceBetween: parseInt(getComputedStyle(document.body).getPropertyValue('--bs-gutter-x')),
                                breakpoints : {
                                    0: {
                                        ...shouldBeEnabled($(productList), 2),
                                        slidesPerView: 2
                                    },
                                    768: {
                                        ...shouldBeEnabled($(productList), 3),
                                        slidesPerView: 3
                                    },
                                    1200: {
                                        ...shouldBeEnabled($(productList), 5),
                                        slidesPerView: 5
                                    },
                                },
                            }
                            let swiper = new Swiper(productList, config);
                            productBtnNext.click(function () { swiper.slideNext(); });
                            productBtnPrev.click(function () { swiper.slidePrev(); })
                        });
                    </script>
                </div>
            </div>
            <?php } ?>
        </div>
	</div>
	<div class="clearfix"></div>
	<?php } else {
	    echo cart_template('empty');
	}
	do_action('cart_after', $cart);
    ?>
</div>
<style>
    h1 {
        display: none;
    }
</style>