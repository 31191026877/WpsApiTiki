<div class="box">
    <div class="header"><h2>Hình đại diện</h2></div>
    <div class="box-content" style="margin-top: 10px;">
        <div class="row m-1">
            <div class="col-md-3">
                <label for="">Ảnh sản phẩm</label>
                <p style="color:#999;margin:5px 0 5px 0;">Cấu hình hiển thị ảnh sản phẩm</p>
            </div>
            <div class="col-md-9">
                <div class="row">
                <?php echo FormBuilder::render(['name' => 'productImg[ratio_w]', 'type' => 'number',
                    'label' => 'Tỉ lệ (rộng)', 'step' => '0.01', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $img['ratio_w']); ?>
                <?php echo FormBuilder::render(['name' => 'productImg[ratio_h]', 'type' => 'number',
                    'label' => 'Tỉ lệ (cao)', 'step' => '0.01', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $img['ratio_h']); ?>
                <?php echo FormBuilder::render(['name' => 'productImg[s]', 'type' => 'select',
                    'label'     => 'Kiểu hiển thị',
                    'options'   => ['cover' => 'Cắt ảnh khi không vừa khung', 'contain' => 'Giữ nguyên theo tỉ lệ ảnh'],
                    'after'     => '<div class="form-group col-md-6">', 'before' => '</div>'], $img['style']); ?>
                <?php echo FormBuilder::render(['name' => 'productImg[e]', 'type' => 'select',
                    'label'     => 'Hiệu ứng hover',
                    'options'   => ['zoom' => 'Phóng to nhẹ ảnh', 'change-img' => 'Đổi ảnh', 'none' => 'Không làm gì'],
                    'after'     => '<div class="form-group col-md-6">', 'before' => '</div>'], $img['effect']); ?>
                </div>
            </div>
        </div>
    </div>
</div>