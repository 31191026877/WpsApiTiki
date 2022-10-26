<div class="modal fade" id="js_modal_confirm">
    <div class="modal-dialog" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-body">
                <p class="js_model_confirm__heading" style="font-weight: bold; font-size: 20px;">Xóa dữ liệu</p>
                <p class="js_model_confirm__description">Bạn muốn xóa trường dữ liệu này ?</p>
            </div>
            <div class="modal-footer" style="border: none;">
                <div style="display: flex;align-items:center; justify-content:space-between;">
                    <label for="toTrash"><input class="icheck" value="1" type="checkbox" name="toTrash" checked> Cho vào thùng rác</label>
                    <button type="button" class="btn-icon btn btn-white" data-bs-dismiss="modal"> <i class="fal fa-times"></i> Đóng</button>
                    <button id="js_modal_confirm_btn__save" class="btn-icon btn-red" type="button">Đồng ý</button>
                </div>
            </div>
        </div>
    </div>
</div>