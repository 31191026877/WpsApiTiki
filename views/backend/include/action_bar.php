<?php
$permission_access = apply_filters('admin_permission_access', true);

if($permission_access == false) {
    Admin::partial('404-error');
    exit();
}
?>
<div class="action-bar">
    <div class="ui-layout">
    <?php
        /**
         * Hook hiển thị khi bắt đầu action bar
         */
        do_action( 'action_bar_before', $module );
        /**
         * Hook hiển thị khi kết thúc action bar
         */
        do_action( 'action_bar_after', $module );
    ?>
    </div>
</div>

<div class="col-md-12"><?php admin_notices();?></div>

<div class="clearfix"></div>

