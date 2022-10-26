<?php Admin::partial('include/action_bar');?>
<?php if(have_posts($objects) || Request::get('category') != null || Request::get('status') == 'trash' || $trash != 0) {?>
<div class="col-md-12">
    <div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title">Danh sách sản phẩm</h1>
        <div class="ui-title-bar__action">
            <?php do_action('admin_product_action_bar_heading');?>
        </div>
    </div>

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
        <div class="pull-left" style="padding-top:20px;">Hiển thị <?php echo count((array)$objects);?> trên tổng số <?php echo $total;?> kết quả</div>
        <div class="pull-right"><?php echo (isset($pagination))?$pagination->html():'';?></div>
    </div>
    <!-- paging -->
</div>
<?php } else {?>
<div class="col-md-5 box-empty">
  <h2>Thêm sản phẩm của bạn</h2>
  <h4>Tiếp cận gần hơn với lần bán hàng đầu tiên của bạn bằng cách thêm sản phẩm hoặc nhập khoảng không quảng cáo sản phẩm hiện có của bạn.</h4>
  <a href="admin/products/add" class="btn-icon btn-green hvr-sweep-to-right"><i class="fa fa-plus-circle"></i>Thêm Mới</a>
</div>
<div class="col-md-7"><img src="https://cdn.shopify.com/s/assets/admin/empty-states-fresh/emptystate-products-fa2065ec7520b72d7a7572b5ce4bab1115bc90a4a6d0b609fd68ab67ad823c65.svg" alt="Emptystate pages"></div>
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
</style>
<?php }?>