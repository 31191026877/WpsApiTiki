<?php $tabs = Admin_Product_Options_Detail::tabs();?>
<div class="col-12 product_options" id="product_options" data-object-id="<?php echo (isset($object->id))?$object->id:0;?>" data-session-id="0">
	<input type="hidden" name="product_options_session_id" value="0">
	<input type="hidden" name="product_options_product_id" value="<?php echo (isset($object->id))?$object->id:0;?>">

	<div class="tab-content">
		<?php foreach ($tabs as $key => $tab): ?>
        <div class="heading"><?php echo $tab['label'];?></div>
		<div class="active show" id="<?php echo $key;?>">
			<?php call_user_func( $tab['callback'], $object, $tab ) ?>
		</div>
		<?php endforeach ?>
	</div>
</div>

<style>
    .product_options .heading {
        padding:10px;
        background-color: var(--content-bg);
        margin-bottom: 10px;
        font-weight: bold;
    }
</style>

<div class="modal fade" id="js_product_options_modal_confirm" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-body">
                <p class="js_model_confirm__heading" style="font-weight: bold; font-size: 20px;">Xác nhận xóa</p>
                <p class="js_model_confirm__description">Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b></p>
            </div>
            <div class="modal-footer" style="border: none;">
                <div style="display: flex;align-items:center; justify-content:space-between;">
                    <button type="button" class="btn-icon btn btn-white" data-bs-dismiss="modal"> <i class="fal fa-times"></i> Đóng</button>
                    <button id="js_product_options_modal_confirm_btn__save" class="btn-icon btn-red" type="button">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div>
