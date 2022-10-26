<?php

	$layout_setting = get_theme_layout_setting();

	$col = '';

	if($layout_setting['style'] == 'horizontal') {

		$col = [];

		$col['lg'] = ( $layout_setting['horizontal']['category_row_count'] != 5) ? 12/$layout_setting['horizontal']['category_row_count'] : 15;
		$col['md'] = ( $layout_setting['horizontal']['category_row_count'] != 5) ? 12/$layout_setting['horizontal']['category_row_count'] : 15;
		$col['sm'] = ( $layout_setting['horizontal']['category_row_count_tablet'] != 5) ? 12/$layout_setting['horizontal']['category_row_count_tablet'] : 15;
		$col['xs'] = ( $layout_setting['horizontal']['category_row_count_mobile'] != 5) ? 12/$layout_setting['horizontal']['category_row_count_mobile'] : 15;

		$col = 'col-xs-'.$col['xs'].' col-sm-'.$col['sm'].' col-md-'.$col['md'].' col-lg-'.$col['lg'].'';
	}
?>
<div class="post post-index">
    <?php do_action('view_'.Template::getPage().'_before', $category, $objects);?>
    <?php echo '<div class="row">';
    if(have_posts($objects)) {
        foreach ($objects as $key => $val) {
            if ($layout_setting['style'] == 'vertical') {
                Template::partial('include/loop/item_post', array('val' => $val));
            } else {
                echo '<div class="col-md-' . $col . '">';
                Template::partial('include/loop/item_post_horizontal', array('val' => $val));
                echo '</div>';
            }
        }
    }
    echo '</div>';
    ?>
    <?php do_action('view_'.Template::getPage().'_after', $category, $objects, (isset($pagination)) ? $pagination : []);?>
</div>