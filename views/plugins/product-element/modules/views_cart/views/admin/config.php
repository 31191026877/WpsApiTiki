<?php
$fonts 	= ['Font mặc định'];
$fonts 	= array_merge($fonts, gets_theme_font());
?>
<div class="box">
    <div class="header"><h2>Lượt xem & Button mua hàng</h2></div>
    <div class="box-content" style="margin-top: 10px;">
        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Lượt xem</label>
                <p style="color:#999;margin:5px 0 5px 0;">Cấu hình hiển thị lượt xem sản phẩm</p>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-2">
                        <?php echo FormBuilder::render(['name' => 'productViewsCart[views][show]', 'type' => 'switch', 'label' => 'Ẩn / Hiện'], $views['show']); ?>
                        <?php echo FormBuilder::render(['name' => 'productViewsCart[views][position]', 'type' => 'number', 'label' => 'Thứ tự'], $views['position']); ?>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][bg]', 'label' => 'Màu nền', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $views['bg']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][color]', 'label' => 'Màu chữ', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $views['color']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][borderColor]', 'label' => 'Màu viền', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $views['borderColor']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][borderWidth]', 'label' => 'Độ dầy viền', 'type' => 'number', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $views['borderWidth']); ?>

                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][bgHover]', 'label' => 'Màu nền (hover)', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $views['bgHover']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][colorHover]', 'label' => 'Màu chữ (hover)', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $views['colorHover']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][borderColorHover]', 'label' => 'Màu viền (hover)', 'type' => 'color', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $views['borderColorHover']); ?>

                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][align]', 'label' => 'Vị trí', 'type' => 'tab', 'options' => ['left' => 'Trái', 'center' => 'Giữa', 'right' => 'Phải'], 'after' => '<div class="form-group col-md-4">', 'before' => '</div>'], $views['align']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][font]', 'label' => 'Font chữ', 'type' => 'select', 'options' => $fonts, 'after' => '<div class="form-group col-md-4">', 'before' => '</div>'], $views['font']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][weight]', 'label' => 'In đậm chữ', 'type' => 'tab', 'options' => ['300' => '300', '400' => '400', '500' => '500', 'bold' => 'Bold'], 'after' => '<div class="form-group col-md-4">', 'before' => '</div>'], $views['weight']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[views][size]', 'label' => 'Font size', 'type' => 'tab', 'value' => 16, 'options' => ['10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '42' => '42', '50' => '50'], 'after' => '<div class="form-group col-md-12">', 'before' => '</div>'], $views['size']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Button mua hàng</label>
                <p style="color:#999;margin:5px 0 5px 0;">Cấu hình hiển thị button mua hàng sản phẩm</p>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-2">
                        <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][show]', 'type' => 'switch', 'label' => 'Ẩn / Hiện'], $cart['show']); ?>
                        <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][position]', 'type' => 'number', 'label' => 'Thứ tự'], $cart['position']); ?>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][bg]', 'label' => 'Màu nền', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $cart['bg']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][color]', 'label' => 'Màu chữ', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $cart['color']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][borderColor]', 'label' => 'Màu viền', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $cart['borderColor']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][borderWidth]', 'label' => 'Độ dầy viền', 'type' => 'number', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $cart['borderWidth']); ?>

                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][bgHover]', 'label' => 'Màu nền (hover)', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $cart['bgHover']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][colorHover]', 'label' => 'Màu chữ (hover)', 'type' => 'color', 'after' => '<div class="form-group col-md-3">', 'before' => '</div>'], $cart['colorHover']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][borderColorHover]', 'label' => 'Màu viền (hover)', 'type' => 'color', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $cart['borderColorHover']); ?>

                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][align]', 'label' => 'Vị trí', 'type' => 'tab', 'options' => ['left' => 'Trái', 'center' => 'Giữa', 'right' => 'Phải'], 'after' => '<div class="form-group col-md-4">', 'before' => '</div>'], $cart['align']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][font]', 'label' => 'Font chữ', 'type' => 'select', 'options' => $fonts, 'after' => '<div class="form-group col-md-4">', 'before' => '</div>'], $cart['font']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][weight]', 'label' => 'In đậm chữ', 'type' => 'tab', 'options' => ['300' => '300', '400' => '400', '500' => '500', 'bold' => 'Bold'], 'after' => '<div class="form-group col-md-4">', 'before' => '</div>'], $cart['weight']); ?>
                            <?php echo FormBuilder::render(['name' => 'productViewsCart[cart][size]', 'label' => 'Font size', 'type' => 'tab', 'value' => 16, 'options' => ['10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '20' => '20', '25' => '25', '30' => '30', '35' => '35', '42' => '42', '50' => '50'], 'after' => '<div class="form-group col-md-12">', 'before' => '</div>'], $cart['size']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Kiểu hiển thị</label>
                <p style="color:#999;margin:5px 0 5px 0;">Cấu hình hiển thị button mua hàng sản phẩm</p>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <?php echo FormBuilder::render(['name' => 'productViewsCart[style]', 'label' => 'Kiểu', 'type' => 'select',
                        'options' => [
                            'block'  => 'Lượt xem & button mua hàng nằm trên 2 hàng',
                            'inline' => 'Lượt xem & button mua hàng nằm trên 1 hàng',
                        ],
                        'after' => '<div class="form-group col-md-12">', 'before' => '</div>'], $style); ?>
                </div>
            </div>
        </div>
    </div>
</div>