<form method="post" id="form-input" data-module="<?php echo $this->template->class;?>">
    <?php Admin::partial('include/action_bar');?>
	<?php Admin::loading('ajax_loader');?>
	<?php if(isset($object->title)) {?>
	<div class="col-md-12">
		<div class="ui-title-bar__group">
			<h1 class="ui-title-bar__title"><?php echo $object->title;?></h1>
			<div class="ui-title-bar__action">
				<?php do_action('admin_product_save_action_bar_heading');?>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php Admin::partial('include/form/form');?>
</form>