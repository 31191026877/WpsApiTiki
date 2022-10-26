<form action="" method="post" id="js_user_form__password">
    <?php echo Admin::loading();?>
    <input type="hidden" name="id" value="<?php echo $user->id;?>">
    <div class="box">
        <div class="box-content">
            <div class="row m-1">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Mật khẩu mới</label>
                    <div class="col-sm-9">
                        <input name="new_password" type="password" class="form-control" placeholder="Mật khẩu mới">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Xác nhận mật khẩu</label>
                    <div class="col-sm-9">
                        <input name="re_new_password" type="password" class="form-control" placeholder="Xác nhận mật khẩu">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn-icon btn-green"><?php echo Admin::icon('save');?>Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>