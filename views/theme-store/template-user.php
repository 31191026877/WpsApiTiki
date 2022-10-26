<!DOCTYPE html>
<html lang="<?= Language::current();?>" <?php do_action('in_tag_html');?>>
    <?php Template::partial('include/head');?>
	<body <?php do_action('in_tag_body');?> style="height: auto">
        <?php Template::partial('include/mobile-header');?>
		<div id="td-outer-wrap">
			<div class="wrapper">
                <?php Template::partial('include/top');?>
				<div class="container">
					<?php if(!Auth::check()) {?>
						<div class="user-signing">
							<?php $this->template->render_view(); ?>
						</div>
					<?php } else { ?>
						<div class="user-profile">
							<div class="user-header">
								<div class="col-sm-4 col-md-3"></div>
								<div class="col-sm-8 col-md-9" style="padding-left: 0;"></div>
							</div>
							<div class="user-action col-sm-4 col-md-3">
                                <?php Template::partial('include/user-action');?>
							</div>
							<div class="user-content col-sm-8 col-md-9">
								<?php $this->template->render_view(); ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
            <?php Template::partial('include/footer');?>
		</div>
	</body>
</html>