<?php Admin::partial('include/action_bar');
	$view 		= Request::get('view');
	$view 		= (empty($view)) ? 'profile' : $view;
	$action_tab = admin_my_action_links();
?>

<?php if(isset($object) && have_posts($object)) { ?>
<div class="ui-layout">
    <div class="ui-title-bar__group">
        <h1 class="ui-title-bar__title"><?php echo $object->firstname.' '.$object->lastname;?></h1>
        <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">Tài khoản <?php echo $object->username;?> - <?php echo User::getRoleName($object->id);?></p>
        <div class="ui-title-bar__action">
            <?php foreach ($action_tab as $key => $tab): ?>
            <a href="<?php echo URL_ADMIN;?>/users/edit?view=<?php echo $key;?>&id=<?php echo $object->id;?>" class="<?php echo ($view == $key)?'active':'';?> btn btn-default">
                <?php echo (isset($tab['icon'])) ? $tab['icon'] : '<i class="fal fa-layer-plus"></i>';?>
                <?php echo $tab['label'];?>
            </a>
            <?php endforeach ?>
            <?php do_action('user_detail_header_action', $user);?>
        </div>
    </div>
</div>
<?php call_user_func( $action_tab[$view]['callback'], $object, model('users'), $action_tab[$view]);
}
else {
    echo notice('danger','Không có dữ liệu để hiển thị');
}