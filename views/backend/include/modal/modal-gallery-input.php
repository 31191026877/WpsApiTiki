<div class="modal fade" id="js_gallery_input_modal">
    <div class="modal-dialog">
        <div id="js_gallery_input_form">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                    <?php echo Admin::loading();?>
                    <?php
                    $FormBuilder = new FormBuilder();
                    $FormBuilder->add('', 'file', ['id' => 'gallery_input_value', 'label' => 'File dữ liệu']);
                    $FormBuilder->add('', 'text', ['id' => 'gallery_input_title', 'label' => 'Tiêu đề (alt)']);
                    $FormBuilder->html(false);
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-icon btn btn-white" data-bs-dismiss="modal"> <i class="fal fa-times"></i> Đóng</button>
                    <button id="js_gallery_input_btn__save" data-action="save" class="btn-icon btn-green" type="button"><?php echo Admin::icon('save');?> Lưu</button>
                    <button id="js_gallery_input_btn__save_close" data-action="save-close" class="btn-icon btn-green" type="button"><?php echo Admin::icon('save');?> Lưu & đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>