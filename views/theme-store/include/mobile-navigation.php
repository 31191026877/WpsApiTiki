<?php
if(!is_numeric(Option::get('mobile_navigation', 1)) || Option::get('mobile_navigation', 1) == 1) { ?>
<div class="navigation-mobile--list">
    <div class="navigation__content">
        <a class="navigation__item ps-toggle--sidebar navigation__item_menu" href="#menu"><i class="fal fa-bars"></i><span> Menu</span></a>
        <?php if(!empty($mobile_category_icon)) { ?>
            <a class="navigation__item ps-toggle--sidebar js_btn_panel__sidebar" href="#menu-category"><i class="fad fa-th-list"></i> Danh mục</span></a>
        <?php } else { ?>
            <a class="navigation__item ps-toggle--sidebar" href="tel:<?php echo Option::get('contact_phone');?>"><i class="fal fa-phone-alt"></i> Liên hệ</span></a>
        <?php } ?>
        <a class="navigation__item ps-toggle--sidebar js_btn_panel__sidebar" href="#search-sidebar"><i class="fal fa-search"></i> <span>Tìm kiếm</span></a>
        <a class="navigation__item ps-toggle--sidebar js_btn_panel__sidebar navigation__item_cart" href="#cart-sidebar">
            <span class="wcmc-total-items"><?php echo Scart::totalQty();?></span>
            <img src="https://img.icons8.com/wired/344/shopping-basket-2.png" alt=""> <span>Cart</span>
        </a>
    </div>
</div>
<?php } ?>
<?php if(Template::isPage('products_detail') && have_posts($object)) {?>
<div class="navigation-mobile--cart">
    <div class="navigation__content">
        <button class="btn btn-effect-default btn-red button_cart product_add_to_cart" data-id="<?php echo $object->id;?>">
            <span class="button_cart__heading"><?php echo apply_filters('button_add_to_cart_text',__('Thêm vào giỏ', 'cart_add_to_cart'));?></span>
        </button>
        <button class="btn btn-effect-default btn-green button_cart_now product_add_to_cart_now" data-id="<?php echo $object->id;?>">
            <span class="button_cart__heading"><?php echo apply_filters('button_add_to_cart_now_text', __('Mua Ngay', 'cart_add_to_cart_now'));?></span>
        </button>
    </div>
</div>
<script>
    $(function () {
        let cart = $('.product-detail-cart');
        cart = cart.position().top + 300;
        $(window).scroll(function() {
            if ($(this).scrollTop() > cart) {
                $('.navigation-mobile--cart').addClass('navigation-show');
                $('.navigation-mobile--list').addClass('navigation-hidden');
            } else {
                $('.navigation-mobile--cart').removeClass('navigation-show');
                $('.navigation-mobile--list').removeClass('navigation-hidden');
            }
        });
    });
</script>
<?php } ?>
