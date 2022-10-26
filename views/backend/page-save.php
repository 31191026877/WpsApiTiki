<form method="post" id="form-input" data-module="<?= $this->template->class;?>">
    <?php Admin::partial('include/action_bar');?>
	<?php do_action( 'before_page_save' );?>
	<?php Admin::loading('ajax_loader');?>
	<?php if(isset($object->title)) {?>
	<div class="col-md-12">
		<div class="ui-title-bar__group">
			<div class="ui-title-bar__action">
				<?php do_action('admin_page_save_action_bar_heading');?>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php Admin::partial('include/form/form');?>
	<?php do_action('after_page_save');?>
</form>