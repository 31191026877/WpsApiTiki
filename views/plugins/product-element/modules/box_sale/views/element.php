<div class="pre-box-sale">
    <section class="pre-box-sale-block animated">
        <h3>
            <span class="icon">
                <i class="ico-star left"></i>
                <i class="ico-star right"></i>
                <i class="ico-triangle right-1"></i>
                <i class="ico-triangle right-2"></i>
                <i class="ico-triangle right-3"></i>
                <i class="ico-triangle left-1"></i>
                <i class="ico-triangle left-2"></i>
                <i class="ico-triangle left-3"></i>
                <i class="ico-line line-1"></i>
                <i class="ico-line line-2"></i>
                <i class="ico-line line-3"></i>
                <i class="ico-gift"></i>
            </span>
            <span class="ttl"><?php echo $config['title'];?></span>
        </h3>
        <section class="pre-box-sale__content"><?php echo $sale;?></section>
    </section>
</div>
<style>
    :root {
        --pre-box-sale-padding      : <?php echo $config['padding'];?>;
        --pre-box-sale-margin       : <?php echo $config['margin'];?>;
        --pre-box-sale-bg_box       : <?php echo (!empty($config['bg_box'])) ? $config['bg_box'] : 'var(--theme-color)';?>;
        --pre-box-sale-text_color   : <?php echo (!empty($config['text_color'])) ? $config['text_color'] : 'var(--theme-color)';?>;
    }
