<?php
include 'include/template-breadcrumb.php';
include 'include/template-index.php';
include 'include/template-detail.php';
include 'include/template-search.php';
include 'include/template-object.php';
if (!function_exists('productAssets')) {
    function productAssets() {
        Template::asset()->location('header')->add('product-detail', PRODUCT_PATH.'assets/css/scmc-style.css', ['minify' => true, 'path'  => ['image' => PRODUCT_PATH.'assets/images']]);
        Template::asset()->location('footer')->add('product-detail', PRODUCT_PATH.'assets/js/scmc-script.js', ['minify' => true]);
        Template::asset()->location('footer')->add('elevatezoom', PRODUCT_PATH.'assets/add-on/elevatezoom-master/jquery.elevateZoom-3.0.8.min.js', ['page' => ['products_detail']]);
    }
    add_action('init','productAssets', 30);
}
if (!function_exists('productAssetsValidate')) {
    function productAssetsValidate() {
        $border = productItemStyle('border');
        $shadow = productItemStyle('shadow');
        $shadowHover = productItemStyle('shadowHover');
        if($shadowHover['style'] == 'none') $shadowHover = $shadow;

        $img = productItemStyle('img');

        $title = productItemStyle('title');
        $title['desktop']['show'] = ($title['desktop']['show'] == 1) ? 'block' : 'none';
        $title['desktop']['font'] = ($title['desktop']['font'] == 0) ? 'var(--font-header)' : $title['desktop']['font'];

        $price = productItemStyle('price');
        $price['desktop']['show'] = ($price['desktop']['show'] == 1) ? 'block' : 'none';
        $price['desktop']['font'] = ($price['desktop']['font'] == 0) ? 'var(--font-header)' : $price['desktop']['font'];
        ?>
        <style>
            :root {
                --prItem-bd-style: <?php echo $border['style'];?>;
                --prItem-bd-width: <?php echo $border['width'];?>px;
                --prItem-bd-color: <?php echo $border['color'];?>;
                --prItem-bd-radius: <?php echo $border['radius'];?>px;
                --prItem-box-shadow:<?php echo $shadow['horizontal'];?>px <?php echo $shadow['vertical'];?>px <?php echo $shadow['blur'];?>px <?php echo $shadow['spread'];?>px <?php echo $shadow['color'];?>;
                --prItem-box-shadow-hover:<?php echo $shadowHover['horizontal'];?>px <?php echo $shadowHover['vertical'];?>px <?php echo $shadowHover['blur'];?>px <?php echo $shadowHover['spread'];?>px <?php echo $shadowHover['color'];?>;

                --prItem-img-ration:<?php echo 100*$img['ratio_h']/$img['ratio_w'];?>%;
                --prItem-img-style:<?php echo $img['style'];?>;


                --prItem-title-display:<?php echo $title['desktop']['show'];?>;
                --prItem-title-align:<?php echo $title['desktop']['align'];?>;
                --prItem-title-font:<?php echo $title['desktop']['font'];?>;
                --prItem-title-weight:<?php echo $title['desktop']['weight'];?>;
                --prItem-title-size:<?php echo $title['desktop']['size'];?>px;
                --prItem-title-color:<?php echo $title['desktop']['color'];?>;

                --prItem-price-display:<?php echo $price['desktop']['show'];?>;
                --prItem-price-align:<?php echo $price['desktop']['align'];?>;
                --prItem-price-font:<?php echo $price['desktop']['font'];?>;
                --prItem-price-weight:<?php echo $price['desktop']['weight'];?>;
                --prItem-price-size:<?php echo $price['desktop']['size'];?>px;
                --prItem-price-color:<?php echo $price['desktop']['color'];?>;
            }
        </style>
        <?php
    }
    add_action('cle_header','productAssetsValidate', 30);
}
