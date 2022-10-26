<?php
	$object_id = (!empty($object->id)) ? $object->id : 0;
	if($module == 'post') $module .= '_'.$ci->postType;
	if($module == 'post_categories') $module .= '_'.$ci->cateType;
?>
<div class="box gallery-object-box" style="overflow:hidden" id="gallery_object_box">
	<div class="header" style="display: block!important;">
		<h3 class="float-start">Thư Viện</h3>
		<div class="gallery-object-action" style="overflow: hidden;">
			<div class="float-end">
                <button style="display: none;" id="js_gallery_object_btn__del" class="btn-icon btn-red del-img" type="button"><?php echo Admin::icon('delete');?>Xóa</button>
                <button id="js_gallery_object_btn__checkall" class="btn-icon btn-blue" type="button"><i class="fad fa-tasks"></i> Chọn Hết</button>
			</div>
		</div>
	</div>
	<div class="box-content collapse in">
		<div class="tab-content gallery-object-tabs">
			<?php include 'gallery-tab.php';?>
		</div>
	</div>
    <div class="hidden">
        <input type="hidden" id="gallery_object_id" value="<?php echo $object_id;?>" >
        <input type="hidden" id="gallery_object_key" value="<?php echo $module;?>" >
    </div>
</div>

<div class="modal fade" id="js_gallery_object_modal">
    <div class="modal-dialog">
        <div id="js_gallery_object_form">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                    <?php echo Admin::loading();?>
                    <?php Admin::partial('function/gallery/html/gallery-form');?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-icon btn btn-white" data-bs-dismiss="modal"> <i class="fal fa-times"></i> Đóng</button>
                    <button id="js_gallery_object_btn__save" data-action="save" class="btn-icon btn-green" type="submit"><?php echo Admin::icon('save');?> Lưu</button>
                    <button id="js_gallery_object_btn__save_close" data-action="save-close" class="btn-icon btn-green" type="button"><?php echo Admin::icon('save');?> Lưu & đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
	.modal-body {
		overflow:hidden;
	}
</style>