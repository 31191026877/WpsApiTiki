<?php
    $gallerys = Gallery::getsItem(['object_id' => $object->id, 'object_type' => 'products']);
    $col_gallery = (have_posts($gallerys)) ? 'col-md-push-2' : '';
    $col_gallery_number = (have_posts($gallerys)) ? 10 : 12;
?>
<div class="row">
	<div class="<?php echo $col_gallery ?> col-lg-<?php echo $col_gallery_number; ?> col-md-<?php echo $col_gallery_number; ?> col-sm-<?php echo $col_gallery_number;?> col-xs-12 box-image-featured">
		<a href="<?php echo Template::imgLink($object->image, $image_type_source);?>" data-fancybox="product-thumb">
			<?php Template::img($object->image, '', ['class' => 'product-image-feature', 'type' => $image_type_source]);?>
		</a>
	</div>
    <?php if(have_posts($gallerys)) {?>
	<div class="col-md-pull-10 col-lg-2 col-md-2 col-sm-2 col-xs-12 product-box-slide">
		<div class="product-thumb-vertical" id="sliderproduct">
            <ul id="list-product-thumb" class="owl-carousel">
                <li class="product-thumb">
                    <a href="<?php echo Template::imgLink($object->image,$image_type_source);?>" data-fancybox='product-thumb' data-image="<?php echo Template::imgLink($object->image, $image_type_source);?>" class="zoomGalleryActive">
                        <?php Template::img($object->image, '', ['type' => $image_type_medium]);?>
                    </a>
                </li>
                <?php foreach ($gallerys as $key => $image): ?>
                <?php if($image->type == 'youtube') {?>
                    <li class="product-thumb">
                        <a href="<?php echo $image->value;?>" data-fancybox data-thumb-type="<?php echo $image->type;?>" data-id="<?php echo Url::getYoutubeID($image->value);?>">
                            <?php Template::img($image->value, '', ['type' => $image_type_medium]);?>
                            <svg class="shopee-svg-icon _3KhUdn " enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0"><g opacity=".54"><g><circle cx="7.5" cy="7.5" fill="#040000" r="7.3"></circle><path d="m7.5.5c3.9 0 7 3.1 7 7s-3.1 7-7 7-7-3.1-7-7 3.1-7 7-7m0-.5c-4.1 0-7.5 3.4-7.5 7.5s3.4 7.5 7.5 7.5 7.5-3.4 7.5-7.5-3.4-7.5-7.5-7.5z" fill="#ffffff"></path></g></g><path d="m6.1 5.1c0-.2.1-.3.3-.2l3.3 2.3c.2.1.2.3 0 .4l-3.3 2.4c-.2.1-.3.1-.3-.2z" fill="#ffffff"></path></svg>
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="product-thumb">
                        <a href="<?php echo Template::imgLink($image->value, $image_type_source);?>" data-thumb-type="<?php echo $image->type;?>" data-fancybox='product-thumb' data-image="<?php echo Template::imgLink($image->value, $image_type_source);?>">
                            <?php Template::img($image->value, '', ['type' => $image_type_medium]);?>
                        </a>
                    </li>
                <?php } ?>
                <?php endforeach ?>
            </ul>
		</div>
	</div>
    <?php } ?>
</div>

<div class="clearfix"></div>

<script type="text/javascript" defer>
	$(function() {

        let productThumbOption = {
            infinite: true,
            vertical: true,
            arrows: true,
            prevArrow:'<button type="button" class="slick-prev"><i class="fal fa-angle-up"></i></button>',
            nextArrow:'<button type="button" class="slick-next"><i class="fal fa-angle-down"></i></button>',
            autoplay: false,
            slidesToShow: 5,
            slidesToScroll: 1,
            initialSlide: 0,
            speed: 1000,
            autoplaySpeed: 200,
            pauseOnHover: 0,
            dots: false,
            centerMode: false,
            focusOnSelect: true,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: { slidesToShow: 3, slidesToScroll: 1, },
                },
                {
                    breakpoint: 992,
                    settings: { slidesToShow: 3, slidesToScroll: 1, },
                },
                {
                    breakpoint: 768,
                    settings: { slidesToShow: 3, slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 600,
                    settings: {
                        infinite: true,
                        vertical: false,
                        prevArrow:'<button type="button" class="slick-prev"><i class="fal fa-angle-left"></i></button>',
                        nextArrow:'<button type="button" class="slick-next"><i class="fal fa-angle-right"></i></button>',
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 320,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        };

        let productThumb = $("#list-product-thumb");

		let zoomConfig 	 = { responsive: true, scrollZoom : true };

		let zoomImageBox = $('.box-image-featured');

		let zoomImage    = $(".product-image-feature");

        productThumb.slick(productThumbOption);

		$(document).on('mouseenter', '#list-product-thumb .product-thumb a', function() {
            let type = $(this).attr('data-thumb-type');
            $('#list-product-thumb .product-thumb a').removeClass('zoomGalleryActive');
            $('.zoomContainer').remove();
            zoomImage.removeData('elevateZoom');
            if(type === 'youtube') {
                zoomImageBox.html('<iframe style="width:100%; height: 400px" src="https://www.youtube.com/embed/'+$(this).attr('data-id')+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>')
            }
            else {
                zoomImageBox.html('<a href="'+$(this).attr('data-image')+'" data-fancybox="product-thumb">' + '<img src="'+$(this).attr('data-image')+'" class="product-image-feature" loading="lazy">' + '</a>');
                zoomImage = $(".product-image-feature");
                if($(window).width() > 740) {
                    zoomImage.elevateZoom(zoomConfig);
                }
            }
            $(this).addClass('zoomGalleryActive');
        });
	});
</script>