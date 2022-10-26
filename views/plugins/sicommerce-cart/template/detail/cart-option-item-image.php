<?php
	$attr 			= apply_filters('product_detail_attribute_type_image', array('style' => 'background:url('.Template::imgLink($attribute->image).');background-repeat: no-repeat; background-size: cover;background-position: center;'), $option, $attribute, $object );

	$attr_string 	= '';

	if(have_posts($attr)) {
		foreach ($attr as $attr_key => $attr_value) {
			$attr_string .= $attr_key.'="'.$attr_value.'" ';
		}
	}
?>
<label class="option-type__swatch option-type__image option-type__<?= $option['id'];?>_<?= $attribute->id;?>" data-label="<?= $attribute->title;?>" data-group="<?= $option['id'];?>" data-id="<?= $attribute->id;?>">
    <input type="radio" name="option[<?= $option['id'];?>]" value="<?= $attribute->id;?>" class="option_input option-type__radio" data-id="<?php echo $object->id;?>">
    <div class="option-type__inner item" <?php echo $attr_string;?> ></div>
    <?php Template::img(Url::base().CART_PATH.'assets/images/bg-product.png');?>
</label>