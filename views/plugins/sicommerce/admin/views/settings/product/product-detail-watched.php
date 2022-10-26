<div class="box">
    <div class="header"> <h2>SẢN PHẨM ĐÃ XEM</h2> </div>
    <div class="box-content">
        <div class="m-3">
                <div class="row">
                <?php
                $From = new FormBuilder();
                $From
                    ->add('product_watched[position]', 'select', [
                        'label' => 'Vị trí sản phẩm đã xem',
                        'options' => [
                            'sidebar' => 'Sidebar',
                            'content' => 'Content sản phẩm',
                            'bottom'  => 'Footer sản phẩm',
                            'disabled' => 'Tắt',
                        ],
                        'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                    ], (!empty($product_watched['position'])) ? $product_watched['position'] : 'sidebar' )
                    ->add('product_watched[style]', 'select', [
                        'label' => 'Kiểu sản phẩm đã xem',
                        'options' => [
                            'slider' => 'Dạng slider',
                            'grid' => 'Dạng danh sách',
                        ],
                        'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                    ], (!empty($product_watched['style'])) ? $product_watched['style'] : '' )
                    ->add('product_watched[columns]', 'range', [
                        'label' => 'Số sản phẩm / hàng',
                        'min' => 1,
                        'max' => 5,
                        'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                    ], (!empty($product_watched['columns'])) ? $product_watched['columns'] : '' )
                    ->add('product_watched[posts_per_page]', 'range', [
                        'label' => 'Số sản phẩm lớn nhất lấy ra',
                        'min' => 1,
                        'max' => 30,
                        'after' => '<div class="col-md-6"><div class="form-group group">', 'before'=> '</div></div>'
                    ], (!empty($product_watched['posts_per_page'])) ? $product_watched['posts_per_page'] : '' )
                    ->html(false);
                ?>
                </div>
        </div>
    </div>
</div>