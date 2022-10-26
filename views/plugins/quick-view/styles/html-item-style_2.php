<?php
if(Language::default() != Language::current()) {
    $config['txt_quickview'] = (!empty($config['txt_quickview_'.Language::current()])) ? $config['txt_quickview_'.Language::current()] : $config['txt_quickview'];
    $config['txt_view'] = (!empty($config['txt_view_'.Language::current()])) ? $config['txt_view_'.Language::current()] : $config['txt_view'];
    $config['txt_buy'] = (!empty($config['txt_buy_'.Language::current()])) ? $config['txt_buy_'.Language::current()] : $config['txt_buy'];
}
?>
<div class="box_quickview box_quickview_style_2">
    <a href="#" class="btn-quickview" data-id="<?php echo $item->id;?>">
        <span class="button_quickview"><?php echo __(html_entity_decode($config['txt_quickview']));?></span>
    </a>
    <?php if(Product::count(['where' => array('parent_id' => $item->id, 'type' => 'variations')]) != 0) { ?>
        <a href="<?php echo Url::permalink($item->slug);?>" class="btn-view" data-id="<?php echo $item->id;?>">
            <span class="button_view"><?php echo __(html_entity_decode($config['txt_view']));?></span>
        </a>
    <?php } else { ?>
        <a href="<?php echo Url::permalink($item->slug);?>" class="btn-view wcmc_add_to_cart" data-id="<?php echo $item->id;?>">
            <span class="button_view"><?php echo __(html_entity_decode($config['txt_buy']));?></span>
        </a>
    <?php } ?>
</div>