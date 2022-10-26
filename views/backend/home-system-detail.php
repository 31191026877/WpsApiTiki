<?php Admin::partial('include/action_bar');?>
<?php if(!isset($systemTab['form']) || $systemTab['form']) { ?>
<form id="system_form" method="post">
    <input type="hidden" name="system_tab_key" value="<?php echo $tabKey;?>">
    <?php Admin::loading();?>
    <div class="clearfix"></div>
    <?php } ?>
    <div role="tabpanel">
        <div class="tab-content" style="padding-top: 10px;">
            <?php call_user_func($systemTab['callback'], $ci, $tabKey ) ?>
            <?php do_action('admin_system_'.$tabKey.'_form_after', $systemTab, $tabKey);?>
        </div>
    </div>
    <?php if(!isset($systemTab['form']) || $systemTab['form'] == true) { ?>
</form>
<script type="text/javascript">
    $(function() {
        $('#system_form').submit(function() {
            let data 		= $(this).serializeJSON();
            $('textarea[type="code"]').each(function(index, el) {
                data[$(this).attr('name')] = editor[$(this).attr('name')].getValue();
            });
            data.action     =  'ajax_system_save';
            let load = $(this).find('.loading');
            load.show();
            $.post(ajax, data, function() {}, 'json').done(function( data ) {
                load.hide();
                show_message(data.message, data.status);
            });
            return false;
        });
    });
</script>
<?php } ?>