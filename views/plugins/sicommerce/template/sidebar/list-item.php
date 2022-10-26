<div class="widget products-detail-box policy_intuitive">
    <div class="sidebar-title"><?php echo ThemeSidebar::heading($title);?></div>
    <div class="list-item">
        <?php foreach ($items as $item) { ?>
        <div class="pr-item">
            <div class="img">
                <?php Template::img($item['image'], $item['description']);?>
            </div>
            <div class="title">
                <?php if(!empty($item['title'])) {?> <h4><?php echo $item['title'];?></h4><?php } ?>
                <p><?php echo $item['description'];?></p>
            </div>
        </div>
        <?php } ?>
    </div>
</div>