<div class="box">
    <div class="header"> <h2>SẢN PHẨM BÁN CHẠY</h2> </div>
    <div class="box-content">
        <div class="m-3">
            <div class="row">
            <?php
            $From = new FormBuilder();
            $From
                ->add('product_selling[position]', 'select', [
                    'label' => 'Vị trí sản phẩm bán chạy',
                    'options' => [
                        'sidebar' => 'Sidebar',
                        'content' => 'Content sản phẩm',
                        'bottom'  => 'Footer sản phẩm',
                        'disabled' => 'Tắt',
                    ],
                    'after' => '<div class="col-md-4"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_selling['position'])) ? $product_selling['position'] : 'content' )
                ->add('product_selling[style]', 'select', [
                    'label' => 'Kiểu sản phẩm bán chạy',
                    'options' => [
                        'slider' => 'Dạng slider',
                        'grid' => 'Dạng danh sách',
                    ],
                    'after' => '<div class="col-md-4"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_selling['style'])) ? $product_selling['style'] : '' )
                ->add('product_selling[data]', 'select', [
                    'label' => 'Dữ liệu sản phẩm bán chạy',
                    'options' => [
                        'auto' => 'Tự động random sản phẩm',
                        'handmade'  => 'Thủ công chọn sản phẩm',
                    ],
                    'after' => '<div class="col-md-4"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_selling['data'])) ? $product_selling['data'] : 'handmade' )
                ->add('product_selling[columns]', 'range', [
                    'label' => 'Số sản phẩm / hàng',
                    'min' => 1,
                    'max' => 5,
                    'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_selling['columns'])) ? $product_selling['columns'] : '' )
                ->add('product_selling[posts_per_page]', 'range', [
                    'label' => 'Số sản phẩm lớn nhất lấy ra',
                    'min' => 1,
                    'max' => 30,
                    'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_selling['posts_per_page'])) ? $product_selling['posts_per_page'] : '' )
                ->html(false);
            ?>
            </div>
        </div>
    </div>
</div>