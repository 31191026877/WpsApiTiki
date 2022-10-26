<form action="" method="post" id="js_user_form__edit">
    <?php echo Admin::loading();?>
    <input type="hidden" name="id" value="<?php echo $user->id;?>">
    <div class="box">
        <div class="box-content">
            <div class="row m-1">
                <div class="col-md-3">
                    <section class="ui-layout__section" style="overflow: hidden; border-right:1px solid #ccc;">
                        <div class="form-group">
                            <label class="control-label">Họ Tên</label>
                            <div class=""> <?= $user->firstname.' '.$user->lastname;?> </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <div class=""><?= $user->email;?></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Điện thoại</label>
                            <div class=""><?= $user->phone;?></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Địa chỉ</label>
                            <div class=""><?= get_user_meta($user->id, 'address', true);?></div>
                        </div>

                        <?php do_action('edit_user_profile_info', $user);?>

                    </section>
                </div>
                <div class="col-md-9">
                    <section class="ui-layout__section" style="overflow: hidden;">
                        <div class="row">
                            <?php $Form->html(false);?>
                            <?php do_action('edit_user_profile', $user);?>
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="submit" class="btn-icon btn-green"><?php echo Admin::icon('save');?>Lưu</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</form>
