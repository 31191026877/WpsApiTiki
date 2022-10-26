<div class="modal fade" id="js_user_modal__login">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="header">Đăng Nhập</h4>
            </div>
            <div class="modal-body">
                <?php echo Admin::loading('js_user_loading');?>
                <form action="" method="post" role="form" id="js_user_form__login">
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                </form>
            </div>
        </div>
    </div>
</div>