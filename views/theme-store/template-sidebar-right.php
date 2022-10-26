<?php /**
Layout-name: Template slider right
*/?>
<?php $layout = get_theme_layout(); ?>
<!DOCTYPE html>
<html lang="<?= Language::current();?>" <?php do_action('in_tag_html');?>>
	<?php Template::partial('include/head'); ?>
	<body <?php do_action('in_tag_body');?>>
		<?php Template::partial('include/mobile-header'); ?>
		<div id="td-outer-wrap">
			<div class="wrapper wrapper-<?php echo $this->template->class;?>">
                <?php Template::partial('include/top'); ?>
                <div class="wrapper-before">
                    <?php do_action('template_wrapper_before');?>
                    <?php do_action('template_'.Template::getPage().'_before');?>
                </div>
				<div class="container wrapper-container">
					<div class="row">
						<div class="col-12 col-lg-9"><?php $this->template->render_view(); ?></div>
						<div class="col-12 col-lg-3 sidebar"><?php Template::partial('include/sidebar');?></div>
					</div>
				</div>
                <?php do_action('template_wrapper_after');?>
                <?php do_action('template_'.Template::getPage().'_after');?>
			</div>
			<?php Template::partial('include/footer'); ?>
		</div>
	</body>
</html>