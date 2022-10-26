<?php
    $fonts 	= ['Font mặc định'];
    $fonts 	= array_merge($fonts, gets_theme_font());
?>
<div class="box">
    <div class="header"><h2>Thông Tin Sản Phẩm</h2></div>
    <div class="box-content" style="margin-top: 10px;">
        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Tên sản phẩm</label>
                <p style="color:#999;margin:5px 0 5px 0;">Cấu hình hiển thị tên sản phẩm</p>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-2">
                        <?php echo FormBuilder::render(['name' => 'productTitle[show]', 'type' => 'switch', 'label' => 'Ẩn / Hiện'], $title['desktop']['show']); ?>
                        <?php echo FormBuilder::render(['name' => 'productTitle[position]', 'type' => 'number', 'label' => 'Thứ tự'], $title['position']); ?>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                        <?php echo FormBuilder::render(['name' => 'productTitle[color]', 'label' => 'Màu chữ', 'type' => 'color', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $title['desktop']['color']); ?>
                        <?php echo FormBuilder::render(['name' => 'productTitle[align]', 'label' => 'Vị trí', 'type' => 'tab', 'options' => ['left' => 'Trái', 'center' => 'Giữa', 'right' => 'Phải'], 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $title['desktop']['align']); ?>
                        <?php echo FormBuilder::render(['name' => 'productTitle[font]', 'label' => 'Font chữ', 'type' => 'select', 'options' => $fonts, 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $title['desktop']['font']); ?>
                        <?php echo FormBuilder::render(['name' => 'productTitle[weight]', 'label' => 'In đậm chữ', 'type' => 'tab', 'options' => ['300' => '300', '400' => '400', '500' => '500', 'bold' => 'Bold'], 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $title['desktop']['weight']); ?>
                        <?php echo FormBuilder::render(['name' => 'productTitle[size]', 'label' => 'Font size', 'type' => 'tab', 'value' => 16, 'options' => ['13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '42' => '42', '50' => '50'], 'after' => '<div class="form-group col-md-12">', 'before' => '</div>'], $title['desktop']['size']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Mobile</label>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-9">
                        <hr />
                        <div class="row">
                        <?php echo FormBuilder::render(['name' => 'productTitleMobile[weight]', 'label' => 'In đậm chữ', 'type' => 'tab', 'options' => ['300' => '300', '400' => '400', '500' => '500', 'bold' => 'Bold'], 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $title['mobile']['weight']); ?>
                        <?php echo FormBuilder::render(['name' => 'productTitleMobile[size]', 'label' => 'Font size', 'type' => 'tab', 'value' => 20, 'options' => ['13' => '13', '14' => '14', '15' => '15', '16' => '16', '18' => '18', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '42' => '42', '50' => '50'], 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $title['mobile']['size']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>

        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Giá sản phẩm</label>
                <p style="color:#999;margin:5px 0 5px 0;">Cấu hình hiển thị giá sản phẩm</p>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-2">
                        <?php echo FormBuilder::render(['name' => 'productPrice[show]', 'type' => 'switch', 'label' => 'Ẩn / Hiện'], $price['desktop']['show']); ?>
                        <?php echo FormBuilder::render(['name' => 'productPrice[position]', 'type' => 'number', 'label' => 'Thứ tự'], $price['position']); ?>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                        <?php echo FormBuilder::render(['name' => 'productPrice[color]', 'label' => 'Màu chữ', 'type' => 'color', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $price['desktop']['color']); ?>
                        <?php echo FormBuilder::render(['name' => 'productPrice[align]', 'label' => 'Vị trí', 'type' => 'tab', 'options' => ['left' => 'Trái', 'center' => 'Giữa', 'right' => 'Phải'], 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $price['desktop']['align']); ?>
                        <?php echo FormBuilder::render(['name' => 'productPrice[font]', 'label' => 'Font chữ', 'type' => 'select', 'options' => $fonts, 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $price['desktop']['font']); ?>
                        <?php echo FormBuilder::render(['name' => 'productPrice[weight]', 'label' => 'In đậm chữ', 'type' => 'tab', 'options' => ['300' => '300', '400' => '400', '500' => '500', 'bold' => 'Bold'], 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $price['desktop']['weight']); ?>
                        <?php echo FormBuilder::render(['name' => 'productPrice[size]', 'label' => 'Font size', 'type' => 'tab', 'value' => 16, 'options' => ['13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '42' => '42', '50' => '50'], 'after' => '<div class="form-group col-md-12">', 'before' => '</div>'], $price['desktop']['size']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Mobile</label>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-9">
                        <hr />
                        <div class="row">
                        <?php echo FormBuilder::render(['name' => 'productPriceMobile[weight]', 'label' => 'In đậm chữ', 'type' => 'tab', 'options' => ['300' => '300', '400' => '400', '500' => '500', 'bold' => 'Bold'], 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $price['mobile']['weight']); ?>
                        <?php echo FormBuilder::render(['name' => 'productPriceMobile[size]', 'label' => 'Font size', 'type' => 'tab', 'value' => 20, 'options' => ['13' => '13', '14' => '14', '15' => '15', '16' => '16', '18' => '18', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '42' => '42', '50' => '50'], 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $price['mobile']['size']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>