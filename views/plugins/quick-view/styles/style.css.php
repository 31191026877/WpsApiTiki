<style type="text/css">
    .box_quickview {
        position: absolute;
        text-align: center;
        z-index: 9;
        opacity: 0;
        transition: all 0.5s;
        font-size: 14px;
    }
    /*-- Style 1--*/
    .box_quickview_style_1 {
        bottom:-25px; left:5px; width:calc(100% - 10px);
        display: flex;
    }
    .box_quickview_style_1 a {
        flex: 1 1 0px;
        display: block;
        background-color: var(--theme-color);
        color: #fff;
        padding:5px 10px;
        margin-right: 5px;
        transition: all 0.5s;
    }
    .box_quickview_style_1 a:last-child {
         margin-right: 0;
    }
    .product-slider-horizontal .item:hover .box_quickview_style_1 {
        opacity: 1;
        bottom:5px;
    }
    .product-slider-horizontal .item:hover .box_quickview { opacity: 1; }
    /*-- Style 2--*/
    .box_quickview_style_2 { right:-35px; top:15px; }
    .box_quickview_style_2 a {
        display: block;
        width: 40px; height: 40px; line-height: 40px; text-align: center;
        border-radius: 50%;
        background-color: var(--theme-color);
        color: #fff;
        margin-bottom: 5px;
        transition: all 0.5s;
    }
    .product-slider-horizontal .item:hover .box_quickview_style_2 {
        opacity: 1;
        right:15px;
    }
    /*-- Style 3--*/
    .box_quickview_style_3 {
        max-width: 100%;
        padding: 0 20px;
        top: 40%;
        left: 50%;
        -webkit-transform: translate(-50%,-50%);
        transform: translate(-50%,-50%);
        z-index: 2;
        white-space: nowrap;
        visibility: hidden;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        -webkit-box-orient: vertical;
        flex-direction: column;
    }
    .box_quickview_style_3 a {
        position: relative;
        display: block;
        padding: 0;
        font-size: 12px;
        margin: 5px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto;
        max-width: 100%;
        box-shadow: 1px 1px 1px rgba(0,0,0,.1);
        border-radius: 40px;
        text-transform: capitalize;
        background: #4e97fd;
        color: #ffffff;
        opacity: 1;
    }
    .box_quickview_style_3 a i {
        position: absolute;
        top: 0;
        left: 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #fff;
        font-size: 20px;
        -webkit-transition: opacity .15s,-webkit-transform .25s;
        transition: opacity .15s,transform .25s,-webkit-transform .25s;
        -webkit-transform: translateY(100%);
        transform: translateY(100%);
    }
    .box_quickview_style_3 a>span {
        max-width: 100%;
        left: 50%;
        z-index: 2;
        white-space: nowrap;
        font-size: 14px;
        margin: 5px 0;
        overflow: hidden;
        text-overflow: ellipsis;
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto;
        display: block;
        padding: 5px 25px;
        -webkit-transition: opacity .15s,-webkit-transform .25s;
        transition: transform .25s,opacity .15s,-webkit-transform .25s;
        background: #4e97fd;
        color: #ffffff;
        text-transform: capitalize;
        opacity: 1;
        visibility: visible;
        top: 50%;
    }
    .box_quickview_style_3 a:hover {
        background: #222;
        color: #fff;
    }
    .box_quickview_style_3 a:not(:hover):after {
        border-color: rgba(0,0,0,.3);
        border-left-color: #fff;
    }
    .box_quickview_style_3 a:after {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -9px;
        margin-left: -9px;
        opacity: 0;
        -webkit-transition: opacity .2s;
        transition: opacity .2s;
        content: "";
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 1px solid rgba(255,255,255,.3);
        border-left-color: #fff;
        border-radius: 50%;
        vertical-align: middle;
    }
    .box_quickview_style_3 a:hover i {
        -webkit-transform: translateY(0) translateZ(0);
        transform: translateY(0) translateZ(0);
    }
    .box_quickview_style_3 a:hover>span {
        -webkit-transform: translateY(-120%) translateZ(0);
        transform: translateY(-120%) translateZ(0);
    }
    .product-slider-horizontal .item:hover .box_quickview_style_3 {
        opacity: 1;
        visibility: visible;
        top: 50%;
    }

    .fancybox-slide--ajax .products-detail {
        max-width  : 80%;
        max-height : 80%;
        margin: auto;
    }
    .fancybox-slide--ajax .row {
        display: flex;
        flex-wrap: wrap;
        margin-top: calc(-1 * var(--bs-gutter-y));
        margin-right: calc(-.5 * var(--bs-gutter-x));
        margin-left: calc(-.5 * var(--bs-gutter-x));
    }
</style>