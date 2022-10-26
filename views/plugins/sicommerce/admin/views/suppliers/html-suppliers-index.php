<div class="ui-layout">
    <div class="col-md-12">
        <div class="ui-title-bar__group">
            <h1 class="ui-title-bar__title">Nhà sản xuất</h1>
            <div class="ui-title-bar__action">
                <?php do_action('admin_suppliers_action_bar_heading');?>
            </div>
        </div>

        <div class="box">
            <!-- .box-content -->
            <div class="box-content">
                <div class="box-heading"><?php $table->display_search();?></div>
                <form method="post" id="form-action" class="table-responsive">
                    <?php $table->display();?>
                </form>
            </div>
            <!-- /.box-content -->
        </div>
        <!-- paging -->
        <div class="paging">
            <div class="pull-right"><?= (isset($pagination))?$pagination->html():'';?></div>
        </div>
        <!-- paging -->
    </div>
</div>
