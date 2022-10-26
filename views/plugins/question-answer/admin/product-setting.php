<div class="box">
    <div class="header"> <h2>CÂU HỎI LIÊN QUAN</h2> </div>
    <div class="box-content">
        <div class="row m-1">
            <div class="col-md-6">
                <div class="row">
                    <?php
                    $From = new FormBuilder();
                    $From
                        ->add('product_question[position]', 'select', [
                            'label' => 'Vị trí câu hỏi',
                            'options' => [
                                'sidebar' => 'Sidebar',
                                'content' => 'Nội dung sản phẩm',
                                'bottom'  => 'Footer sản phẩm',
                                'disabled' => 'Tắt',
                            ],
                            'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                        ], (!empty($productQuestion['position'])) ? $productQuestion['position'] : 'sidebar' )
                        ->add('product_question[data]', 'select', [
                            'label' => 'Dữ liệu sản phẩm bán chạy',
                            'options' => [
                                'auto' => 'Tự động random',
                                'handmade'  => 'Thủ công chọn từng câu hỏi',
                            ],
                            'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                        ], (!empty($productQuestion['data'])) ? $productQuestion['data'] : 'handmade' )
                        ->add('product_question[limit]', 'range', [
                            'label' => 'Số câu hỏi hiển thị',
                            'min' => 1,
                            'max' => 30
                        ], (!empty($productQuestion['limit'])) ? $productQuestion['limit'] : '' )
                        ->html(false);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>