<form action="" method="post" id="js_user_form__login" autocomplete="off">
    <div class="login-form">
        <div class="row">
            <div class="col-sm-6 col-md-6 login-info-company">
                <div class="login-info-content">
                    <div class="text-login-info text-center">
                        <p style="font-size:20px; font-weight:500; line-height: 30px; margin: 10px 0">Cảm ơn bạn đã tin tưởng và lựa chọn <b>Siêu Kinh Doanh</b>!</p>
                        <p style="font-size:15px; line-height: 25px;margin-bottom: 10px;">Chúng tôi sẽ nỗ lực hết mình để mang đến những trải nghiệm tốt nhất và giúp việc kinh doanh của bạn thành công.</p>
                        <?php if( have_posts($support) && !empty($support->cskh_hotline)) { ?>
                            <p style="font-size:15px;text-transform: uppercase;opacity: 0.7;"><i class="fad fa-at"></i> CSKH: <?php echo $support->cskh_email;?></p>
                            <p style="font-size:15px;text-transform: uppercase;opacity: 0.7;"><i class="fad fa-user-headset"></i> HOTLINE:  <?php echo $support->cskh_hotline;?></p>
                        <?php } ?>
                    </div>
                    <div class="login-info-img text-center">
                        <img src="<?php echo Admin::imgTemplateLink('login_banner.svg');?>" class="img-responsive">
                    </div>
                    <div class="login-info-bg">
                        <?php echo Admin::imgTemplate('login_bg_2.png');?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 login-widget" style="position: relative">
                <?php echo form_open();?>
                <?php Admin::loading('jsLoader'); ?>
                <div class="login-widget-heading">
                    <div class="header-title">
                        <h3>ĐĂNG NHẬP</h3>
                        <h3>ĐĂNG NHẬP</h3>
                    </div>
                    <h5>Đăng nhập quản lý website</h5>
                    <hr>
                </div>
                <div class="login-widget-content">
                    <div class="form-group">
                        <span class="icon"><i class="fal fa-user"></i></span>
                        <input name="username" value="<?= set_value('username', '');?>" type="text" class="form-control" placeholder="Tài Khoản" autofocus required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <span class="icon"><i class="fal fa-lock"></i></span>
                        <input name="password" value="<?= set_value('password', '');?>" type="password" class="form-control" id="password" placeholder="Mật khẩu" required>
                    </div>
                </div><!-- /login-widget-content -->
                <div class="login-widget-bottom">
                    <div class="col-md-12" style="padding:0">
                        <button name="login" value="Đăng nhập" type="submit" class="btn btn-block">Đăng Nhập</button>
                    </div>
                    <div class="col-md-12 text-center" style="padding:0">
                        <p>Copyright 2016 - <?php echo date('Y');?> © SKD Technologies.</p>
                        <p>Version: <?php echo cms_info('version');?></p>
                    </div>
                </div>
                <div class="login-widget-bg">
                    <?php echo Admin::imgTemplate('login_bg_1.png');?>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center skd-logo">
        <p>Powered by <img src="<?php echo Cms::logo();?>"></p>
    </div>

</form>
<script>
    $(function () {

        $(document).on( 'submit', '#js_user_form__login', function () {

            let data = {
                'action'   : 'Ajax_Admin_User_Action::login',
                'username' : $(this).find('input[name="username"]').val(),
                'password' : $(this).find('input[name="password"]').val(),
            };

            let load = $(this).find('#jsLoader');

            load.show();

            $('button[type="submit"]').hide();

            $.post(ajax,data, function(data) {}, 'json').done(function( response ) {

                $.toast({ heading: "Thông Báo", text: response.message, position: 'bottom-center', icon: response.status, hideAfter: 5000, });

                if(response.status === 'success') {
                    window.location = getParameterByName('redirect_to');
                }
                else {
                    load.hide();
                    $('button[type="submit"]').show();
                }
            });

            return false;
        });

        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            let regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
    })
</script>