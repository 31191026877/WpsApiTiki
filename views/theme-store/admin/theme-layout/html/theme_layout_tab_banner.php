<?php
    $banner     = get_theme_layout_setting('banner');
?>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>BANNER HEIGHT</h2></div>
        <div class="box-content m-2">
            <?php  $input = array('field' => 'banner[height]', 'type'	=> 'number', 'label' => 'Chiều cao banner (px)'); ?>
			<?php echo _form($input, $banner['height']);?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>BANNER TYLE</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="page-banner-type-left">
                            <div class="name">
                                <input type="radio" value="left" name="banner[type]" id="page-banner-type-left" <?php echo ($banner['type'] == 'left') ? 'checked' : '';?>>
                                <span>BANNER LEFT</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="page-banner-type-center">
                            <div class="name">
                                <input type="radio" value="center" name="banner[type]" id="page-banner-type-center" <?php echo ($banner['type'] == 'center') ? 'checked' : '';?>>
                                <span>BANNER CENTER</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="page-banner-type-right">
                            <div class="name">
                                <input type="radio" value="right" name="banner[type]" id="page-banner-type-right" <?php echo ($banner['type'] == 'right') ? 'checked' : '';?>>
                                <span>BANNER RIGHT</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>BANNER PAGE</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="page-banner-in-container">
                            <div class="name">
                                <input type="radio" value="in-container" name="banner[page]" id="page-banner-in-container" <?php echo ($banner['page'] == 'in-container') ? 'checked' : '';?>>
                                <span>CONTAINER BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="page-banner-full-width">
                            <div class="name">
                                <input type="radio" value="full-width" name="banner[page]" id="page-banner-full-width" <?php echo ($banner['page'] == 'full-width') ? 'checked' : '';?>>
                                <span>FULL WIDTH BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>BANNER POST CATEGORY</h2></div>
        <div class="box-content">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="post_category-banner-in-container">
                            <div class="name">
                                <input type="radio" value="in-container" name="banner[post_category]" id="post_category-banner-in-container" <?php echo ($banner['post_category'] == 'in-container') ? 'checked' : '';?>>
                                <span>CONTAINER BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="post_category-banner-full-width">
                            <div class="name">
                                <input type="radio" value="full-width" name="banner[post_category]" id="post_category-banner-full-width" <?php echo ($banner['post_category'] == 'full-width') ? 'checked' : '';?>>
                                <span>FULL WIDTH BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>BANNER POST</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="post-banner-in-container">
                            <div class="name">
                                <input type="radio" value="in-container" name="banner[post]" id="post-banner-in-container" <?php echo ($banner['post'] == 'in-container') ? 'checked' : '';?>>
                                <span>CONTAINER BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="post-banner-full-width">
                            <div class="name">
                                <input type="radio" value="full-width" name="banner[post]" id="post-banner-full-width" <?php echo ($banner['post'] == 'full-width') ? 'checked' : '';?>>
                                <span>FULL WIDTH BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<?php if(class_exists('sicommerce')) {?>
<div class="col-md-12">
    <div class="box">
        <div class="header"><h2>BANNER PRODUCT CATGORY</h2></div>
        <div class="box-content m-2">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="products_category-banner-in-container">
                            <div class="name">
                                <input type="radio" value="in-container" name="banner[products_category]" id="products_category-banner-in-container" <?php echo ($banner['products_category'] == 'in-container') ? 'checked' : '';?>>
                                <span>CONTAINER BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="layout-item">
                        <label for="products_category-banner-full-width">
                            <div class="name">
                                <input type="radio" value="full-width" name="banner[products_category]" id="products_category-banner-full-width" <?php echo ($banner['products_category'] == 'full-width') ? 'checked' : '';?>>
                                <span>FULL WIDTH BANNER</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php } ?>