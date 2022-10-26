<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>PRODUCTS</h2></div>
        <div class="box-content">
            <div class="col-md-6 col-lg-6">
                <div class="layout-item">
                    <label for="layout-products-1">
                        <div class="img"></div>
                        <div class="name">
                            <input type="radio" value="layout-products-1" name="layout[products-layout]" id="layout-products-1" <?php echo ($layout_products == 'layout-products-1') ? 'checked' : '';?>>
                            <span>Layout 1</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <div class="layout-item">
                    <label for="layout-products-2">
                        <div class="img"></div>
                        <div class="name">
                            <input type="radio" value="layout-products-2" name="layout[products-layout]" id="layout-products-2" <?php echo ($layout_products == 'layout-products-2') ? 'checked' : '';?>>
                            <span>Layout 2</span>
                        </div>
                    </label>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>