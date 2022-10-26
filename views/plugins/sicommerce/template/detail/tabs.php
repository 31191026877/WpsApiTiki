<?php
/**
 * product_tabs filters
 * @hook product_detail_tab_default filters
 */
$tabs = apply_filters( 'product_tabs', []);
if(have_posts($tabs)): ?>
<div class="tab-box">
    <?php if(count($tabs) >= 1) {?>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs nav-pills" id="pills-tab" role="tablist">
		<?php foreach ( $tabs as $key => $tab ) : ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?php echo ($key == 'content') ? 'active' : '';?>" id="pills-<?= $key;?>-tab" data-bs-toggle="pill" data-bs-target="#pills-<?= $key;?>" type="button" role="tab" aria-controls="pills-<?= $key;?>" aria-selected="true"><?= __($tab['title'], 'product_tab_title_'.$key);?></a>
        </li>
		<?php endforeach; ?>
	</ul>
    <?php } ?>
	<!-- Tab panes -->
	<div class="tab-content" id="pills-tabContent">
		<?php foreach ( $tabs as $key => $tab ) : ?>
		<div class="tab-pane fade <?php echo ($key == 'content') ? 'show active' : '';?>" id="pills-<?= $key;?>" role="tabpanel">
			<?php call_user_func( $tab['callback'], $object ) ?>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif;?>
