<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>Post Category Layout</h2></div>
        <div class="box-content">
            <?php foreach ($layout_list as $layout_key => $layout_value) {
                $layout_type = 'post-category';
                $layout_active = $layout_post_category;
                ?>
                <div class="col-md-3 col-lg-3">
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
                <?php
            } ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>