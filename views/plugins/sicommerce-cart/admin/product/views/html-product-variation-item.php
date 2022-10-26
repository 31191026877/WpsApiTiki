<?php
foreach ($attribute as $key => $value):
	$options[$key] = Attributes::get($key);
	foreach ($value['value'] as $k => $val) {
		$options[$key]->{'attribute'}[$val] = Attributes::getItem($val);
	}
endforeach;

$meta = Metadata::get('products', $variations_id );

$review_image        = (!empty($variation->image))? $variation->image : Url::base().CART_PATH.'assets/images/Placeholder.jpg';

$upload_image        = (isset($variation->image) && $variation->image != Url::base().CART_PATH.'assets/images/Placeholder.jpg') ? $variation->image : '';

$variable_price      = (isset($variation->price))?$variation->price:0;

$variable_price_sale = (isset($variation->price_sale))?$variation->price_sale:0;

$variable_weight 	 = (isset($variation->weight)) ? $variation->weight :0;

$default = (isset($variation) && Product::getMeta($variation->parent_id, 'default', true) == $variations_id) ? 'checked' : '';
?>
<div class="variations-item" data-variations-id="<?php echo $variations_id;?>">
	<div id="variations_heading<?php echo $variations_id;?>" class="variations-item-heading">
		<input type="hidden" name="product_variations_id[]" value="<?php echo $variations_id;?>">
		<?php foreach ($options as $key => $option): ?>
			<?php if( have_posts($option) ) : ?>
				<?php $attribute_op = (isset($meta->{'attribute_op_'.$option->id}))?$meta->{'attribute_op_'.$option->id}:0; ?>
				<select required name="attribute_op_<?php echo $option->id;?>[<?php echo $variations_id;?>]" class="form-control attribute_value" data-option-id="<?php echo $option->id;?>" style="width: auto;display: inline-block;">
					<option value="0">Chọn <?php echo $option->title;?></option>
					<?php foreach ($option->attribute as $attribute): ?>
						<option value="<?php echo $attribute->id;?>" <?php echo ($attribute_op == $attribute->id)?'selected=selected':'';?>><?php echo $attribute->title;?></option>
					<?php endforeach ?>
				</select>
			<?php else: ?>
				<select required class="form-control attribute_value">
					<option value="0">Không biết</option>
				</select>
			<?php endif ?>
		<?php endforeach;?>
        <label>
            <input type="radio" name="variable_default" value="<?php echo $variations_id;?>" <?php echo $default;?>> Mặc định
        </label>
        <a class="btn btn-red variations-del text-right" href="" data-id="<?php echo $variations_id;?>">Xóa</a>
	</div>
	<div id="variations_collapse<?php echo $variations_id;?>" class="variations-item-content" style="margin: 10px 0;">
	  	<div class="row m-1">
            <?php
            $Form = new FormBuilder();
            $Form
                ->add('upload_image['.$variations_id.']', 'image', [
                    'label' => 'Ảnh biến thể',
                    'after' => '<div class="form-group col-md-12">', 'before' => '</div>',
                    'data-name' => 'variable_image', 'id' => 'field_image_'.$variations_id
                ], Template::imgLink($upload_image))
                ->add('variable_code['.$variations_id.']', 'text', [
                    'label' => 'Mã sản phẩm biến thể',
                    'after' => '<div class="form-group col-md-3">', 'before' => '</div>',
                    'data-name' => 'variable_code',
                    'note' => 'Mã sản phẩm biến thể (SKU)'
                ], (!empty($variations_code)) ? $variations_code : '')
                ->add('variable_price['.$variations_id.']', 'text', [
                    'label' => 'Giá bán',
                    'after' => '<div class="form-group col-md-3">', 'before' => '</div>',
                    'data-name' => 'variable_price',
                    'required' => true,
                    'value' => 0
                ], $variable_price)
                ->add('variable_price_sale['.$variations_id.']', 'text', [
                    'label' => 'Giá khuyến mãi',
                    'after' => '<div class="form-group col-md-3">', 'before' => '</div>',
                    'data-name' => 'variable_price_sale',
                    'required' => true,
                    'value' => 0
                ], $variable_price_sale)
                ->add('variable_weight['.$variations_id.']', 'text', [
                    'label' => 'Khối lượng biến thể',
                    'after' => '<div class="form-group col-md-3">', 'before' => '</div>',
                    'data-name' => 'variable_weight',
                    'required' => true,
                    'value' => 0
                ], $variable_weight);
            $Form = apply_filters('admin_product_variation_form', $Form, $variations_id, (isset($variation)) ? $variation : []);
            $Form->html(false);
            ?>
			<div class="row">
				<?php $field = []; ?>
				<?php do_action( 'product_variation_html', $variations_id, (isset($variation)) ? $variation : [] );?>
			</div>
	  	</div>
	</div>
</div>