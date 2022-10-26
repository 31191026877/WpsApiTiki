<div class="box">
    <div class="header"><h2>BORDER</h2></div>
    <div class="box-content" style="margin-top: 10px;">
        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Viền khung</label>
                <p style="color:#999;margin:5px 0 5px 0;">Cấu hình tên hiển thị sản phẩm</p>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        <div class="box-ef-option-container">
                            <?php foreach ($borderStyle as $key => $item) { ?>
                                <label for="productBoxBorder_s_<?php echo $item;?>" class="ef-option-item <?php echo ($item == $border['style']) ? 'hover' : '';?>">
                                    <span class="ef-option-item-content">
                                        <?php if($item == 'none') { ?>
                                            <i class="fal fa-ban ef-option-item-icon"></i>
                                        <?php } else { ?>
                                            <span class="ef-option-item-effect border-effect-border" style="border-style: <?php echo $item;?>"></span>
                                        <?php } ?>
                                    </span>
                                    <input type="radio" name="productBoxBorder[s]" id="productBoxBorder_s_<?php echo $item;?>" value="<?php echo $item;?>" <?php echo ($item == $border['style']) ? 'checked=checked' : '';?>>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                        <?php  $form = new FormBuilder();
                        $form->add('productBoxBorder[w]', 'number', ['label' => 'Độ dầy', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $border['width']);
                        $form->add('productBoxBorder[c]', 'color', ['label' => 'Màu viền', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $border['color']);
                        $form->add('productBoxBorder[r]', 'number', ['label' => 'Bo tròn 4 góc', 'after' => '<div class="form-group col-md-12">', 'before' => '</div>'], $border['radius']);
                        $form->html(false);
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .border-effect-border {
        border:1px solid #BEC9D6;
    }
</style>