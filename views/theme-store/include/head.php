<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/>
    <base href="<?= Url::base();?>">
    <?php $icon = (empty(option::get('seo_favicon'))) ? option::get('logo_header') : option::get('seo_favicon'); ?>
    <link rel="icon" href="<?= Template::imgLink($icon);?>" sizes="16x16" type="image/png">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <?php do_action('cle_header');?>
    <script src="<?= Path::theme();?>assets/js/jquery-3.5.1.min.js"></script>
    <script type='text/javascript' defer>
        /* <![CDATA[ */
        domain  = '<?= Url::base();?>';
        base    = '<?= Url::admin();?>';
        ajax    = '<?= Url::admin('ajax');?>';
        menu_mb_position = '<?php echo option::get('menu_mobile_position');?>';
        /* ]]> */
    </script>
    <?php
    $footerPT           =  (is_numeric(Option::get('footer_padding_top')) ? Option::get('footer_padding_top') : '50').'px';
    $footerPB           =  (is_numeric(Option::get('footer_padding_bottom')) ? Option::get('footer_padding_bottom') : '50').'px';
    $footerHeaderSize   =  (is_numeric(Option::get('footer_header_size')) ? Option::get('footer_header_size') : '18').'px';
    ?>
    <style>
        :root {
            font-size: 14px;
            --theme-color:<?php echo Option::get('theme_color');?>;
            --btn-blue:#3F7DF6;
            --btn-green:#449D44;
            --btn-yellow:#EC971F;
            --btn-red:#ff0000;
            --btn-theme:var(--theme-color);
            --header-mb-bg:<?php echo (Option::get('header_mobile_bg_color')) ? Option::get('header_mobile_bg_color'):'#fff';?>;
            --menu-mb-bg:<?php echo (empty(Option::get('menu_mobile_bg_color'))) ? '#fff' : Option::get('menu_mobile_bg_color');?>;
            <?php if(!empty(Option::get('menu_mobile_bg_img'))) {?>
            --menu-mb-bg-img:url('<?php echo Template::imgLink(option::get('menu_mobile_bg_img'));?>');
            <?php } ?>
            --menu-mb-txt:<?php echo Option::get('menu_mobile_txt_color');?>;
            --menu-mb-br:<?php echo (empty(Option::get('menu_mobile_br_color'))) ? '#fff' : Option::get('menu_mobile_br_color');?>;
            <?php if(empty(Option::get('search_mobile_bg_img'))) { $search_mobile_bg_color = (empty(Option::get('search_mobile_bg_color'))) ? '#fff' : Option::get('search_mobile_bg_color');?>
            --search-mb-bg:<?php echo $search_mobile_bg_color;?>;
            <?php } else { ?>
            --search-mb-bg:url('<?php echo Template::imgLink(Option::get('search_mobile_bg_img'));?>');
            <?php } ?>
            --search-mb-color:<?php echo (Option::get('header_mobile_color_search')) ? Option::get('header_mobile_color_search'):'#000';?>;
            --menu-mb-color:<?php echo (Option::get('header_mobile_icon_menu')) ? Option::get('header_mobile_icon_menu'):'#000';?>;
            --body-img:<?php echo Option::get('body_img');?>;
            --font-family:<?php echo Option::get('text_font');?>;
            --font-header:<?php echo Option::get('header_font');?>;

            --footer-header-color:<?php echo empty(Option::get('footer_header_color')) ? '#fff' : Option::get('footer_header_color');?>;
            --footer-header-size:<?php echo $footerHeaderSize;?>;
            --footer-text-color:<?php echo empty(Option::get('footer_text_color')) ? '#fff' : Option::get('footer_text_color');?>;
            --footer-bottom-public:<?php echo (Option::get('footer_bottom_public')) ? 'block' : 'none';?>;
            --footer-bottom-bg:<?php echo (Option::get('footer_bottom_bg_color')) ? Option::get('footer_bottom_bg_color') :'#282828';?>;
            --footer-bottom-color:<?php echo (Option::get('footer_bottom_text_color')) ? Option::get('footer_text_color') :'#fff';?>;
            --footer-padding:<?php echo $footerPT;?> 0 <?php echo $footerPB;?> 0;
        }
    </style>
</head>