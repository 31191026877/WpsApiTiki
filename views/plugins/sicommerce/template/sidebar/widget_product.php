<div class="widget widget_product">
    <div class="sidebar-title"><?php echo ThemeSidebar::heading($heading);?></div>
    <div class="sidebar-content box-content product-slider-vertical">
        <?php foreach ($products as $key => $val): ?>
            <?php Prd::template('loop/item_product_vertical', ['val' => $val]);?>
        <?php endforeach ?>
    </div>
</div>