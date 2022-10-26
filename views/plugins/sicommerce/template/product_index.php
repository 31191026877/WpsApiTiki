<form method="get" id="js_product_index_form__load">
    <div class="page-product-index">
        <div class="product-slider-horizontal" style="margin-top: 10px; position: relative; min-height: 200px">
            <?php echo Admin::loading();?>
            <?php
            /**
             * page_products_index_view hook.
             *
             * @hooked page_products_index_list_product - 10 - hiển thị danh sách sản phẩm
             * @hooked page_products_index_pagination - 20 - hiển thị phân trang
             */
            do_action('page_products_index_view');
            ?>
        </div>
    </div>
</form>