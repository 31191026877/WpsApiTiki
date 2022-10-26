<?php
include_once 'product-detail-tabs.php';
include_once 'product-detail.php';
include_once 'customOption.php';
function customCss()
{
    include_once 'customCss.css';
}
add_action('theme_custom_css', 'customCss', 50);
