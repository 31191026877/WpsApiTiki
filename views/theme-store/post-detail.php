<div class="object-detail post">
	<?php if(have_posts($object)) {?>
        <?php do_action('view_'.Template::getPage().'_before', $object);?>
		<div class="info" style="overflow: hidden;">
			<div class="pull-left">
				<div class="block"> <span class="post-time"> <i class="far fa-calendar"></i> <?php echo date('d/m/Y', strtotime($object->created));?></span> </div>
				<?php if( isset($category->name) ) : ?>
				<div class="block"> <span class="post-time"> <i class="fas fa-file-alt"></i> <?php echo $category->name;?></span> </div>
				<?php endif;?>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="excerpt"><?php echo $object->excerpt;?></div>
		<!-- content -->
		<div class="object-detail-content"><?php the_content();?></div>

        <?php do_action('view_'.Template::getPage().'_after', $object);?>
	<?php } ?>
</div>