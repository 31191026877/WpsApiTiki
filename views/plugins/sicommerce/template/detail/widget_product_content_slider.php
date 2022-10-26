<div class="arrow_box" id="widget_products_<?php echo $id;?>_btn">
	<div class="prev arrow"><i class="fal fa-chevron-left"></i></div>
	<div class="next arrow"><i class="fal fa-chevron-right"></i></div>
</div>
<div id="widget_products_<?php echo $id;?>_list" class="owl-carousel row">
	<?php foreach ($products as $key => $val): ?>
		<?php echo Prd::template('loop/item_product', array('val' =>$val));?>
	<?php endforeach ?>
</div>
<style>
    .product-slider-horizontal .slick-slide {
        margin-left: 10px;
        margin-right: 10px;
    }
</style>
<script defer>
	$(document).ready(function(){
	    let productRelated = $("#widget_products_<?php echo $id;?>_list");
	    let productRelatedPrev = $("#widget_products_<?php echo $id;?>_btn .prev");
	    let productRelatedNext = $("#widget_products_<?php echo $id;?>_btn .next");
        productRelated.slick({
            infinite: true,
            dots: false,
            autoplay: true,
            autoplaySpeed: 2000,
            speed: 700,
            slidesToShow: <?php echo $columns;?>,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1000,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });
        productRelatedNext.click(function() {
            productRelated.slick('slickNext');
            return false;
        });
        productRelatedPrev.click(function() {
            productRelated.slick('slickPrev');
            return false;
        });
	});
</script>