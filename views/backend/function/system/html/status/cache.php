<div class="row">
    <div class="col-md-3">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h3 class="ui-title-bar__title" style="font-size:20px;">Cache</h3>
            <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">Quản lý cache hệ thống, cahe user, cahe post, cahce page ...</p>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-content" style="padding:10px;">
                <div class="row">
                    <?php
                    $form = new FormBuilder();

                    $form
                        ->add('cms_config[widget_cache]', 'switch', [
                            'label' => 'Bật / tắt cache widget',
                            'after' => '<div class="col-md-4"><div class="form-group group">', 'before'=> '</div></div>'
                        ], Cms::config('widget_cache'))
                        ->add('cms_config[widget_time]', 'number', [
                            'label' => 'Thời gian cache widget (phút)',
                            'after' => '<div class="col-md-8"><div class="form-group group">', 'before'=> '</div></div>'
                        ], Cms::config('widget_time'));
                    $form->html(false);
                    ?>
                </div>

                <?php echo notice('warning', 'Việc xóa cache ở đây chỉ liên quan đến các việc chỉnh sửa dữ liệu không liên quan đến cache giao diện được lưu ở trình duyệt.');?>
                <table class="table table-condensed table-hover">
                    <tbody>
                    <?php foreach (AdminCacheManager::getsObject() as $key => $item): ?>
                        <tr>
                            <td><?php echo $item['label'];?></td>
                            <td><button type="button" class="cache-clear btn btn-<?php echo $item['color'];?> btn-block" data-clear="<?php echo $key;?>"><?php echo $item['btnlabel'];?></button></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<hr />

<script type="text/javascript">
    $(function() {
        $('.cache-clear').click(function(event){
            let data = {
                action : 'ajax_admin_cache_clear',
                data : $(this).attr('data-clear'),
            };
            let jqxhr  = $.post(ajax, data, function() {}, 'json');
            jqxhr.done(function( data ) {
                if(data.status == 'success') {
                    show_message(data.message, data.status);
                }
            });
            return false;
        });
    });
</script>