<?php if(have_posts($product_attribute)) {?>
<div class="attributes-item">
    <div class="attributes-item-name"><?php echo $product_attribute->title;?></div>
    <div class="attributes-item-option">
        <?php $attributes = Attributes::getsItem(['attribute' => $product_attribute->id]); ?>
        <select name="attribute_values[<?php echo $product_attribute->id;?>][]" multiple data-placeholder="Chọn tên chủng loại của thuộc tính" class="form-control attribute_values" tabindex="-1" aria-hidden="true">
            <?php foreach ($attributes as $key => $attribute): ?>
                <option value="<?php echo $attribute->id;?>" <?php echo (in_array($attribute->id , (array)$product_attribute->attributes_item) !== false)?'selected':'';?>><?php echo $attribute->title;?></option>
            <?php endforeach ?>
        </select>
        <input class="attribute_names" type="hidden" name="attribute_names[<?php echo $product_attribute->id;?>]" value="<?php echo $product_attribute->id;?>">
    </div>
    <a class="attribute-del btn btn-red" data-id="<?php echo $product_attribute->id;?>" style="color:#fff;"><?php echo Admin::icon('delete');?></a>
</div>
<?php }  else { ?>
<div class="attributes-item">
    <div class="attributes-item-name" style="color:red;"><del>(không tồn tại)</del></div>
    <a class="attribute-del btn btn-red pull-right" data-id="0">Xóa</a>
</div>
<?php }
