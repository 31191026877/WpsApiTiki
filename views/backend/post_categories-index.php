<?php Admin::partial('include/action_bar');?>
<div class="row">
    <div class="col-md-5">
        <div class="box">
            <form method="post" id="form-input-category" data-module="<?= $this->template->class;?>" class="table-responsive">
                <?php Admin::loading('ajax_loader');?>
                <div class="box-content" style="padding:10px;">
                    <?php Admin::partial('include/form/form');?>
                    <div class="m-1">
                        <button type="submit" name="save" class="btn-icon btn-green"><?php echo Admin::icon('save');?>Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="box">
            <div class="box-content">
                <div class="box-heading"><?php $table->display_search();?></div>
                <form method="post" id="form-action"><?php $table->display();?></form>
            </div>
        </div>
    </div>
</div>