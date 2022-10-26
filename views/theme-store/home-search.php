<?php
$type 		= Str::clear(Request::get('type'));
$keyword 	= Str::clear(Request::get('keyword'));
$args = Qr::set()->where('title', 'like', '%tuyệt%');
$arr = Posts::gets($args);
show_r($arr);
?>
<?php if ($type == 'post' || $type == null) : ?>
	<?php if (have_posts($objects)) : ?>
		<div class="search-page">
			<div class="post">
				<?php foreach ($objects as $key => $val) : ?>
					<?php Template::partial('include/loop/item_post', array('val' => $val)); ?>
				<?php endforeach ?>
			</div>
		</div>
	<?php else : ?>
		<?php echo notice('error', 'Không có kết quả tim kiếm cho từ khóa'); ?>
	<?php endif ?>
<?php endif ?>
<?php do_action('get_search_view', $objects, $type, $keyword); ?>