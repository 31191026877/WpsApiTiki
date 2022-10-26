
<?php
    $cms_status         = Option::get('cms_status', 'public');
    $cms_password       = Option::get('cms_password', '');
    $cms_close_title    = Option::get('cms_close_title', '');
    $cms_close_content  = Option::get('cms_close_content', '');
?>
<div class="row">
    <div class="col-md-3">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h3 class="ui-title-bar__title" style="font-size:20px;">Trạng thái hệ thống</h3>
            <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">
                Thông tin đóng mở website giúp quá trình bảo trì bảo dưỡng website
            </p>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-content" style="padding:10px;">
                <div class="form-group row" style="overflow:hidden;">
                    <label for="input" class="control-label col-md-3">Trạng thái website:</label>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <div class="checkbox">
                            <label style="padding-left:0;"> <input type="radio" name="cms_status" value="public" class="icheck" <?php echo ($cms_status == 'public')?'checked':'';?>> Công khai </label>
                        </div>
                        <div class="checkbox">
                            <label style="padding-left:0;"> <input type="radio" name="cms_status" value="close" class="icheck" <?php echo ($cms_status == 'close')?'checked':'';?>> Bảo trì / Đóng </label>
                        </div>
                        <div class="checkbox">
                            <label style="padding-left:0;"> <input type="radio" name="cms_status" value="close-home" class="icheck" <?php echo ($cms_status == 'close-home')?'checked':'';?>> Bảo trì / Đóng trang chủ </label>
                        </div>
                        <div class="checkbox">
                            <label style="padding-left:0;"> <input type="radio" name="cms_status" value="password" class="icheck" <?php echo ($cms_status == 'password')?'checked':'';?>> Truy cập bằng mật khẩu </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row" style="overflow:hidden;">
                    <label for="input" class="control-label col-md-3">Password:</label>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <input type="text" name="cms_password" class="form-control" value="<?php echo $cms_password;?>">
                    </div>
                </div>
                <div class="form-group row" style="overflow:hidden;">
                    <label for="input" class="control-label col-md-3">Tiêu đề (close):</label>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <input type="text" name="cms_close_title" class="form-control" value="<?php echo $cms_close_title;?>">
                    </div>
                </div>
                <div class="form-group row" style="overflow:hidden;">
                    <label for="input" class="control-label col-md-3">Lý do (close):</label>
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <textarea name="cms_close_content" class="form-control"><?php echo $cms_close_content;?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr />





