<?php if(isset($metaBox) && have_posts($metaBox)) { ?>
    <?php foreach ($metaBox as $options): if(is_array($options)) $options = (object)$options; ?>
        <div class="col-md-12">
            <h5 style="font-weight: bold;"><?php echo $options->title;?></h5>
            <?php foreach ($options->items as $item): ?>
                <?php $upload_image = (isset($img[$item->id])) ? $img[$item->id] : base_url().CART_PATH.'assets/images/Placeholder.jpg';?>
                <div class="form-group" style="display: inline-block;margin-right: 10px;position:relative;" data-toggle="tooltip" data-placement="top" title="<?php echo $item->title;?>">
                    <label style="display: block; font-size: 10px;"><?php echo $item->title;?></label>
                    <button class="iframe-btn" data-fancybox data-type="iframe" data-id="<?php echo $id;?>" data-src="<?= base_url();?>scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=field_image_attribute_img_<?php echo $id;?>_<?php echo $item->id;?>&amp;callback=product_options_img_responsive_file_manager_callback" type="button" style="padding:0;background-color: transparent;border: 0;">
                        <img src="<?php echo Template::imgLink($upload_image);?>" class="field-btn-img" style="width:50px;height: 50px;">
                    </button>
                    <input name="attr_op_img[<?php echo $id;?>][<?php echo $item->id;?>]" value="<?php echo $upload_image;?>" type="hidden" id="field_image_attribute_img_<?php echo $id;?>_<?php echo $item->id;?>" class=" form-control">
                    <span class="attr_op_img_remove" style="color:red;position:absolute; top:10px; right:0;cursor: pointer;"><i class="fad fa-times-circle"></i></span>
                </div>
            <?php endforeach ?>
        </div>
    <?php endforeach ?>
<?php } ?>