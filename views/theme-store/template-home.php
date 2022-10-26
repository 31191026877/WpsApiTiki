<?php /**
Layout-name: Template Trang Chủ
*/?>
<!DOCTYPE html>
<html lang="<?= Language::current();?>" <?php do_action('in_tag_html');?>>
    <?php Template::partial('include/head');?>
	<body class="" <?php do_action('in_tag_body');?> style="height: auto">
        <?php Template::partial('include/mobile-header');?>
		<div id="td-outer-wrap">
			<div class="wrapper">
                <?php Template::partial('include/top');?>
                <?php
                $layout_home = Option::get('layout_home', 'layout-home-1');
                if($layout_home == 'layout-home-3') {
                    Template::partial('home-layout-3');
                }
                else {
                    $this->template->render_view();
                } ?>
			</div>
            <?php Template::partial('include/footer');?>
		</div>
	</body>
</html>