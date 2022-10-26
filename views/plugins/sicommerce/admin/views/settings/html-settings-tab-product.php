<?php
$section 	= ((Request::get('section')) ? Request::get('section') : 'object' );
$tabs 		= Admin_Product_Setting::tabProductSub();
?>
<div class="section-list">
	<ul>
		<?php foreach ($tabs as $key => $tab): ?>
		<li class="<?php echo ($section == $key )?'active':'';?>"><a href="<?php echo Url::admin(sicommerce::url('setting'));?>&tab=product&section=<?= $key ?>"><?= $tab['label'];?></a></li>
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
	<?php call_user_func( $tabs[$section]['callback'], $ci, $section ) ?>
</div>

<script type="text/javascript">
	$(function() {
		$('#mainform').submit(function() {
			$('.loading').show();
			let data 		= $(this).serializeJSON();
			data.action     = 'Product_Admin_Setting_Ajax::save';
			$.post(ajax, data, function() {}, 'json').done(function(response) {
				$('.loading').hide();
	  			show_message(response.message, response.status);
			});
			return false;
		});
	});
</script>