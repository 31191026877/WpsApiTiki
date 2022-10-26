<div class="master-login-bar <?php echo (!empty($_COOKIE['master_login_bar']))?'master-login-bar-hiden':'';?>">
    <div class="col-md-6">
        <form action="" method="post" id="master_login_as__form">
            <div class="form-group">
                <label>Logged in as</label>
                <div class="input">
                    <select name="user_login_as" class="form-control">
                        <?php foreach ($users_login_as as $users_login_a) {?>
                            <option value="<?php echo $users_login_a->id;?>" <?php echo ($users_login_a->id == $user_current->id) ? 'selected' : '';?>><?php echo $users_login_a->username.' - '.$users_login_a->firstname.' '.$users_login_a->lastname;?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo admin_url("");?>" class="login-master"><span><i class="fal fa-tachometer-alt-fastest"></i> Go to admin</span></a>
        <a href="#" class="login-master login-master-back"><span><i class="fal fa-power-off"></i> Logout</span></a>
        <a href="#" class="login-master login-master-close"><span><i class="fal fa-chevron-up"></i></span></a>
    </div>
</div>
<div class="master-login-bar-show">
    <a href="#" class="login-master login-master-show"><span><i class="fal fa-chevron-down"></i></span></a>
</div>
<style>
    .master-login-bar-show {
        position: fixed;
        top:0; right: 20px; z-index:999999998;
        width:50px;
        background-color: #34383C; color:#fff;
        padding:5px 0;
        line-height: 10px;
        transition: all 0.5s;
        border-radius: 0 0 5px 5px;
        text-align: center;
    }
    .master-login-bar {
        position: fixed;
        top:0; left: 0; z-index:999999999;
        width:100%;
        background-color: #34383C; color:#fff;
        padding:10px 0;
        line-height: 35px;
        transition: all 0.5s;
    }
    .master-login-bar.master-login-bar-hiden { top:-150px; }
    .master-login-bar .form-group { overflow: hidden; margin: 0;}
    .master-login-bar .form-group label { float: left; width:100px;}
    .master-login-bar .form-group .input { float: left; width:calc(100% - 100px);}
    .master-login-bar .form-group .input select { background-color: #34383C; color:#fff; }
    .master-login-bar .form-group .input select option { padding:10px; }
    .master-login-bar .login-master { display:inline-block;font-weight: bold; color:#fff!important; padding:0 20px;}
    .master-login-bar .login-master i { color:red; padding-right: 5px; }
    .master-login-bar .login-master-close {
        border:1px solid #757575a6; text-align: center; border-radius: 5px;
    }
    .master-login-bar .login-master-close i {
        padding-right: 0;
    }
</style>
<script async>
    $(function () {
        $('.master-login-bar .login-master-close').click(function () {
            $(this).closest('.master-login-bar').addClass('master-login-bar-hiden');
            setCookie('master_login_bar', 1, 7*24*60*60);
            return false;
        });
        $('.master-login-bar-show .login-master-show').click(function () {
            $('.master-login-bar').removeClass('master-login-bar-hiden');
            setCookie('master_login_bar', 0, 7*24*60*60);
            return false;
        });
        $('#master_login_as__form select[name="user_login_as"]').change(function () {
            var data = {
                'action': 'Ajax_Admin_User_Action::loginAs',
                'id':$(this).val(),
                'csrf_test_name' : encodeURIComponent(getCookie('csrf_cookie_name'))
            };
            $jqxhr = $.post(ajax, data, function () { }, 'json');
            $jqxhr.done(function (response) {
                if (response.status === 'success') {
                    window.location.replace("");
                }
            });
            $jqxhr.fail(function (response) {});
            $jqxhr.always(function (response) { });
        });
        $('.master-login-bar .login-master-back').click(function () {
            var data = {
                'action': 'Ajax_Admin_User_Action::loginAsBackRoot',
                'csrf_test_name' : encodeURIComponent(getCookie('csrf_cookie_name'))
            };
            $jqxhr = $.post(ajax, data, function () { }, 'json');
            $jqxhr.done(function (response) {
                if (response.status === 'success') {
                    window.location.replace("admin/user");
                }
            });
            $jqxhr.fail(function (response) {});
            $jqxhr.always(function (response) { });
        });
    })
</script>