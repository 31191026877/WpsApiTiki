<header>
  <div class="header-left">
    <div class="logo"><a href=""><img src="<?= $this->template->get_assets();?>images/cms-logo.png" alt=""></a></div>
  </div>
  <div class="header-right">
    <a href="<?= base_url();?>" class="user">Trang chủ</a>
    <div class="dropdown user">
      <a id="dLabel" data-target="#" href="" data-toggle="dropdown" role="button">
        <img src="<?= (!empty($user->avatar))?SOURCE.$user->avatar:$this->template->get_assets().'images/avatar.png';?>" alt="" class="img-circle">
      </a>

      <div class="dropdown-menu" aria-labelledby="dLabel">
        <div class="info">
          <div class="img pull-left">
            <img src="<?= (!empty($user->avatar))?SOURCE.$user->avatar:$this->template->get_assets().'images/avatar.png';?>" alt="" class="img-circle">
          </div>
          <div class="title pull-left">
            <p>xin chào, <b><?= $user->firstname.' '.$user->lastname;?></b><br /><?= $user->email;?></p>
            <hr />
            <p><b>Quản trị viên</b></p>
            <!-- <p style="color:#ccc">Lần cuối đăng nhập: </p> -->
            <!-- <p>13-03-2016 07:33</p> -->
          </div>
        </div>
        <div class="action">
          <a href="<?= URL_ADMIN;?>/user/edit/<?= $user->id;?>" class="pull-left btn btn-default">Thông Tin</a>
          <a href="<?= URL_ADMIN;?>/user/logout" class="pull-right btn btn-default">Đăng xuất</a>
        </div>
      </div>
       
    </div>
  </div>
</header><!-- /header -->