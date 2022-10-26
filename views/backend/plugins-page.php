<?php Admin::partial('include/action_bar');?>
<?php echo call_user_func( $callback_page['callback'], $this, get_model());?>