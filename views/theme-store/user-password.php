<form class="form-horizontal col-md-7" method="post">
	<h1 class="user-header-title">Đổi mật khẩu</h1>
	<?php echo form_open();?>

    <?php echo $this->template->get_message();?>
    <div class="row">
        <div class="form-group">
            <label class="col-sm-4 control-label">Mật khẩu cũ</label>
            <div class="col-sm-8">
                <input name="old_password" type="password" class="form-control" placeholder="Nhập mật khẩu cũ">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">Mật khẩu mới</label>
            <div class="col-sm-8">
                <input name="new_password" type="password" class="form-control" placeholder="Mật khẩu từ 6 đến 32 ký tự">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label">Nhập lại</label>
            <div class="col-sm-8">
                <input name="re_new_password" type="password" class="form-control" placeholder="Nhập lại mật khẩu mới">
            </div>
        </div>


    </div>
    <div class="form-group">
        <div class="">
            <button type="submit" class="btn btn-theme btn-effect-default">Cập Nhật</button>
        </div>
    </div>
</form>


