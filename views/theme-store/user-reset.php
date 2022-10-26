<div class="col-md-10 col-md-offset-1" style="overflow: hidden;">
    <div class="user-signin-content">
        <div class="col-md-6 signin-content-left text-center">
            <?php Template::img('https://images.squarespace-cdn.com/content/v1/5accc6315ffd203815e80280/1544109108235-MFAQX7IWXL9RGBF2TNSB/ke17ZwdGBToddI8pDm48kDY3rOgKm1mo685wv6dDPRkUqsxRUqqbr1mOJYKfIPR7LoDQ9mXPOjoJoqy81S2I8N_N4V1vUb5AoIIIbLZhVYxCRW4BPu10St3TBAUQYVKcdbLZwkPBnpAUPop9d68aW-OL5gW4aXBFEKLcvNWREOzsDnpMaSw_3Aszx2j4nFBu/importer%403x.png?format=750w');?>
        </div>
        <div class="col-md-6 signin-content-right">
            <?php if(!is_skd_error($error) ) { ?>
            <form action="" method="POST" role="form" autocomplete="off">
                <h1 class="text-left"><?php echo __('RESET MẬT KHẨU', 't_forgot');?></h1>
                <hr />
                <?php echo form_open(); ?>
                <?php $this->template->get_message();?>

                <div class="form-group">
                    <label for="">Mật khẩu</label>
                    <input name="password" type="password" class="form-control" placeholder="Nhập mật khẩu">
                </div>

                <div class="form-group">
                    <label for="">Nhập lại mật khẩu</label>
                    <input name="re_password" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-effect-default btn-blue"><?php echo __('Đổi mật khẩu');?></button>
                    </div>
                    <div class="col-md-6 text-right">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p><a href="<?php echo Url::login();?>"><i class="fal fa-long-arrow-left"></i> Đăng nhập</a></p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p><a href="<?php echo Url::register();?>">Đăng ký <i class="fal fa-long-arrow-right"></i></a></p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
            <?php } else {
                foreach ($error->errors as $error_key => $error_value) {
                    echo notice('error', $error_value[0]);
                }
            } ?>
        </div>
    </div>
</div>
<style>
    .form-group label { font-weight: bold; }
</style>