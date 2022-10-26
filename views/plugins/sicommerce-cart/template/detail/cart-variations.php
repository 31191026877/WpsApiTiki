<div class="product-cart-options">
    <div class="title"> <?php echo $option['title'];?>: <span class="option-type__label option-type__selected-option"></span></div>
    <div class="options">
        <?php
        foreach ($option['items'] as $key => $attribute):
            $data = array('option' => $option, 'attribute' => $attribute, 'default' => $default );
            if($option['option_type'] == 'label')  cart_template('detail/cart-option-item-label', $data);
            if($option['option_type'] == 'color')  cart_template('detail/cart-option-item-color', $data);
            if($option['option_type'] == 'image')  cart_template('detail/cart-option-item-image', $data);
        endforeach;
        ?>
    </div>
</div>