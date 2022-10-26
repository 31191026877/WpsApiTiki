<?php Admin::partial('include/action_bar');?>
<div class="col-md-12">
    <div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title">Danh sách thành viên</h1>
        <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">Thông tin email, số điện thoại, địa chỉ...</p>
        <div class="ui-title-bar__action">
            <?php do_action('admin_user_action_bar_heading');?>
        </div>
    </div>
	<div class="box">
        <div class="box-content">
            <div class="box-heading"><?php $table_list->display_search();?></div>
            <form method="post" id="form-action">
                <?php $table_list->display();?>
            </form>
        </div>
	</div>
    <!-- paging -->
    <div class="paging">
        <div class="pull-right"><?= (isset($pagination))?$pagination->html():'';?></div>
    </div>
    <!-- paging -->
</div>

<?php if(Admin::isRoot()) {?>
<!-- Modal -->
<div class="modal fade" id="modalreset" tabindex="-1" role="dialog">
  	<div class="modal-dialog" role="document">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title" id="myModalLabel">Đổi Mật Khẩu</h4>
	      	</div>
	      	<form id="form-check-pass">
		        <div class="modal-body">
		          	<div class="form-group">
		            	<label for="">Mật Khẩu <b style="color:red">root</b></label>
		            	<input name="password" type="password" class="form-control" placeholder="password" required>
		          	</div>
		        </div>
		        <div class="modal-footer">
		          	<button type="button" class="btn btn-default" data-bs-dismiss="modal">Hủy</button>
		          	<button type="submit" class="btn btn-primary">Lưu</button>
		        </div>
	      	</form>
	    </div>
  	</div>
</div>
<?php } ?>