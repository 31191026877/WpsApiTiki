<div class="modal fade" id="js_hot_key_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?php $hotKeyList = apply_filters('admin_list_hot_key', [
                    'system' => ['key' => 'F6', 'label' => 'Cấu hình hệ thống'],
                    'option' => ['key' => 'F7', 'label' => 'Cấu hình giao diện'],
                    'widgets' => ['key' => 'F8', 'label' => 'Cấu hình widget'],
                    'back' => ['key' => 'CTRL + B', 'label' => 'Quay lại trang trước'],
                    'help' => ['key' => 'CTRL + H', 'label' => 'Mở bảng trợ giúp'],
                ]); ?>
                <?php foreach ($hotKeyList as $item) { ?>
                    <p><strong><?php echo $item['key'];?></strong> : <?php echo $item['label'];?></p>
                <?php } ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->