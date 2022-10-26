<?php

$search_icon_color = (!empty(option::get('search_icon_color'))) ? option::get('search_icon_color') : option::get('theme_color');

?>

<style type="text/css">
    :root {

        --header-logo-height: <?php echo $logoHeight; ?>px;

        --header-search-color: <?php echo $search_icon_color; ?>;

    }

    header .row-flex-center {

        display: flex;
        flex-wrap: wrap;
        align-items: center;

    }

    header .header-content {

        padding: 10px 0;

        <?php echo $background; ?>
    }

    header .logo img {
        max-height: var(--header-logo-height);
    }

    header .header-content .navigation .container {
        width: 100% !important;
        padding: 0 !important;
    }

    header .header-content .btn-search {

        font-size: 20px;
        font-weight: bold;
        color: var(--header-search-color);
        display: inline-block;
        margin: 0 10px 0 0;

    }

    header .header-content .btn-cart-top {

        margin-top: 0;

    }

    header .btn-cart-top img {

        max-width: 25px;

    }

    header .header-content .group-account {

        overflow: hidden;

        line-height: 50px;

        cursor: pointer;

        display: flex;

        align-items: center;

    }

    header .header-content .group-account i {

        width: fit-content;
        margin-right: 10px;

    }

    header .header-content .group-account span {

        font-size: 15px;

        color: #000;

        display: -webkit-box;

        -webkit-line-clamp: 1;

        -webkit-box-orient: vertical;

        overflow: hidden;

    }

    header .header-content .group-account .account-popup {

        display: none;

        position: absolute;

        top: 60px;

        right: 40px;

        border: none;

        margin: 0;

        padding: 20px;

        z-index: 999;

        min-width: 200px;

        background-color: #fff;
        border: 1px solid var(--theme-color);

        box-shadow: 0px 17px 10px 0px rgba(81, 81, 81, 0.23);

        border-radius: 5px;

    }

    header .header-content .group-account .account-popup:before {

        border: 12px solid transparent;

        border-bottom: 12px solid #f8f8f8;

        bottom: 100%;

        right: 135px;

        content: " ";

        height: 0;

        width: 0;

        position: absolute;

        pointer-events: none;

    }

    header .header-content .group-account .account-popup:after {

        top: -20px;

        right: 0;

        content: " ";

        height: 20px;

        width: 100%;

        position: absolute;

    }
    header .header-content .group-account .account-popup .btn-logout::before{
        background-color: #fff;

    }
    header .header-content .group-account .account-popup .btn-logout{
        color: #000;
        border: solid 1px #ebebeb;
    }
    header .header-content .group-account .account-popup .btn-logout:hover{
        background-color: var(--theme-color);
        color: #fff;
    }
    header .header-content .group-account .account-popup .btn_account::before {
        background: #91ad41;
        
        background-image: -webkit-linear-gradient(35deg, #91ad41 0%, #ff8a6c 100%)!important;
    }
   

    header .header-content .group-account .account-popup a {

        display: block;

        font-size: 15px;

        text-align: center;

        margin-bottom: 10px;
        text-transform: unset;
        border-radius: 25px;
        color: #fff;
        border: none;

    }

    header .header-content .group-account .account-popup a:hover {
        background: #fff;
        color: var(--theme-color);
        border: solid 1px var(--theme-color);
    }

    header .header-content .group-account .account-popup a:last-child {
        margin-bottom: 0;
    }
    header .header-content .group-account .account-popup .btn_account:hover{
        background-color: var(--theme-color);
        color: #fff;
    }
    header .header-content .group-account:hover .account-popup {

        display: block;

    }
</style>