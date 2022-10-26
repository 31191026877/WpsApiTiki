<form class="form-horizontal col-md-7" method="post">
	<h1 class="user-header-title">Thông tin Tài khoản</h1>

	<?php $this->template->get_message();?>

	<?php echo form_open();?>

	<?php $user = Auth::user();?>

	<div class="row">

		<div class="form-group col-md-6">
			<label class="control-label">Họ và chữ lót</label>
			<div class="">
				<input name="firstname" type="text" class="form-control" placeholder="Họ" value="<?php echo $user->firstname;?>">
			</div>
		</div>

		<div class="form-group col-md-6">
			<label class="control-label">Tên</label>
			<div class="">
				<input name="lastname" type="text" class="form-control" placeholder="Tên" value="<?php echo $user->lastname;?>">
			</div>
		</div>

		<div class="form-group col-md-12">
			<label class="control-label">Số điện thoại</label>
			<div class="">
				<input name="phone" type="text" class="form-control" placeholder="Số điện thoại" value="<?php echo $user->phone;?>">
			</div>
		</div>

		<div class="form-group col-md-12">
			<label class="control-label">Email</label>
			<div class="">
				<input name="email" type="email" class="form-control" placeholder="email" value="<?php echo $user->email;?>">
			</div>
		</div>

		<div class="form-group col-md-12">
			<label class="control-label">Ngày Sinh</label>
			<div class="">
				<input name="birthday" type="date" class="form-control" value="<?php echo ( !empty(get_user_meta($user->id, 'birthday', true)) ) ? date('Y-m-d', strtotime(get_user_meta($user->id, 'birthday', true))) : '';?>">
			</div>
		</div>

		<div class="form-group col-md-12">
			<label class="control-label">Địa chỉ</label>
			<div class="">
				<input name="address" type="text" class="form-control" value="<?php echo get_user_meta($user->id, 'address', true);?>">
			</div>
		</div>

		<div class="form-group col-md-12">
			<button type="submit" class="btn btn-blue btn-effect-default">Cập Nhật</button>
		</div>
	  </div>
</form>





