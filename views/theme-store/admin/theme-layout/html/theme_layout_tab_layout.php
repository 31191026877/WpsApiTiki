<?php
    $layout_list                = theme_layout_list();
    $layout_home                = option::get('layout_home',              'layout-home-1');
    $layout_page                = option::get('layout_page',              'layout-full-width-banner');
    $layout_post                = option::get('layout_post',              'layout-sidebar-right-banner-2');
    $layout_post_category       = option::get('layout_post_category',     'layout-sidebar-right-banner-2');
    $layout_products_category   = option::get('layout_products_category', 'layout-sidebar-right-banner-2');
    $layout_products            = option::get('layout_products',          'layout-products-1');
?>
<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>Home Layout</h2></div>
        <div class="m-2">
            <div class="box-content row">
                <?php for($i = 1; $i <= 3; $i++) { ?>
                <div class="col-md-3 col-lg-2">
                    <div class="layout-item">
                        <label for="home-layout-<?php echo $i;?>">
                            <div class="img"><?php Template::img('layout/home-layout-'.$i.'.png');?></div>
                            <div class="name">
                                <input type="radio" value="layout-home-<?php echo $i;?>" name="layout[home-layout]" id="home-layout-<?php echo $i;?>" <?php echo ($layout_home == 'layout-home-'.$i) ? 'checked' : '';?>>
                                <span>Home <?php echo $i;?></span>
                            </div>
                        </label>
                    </div>
                </div>
                <?php } ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>Page Layout</h2></div>
        <div class="box-content m-2">
            <div class="row">
            <?php foreach ($layout_list as $layout_key => $layout_value) {
                $layout_type = 'page';
                $layout_active = $layout_page;
                include 'theme_layout_item.php';
            } ?>
            <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>Post Category Layout</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <?php foreach ($layout_list as $layout_key => $layout_value) {
                    $layout_type = 'post-category';
                    $layout_active = $layout_post_category;
                    include 'theme_layout_item.php';
                } ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>Post Layout</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <?php foreach ($layout_list as $layout_key => $layout_value) {
                    $layout_type = 'post';
                    $layout_active = $layout_post;
                    include 'theme_layout_item.php';
                } ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<?php if(class_exists('sicommerce')) {?>
<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>PRODUCTS CATEGORY</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <?php foreach ($layout_list as $layout_key => $layout_value) {
                    $layout_type = 'products-category';
                    $layout_active = $layout_products_category;
                    include 'theme_layout_item.php';
                } ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>PRODUCTS</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <div class="col-md-2 col-lg-1">
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
                <div class="col-md-2 col-lg-1">
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
                <div class="col-md-2 col-lg-1">
                    <div class="layout-item">
                        <label for="layout-products-3">
                            <div class="img"></div>
                            <div class="name">
                                <input type="radio" value="layout-products-3" name="layout[products-layout]" id="layout-products-3" <?php echo ($layout_products == 'layout-products-3') ? 'checked' : '';?>>
                                <span>Layout 3</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

