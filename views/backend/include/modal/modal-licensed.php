<div class="modal fade" id="js_licensed_modal__info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding: 0;">
                <h4 class="header" style="margin: 0;">Licensed</h4>
            </div>
            <div class="modal-body">
                <?php echo Admin::loading();?>
                <form action="" method="post" role="form" id="js_licensed_form__save">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="api_user" class="control-label">API USERNAME</label>
                            <div class="group">
                                <input type="text" name="api_user" value="<?php echo Option::get('api_user');?>" id="api_user" class="form-control ">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="api_secret_key" class="control-label">SECRET KEY</label>
                            <div class="group">
                                <input type="text" name="api_secret_key" value="<?php echo Option::get('api_secret_key');?>" id="api_secret_key" class="form-control">
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>