<?php Admin::partial('include/action_bar');?>
<?php if(have_posts($objects) || Request::get('category') != null ||Request::get('keyword') != null || Request::get('status') == 'trash' || $trash != 0) {?>
<div class="col-md-12">
    <div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title"><?= $this->post['labels']['name'];?></h1>
        <div class="ui-title-bar__action"><?php do_action('admin_post_'.$this->postType.'_action_bar_heading');?></div>
    </div>
    <!-- paging -->
    <div class="box" style="overflow:inherit;">
        <div class="box-heading"><?php $table->display_search();?></div>
        <!-- .box-content -->
        <div class="box-content">
            <form method="post" id="form-action"><?php $table->display();?></form>
        </div>
        <!-- /.box-content -->
    </div>
    <!-- paging -->
    <div class="paging">
        <div class="pull-left" style="padding-top:20px;">Hiển thị <?= count((array)$objects);?> trên tổng số <?= $total;?> kết quả</div>
        <div class="pull-right"><?php echo (isset($pagination)) ? $pagination->html() : '';?></div>
    </div>
    <!-- paging -->
</div>
<?php } else {?>
<div class="col-md-5 box-empty">
    <?php
        if(!empty($this->post['capabilities']['add']) && Auth::hasCap($this->post['capabilities']['add'])) {
    ?>
        <h2>Thêm một <?php echo $this->post['labels']['singular_name'];?> ngay bây giờ</h2>
        <h4>Bài viết trên blog là một cách tuyệt vời để xây dựng một cộng đồng xung quanh các sản phẩm và thương hiệu của bạn.</h4>
        <a href="<?php echo Url::admin('post/add?post_type='.$this->postType);?>" class="btn-icon btn-green"><?php echo Admin::icon('add');?> Thêm Mới</a>
    <?php } else { ?>
        <h2><?php echo $this->post['labels']['singular_name'];?> đang trống</h2>
    <?php } ?>
</div>
<div class="col-md-7"><?php Admin::imgTemplate('empty-post.svg');?></div>
<style type="text/css">
  	.box-empty {
    	margin-top: 50px;
  	}
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