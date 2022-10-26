<?php Admin::partial('include/action_bar');?>
<?php if(have_posts($objects) || Request::get('keyword') != null || Request::get('status') == 'trash' || $trash != 0) {?>
<div class="col-md-12">
    <div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title">Trang nội dung</h1>
        <div class="ui-title-bar__action">
            <?php do_action('admin_page_action_bar_heading');?>
        </div>
    </div>
    <div class="box" style="overflow: inherit;">
        <div class="box-heading"><?php $table->display_search();?></div>
        <div class="box-content">
            <form method="post" id="form-action"><?php $table->display();?></form>
        </div>
    </div>
    <div class="paging"><div class="pull-right"><?php echo (isset($pagination)) ? $pagination->html() : '';?></div></div>
</div>
<?php } else if(Request::get('status') != 'trash' ) {?>
<div class="col-md-5 box-empty">
    <h2>Thêm trang vào website của bạn</h2>
    <h4>Viết tiêu đề và mô tả trang rõ ràng để cải thiện tối ưu hóa công cụ tìm kiếm (SEO) của bạn và giúp khách hàng tìm thấy trang web của bạn.</h4>
    <a href="<?php echo Url::admin('page/add');?>" class="btn-icon btn-green"><?php echo Admin::icon('add');?> Thêm Mới</a>
</div>
<div class="col-md-7"><?php Admin::imgTemplate('empty-page.svg');?></div>
<style>
    .box-empty { margin-top: 50px; }
    .box-empty h2 {
        font-size: 30px;
        font-weight: bold;
    }
    .box-empty h4 {
        font-size: 18px;
        line-height: 2.8rem;
        font-weight: 400;
        color: #637381;
    }
    .page-content .action-bar { display: none; }
</style>
<?php }?>
