<div class="col-md-2 col-lg-1">
    <div class="layout-item">
        <label for="<?php echo $layout_type;?>-layout-<?php echo $layout_key;?>">
            <div class="img"><?php Template::img($layout_value['image']);?></div>
            <div class="name">
                <input type="radio" value="<?php echo $layout_key;?>" name="layout[<?php echo $layout_type;?>-layout]" id="<?php echo $layout_type;?>-layout-<?php echo $layout_key;?>" <?php echo ($layout_active == $layout_key) ? 'checked' : '';?>>
                <span><?php echo $layout_value['label'];?></span>
            </div>
        </label>
    </div>
</div>