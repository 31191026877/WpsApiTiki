<div id="adminmenumain" role="navigation" aria-label="Trình đơn chính">
    <div class="header-logo">
        <a href=""><img src="<?php echo Cms::logo();?>" alt="cms skilldo"></a>
        <a href="" class="pull-right" target="_black" style="padding-top:5px;color:#4CB6FF;"><i class="fad fa-house-damage"></i></a>
    </div>
    <div id="adminmenuback"></div>
    <div id="adminmenuwrap">
        <ul id="adminmenu">
            <?php if(Auth::hasCap('builder')) {?><a href="<?php echo Url::admin('plugins?page=builder');?>" class="btn btn-theme" style="padding:10px;margin-top: 5px;margin-left: 0;" target="_black"><i class="fad fa-pencil-paintbrush"></i> Builder</a><?php } ?>
            <?php do_action('admin_menu_content_top');?>
            <?php AdminMenu::render($group, $active);?>
        </ul>
    </div>
    <div class="nav-user">
        <div class="account-info">
            <a href="javascript:void(0)" title="Cấu hình" style="padding: 10px 10px 10px 20px;" data-bs-toggle="collapse" data-bs-target="#list-action-user" id="show-action-user">
                <img class="profile-pic img-circle" src="<?= (!empty($user->avatar))?SOURCE.$user->avatar:Admin::imgTemplateLink('avatar.png');?>">
                <span style="margin:0 5px"><?php echo $user->firstname.' '.$user->lastname;?></span>
                <span id="caret-menu-user"><i class="fal fa-angle-up caret-menu-icon"></i></span>
            </a>

            <div id="list-action-user" class="collapse">
                <ul class="nav-user-sub">
                    <li><a href="<?php echo Url::admin('users/edit?view=profile') ;?>"><i class="fal fa-user"></i> <span>Tài khoản của bạn</span></a></li>
                    <li><a href="<?php echo Url::admin('users/edit?view=password') ;?>"><i class="fal fa-unlock"></i> <span>Đổi mật khẩu</span></a></li>
                    <li class="divider"></li>
                    <?php if(!empty($_SESSION['user_after'])) {?>
                        <li><a href="#" style="color:red;" id="btn_login_as_back"><i class="fal fa-undo"></i> <span>Quay lại tài khoản trước</span></a></li>
                    <?php } ?>
                    <li><a href="<?php echo Url::admin('users/logout') ;?>"><i class="fal fa-sign-out-alt"></i> <span>Đăng xuất</span></a></li>
                    <?php if(Admin::isRoot()) {?>
                    <li><a data-bs-toggle="modal" href="#js_licensed_modal__info"><i class="fal fa-file-certificate"></i> Licensed</a></li>
                    <?php } ?>
                    <li><span style="color:#fff">version skilldo cms <?php echo cms::version();?></span></li>
                </ul>
            </div>
        </div>
    </div>
</div>