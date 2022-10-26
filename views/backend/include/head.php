<head>
    <base href="<?php echo Url::base();?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" href="<?php echo Admin::imgLink('logo.png');?>">
    <title>Admin - sikido</title>
    <?php do_action('admin_header') ;?>
    <script>
        $( document ).ready(function() {
            domain      = '<?= Url::base();?>';
            base        = '<?= Url::admin();?>';
            path        = '<?= Url::adminModule();?>';
            ajax        = '<?= Url::admin('ajax');?>';
            cateType   = '<?= $ci->cateType;?>';
            postType   = '<?= $ci->postType;?>';
            language    = '<?= Language::default();?>';
            urlType = '';
            if(cateType.length > 0  && postType.length > 0) 	urlType = '?cate_type='+cateType+'&post_type='+postType;
            if(cateType.length > 0  && postType.length === 0) urlType = '?cate_type='+cateType;
            if(cateType.length === 0 && postType.length > 0) 	urlType = '?post_type='+postType;
            <?php if(isset($object) && have_posts($object)) {?>
            object_id  = '<?= $object->id;?>';
            <?php }?>
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
            --box-shadow:0 0 10px 0 rgba(211,211,211,0.64);
            --box-shadow-hover:0 0 20px 0 rgba(55,42,70,0.5);
        }
    </style>
</head>
