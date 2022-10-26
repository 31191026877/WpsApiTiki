<?php $menus = ThemeMenu::getData('mobile-category-icon');?>
<div class="header-menu-mobile hidden-sm hidden-md hidden-lg">
    <?php foreach ($menus as $menu) { $menu->data['icon'] = (isset($menu->data['icon'])) ? $menu->data['icon'] : '';?>
        <div class="menu__item">
            <div class="img"><a href="<?php echo $menu->slug;?>"><?php Template::img($menu->data['icon'], $menu->name);?></a></div>
            <div class="title"><a href="<?php echo $menu->slug;?>"><?php echo $menu->name;?></a></div>
        </div>
    <?php } ?>
</div>

<div class="panel--sidebar" id="menu-category">
    <div class="panel__header">
        <h3>DANH MỤC</h3>
        <div class="panel__close"><a href="#" class="js_panel__close"><i class="fal fa-times"></i></a></div>
    </div>
    <div class="panel__content">
        <div class="mobile-category-icon">
            <?php foreach ($menus as $menu) { ?>
                <div class="menu__item">
                    <div class="img"><a href="<?php echo $menu->slug;?>"><?php Template::img($menu->data['icon'], $menu->name);?></a></div>
                    <div class="title"><a href="<?php echo $menu->slug;?>"><?php echo $menu->name;?></a></div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<style>
    .header-menu-mobile {
        padding:5px 10px;
        background-color: #E6E6E6;
        display: flex;
        overflow-x: auto;
        -ms-scroll-snap-type: x proximity;
        scroll-snap-type: x proximity;
        gap: 1rem;
    }
    .header-menu-mobile .menu__item {
        text-align: center;
        display: inline-block;
        scroll-snap-stop: normal;
        scroll-snap-align: start;
        flex: 0 0 auto;
        width: 20%; margin: 0 0 0 0px;
    }
    .header-menu-mobile .menu__item .img {
        height: 50px; width:50px;background-color: #fff; padding:12px; border-radius: 50%;
        text-align: center;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        display: inline-block;
    }
    .header-menu-mobile .menu__item:hover .img {
        background-color: var(--theme-color);
    }
    .header-menu-mobile .menu__item .title {
        color: #000;
        text-align: center;
        margin-top: 0px; line-height: 15px;
    }
    .header-menu-mobile .menu__item .title a {
        color: #000;
        text-align: center;
        line-height: 15px;
        font-size: 10px;
    }
    .mobile-category-icon {
        display: flex;
        flex-wrap: wrap;
        background-color: rgb(255, 255, 255);
        -webkit-box-pack: center;
        justify-content: center;
    }
    .mobile-category-icon .menu__item {
        display: block;
        text-align: center;
        width: 100%;
        max-width: calc(100% / 3 - 20px);
        color: rgb(51, 51, 51);
        margin: 12px 10px;
        padding: 10px 16px;
        text-decoration: none;
        border-radius: 10px;
        box-shadow: rgba(17, 17, 26, 0.05) 0px 1px 0px, rgba(17, 17, 26, 0.1) 0px 0px 8px;
    }
    .mobile-category-icon .menu__item .img {
        height: 70px; width:70px;background-color: #fff; padding:0; border-radius: 50%;
        text-align: center;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .mobile-category-icon .menu__item:hover {
        background-color: var(--theme-color);
    }
    .mobile-category-icon .menu__item .title {
        color: #000;
        text-align: center;
        margin-top: 0px;
    }
    .mobile-category-icon .menu__item .title a {
        color: #000;
        text-align: center;
    }
</style>