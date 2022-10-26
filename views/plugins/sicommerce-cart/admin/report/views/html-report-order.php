<?php
$section 	= ((Request::get('section')) ? Request::get('section') : 'revenue_time' );
$tabs 		= Admin_Cart_Report::tabOrderSub();
?>
<div class="section-list">
    <ul>
        <?php foreach ($tabs as $key => $tab): ?>
            <li class="<?php echo ($section == $key )?'active':'';?>"><a href="<?php echo Url::admin().sicommerce_cart::url('report');?>&tab=order&section=<?= $key ?>"><?= $tab['label'];?></a></li>
        <?php endforeach ?>
    </ul>
</div>

<style type="text/css">
    .section-list ul { overflow:hidden; }
    .section-list ul li { float: left; }
    .section-list ul li a { display: block; margin-right: 10px; position: relative; }
    .section-list ul li a:after { content: ''; position: relative; right:-5px; }
    .section-list ul li.active a { color:#000; }
</style>
<div class="clearfix"></div>
<div>
    <?php call_user_func( $tabs[$section]['callback'], $section ) ?>
</div>