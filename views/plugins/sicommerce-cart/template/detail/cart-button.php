<div class="product_alert"></div>
<div class="product-detail-cart">
    <input type="hidden" id="product_select_input_<?php echo $object->id;?>" name="product_id" value="<?php echo (empty($default_id)) ? $object->id : $product_default_id ;?>">
    <div style="overflow: hidden;">
        <div class="addtocart_quantity">
            <div class="quantity-title" style="overflow: hidden;height: auto;">
                <p><?php echo __('Số lượng', 'cart_quantity');?> :</p>
            </div>
            <div class="btn-and-quantity">
                <div class="spinner">
                    <span class="quantity-btn minus quantity-down"></span>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="quantity-selector">
                    <span class="quantity-btn plus quantity-up"></span>
                </div>
            </div>
        </div>
        <div class="addtocart_button">
            <?php do_action('add_to_cart_before', $object);?>
            <button class="btn btn-effect-default btn-red button_cart product_add_to_cart" data-id="<?php echo $object->id;?>">
                <span class="button_cart__heading"><?php echo apply_filters('button_add_to_cart_text',__('Thêm vào giỏ', 'cart_add_to_cart'));?></span>
                <span class="button_cart__desc"><?php echo apply_filters('button_add_to_cart_text_desc', __('và mua sản phẩm khác', 'cart_add_to_cart_desc'));?></span>
            </button>
            <button class="btn btn-effect-default btn-green button_cart_now product_add_to_cart_now" data-id="<?php echo $object->id;?>">
                <span class="button_cart__heading"><?php echo apply_filters('button_add_to_cart_now_text', __('Mua Ngay', 'cart_add_to_cart_now'));?></span>
                <span class="button_cart__desc"><?php echo apply_filters('button_add_to_cart_now_text_desc', __('Thanh toán ngay', 'cart_add_to_cart_now_desc'));?></span>
            </button>
            <?php do_action('add_to_cart_after', $object);?>
        </div>
    </div>
</div>