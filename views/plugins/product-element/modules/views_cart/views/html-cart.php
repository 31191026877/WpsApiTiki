<div class="pre-cart-box">
    <?php if($count == 0) {?>
        <button class="btn product_add_to_cart_now" data-id="<?php echo $object->id;?>">
            <span><i class="fa-thin fa-basket-shopping-simple"></i> <?php echo __('Mua Ngay');?></span>
        </button>
    <?php } else { ?>
        <a class="btn" href="<?php echo Url::permalink($object->slug);?>">
            <span><i class="fa-thin fa-basket-shopping-simple"></i> <?php echo __('Xem Ngay');?></span>
        </a>
    <?php } ?>
</div>