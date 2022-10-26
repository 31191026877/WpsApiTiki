<div class="row">
    <div class="col-md-9">
        <?php $this->template->render_view(); ?>
    </div>
    <div class="col-md-3 sidebar">
        <?php Sidebar::render('sidebar-main');?>
    </div>
</div>