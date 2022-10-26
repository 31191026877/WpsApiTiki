<?php if(have_posts($meta_options)) {?>
    <form class="product_options_form__box" id="product_options_form__box" data-id="<?php echo $object->id;?>"
          data-product-options="<?php echo htmlspecialchars(json_encode($variations_options));?>"
          data-product-variations="<?php echo htmlspecialchars(json_encode($variations));?>">
        <?php foreach ($meta_options as $key => $option):
            product_detail_variations($option, $product_default);
        endforeach; ?>
    </form>
<?php } ?>