</style>
<style type="text/css">
    .pre-box-sale-block {
        margin: var(--pre-box-sale-margin);
        overflow: visible;
        -webkit-border-radius: 0 0 4px 4px;
        -moz-border-radius: 0 0 4px 4px;
        -ms-border-radius: 0 0 4px 4px;
        -o-border-radius: 0 0 4px 4px;
        border-radius: 0 0 4px 4px;
        -moz-background-clip: padding;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
    }
    .pre-box-sale-block > h3 {
        height: 42px;
        line-height: 42px;
        background-color: var(--pre-box-sale-bg_box);
        color: var(--pre-box-sale-text_color);
        text-transform: uppercase;
        font-size: 14px;
        text-shadow: 0px 0px 1px rgba(0, 0, 0, 0.31);
        -webkit-border-radius: 4px 4px 0 0;
        -moz-border-radius: 4px 4px 0 0;
        -ms-border-radius: 4px 4px 0 0;
        -o-border-radius: 4px 4px 0 0;
        border-radius: 4px 4px 0 0;
        -moz-background-clip: padding;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        font-family: 'Roboto Condensed', sans-serif;
        font-weight: 300;
        margin-bottom: 0;
    }
    .pre-box-sale-block > h3 span {
        float: left;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
        border-radius: 50%;
        text-align: center;
        background: none;
        width: auto;
        height: 42px;
        line-height: 42px;
        position: relative;
        margin: 0;
        font-weight: bold;
        color:var(--pre-box-sale-text_color);
    }
    .pre-box-sale-block > h3 span.icon::before {
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
        border-radius: 50%;
        -moz-background-clip: padding;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        margin: 8px 10px 0;
        background-color: #00f3bb;
        width: 40px;
        height: 40px;
        content: '';
        position: absolute;
        bottom: 5px;
        left: 3px;
        z-index: 1;
    }
    .pre-box-sale-block > h3 span i {
        background-repeat: no-repeat;
        background-size: cover;
        position: absolute;
        z-index: 1;
    }
    .pre-box-sale-block > h3 span i.ico-star {
        width: 20px;
        height: 20px;
        -webkit-transition: all 0.6s ease-out;
        -moz-transition: all 0.6s ease-out;
        -ms-transition: all 0.6s ease-out;
        -o-transition: all 0.6s ease-out;
        transition: all 0.6s ease-out;
    }
    .pre-box-sale-block > h3 span i.ico-star.left {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/star-2.png);
        left: 8px;
        top: -18px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: translateY(20px) scale(0, 0);
        -moz-transform: translateY(20px) scale(0, 0);
        -ms-transform: translateY(20px) scale(0, 0);
        -o-transform: translateY(20px) scale(0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-star.left {
        -webkit-animation: star 3s infinite;
        -o-animation: star 3s infinite;
        animation: star 3s infinite;
        -webkit-animation: star 3s infinite 300ms 0 ease;
        -moz-animation: star 3s infinite 300ms 0 ease;
        -ms-animation: star 3s infinite 300ms 0 ease;
    }
    .pre-box-sale-block > h3 span i.ico-star.right {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/star-1.png);
        right: 12px;
        top: -25px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: translateY(20px) scale(0, 0);
        -moz-transform: translateY(20px) scale(0, 0);
        -ms-transform: translateY(20px) scale(0, 0);
        -o-transform: translateY(20px) scale(0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-star.right {
        -webkit-animation: star 3s infinite 0.2s;
        -o-animation: star 3s infinite 0.2s;
        animation: star 3s infinite 0.2s;
        -webkit-animation: star 3s infinite 0.2s 300ms 0 ease;
        -moz-animation: star 3s infinite 0.2s 300ms 0 ease;
        -ms-animation: star 3s infinite 0.2s 300ms 0 ease;
    }
    .pre-box-sale-block > h3 span i.ico-triangle.right-1 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/triangle-3.png);
        width: 8px;
        height: 11px;
        top: -40px;
        right: 13px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-triangle.right-1 {
        -webkit-animation: firework 3s infinite 0.3s;
        -o-animation: firework 3s infinite 0.3s;
        animation: firework 3s infinite 0.3s;
        -webkit-animation: firework 3s infinite 0.3s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.3s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.3s 300ms 0 ease;
        transform-origin: 0 100%;
    }
    .pre-box-sale-block > h3 span i.ico-triangle.right-2 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/triangle-1.png);
        width: 10px;
        height: 7px;
        top: -12px;
        right: 0;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-triangle.right-2 {
        -webkit-animation: firework 3s infinite 0.4s;
        -o-animation: firework 3s infinite 0.4s;
        animation: firework 3s infinite 0.4s;
        -webkit-animation: firework 3s infinite 0.4s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.4s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.4s 300ms 0 ease;
        transform-origin: 0 100%;
    }
    .pre-box-sale-block > h3 span i.ico-triangle.right-3 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/triangle-2.png);
        width: 12px;
        height: 4px;
        top: -24px;
        right: -2px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-triangle.right-3 {
        -webkit-animation: firework 3s infinite 0.5s;
        -o-animation: firework 3s infinite 0.5s;
        animation: firework 3s infinite 0.5s;
        -webkit-animation: firework 3s infinite 0.5s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.5s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.5s 300ms 0 ease;
        transform-origin: 0 100%;
    }
    .pre-box-sale-block > h3 span i.ico-triangle.left-1 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/triangle-4.png);
        width: 8px;
        height: 11px;
        top: -25px;
        left: 0px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-triangle.left-1 {
        -webkit-animation: firework 3s infinite 0.3s;
        -o-animation: firework 3s infinite 0.3s;
        animation: firework 3s infinite 0.3s;
        -webkit-animation: firework 3s infinite 0.3s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.3s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.3s 300ms 0 ease;
        transform-origin: 100% 0;
    }
    .pre-box-sale-block > h3 span i.ico-triangle.left-2 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/triangle-5.png);
        width: 10px;
        height: 7px;
        top: -29px;
        left: 10px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-triangle.left-2 {
        -webkit-animation: firework 3s infinite 0.4s;
        -o-animation: firework 3s infinite 0.4s;
        animation: firework 3s infinite 0.4s;
        -webkit-animation: firework 3s infinite 0.4s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.4s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.4s 300ms 0 ease;
        transform-origin: 100% 0;
    }
    .pre-box-sale-block > h3 span i.ico-triangle.left-3 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/triangle-6.png);
        width: 12px;
        height: 4px;
        top: -6px;
        left: -6px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-triangle.left-3 {
        -webkit-animation: firework 3s infinite 0.5s;
        -o-animation: firework 3s infinite 0.5s;
        animation: firework 3s infinite 0.5s;
        -webkit-animation: firework 3s infinite 0.5s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.5s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.5s 300ms 0 ease;
        transform-origin: 100% 0;
    }
    .pre-box-sale-block > h3 span i.ico-line.line-1 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/line-2.png);
        width: 12px;
        height: 11px;
        top: -37px;
        right: 0px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-line.line-1 {
        -webkit-animation: firework 3s infinite 0.3s;
        -o-animation: firework 3s infinite 0.3s;
        animation: firework 3s infinite 0.3s;
        -webkit-animation: firework 3s infinite 0.3s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.3s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.3s 300ms 0 ease;
        transform-origin: 0 100%;
    }
    .pre-box-sale-block > h3 span i.ico-line.line-2 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/line-1.png);
        width: 10px;
        height: 14px;
        top: -32px;
        right: 30px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-line.line-2 {
        -webkit-animation: firework 3s infinite 0.4s;
        -o-animation: firework 3s infinite 0.4s;
        animation: firework 3s infinite 0.4s;
        -webkit-animation: firework 3s infinite 0.4s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.4s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.4s 300ms 0 ease;
        transform-origin: 0 100%;
    }
    .pre-box-sale-block > h3 span i.ico-line.line-3 {
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/line-3.png);
        width: 14px;
        height: 9px;
        top: -16px;
        left: -13px;
        filter: alpha(opacity=0);
        -webkit-opacity: 0;
        -moz-opacity: 0;
        opacity: 0;
        -webkit-transform: scale3d(0, 0, 0);
        -moz-transform: scale3d(0, 0, 0);
        -ms-transform: scale3d(0, 0, 0);
        -o-transform: scale3d(0, 0, 0);
    }
    .pre-box-sale-block.animated > h3 span i.ico-line.line-3 {
        -webkit-animation: firework 3s infinite 0.5s;
        -o-animation: firework 3s infinite 0.5s;
        animation: firework 3s infinite 0.5s;
        -webkit-animation: firework 3s infinite 0.5s 300ms 0 ease;
        -moz-animation: firework 3s infinite 0.5s 300ms 0 ease;
        -ms-animation: firework 3s infinite 0.5s 300ms 0 ease;
        transform-origin: 100% 0;
    }
    .pre-box-sale-block > h3 span i.ico-gift {
        margin-top: -28px;
        margin-right: 5px;
        position: relative;
        z-index: 10;
        display: inline-block;
        width: 60px;
        height: 69px;
        background-size: contain;
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/ico-gift.png);
        background-repeat: no-repeat;
        background-position: center 19px;
        margin-left: 3px;
    }
    .pre-box-sale-block > h3 span.ttl::before {
        display: none;
        content: "";
        width: 40px;
        background-image: url(<?php echo PR_EL_PATH;?>/assets/images/sale/light-blur.png);
        background-repeat: no-repeat;
        background-size: 40px auto;
        top: 0;
        bottom: 1px;
        position: absolute;
        z-index: 2;
        -webkit-animation: light-ttl 3s infinite;
        -o-animation: light-ttl 3s infinite;
        animation: light-ttl 3s infinite;
        -webkit-animation: light-ttl 3s infinite 300ms 0 ease;
        -moz-animation: light-ttl 3s infinite 300ms 0 ease;
        -ms-animation: light-ttl 3s infinite 300ms 0 ease;
    }
    .pre-box-sale-block.animated > h3 span.ttl::before {
        display: inline-block;
    }

    @keyframes star {
        0% {
            filter: alpha(opacity=0);
            -webkit-opacity: 0;
            -moz-opacity: 0;
            opacity: 0;
            -webkit-transform: translateY(20px) scale(0, 0);
            -moz-transform: translateY(20px) scale(0, 0);
            -ms-transform: translateY(20px) scale(0, 0);
            -o-transform: translateY(20px) scale(0, 0);
        }
        20% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: translateY(0) scale(1, 1);
            -moz-transform: translateY(0) scale(1, 1);
            -ms-transform: translateY(0) scale(1, 1);
            -o-transform: translateY(0) scale(1, 1);
        }
        85% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: translateY(0) scale(1, 1);
            -moz-transform: translateY(0) scale(1, 1);
            -ms-transform: translateY(0) scale(1, 1);
            -o-transform: translateY(0) scale(1, 1);
        }
    }
    @-webkit-keyframes star {
        /*Safari and Chrome*/
        0% {
            filter: alpha(opacity=0);
            -webkit-opacity: 0;
            -moz-opacity: 0;
            opacity: 0;
            -webkit-transform: translateY(20px) scale(0, 0);
            -moz-transform: translateY(20px) scale(0, 0);
            -ms-transform: translateY(20px) scale(0, 0);
            -o-transform: translateY(20px) scale(0, 0);
        }
        20% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: translateY(0) scale(1, 1);
            -moz-transform: translateY(0) scale(1, 1);
            -ms-transform: translateY(0) scale(1, 1);
            -o-transform: translateY(0) scale(1, 1);
        }
        85% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: translateY(0) scale(1, 1);
            -moz-transform: translateY(0) scale(1, 1);
            -ms-transform: translateY(0) scale(1, 1);
            -o-transform: translateY(0) scale(1, 1);
        }
    }
    @-webkit-keyframes firework {
        /*Safari and Chrome*/
        0% {
            filter: alpha(opacity=0);
            -webkit-opacity: 0;
            -moz-opacity: 0;
            opacity: 0;
            -webkit-transform: scale3d(0, 0, 0);
            -moz-transform: scale3d(0, 0, 0);
            -ms-transform: scale3d(0, 0, 0);
            -o-transform: scale3d(0, 0, 0);
        }
        10% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: scale3d(1, 1, 1);
            -moz-transform: scale3d(1, 1, 1);
            -ms-transform: scale3d(1, 1, 1);
            -o-transform: scale3d(1, 1, 1);
        }
        85% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: scale3d(1, 1, 1);
            -moz-transform: scale3d(1, 1, 1);
            -ms-transform: scale3d(1, 1, 1);
            -o-transform: scale3d(1, 1, 1);
        }
    }
    @-moz-keyframes firework {
        /*Safari and Chrome*/
        0% {
            filter: alpha(opacity=0);
            -webkit-opacity: 0;
            -moz-opacity: 0;
            opacity: 0;
            -webkit-transform: scale3d(0, 0, 0);
            -moz-transform: scale3d(0, 0, 0);
            -ms-transform: scale3d(0, 0, 0);
            -o-transform: scale3d(0, 0, 0);
        }
        10% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: scale3d(1, 1, 1);
            -moz-transform: scale3d(1, 1, 1);
            -ms-transform: scale3d(1, 1, 1);
            -o-transform: scale3d(1, 1, 1);
        }
        85% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: scale3d(1, 1, 1);
            -moz-transform: scale3d(1, 1, 1);
            -ms-transform: scale3d(1, 1, 1);
            -o-transform: scale3d(1, 1, 1);
        }
    }
    @keyframes firework {
        0% {
            filter: alpha(opacity=0);
            -webkit-opacity: 0;
            -moz-opacity: 0;
            opacity: 0;
            -webkit-transform: scale3d(0, 0, 0);
            -moz-transform: scale3d(0, 0, 0);
            -ms-transform: scale3d(0, 0, 0);
            -o-transform: scale3d(0, 0, 0);
        }
        10% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: scale3d(1, 1, 1);
            -moz-transform: scale3d(1, 1, 1);
            -ms-transform: scale3d(1, 1, 1);
            -o-transform: scale3d(1, 1, 1);
        }
        85% {
            filter: alpha(opacity=100);
            -webkit-opacity: 1;
            -moz-opacity: 1;
            opacity: 1;
            -webkit-transform: scale3d(1, 1, 1);
            -moz-transform: scale3d(1, 1, 1);
            -ms-transform: scale3d(1, 1, 1);
            -o-transform: scale3d(1, 1, 1);
        }
    }
    @-webkit-keyframes light-ttl {
        /*Safari and Chrome*/
        0% {
            left: 0;
        }
        100% {
            left: 100%;
        }
    }
    @-moz-keyframes light-ttl {
        /*Safari and Chrome*/
        0% {
            left: 0;
        }
        100% {
            left: 100%;
        }
    }
    @keyframes light-ttl {
        0% {
            left: 0;
        }
        100% {
            left: 100%;
        }
    }

    .pre-box-sale-block .pre-box-sale__content {
        border: 1px solid #ddd;
        background: none;
        border-top: none;
        -webkit-border-radius: 0 0 4px 4px;
        -moz-border-radius: 0 0 4px 4px;
        -ms-border-radius: 0 0 4px 4px;
        -o-border-radius: 0 0 4px 4px;
        border-radius: 0 0 4px 4px;
        -moz-background-clip: padding;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        padding: var(--pre-box-sale-padding);
    }
</style>