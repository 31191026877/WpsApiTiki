<div class="row m-1">
    <div class="col-md-2 widget-service-kho-sidebar">
        <div class="headet-title">
            <h3>Widget Studio</h3>
            <p>Pre-designed layouts</p>
        </div>
        <ul class="sb-categories">
            <li class="sb-cat active"><a href="#" class="widget-cat-item" data-id="0" >Tất Cả Widget</a></li>
            <?php foreach ($categories as $category): ?>
            <li class="sb-cat"><a href="#" class="widget-cat-item" data-id="<?php echo $category->id;?>"><?php echo $category->name;?></a></li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="col-md-10 widget-service-kho-list scrollbar">
        <div class="row" id="widget-service-kho-list__item">
            <?php foreach ($widgets as $item): ?>
                <?php Admin::partial('include/ajax-page/widget_service_item', array( 'item' => $item ) );?>
            <?php endforeach ?>
        </div>
    </div>
</div>