<div class="col-md-12">
  	<div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title">Đơn hàng</h1>
        <div class="ui-title-bar__action">
            <?php do_action('admin_order_action_bar_heading');?>
        </div>
    </div>
</div>

<div class="col-md-12">
	<div class="box">
		<?php $table_list->display();?>
        <!-- paging -->
        <div class="paging">
            <div class="pull-right"><?= (isset($pagination)&&is_object($pagination))?$pagination->html():'';?></div>
        </div>
        <!-- paging -->
	</div>
</div>

<style>
    .page-content .action-bar { height:auto;}
    .action-bar .form-group { margin-right:10px; }
    .action-bar .pull-left { width:100%; }
    table tr.wc-cancelled td { color:red; }
    table tr.wc-cancelled td.column-total { text-decoration: line-through; }
</style>