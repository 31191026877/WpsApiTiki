<?php
$mobile_account = Option::get('mobile_account');
$content1 = '';
$content2 = '';
if(!empty($mobile_account)) {
    $content1 = '<div class="text-center" style="display: block"><span class="avatar avatar-placeholder"><svg width="50" height="50" fill="unset" class="" stroke="unset" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M 30.933594 32.527344 C 30.785156 30.914063 30.84375 29.789063 30.84375 28.316406 C 31.574219 27.933594 32.882813 25.492188 33.101563 23.429688 C 33.675781 23.382813 34.582031 22.824219 34.847656 20.613281 C 34.988281 19.425781 34.421875 18.757813 34.074219 18.546875 C 35.007813 15.738281 36.949219 7.046875 30.488281 6.148438 C 29.820313 4.980469 28.117188 4.390625 25.90625 4.390625 C 17.050781 4.554688 15.984375 11.078125 17.925781 18.546875 C 17.578125 18.757813 17.011719 19.425781 17.152344 20.613281 C 17.421875 22.824219 18.324219 23.382813 18.898438 23.429688 C 19.117188 25.492188 20.476563 27.933594 21.210938 28.316406 C 21.210938 29.789063 21.265625 30.914063 21.117188 32.527344 C 19.367188 37.238281 7.546875 35.914063 7 45 L 45 45 C 44.453125 35.914063 32.683594 37.238281 30.933594 32.527344 Z"></path><path fill="none" stroke-miterlimit="10" stroke-width="3" d="M50 25L0 25M50 10L0 10M0 40L25 40"></path></svg></span></div>';
    if(Auth::check()) {
        $content2 = '<div class="user-information login"> <span class="user-name">'.Auth::user()->firstname.' '.Auth::user()->lastname.'</span> <br />  <div class="user-link-action"> <a href="'.Url::account().'" class="user-link-info"><svg fill="currentColor" class="" stroke="unset" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M 12 2 C 8.699219 2 6 4.699219 6 8 L 6 42.417969 C 6 45.59375 8.832031 48 12 48 L 44 48 L 44 46 L 12 46 C 9.839844 46 8 44.378906 8 42.417969 C 8 40.457031 9.800781 39 12 39 L 44 39 L 44 2 Z M 12 4 L 42 4 L 42 37 L 12 37 C 10.507813 37 9.09375 37.539063 8 38.417969 L 8 8 C 8 5.78125 9.78125 4 12 4 Z M 15 9 C 13.90625 9 13 9.90625 13 11 L 13 15 C 13 16.09375 13.90625 17 15 17 L 36 17 C 37.09375 17 38 16.09375 38 15 L 38 11 C 38 9.90625 37.09375 9 36 9 Z M 15 11 L 36 11 L 36 15 L 15 15 Z"></path></svg> Thông tin</a> <a href="'.Url::logout().'" class="user-link-info"><svg fill="currentColor" class="" stroke="unset" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M 25 2 C 17.389725 2 10.633395 5.7052643 6.4492188 11.408203 A 1.0001 1.0001 0 1 0 8.0605469 12.591797 C 11.882371 7.3827357 18.038275 4 25 4 C 36.609534 4 46 13.390466 46 25 C 46 36.609534 36.609534 46 25 46 C 18.038275 46 11.883451 42.617435 8.0605469 37.408203 A 1.0001 1.0001 0 1 0 6.4492188 38.591797 C 10.634315 44.294565 17.389725 48 25 48 C 37.690466 48 48 37.690466 48 25 C 48 12.309534 37.690466 2 25 2 z M 10.980469 15.990234 A 1.0001 1.0001 0 0 0 10.292969 16.292969 L 2.3808594 24.205078 A 1.0001 1.0001 0 0 0 2.3828125 25.796875 L 10.292969 33.707031 A 1.0001 1.0001 0 1 0 11.707031 32.292969 L 5.4140625 26 L 27 26 A 1.0001 1.0001 0 1 0 27 24 L 5.4140625 24 L 11.707031 17.707031 A 1.0001 1.0001 0 0 0 10.980469 15.990234 z"></path></svg> Đăng xuất</a> </div> </div>';
    }
    else {
        $content2 = '<div class="user-information"><a href="'.Url::login().'">Đăng nhập</a><a href="'.Url::register().'">Đăng ký</a></div>';
    }
}
?>
<nav id="menu" class="menu-mobile" style="display:none;">
    <ul>
        <?php if(Language::hasMulti()) {?>
            <li class="language">
                <?php foreach (Language::list() as $key => $item) { ?>
                    <?php if(empty($item['flag'])) {?>
                        <a href="<?php echo Url::language($key);?>"><?php echo $item['label'];?></a>
                    <?php } else { ?>
                        <a href="<?php echo Url::language($key);?>"><?php echo Template::img($item['flag'], $item['label']). ' '. $item['label'];?></a>
                    <?php } ?>
                <?php } ?>
            </li>
        <?php } ?>
        <?php ThemeMenu::render(['theme_location' => 'main-nav', 'walker' => 'store_mobile_nav_menu']);?>
    </ul>
</nav>
<script defer>
    $(function () {
        let content1 = '<?php echo $content1;?>';
        let content2 = '<?php echo $content2;?>';
        let extensions = ["pagedim-black"];
        if(menu_mb_position === 'center') {
            extensions.push('position-bottom');
            extensions.push('fullscreen');
        }
        else {
            extensions.push("position-"+menu_mb_position);
        }
        if(content1.length === 0) {
            new Mmenu(document.querySelector('#menu'), {
                "extensions": extensions,
            });
        }
        else {
            new Mmenu(document.querySelector('#menu'), {
                "extensions": extensions,
                "navbars" : [{
                    "content" : content1
                },{
                    "content" : content2
                }]
            });
        }
    });
</script>