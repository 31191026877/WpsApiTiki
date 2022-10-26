<div class="box">
    <div class="header"><h3>LOẠI</h3></div>
    <div class="box-content row m-1">
        <div class="col-md-6">
            <div class="radio">
                <label class="text-center">
                    <img src="<?php echo PR_EL_PATH.'/assets/images/sale/style1.png';?>" style="max-width: 100%;" alt="">
                    <input type="radio" name="box_sale[style]" value="1" <?php echo (Product_Element_Box_Sale::config('style') == 1) ? 'checked' : '';?>>
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="radio">
                <label class="text-center">
                    <img src="<?php echo PR_EL_PATH.'/assets/images/sale/style2.png';?>" style="max-width: 100%;" alt="">
                    <input type="radio" name="box_sale[style]" value="2" <?php echo (Product_Element_Box_Sale::config('style') == 2) ? 'checked' : '';?>>
                </label>
            </div>
        </div>
    </div>
</div>
<div class="box">
    <div class="header"><h3>TEXT</h3></div>
    <div class="box-content row m-1">
        <?php
        $Form = new FormBuilder();
        $Form->add('box_sale[position]', 'number', ['label' => 'Thứ tự gắn vào hook "product_detail_info"'], Product_Element_Box_Sale::config('position'));
        $Form->add('box_sale[title]', 'text', ['label' => 'Tiêu đề'], Product_Element_Box_Sale::config('title'));
        $Form->html(false);
        ?>
    </div>
</div>

<div class="box">
    <div class="header"><h3>STYLE</h3></div>
    <div class="box-content row m-1">
        <?php
        $Form->add('box_sale[padding]', 'text', ['label' => 'Padding', 'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'], Product_Element_Box_Sale::config('padding'));
        $Form->add('box_sale[margin]', 'text', ['label' => 'Margin', 'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'], Product_Element_Box_Sale::config('margin'));
        $Form->add('box_sale[bg_box]', 'color', ['label' => 'Màu Box', 'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'], Product_Element_Box_Sale::config('bg_box'));
        $Form->add('box_sale[text_color]', 'color', ['label' => 'Màu tiêu đề', 'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'], Product_Element_Box_Sale::config('text_color'));
        $Form->html(false);
        ?>
    </div>
</div>

<div class="box">
    <div class="header"><h3>Gở bỏ tempalte</h3></div>
    <div class="box-content">
        <div class="col-md-12">
            <p>remove_action('product_detail_info', ['Product_Element_Box_Sale', 'render'], Product_Element_Box_Sale::config('position'));</p>
        </div>
    </div>
</div>

<style>
    .select-img .checkbox img { width:50px; border:1px solid #ccc; }
    .select-img .checkbox input:checked + label img {
        border:1px solid #000;
    }
    .select-img .checkbox label span { display: none;}
</style>
