<form action="" method="post" id="user_form__created">

    <?php Admin::partial('include/action_bar');?>

    <?php echo form_open();?>
    
    <div class="ui-layout customer-sections">
        <div class="ui-title-bar__group">
            <h1 class="ui-title-bar__title">Thêm mới nhân viên</h1>
            <div class="ui-title-bar__action">
                <?php do_action('user_created_header_action');?>
            </div>
        </div>
	</div>

    <div class="ui-layout customer-sections">
        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-content">
                        <div class="ui-layout__title row m-1"><h2 class="heading">Thông tin đăng nhập</h2></div>
                        <hr />
                        <div class="row m-1">
                            <?php echo $Form_login->html();?>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box-content">
                        <div class="ui-layout__title row m-1"><h2 class="heading">Thông tin cơ bản</h2></div>
                        <hr />
                        <div class="row m-1">
                            <?php echo $Form_info->html();?>
                        </div>
                    </div>
                </div>
                <?php
                /**
                 * customer_created_sections_primary
                 */
                echo do_action('user_created_sections_primary');
                ?>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-content">
                        <div class="ui-layout__title row m-1"><h2 class="heading">Ghi chú</h2></div>
                        <hr />
                        <div class="row m-1">
                            <div class="col-md-12 form-group">
                                <textarea name="note" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                /**
                 * customer_created_sections_secondary
                 */
                echo do_action('user_created_sections_secondary');
                ?>
            </div>
        </div>
    </div>
</form>