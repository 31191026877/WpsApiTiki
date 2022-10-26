<aside class="product-category-sidebar box-sidebar">
    <div class="header-title"> <h2 class="header">Danh mục sản phẩm</h2></div>
    <div class="box-content">
        <div class="nav-item">
            <div class="product-category-menu">
                <div class="panelvmenu">
                    <?php foreach ($categories as $categoryLv1) { ?>
                        <a class="list-group-item-vmenu" href="<?php echo Url::permalink($categoryLv1->slug);?>"><?php echo $categoryLv1->name;?></a>
                        <?php if(have_posts($categoryLv1->child)) {?>
                            <a href="#path_<?php echo $categoryLv1->id;?>" data-toggle="collapse" data-parent="#MainMenu" class="icon arrow-sub-vmenu collapsed">
                                <i class="icon-show fal fa-angle-right"></i>
                            </a>
                            <div class="collapse" id="path_<?php echo $categoryLv1->id;?>" style="height: 0px;">
                                <?php foreach ($categoryLv1->child as $categoryLv2) { ?>
                                    <a class="list-group-item-vmenu" href="<?php echo Url::permalink($categoryLv2->slug);?>"><?php echo $categoryLv2->name;?></a>
                                    <?php if(have_posts($categoryLv2->child)) {?>
                                        <a href="#path_<?php echo $categoryLv2->id;?>" data-toggle="collapse" data-parent="#MainMenu" class="icon arrow-sub-vmenu collapsed"><i class="icon-show fal fa-angle-right pull-right"></i></a>
                                        <div class="collapse" id="path_<?php echo $categoryLv2->id;?>" style="height: 0px;">
                                            <?php foreach ($categoryLv2->child as $categoryLv3) { ?>
                                            <a class="list-group-item-vmenu" href="<?php echo Url::permalink($categoryLv2->slug);?>"><?php echo $categoryLv3->name;?></a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</aside>

<style>

    .sidebar .header-title {
        margin-bottom: 0;
    }
    .sidebar .header-title h2.header {
        color: #fff;
        padding: 20px 10px;
        margin-bottom: 0; margin-top: 0;
        background-color: var(--theme-color);
    }

    .product-category-sidebar {
        padding-bottom: 0px;
        background: #fff;
    }

    .product-category-sidebar .nav-item .product-category-menu  {
        z-index: 99;
        width: 100%;
    }

    .product-category-sidebar .nav-item .product-category-menu a.list-group-item-vmenu {
        font-size: 12px;
        padding: 10px 15px !important ;
        background-color: #fff;
        color:#000;
        line-height: 25px;
        display: block;
        text-transform: uppercase;
        width: 100%;
        border: 1px solid var(--theme-color);
        border-bottom: 1px dashed var(--theme-color);
        border-top: none;
    }

    .product-category-sidebar .nav-item .product-category-menu .icon {
        position: relative;
        z-index: 999;
        float: right;
        border-left: 1px solid #ccc;
    }
    .product-category-sidebar .nav-item .product-category-menu .icon i {
        position: absolute;
        font-size: 20px;
        top: -46px;
        color: var(--theme-color);
        right: 0px;
        height: 46px;
        width: 46px;
        text-align: center;
        line-height: 46px;
    }

    .product-category-sidebar .nav-item:last-child{
        border-bottom: none;
    }

    .product-category-sidebar #mobile-aside-content {
        padding-bottom: 0px
    }

    .product-category-sidebar .nav-item a:hover{
        color:  #fff;
        background-color: var(--theme-color);
    }

    .product-category-sidebar .nav-item.active>a {
        color: #fff
    }

    .product-category-sidebar .nav-item:hover>a {
        color: #fff;
        background-color:var(--theme-color);
    }
</style>