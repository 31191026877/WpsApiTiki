<div class="box">
    <div class="header"> <h2>GALLERY</h2> </div>
    <div class="box-content">
        <div class="row m-1">
            <div class="col-md-6">
                <label class="product-gallery" for="product_gallery_horizontal">
                    <?php Template::img(Url::base().PRODUCT_PATH.'assets/images/product-gallery.svg');?>
                    <input type="radio" name="product_gallery" id="product_gallery_horizontal" value="product_gallery_horizontal" <?php echo ($product_gallery == 'product_gallery_horizontal')?'checked':'';?>>
                </label>
                <label class="product-gallery" for="product_gallery_vertical">
                    <?php Template::img(Url::base().PRODUCT_PATH.'assets/images/product-gallery-vertical.svg');?>
                    <input type="radio" name="product_gallery" id="product_gallery_vertical" value="product_gallery_vertical" <?php echo ($product_gallery == 'product_gallery_vertical')?'checked':'';?>>
                </label>
                <style type="text/css">
                    .product-gallery { padding:10px; }
                </style>

            </div>
        </div>
    </div>
</div>