<?php
    $object = get_object_current('object');
    $slug   = '';
    if(have_posts($object)) $slug = $object->slug;
?>

<div class="cart-heading-bar">
    <div class="container">
        <div class="cart-heading-bar__content">
            <div class="logo-box" style="display: flex;">
                <a class="active" href="<?php echo Url::base();?>">
                    <?php Template::img(Option::get('logo_header'), Option::get('general_label'));?>
                </a>
            </div>

            <div class="cart-heading-bar__rule">
                <div class="step <?php echo ($slug == 'gio-hang')?'active':'';?>">
                    <div class="step-number">1</div>
                    <div class="step-label"><?php echo __('Giỏ hàng', 'cart_step_cart');?></div>
                </div>

                <div class="step <?php echo ($slug == 'thanh-toan')?'active':'';?>">
                    <div class="step-number">2</div>
                    <div class="step-label"><?php echo __('Thanh toán', 'cart_step_pay');?></div>
                </div>
                
                <div class="step <?php echo ($slug == 'don-hang')?'active':'';?>">
                    <div class="step-number">3</div>
                    <div class="step-label"><?php echo __('Hoàn tất', 'cart_step_success');?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cart-heading-bar {
        display:none;
        position: fixed; top:0; left:0; width:100%; box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px 0px, rgba(0, 0, 0, 0.14) 0px 0px 1px 0px, rgba(0, 0, 0, 0.12) 0px 2px 1px -1px; background: rgb(255, 255, 255); z-index: 60;
    }
    .cart-heading-bar .cart-heading-bar__content {
        display: flex; justify-content: space-between; align-items: center;
        height: 70px; min-height: 70px; max-height: 70px;
        padding-left: 10px;
        padding-right: 10px;
    }
    .cart-heading-bar .cart-heading-bar__content .logo-box img {
        max-height: 70px;
    }
    .cart-heading-bar__rule {
        display: flex; justify-content: space-between; height: 100%;
    }
    .cart-heading-bar__rule .step {
        display: flex; justify-content: space-between; position: relative; align-items: center; height: 100%; padding-left: 10px; padding-right: 10px; margin-left: 8px; margin-right: 8px;
    }
    .cart-heading-bar__rule .step.active {
        background: rgb(240, 242, 245);
    }
    .cart-heading-bar__rule .step.active::after {
        content:'';
        position: absolute; width: 12px; height: 100%; background: rgb(240, 242, 245); transform: skewX(-12deg); top: 0px; left: -5px;
    }
    .cart-heading-bar__rule .step.active::before {
        content:'';
        position: absolute; width: 12px; height: 100%; background: rgb(240, 242, 245); transform: skewX(-12deg); top: 0px; right: -5px;
    }
    .cart-heading-bar__rule .step .step-number {
        font-family: avenir-next-regular, arial, serif; color: rgb(77, 78, 79);
    }
    .cart-heading-bar__rule .step .step-label {
        display: block; color: rgb(77, 78, 79); margin-left: 8px; text-transform: uppercase;
    }
    .cart-heading-bar__rule .step.active {
        font-weight:bold;
    }
    .wrapper .object-detail { background-color: transparent!important;}
    @media(min-width:768px) {
        .wrapper { margin-top:70px!important; }
        .cart-heading-bar { display:block;}
    }

    header, footer, .box-bg-top { display: none!important;}
</style>