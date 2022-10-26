<?php
$tabs = Admin_Cart_Setting::tabs();
reset($tabs);
$current_tab = (Request::get('tab') != '')?Request::get('tab'):key($tabs); ?>
<form id="mainform" method="post">
	<?php echo form_open();?>
	<div class="action-bar">
	    <div class="pull-right">
	        <button type="submit" class="btn-icon btn-green"><i class="fas fa-save"></i>Lưu</button>
	    </div>     
	</div>
	<div class="ui-layout">
        <?php echo Admin::loading('ajax_item_save_loader');?>
		<div class="col-md-12">
			<div class="ui-title-bar__group" style="padding-bottom:5px;">
				<h1 class="ui-title-bar__title">Cấu hình đơn hàng - <?php echo $tabs[$current_tab]['label'];?></h1>
				<div class="ui-title-bar__action">
					<?php foreach ($tabs as $key => $tab): ?>
					<a href="<?php echo Url::admin(sicommerce_cart::url('setting').'&tab='.$key);?>" class="<?php echo ($key == $current_tab)?'active':'';?> btn btn-default">
						<?php echo (isset($tab['icon'])) ? $tab['icon'] : '<i class="fal fa-layer-plus"></i>';?>
						<?php echo $tab['label'];?>
					</a>
					<?php endforeach ?>
				</div>
			</div>
		</div>

		<?php $ci->template->get_message();?>
		<div class="col-md-12">
			<div role="tabpanel">
				<!-- Tab panes -->
				<div class="tab-content" style="padding-top: 10px;">
					<?php call_user_func( $tabs[$current_tab]['callback'], $ci, $current_tab ) ?>
				</div>
			</div>
		</div>

	</div>
</form>