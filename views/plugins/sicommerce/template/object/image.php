<?php
$style = Prd::itemStyle('img.effect');
$style = (empty($style)) ? 'zoom' : $style;
?>
<div class="img <?php echo $style;?>">
	<picture>
	    <?php Template::img($val->image, $val->title, ['type' => $image_type]);?>
        <?php
        if($style == 'change-img') {
            $img = Gallery::getItem(Qr::set('object_id', $val->id)->where('object_type', 'products')->where('type', 'image')->select('id', 'value'));
            if(have_posts($img)) {
                Template::img($img->value, $val->title, ['type' => $image_type, 'class' => 'product-hover-image']);
            }
        }
        ?>
	</picture>
</div>