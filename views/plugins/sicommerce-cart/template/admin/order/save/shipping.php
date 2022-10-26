<?php
/**
 * @since 2.6.0 - New File
 */
$cart_shipping 			= shipping_gateways();
$cart_shipping_default 	= Option::get('cart_shipping_default', key($cart_shipping));
if(have_posts($cart_shipping)) {
?>
<div class="box" id="order_save_shipping">
    <div class="box-content">
        <section class="ui-layout__section"><header class="ui-layout__title"><h2>Hình thức vận chuyển</h2></header></section>
        <div class="clearfix"> </div>
        <!-- tìm kiếm sản phẩm -->
        <section class="ui-layout__section">
            <?php if( have_posts($cart_shipping) ) {
                foreach ($cart_shipping as $key => $shipping) {
                    if( $shipping['enabled'] == 1 ) {
                        echo '<div class="radio"><label>';
                        echo '<input type="radio" name="shipping_type" id="_shipping_'.$key.'" value="'.$key.'" '.(($key == $cart_shipping_default)?'checked':'').'>';
                        echo $shipping['label'];
                        echo '</label></div>';
                        do_action('admin_order_shipping_'.$key.'_view', $shipping );
                    }
                }
            } ?>

            <div class="form-group">
                <label for=""></label>
                <input type="text" class="form-control" name="_shipping_price" placeholder="Phí shipping...">
            </div>
        </section>
        <div class="clearfix"> </div>
    </div>
</div>
<?php }