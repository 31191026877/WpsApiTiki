<div class="box product-slider-horizontal products-<?php echo (!empty($id)) ? $id : '';?>">
    <div class="header-title"><h3 class="header"><?php echo $heading;?></h3></div>
    <div class="box-content" style="overflow: inherit">
        <?php if($style == 'slider') Prd::template('detail/widget_product_content_slider', ['products' => $products, 'columns' => $columns, 'id' => (!empty($id)) ? $id : time()]); ?>
        <?php if($style == 'grid') Prd::template('detail/widget_product_content_grid', ['products' => $products, 'columns' => $columns, 'id' => (!empty($id)) ? $id : time()]); ?>
    </div>
</div>
