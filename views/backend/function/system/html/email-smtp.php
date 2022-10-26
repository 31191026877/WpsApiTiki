<div class="row">
    <div class="col-md-3">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h3 class="ui-title-bar__title" style="font-size:20px;">Thông tin SMTP</h3>
            <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">
                Thông tin gửi email thông qua hệ thống smtp
            </p>
            <a class="btn btn-blue" data-bs-toggle="modal" href="#modal-id">TEST SMTP</a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-content" style="padding:10px;">
                <div class="form-group">
                    <label for="input" class="control-label">Username (email):</label>
                    <input type="text" name="smtp-user" class="form-control" value="<?php echo Option::get('smtp-user','');?>" required="required">
                </div>
                <div class="form-group">
                    <label for="input" class="control-label">Password (Mã ứng dụng ):</label>
                    <input type="text" name="smtp-pass" class="form-control" value="<?php echo Option::get('smtp-pass','');?>" required="required">
                </div>
                <div class="form-group">
                    <label for="input" class="control-label">Server:</label>
                    <input type="text" name="smtp-server" class="form-control" value="<?php echo Option::get('smtp-server','');?>" required="required">
                </div>
                <div class="form-group">
                    <label for="input" class="control-label">Port:</label>
                    <input type="text" name="smtp-port" class="form-control" value="<?php echo Option::get('smtp-port','');?>" required="required">
                </div>
                <div class="form-group">
                    <label for="input" class="control-label">Encryption:</label>
                    <select name="smtp-encryption" id="smtp-encryption" class="form-control">
                        <option value="tls" <?php echo (Option::get('smtp-encryption','tls') == 'tls') ? 'selected' : '';?>>TLS</option>
                        <option value="ssl" <?php echo (Option::get('smtp-encryption','tls') == 'ssl') ? 'selected' : '';?>>SSL</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<hr />

<div class="modal fade" id="modal-id">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div id="smtp_form_test">
                        <div class="form-group">
                            <label for="input" class="control-label">Từ email (From email):</label>
                            <input type="text" name="smtp-test-from" class="form-control" value="<?php echo Option::get('contact_mail');?>">
                        </div>

                        <div class="form-group">
                            <label for="input" class="control-label">Đến email (To email):</label>
                            <input type="text" name="smtp-test-to" class="form-control" value="<?php echo Option::get('contact_mail');?>">
                        </div>

                        <div class="form-group">
                            <label for="input" class="control-label">Tên người gửi (Name):</label>
                            <input type="text" name="smtp-test-name" class="form-control" value="<?php echo Option::get('general_label');?>">
                        </div>

                        <div class="form-group">
                            <label for="input" class="control-label">Tiêu đề (subject):</label>
                            <input type="text" name="smtp-test-subject" class="form-control" value="Kiểm tra tính năng gửi email - <?php echo Option::get('general_label');?>">
                        </div>

                        <div class="form-group">
                            <label for="input" class="control-label">Nội dung (content):</label>
                            <textarea name="smtp-test-content" class="form-control" rows="3" required="required">Đây là nội dung kiểm tra!</textarea>
                        </div>
                        <div id="smtp_form_test_result"></div>
                        <hr />
                        <div class="col-xs-12 box-content text-right">
                            <button type="button" id="smtp_btn_test" class="btn-icon btn-green"><?php echo Admin::icon('save');?>Gửi</button>
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(function() {
        $('#smtp_btn_test').click(function() {
            let data 		= $( ':input', $('#smtp_form_test') ).serializeJSON();
            data.action     =  'ajax_email_smtp_test';
            load = $('.loading'); load.show();
            $jqxhr   = $.post(ajax, data, function() {}, 'json');
            $jqxhr.done(function( response ) {
                load.hide();
                show_message(response.message, response.status);
                $('#smtp_form_test_result').html(response.data);
            });
            $jqxhr.fail(function (response) { load.hide(); $('#smtp_form_test_result').html(response); });
            $jqxhr.always(function (response) { load.hide(); $('#smtp_form_test_result').html(response); });
            return false;
        });
    })
</script>