<div class="widget">
    <div class="sidebar-title"><?php echo ThemeSidebar::heading(__('Tin Tức Liên Quan', 'product_heading_new_related'));?></div>
    <div class="sidebar-content box-content post-slider-vertical">
        <?php foreach ($posts as $key => $val): ?>
            <?php Template::partial('include/loop/item_post_sidebar', ['val' => $val]);?>
        <?php endforeach ?>
    </div>
</div>
<style>
    .post-slider-vertical .item {
        margin-bottom: 10px;
        overflow: hidden;
    }
    .post-slider-vertical .item .img {
        float: left;
        width: 80px;
        box-shadow: 0 0 5px 0 rgba(72,139,216,.2);
        border-radius: 5px;
        overflow: hidden;
    }
    .post-slider-vertical .item .img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .post-slider-vertical .item .title {
        float: left; padding-left: 10px;
        width: calc(100% - 80px);
    }
    .post-slider-vertical .item .title h3 {
        font-size: 13px;
        font-weight: 500;
        color: #323c3f;
        line-height: 20px;
        letter-spacing: 0.01em;
        margin-top: 0;
    }
    .post-slider-vertical .item .title h3 a {
        color: #323c3f;
    }
    .post-slider-vertical .item .title h3 a:hover {
        color: var(--theme-color);
    }
    .post-slider-vertical .item .img:hover {
        box-shadow: 0 7px 10px 0 rgba(72,139,216,.2);
    }
</style>