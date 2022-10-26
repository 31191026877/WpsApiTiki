<?php /**
Layout-name: Template Full Width
*/?>
<?php $layout = get_theme_layout();?>
<!DOCTYPE html>
<html lang="<?= Language::current();?>" <?php do_action('in_tag_html');?>>
	<?php Template::partial('include/head'); ?>
	<body class="" <?php do_action('in_tag_body');?> style="height: auto">
        <?php Template::partial('include/mobile-header'); ?>
		<div id="td-outer-wrap">
			<div class="wrapper">
                <?php Template::partial('include/top'); ?>
                <div class="wrapper-before">
                    <?php do_action('template_wrapper_before');?>
                    <?php do_action('template_'.Template::getPage().'_before');?>
                </div>
				<div class="container wrapper-container"><?php $this->template->render_view(); ?></div>
                <?php do_action('template_wrapper_after');?>
                <?php do_action('template_'.Template::getPage().'_after');?>
			</div>
			<?php Template::partial('include/footer'); ?>
		</div>
	</body>
</html>