<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" href="<?php echo Admin::imgLink('logo.png');?>">
    <base href="<?= Url::base()?>">
    <title>Đăng nhập</title>
    <?php do_action('admin_header');?>
    <script>
      $( document ).ready(function() {
          domain      = '<?= Url::base();?>';
          base        = '<?= Url::admin();?>';
          path        = '<?= Url::adminModule();?>';
          ajax        = '<?= Url::admin('ajax');?>';
          $.ajaxSetup({
              beforeSend: function(xhr, settings) {
                  if (settings.data.indexOf('csrf_test_name') === -1) {
                      settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
                  }
              }
          });
          function getCookie(cname) {
              let name = cname + "=";
              let decodedCookie = decodeURIComponent(document.cookie);
              let ca = decodedCookie.split(';');
              for (let i = 0; i < ca.length; i++) {
                  let c = ca[i];
                  while (c.charAt(0) == ' ') {
                      c = c.substring(1);
                  }
                  if (c.indexOf(name) == 0) {
                      return c.substring(name.length, c.length);
                  }
              }
              return "";
          }
      });
    </script>
    <style>
        :root {
            --menu-bg:<?php echo Cms::config('menu_bg');?>;
            --menu-active-bg:<?php echo Cms::config('menu_active_bg');?>;
            --font-main:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", sans-serif;
            --body-bg:#e6e8ea;
            --content-bg:<?php echo Cms::config('content_bg');?>;
            --theme-color:<?php echo Cms::config('theme_color');?>;
            --header-title-bg:#e7eaef;
            --header-title-color:#214171;
            --header-height:50px;
            --tab-height:42px;
            --blue:#416DEA;
            --red:#f82222;
            --green:#08e783;
            --box-shadow:0 0 10px 0 rgba(55,42,70,0.2);
            --box-shadow-hover:0 0 20px 0 rgba(55,42,70,0.5);
        }
    </style>

</head>

    <body class="login-page">
        <div class="login-widget">
            <?php $this->template->render_view(); ?>
        </div><!-- /login-widget -->
        <?php do_action('admin_footer');?>
    </body>
</html>
