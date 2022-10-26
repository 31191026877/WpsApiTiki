<!DOCTYPE html>
<html lang="<?= Language::current();?>" <?php do_action('in_tag_html');?>>
	<?php Template::partial('include/head'); ?>
	<body <?php do_action('in_tag_body');?> style="height: auto">
		<?php Template::partial('include/mobile-header'); ?>
		<div id="td-outer-wrap">
			<div class="wrapper">
                <?php Template::partial('include/top'); ?>
				<?php Template::partial('include/banner'); ?>
				<?php $this->template->render_view(); ?>
			</div>
			<?php Template::partial('include/footer'); ?>
		</div>
	</body>
</html>