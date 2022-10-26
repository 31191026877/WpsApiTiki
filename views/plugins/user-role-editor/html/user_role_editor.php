<form id="mainform" method="post">
	<?php echo form_open();?>

	<div class="box">
        <?php echo Admin::loading();?>
		<div class="box-content">
			<div class="row m-2" style="overflow: hidden;">
				<label class="col-md-6">Chọn vai trò và tùy chỉnh khả năng làm việc của vai trò đó</label>
				<div class="col-md-6">
					<select name="role_name" class="form-control" required="required" id="rolelist">
						<?php foreach ($role as $key => $name): ?>
						<option value="<?php echo $key;?>" <?php echo ($role_name == $key )?'selected':'';?> ><?php echo $name;?></option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<hr>
            <div class="row m-2">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-3 ure_caps_groups">
                            <ul id="ure_caps_groups_list">
                                <li id="ure_caps_group_all" class="active">Tất cả </li>
                                <?php foreach ( $role_group as $key => $value): ?>
                                    <li id="ure_caps_group_<?php echo $key;?>" class=""><?php echo $value['label'];?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                        <div class="col-md-9 scroll scrollbar">
                            <?php foreach ( $role_group as $key => $value): ?>
                                <div class="ure_caps_group ure_caps_group_<?php echo $key;?>">
                                    <h4 style="font-size: 18px;margin: 20px 0;"><?php echo $value['label'];?></h4>
                                    <?php foreach ($value['capabilities'] as $capabilities): ?>
                                        <?php if(!isset($role_label[$capabilities])) continue; ?>
                                        <div class="checkbox">
                                            <label> <input type="checkbox" class="icheck" name="capabilities[<?php echo $capabilities;?>]" value="1" <?php echo (!empty($role_current[$capabilities]))?'checked':'';?>> <?php echo $role_label[$capabilities];?> </label>
                                        </div>
                                    <?php endforeach ?>
                                    <hr />
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="button-action">
                        <button type="submit" class="btn btn-green d-block" style="width: 100%;">Cập nhật</button>
                        <hr />
                        <button type="button" class="btn btn-white d-block" style="width: 100%;" data-fancybox data-src="#hidden-content">Thêm nhóm quyền</button>
                    </div>
                </div>
            </div>
		</div>
	</div>
</form>

<!-- popup thêm menu -->
<div style="display: none; padding:10px; min-width: 350px;" id="hidden-content">

        <h4>THÊM NHÓM QUYỀN</h4>
		<hr />

        <form id="form-add-role" autocomplete="off">

			<div class="form-group">
				<label for="">Key Role</label>
				<input type="text" name="key" class="form-control" value="" required>
			</div>

			<div class="form-group">
				<label for="">Label Role</label>
				<input type="text" name="label" class="form-control" value="" required>
			</div>

            <div class="clearfix"></div>

            <div class="text-right">
            	<button class="btn-icon btn-green"><i class="fa fa-save"></i> Lưu</button>
            </div>
        </form>
</div>

<style type="text/css">
	.button-action {
		background-color: #F5F5F5;
		padding:10px;
		border-radius: 5px;
		border:1px solid #999;
	}
	.scroll { height: 400px; overflow: auto; border-left: 1px solid #ccc; }
	.ure_caps_groups ul li{
		padding:5px; cursor: pointer;
	}
	.ure_caps_groups ul li.active {
		background-color: #ccc;
	}
    .action-bar button[type="submit"] { display:none;}
</style>

<script defer>
  	$(function() {
      	$('#rolelist').change(function () {
        	window.location ="admin/system/role?role="+$(this).val()+"";
        	return true;
      	});

      	$('#ure_caps_groups_list li').click(function() {

      		let id = $(this).attr('id');

      		$('#ure_caps_groups_list li').removeClass('active');

      		$(this).addClass('active');

      		if( id === 'ure_caps_group_all' ) {

      			$('.ure_caps_group').show();
      		}
      		else {

      			$('.ure_caps_group').hide();

      			$('.'+id).show();
      		}
      	});

      	$('#mainform').submit(function() {

      		let loading = $(this).find('.loading');

			let data 		= $(this).serializeJSON();

			data.action     =  'AdminRoleAjax::save';

			loading.show();

			$.post(ajax, data, function() {}, 'json').done(function( data ) {
				loading.hide();
	  			show_message(data.message, data.status);
			});

			return false;

		});

		$(document).on('submit', '#form-add-role', function(event) {

			let data 		= $(this).serializeJSON();

			data.action     =  'AdminRoleAjax::add';

			$.post(ajax, data, function(data) {}, 'json').done(function( data ) {

				show_message(data.message, data.status);

				if(data.status === 'success') window.location.reload();
			});

			return false;
		});
  	});
</script>