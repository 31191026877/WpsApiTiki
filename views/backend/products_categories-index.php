<?php Admin::partial('include/action_bar');?>
<div class="row">
    <?php if( Auth::hasCap('product_cate_edit') ) { ?>
    <div class="col-md-5">
        <div class="box">
            <form method="post" id="form-input-category" data-module="<?php echo Template::getClass();?>" class="form-categories" class="table-responsive" autocomplete="off">
                <?php Admin::loading('ajax_loader');?>
                <div class="box-content" style="padding:10px;">
                    <?php Admin::partial('include/form/form');?>
                    <div class="col-md-12"><button type="submit" name="save" class="btn-icon btn-green"><?php echo Admin::icon('save');?> Lưu</button></div>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>

    <div class="col-md-7">
        <div class="box">
            <div class="box-content">
                <div class="box-heading"><?php $table->display_search();?></div>
                <form method="post" id="form-action"><?php $table->display();?></form>
            </div>
        </div>
    </div>
</div>
<style>
    .page-content .box .box-content.collapse { display: block; padding:0; }
</style>