<style>

    :root {

        --nav-font:<?php echo (option::get('nav_font')) ? option::get('nav_font') : option::get('text_font');?>;

        --nav-weight:<?php echo option::get('nav_font_weight');?>;

        --nav-font-size:<?php echo option::get('nav_font_size');?>px;

        --nav-padding:<?php echo option::get('nav_padding');?>;

        --nav-bg-color:<?php echo empty(option::get('nav_bg_color')) ? option::get('theme_color') : option::get('nav_bg_color') ;?>;

        --nav-bg-color-hv:<?php echo option::get('nav_bg_color_hover');?>;

        --nav-txt-color:<?php echo option::get('nav_text_color');?>;

        --nav-txt-color-hv:<?php echo option::get('nav_text_color_hover');?>;

        --navs-bg-color:<?php echo empty(option::get('navsub_bg_color')) ? '#fff' : option::get('navsub_bg_color') ;?>;

        --navs-bg-color-hv:<?php echo option::get('navsub_bg_color_hover');?>;

        --navs-txt-color:<?php echo option::get('navsub_text_color');?>;

        --navs-txt-color-hv:<?php echo option::get('navsub_text_color_hover');?>;

    }
    .navigation ul .active a{
        color: var(--theme-color);
    }

</style>