<form method="post" id="form-input" data-module="<?= $this->template->class;?>">
    <?php Admin::partial('include/action_bar');?>
	<?php Admin::loading('ajax_loader');?>

	<?php if(isset($object->name)) {?>
	<div class="col-md-12">
		<div class="ui-title-bar__group"><div class="ui-title-bar__action"><?php do_action('admin_post_category_save_'.$object->cate_type.'_action_bar_heading');?></div></div>
	</div>
	<?php } ?>

	<?php Admin::partial('include/form/form');?>
</form>