<div class="box-content" style="overflow: hidden;">
	<form action="" method="post" class="form-horizontal" role="form" id="service-widget-license">
		<?php echo form_open(); ?>
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

		<div class="col-md-12">
			<div class="group text-right" style="padding-top:10px;">
				<button class="btn btn-icon btn-green" type="submit">Save</button>
			</div>
		</div>

	</form>
</div>
