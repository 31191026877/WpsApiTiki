<!DOCTYPE html>
<html lang="vi">
    <?php Admin::partial('include/head'); ?>
    <body>
        <div class="wrapper">
            <?php Admin::partial('include/navigation'); ?>
            <div class="page-content">
                <div class="mobile-nav">
                    <div class="pull-left"><a href="" class="menu-i"><i class="fa fa-bars" aria-hidden="true"></i></a></div>
                </div>
                <div class="page-body">
                    <div class="ui-layout">
                    <?php $this->template->render_view(); ?>
                    </div>
                </div>
            </div>
        </div><!-- container -->
        <!-- footer -->
        <?php Admin::partial('include/footer'); ?>
        <!--/footer -->
    </body>
</html>
