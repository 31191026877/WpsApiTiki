<div class="clearfix"></div>
<div class="col-md-12">
	<div class="box">
		<div class="box-content" style="padding:10px 15px;">
			<a href="<?php echo Url::admin('plugins?page=theme-layout&type=layout');?>"      class="<?php echo ($type == 'layout') ? 'btn-red' : 'btn-green';?> btn-icon">LAYOUT</a>
			<a href="<?php echo Url::admin('plugins?page=theme-layout&type=header');?>"      class="<?php echo ($type == 'header') ? 'btn-red' : 'btn-green';?> btn-icon">HEADER</a>
			<a href="<?php echo Url::admin('plugins?page=theme-layout&type=navigation');?>"  class="<?php echo ($type == 'navigation') ? 'btn-red' : 'btn-green';?> btn-icon">NAVIGATION</a>
			<a href="<?php echo Url::admin('plugins?page=theme-layout&type=top-bar');?>"     class="<?php echo ($type == 'top-bar') ? 'btn-red' : 'btn-green';?> btn-icon">TOP BAR</a>
			<a href="<?php echo Url::admin('plugins?page=theme-layout&type=heading');?>"     class="<?php echo ($type == 'heading') ? 'btn-red' : 'btn-green';?> btn-icon">HEADING</a>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<?php 
    if($type == 'header' || $type == 'navigation' || $type == 'top-bar' ) {
        include_once 'theme_header_content.php';
    }
    else include_once 'theme_'.$type.'_content.php';
?>
<style>
	.header_service__img { padding-top:10px; }
	.header_service__img img { max-width:100%; display: inline-block; }
	.header_service__action { padding-top: 20px; }
</style>