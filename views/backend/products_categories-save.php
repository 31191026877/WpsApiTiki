<form method="post" id="form-input" data-module="<?= $this->template->class;?>">
    <?php Admin::partial('include/action_bar');?>
	<div id="ajax_loader" class="ajax-load-qa">&nbsp;</div>
	<?php if(isset($object->name)) {?>
	<div class="col-md-12">
		<div class="ui-title-bar__group">
			<h1 class="ui-title-bar__title"><?php echo $object->name;?></h1>
			<div class="ui-title-bar__action">
				<?php do_action('admin_product_category_save_action_bar_heading');?>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php Admin::partial('include/form/form');?>
</form>