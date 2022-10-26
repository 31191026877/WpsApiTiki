<?php $layout = get_theme_layout();?>
<!DOCTYPE html>
<html lang="<?= Language::current();?>" <?php do_action('in_tag_html');?>>
    <?php $this->template->render_include('head'); ?>
    <body class="" <?php do_action('in_tag_body');?> style="height: auto">
        <?php $this->template->render_include('mobile-search'); ?>
        <div id="td-outer-wrap">
            <?php $this->template->render_include('top'); ?>
            <div class="wrapper">
                <?php $this->template->render_include('banner');?>
                <div class="container">
                    <?php $this->template->render_view(); ?>
                </div>
            </div>
            <?php $this->template->render_include('footer'); ?>
        </div>
    </body>
</html>