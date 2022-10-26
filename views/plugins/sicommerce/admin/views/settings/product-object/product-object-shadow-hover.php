<div class="box">
    <div class="header"> <h2>SHADOW HOVER</h2> </div>
    <div class="box-content" style="margin-top: 10px;">
        <div class="row m-1">
            <div class="col-xs-12 col-12 col-md-3">
                <label for="">Đổ bóng khi hover</label>
                <p style="color:#999;margin:5px 0 5px 0;">Thêm hiệu ứng bóng xung quanh khung sản phẩm</p>
                <div class="productBoxShadowHover_review">Xem trước</div>
            </div>
            <div class="col-xs-12 col-12 col-md-9">
                <div class="row">
                    <div class="col-xs-12 col-12 col-md-6">
                        <div class="box-ef-option-container">
                            <label for="productBoxShadowHover_s_none" class="productBoxShadowHover_style_item ef-option-item <?php echo ('none' == $shadow['style']) ? 'hover' : '';?>" data-shadow="<?php echo htmlentities(json_encode(['h' => 0, 'v' => 0, 'b' => 0, 'sp' => 0]));?>">
                                <span class="ef-option-item-content"><i class="fal fa-ban ef-option-item-icon"></i></span>
                                <input type="radio" name="productBoxShadowHover[s]" id="productBoxShadowHover_s_none" value="none" <?php echo ('none' == $shadow['style']) ? 'checked=checked' : '';?>>
                            </label>
                            <?php foreach ($shadowStyle as $key => $item) { ?>
                                <label for="productBoxShadowHover_s_<?php echo $key;?>" class="productBoxShadowHover_style_item ef-option-item <?php echo ($key == $shadow['style']) ? 'hover' : '';?>" data-shadow="<?php echo htmlentities(json_encode($item));?>">
                                    <span class="ef-option-item-content"><span class="ef-option-item-effect shadow-effect-<?php echo $key;?>"></span></span>
                                    <input type="radio" name="productBoxShadowHover[s]" id="productBoxShadowHover_s_<?php echo $key;?>" value="<?php echo $key;?>" <?php echo ($key == $shadow['style']) ? 'checked=checked' : '';?>>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-12 col-md-6 js-product-box-shadow-hover-config">
                        <div class="row">
                        <?php $form = new FormBuilder();
                        $form->add('productBoxShadowHover[h]', 'number', ['label' => 'Trục dọc', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $shadow['horizontal']);
                        $form->add('productBoxShadowHover[v]', 'number', ['label' => 'Trục ngang', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $shadow['vertical']);
                        $form->add('productBoxShadowHover[b]', 'number', ['label' => 'Blur Strength', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $shadow['blur']);
                        $form->add('productBoxShadowHover[sp]', 'number', ['label' => 'Spread Strength', 'after' => '<div class="form-group col-md-6">', 'before' => '</div>'], $shadow['spread']);
                        $form->add('productBoxShadowHover[c]', 'color', ['label' => 'Màu bóng'], $shadow['color']);
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
    .productBoxShadowHover_review {
        width: 150px; height: 150px;box-shadow: 0 0 10px 1px rgba(0,50,82,0.4);
        margin: 20px auto; text-align: center; line-height: 150px;
        color: #ccc;
    }
    .shadow-effect-1 {
        box-shadow: 0 0 10px 1px rgba(0,50,82,0.4);
    }
    .shadow-effect-2 {
        box-shadow: 4px 4px 6px 0 rgba(0,50,82,0.4);
    }
    .shadow-effect-3 {
        box-shadow: 0 8px 6px -6px rgba(0,50,82,0.4);
    }
    .shadow-effect-4 {
        box-shadow: 4px 4px 0 0 rgba(0,50,82,0.4);
    }
    .shadow-effect-5 {
        box-shadow: 0 2px 0 4px rgba(0,50,82,0.4);
    }
</style>
<script type="text/javascript">
    $(function() {
        function productBoxShadowHoverReview() {
            let shadow = $('#productBoxShadowHover_h').val() + 'px';
            shadow += ' ';
            shadow += $('#productBoxShadowHover_v').val() + 'px';
            shadow += ' ';
            shadow += $('#productBoxShadowHover_b').val() + 'px';
            shadow += ' ';
            shadow += $('#productBoxShadowHover_sp').val() + 'px';

            let color = $('#productBoxShadowHover_c').val();
            if(color == '') color = 'rgba(0,50,82,0.4)';
            shadow += ' ';
            shadow += color;
            $('.productBoxShadowHover_review').css('box-shadow', shadow);
        }
        productBoxShadowHoverReview();
        $('.productBoxShadowHover_style_item').click(function () {
            let shadow = $(this).data('shadow');
            $('#productBoxShadowHover_h').val(shadow.h);
            $('#productBoxShadowHover_v').val(shadow.v);
            $('#productBoxShadowHover_b').val(shadow.b);
            $('#productBoxShadowHover_sp').val(shadow.sp);
            productBoxShadowHoverReview();
        });
        $(document).on('keyup change', '.js-product-box-shadow-hover-config input', function (){
            productBoxShadowHoverReview();
        })
    });
</script>