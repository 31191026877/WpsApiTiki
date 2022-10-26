<style>
    body,
    .wrapper {
        <?php
        $background = Option::get('bodyBg');
        if (empty($background)) {
            $bgColor    = Option::get('body_color');
            $bgImg      = Option::get('body_img');
            $background = ['color' => $bgColor, 'image' => $bgImg];
            if (!empty($bgColor) || !empty($bgImg)) {
                Option::update('bodyBg', $background);
            }
        }
        echo Template::cssBg($background);
        ?>
    }

    /** footer */
    footer {
        <?php
        $background = Option::get('footer_bg');
        if (empty($background)) {
            $bgColor    = Option::get('footer_bg_color');
            $bgImg      = Option::get('footer_bg_image');
            $background = ['color' => $bgColor, 'image' => Option::get('footer_bg_image')];
            if (!empty($bgColor) || !empty($bgImg)) {
                Option::update('footer_bg', $background);
            }
        }
        echo Template::cssBg($background);
        ?>
    }

    .header-title:before {
        content: "";
        background-image: url(<?php echo Template::imgLink(Option::get('iconBelowHeader')) ?>);
        background-repeat: no-repeat;
        width: 69px;
        height: 22px;
        position: absolute;
        left: 47%;
        bottom: 0px;
    }


    <?php do_action('theme_custom_css'); ?>
</style>