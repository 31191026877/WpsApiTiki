<div class="object-detail">
	<?php if(have_posts($object)) {?>
        <?php do_action('view_'.Template::getPage().'_before', $object);?>
		<!-- content -->
		<div class="object-detail-content">
			<?php the_content();?>
		</div>
        <?php do_action('view_'.Template::getPage().'_after', $object);?>
	<?php } ?>
</div>
