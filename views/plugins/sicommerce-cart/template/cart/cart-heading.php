<div class="cart-heading row">
    <div class="col-md-8">
        <h2 class="cart-heading__title" style="margin:0;"><?php echo __('Giỏ Hàng Của Bạn', 'cart_heading_title');?></h2>
    </div>
    <?php $cart = Scart::getItems();?>

	<?php if(have_posts($cart)) { ?>
    <div class="cart-heading__button col-md-4">
        <a class="btn btn-default btn-block" href="<?php echo Url::permalink('san-pham');?>"><?php echo __('MUA THÊM', 'cart_tieptucmuahang');?></a>
        <a class="btn btn-red btn-block" href="<?php echo Url::permalink('thanh-toan');?>"><?php echo __('THANH TOÁN', 'cart_thanhtoan');?></a>
    </div>
    <?php } ?>
</div>