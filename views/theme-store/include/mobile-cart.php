<div class="panel--sidebar" id="cart-sidebar">
    <div class="panel__header">
        <h3>Shopping Cart</h3>
        <div class="panel__close"><a href="#" class="js_panel__close"><i class="fal fa-times"></i></a></div>
    </div>
    <div class="panel__content">
        <?php
        $cart = Scart::getItems();
        ?>
        <div class="page-cart page-cart-content">
            <?php echo Admin::loading();?>
            <div class="cart-error"></div>
            <div class="cart-content page-cart-box">
                <?php do_action('cart_before_table', $cart); ?>
                <form method="post" class="" id="page_cart_form">
                    <?php echo form_open();?>
                    <div class="page-cart-tbody">
                        <?php
                        do_action('cart_before_contents', $cart);
                        foreach ($cart as $item) { $item = (object)$item;
                            echo cart_template('cart/cart-items', array('item' => $item));
                        }
                        do_action('cart_after_contents', $cart);
                        ?>
                    </div>
                </form>
                <?php do_action('cart_after_table', $cart); ?>
            </div>
            <div class="cart-collaterals page-cart-right">
                <?php echo cart_template('cart/cart-total');?>
                <div class="clearfix"></div>
                <div class="cart-button">
                    <a class="btn btn-default pull-left" href="<?php echo Url::permalink('san-pham');?>"><?php echo __('MUA THÊM', 'wcmc_tieptucmuahang');?></a>
                    <a class="btn btn-red pull-right" href="<?php echo Url::permalink('thanh-toan');?>"><?php echo __('THANH TOÁN', 'wcmc_thanhtoan');?></a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<style>
    #cart-sidebar .cart-content {
        min-height: calc(100vh - 396px);
    }
    #cart-sidebar .page-cart-content .page-cart-box {
        margin-bottom: 0;
        box-shadow: none;
    }
    #cart-sidebar .page-cart .page-cart-box .page-cart-tbody .cart__item .pr-name h3 {
        text-align: left; font-size: 14px; font-weight: 500;
    }
    #cart-sidebar .page-cart .page-cart-box .page-cart-tbody .cart__item .pr-name .variant-title {
        text-align: left; font-size: 13px; margin-bottom: 0;
    }
    #cart-sidebar .page-cart .page-cart-box .page-cart-tbody .cart__item .cart_item__info .pr-price {
        text-align: left; font-size: 13px;
    }

    #cart-sidebar .page-cart .page-cart-box .page-cart-tbody .cart__item .cart_item__quantity {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    #cart-sidebar .page-cart .page-cart-box .page-cart-tbody .cart__item .cart_item__quantity .btn-minus,
    #cart-sidebar .page-cart .page-cart-box .page-cart-tbody .cart__item .cart_item__quantity .btn-plus {
        height: 20px;
    }
    #cart-sidebar .page-cart .page-cart-box .wcmc-cart-tbody .cart__item .cart_item__quantity .quantity-number {
        height: 20px; line-height: 20px;
    }
    #cart-sidebar .page-cart .page-cart-box .wcmc-cart-tbody .cart__item .cart_item__quantity .quantity-number .number {
        font-size: 13px;
    }
    #cart-sidebar .cart-collaterals {
        color:#000; background-color: #fff;
        border-top: 1px solid #e1e1e1;
    }
    #cart-sidebar .cart-collaterals .cart-subtotal th {
        font-size: 18px;
        font-weight: 600;
        background-color: #fff;
        padding:20px 20px 10px 20px;
    }
    #cart-sidebar .cart-collaterals .cart-subtotal td {
        font-size: 18px;
        font-weight: 600;
        color: red;
        background-color: #fff;
        text-align: right;
        padding:20px 20px 10px 20px;
    }
    #cart-sidebar .cart-collaterals .cart-subtotal:last-child {
        display: none;
    }

    #cart-sidebar .page-cart-empty { color:#000; }
    #cart-sidebar .page-cart-empty h2 { font-size: 18px; }
    #cart-sidebar .page-cart-empty p { font-size: 15px; }

    #cart-sidebar .cart-button {
        overflow: hidden;padding:10px 20px 20px 20px;
    }
    #cart-sidebar .cart-button .btn {
        width: 40%; margin: 0;
        box-shadow: none;
        background-color: var(--theme-color);
        color:#fff;
        border: 0;
    }
</style>