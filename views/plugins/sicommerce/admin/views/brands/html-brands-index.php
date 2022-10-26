<div class="ui-layout">
    <div class="col-md-12">
        <div class="ui-title-bar__group">
            <h1 class="ui-title-bar__title">Thương hiệu</h1>
            <div class="ui-title-bar__action">
                <?php do_action('admin_brands_action_bar_heading');?>
            </div>
        </div>
        <div class="box" style="overflow:inherit;">
            <div class="box-heading"><?php $table->display_search();?></div>
            <!-- .box-content -->
            <div class="box-content">
                <form method="post" id="form-action"><?php $table->display();?></form>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- paging -->
        <div class="paging">
            <div class="pull-right"><?php echo (isset($pagination)) ? $pagination->html() : '';?></div>
        </div>
        <!-- paging -->
    </div>
</div>