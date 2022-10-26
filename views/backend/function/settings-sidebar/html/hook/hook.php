<?php
global $wp_filter;
$listHook = apply_filters('setting_sidebar_hook_list', []);
?>
<div class="hook-list">
    <?php if(have_posts($listHook)) {?>
        <?php foreach ($listHook as $hookGroup) { ?>
            <h4 class="text-uppercase mb-0"><?php echo $hookGroup['name'];?></h4>
            <?php foreach ($hookGroup['list'] as $hookName) { ?>
                <div class="hook-group">
                    <h4 class="hook-header" data-id="#<?php echo $hookName;?>"><i class="fad fa-brackets"></i> <?php echo $hookName;?></h4>
                    <div class="hook-detail" id="<?php echo $hookName;?>">
                        <?php if(isset($wp_filter[$hookName]) && have_posts($wp_filter[$hookName])) {?>
                            <?php foreach ($wp_filter[$hookName]->callbacks as $position => $functions) { ?>
                                <?php foreach ($functions as $name => $function) { ?>
                                    <?php if(is_string($function['function'])) {?>
                                        <a href="http://developers.sikido.vn/docs/cms/v4-0-0/hooks#<?php echo $function['function'];?>" target="_blank"><i class="fad fa-directions"></i> <?php echo $function['function'];?> <span class="hook-position"><?php echo $position;?></span></a>
                                    <?php } else { ?>
                                        <a href="#"><i class="fad fa-directions"></i> class::<?php echo get_class($function['function'][0]);?> - Function:: <?php echo $function['function'][1];?> <span class="hook-position"><?php echo $position;?></span></a>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</div>
<style>
    .hook-list {overflow: hidden;}
    .hook-list .hook-header {
        padding:10px; border-radius: 5px;
        background-color:var(--btn-blue);
        color: #fff;
        cursor: pointer;
        font-size: 15px;
    }
    .hook-list .hook-detail {
        display: none;
        border:1px solid #ccc;
        padding:15px;
    }
    .hook-list .hook-detail.in { display: block;}
    .hook-list .hook-detail a {
        display: block; width: 100%;
        margin-bottom: 15px;
        position: relative;
    }
    .hook-list .hook-detail a span{
        position: absolute; width: 30px; height: 30px; line-height: 30px; text-align: center; border-radius: 50%;
        background-color: var(--btn-red); display: inline-block; right: 0; color:#fff;
    }
</style>
<script>
    $(function () {
        $('.hook-header').click(function () {
            let id = $(this).attr('data-id');
            $(id).toggleClass('in');
        });
    })
</script>
