<div class="box">
    <div class="header"> <h2>SẢN PHẨM LIÊN QUAN</h2> </div>
    <div class="box-content">
        <div class="m-3">
            <div class="row">
            <?php
            $From = new FormBuilder();
            $From
                ->add('product_related[position]', 'select', [
                    'label' => 'Vị trí sản phẩm liên quan',
                    'options' => [
                        'content' => 'Content sản phẩm',
                        'bottom'  => 'Footer sản phẩm',
                        'disabled' => 'Tắt',
                    ],
                    'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_related['position'])) ? $product_related['position'] : 'content' )
                ->add('product_related[style]', 'select', [
                    'label' => 'Kiểu sản phẩm liên quan',
                    'options' => [
                        'slider' => 'Dạng slider',
                        'grid' => 'Dạng danh sách',
                    ],
                    'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_related['style'])) ? $product_related['style'] : '' )
                ->add('product_related[columns]', 'range', [
                    'label' => 'Số sản phẩm / hàng',
                    'min' => 1,
                    'max' => 5,
                    'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_related['columns'])) ? $product_related['columns'] : '' )
                ->add('product_related[posts_per_page]', 'range', [
                    'label' => 'Số sản phẩm lớn nhất lấy ra',
                    'min' => 1,
                    'max' => 30,
                    'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                ], (!empty($product_related['posts_per_page'])) ? $product_related['posts_per_page'] : '' )
                ->html(false);
            ?>
            </div>
        </div>
    </div>
</div>