let userID 			= 0;

let check 		= false;

let UserHandler = function() {
	$( document )
		.on( 'submit', '#form-check-pass', 	this.resetPassSecurity )
		.on( 'submit', '#form-reset-pass', 	this.resetPass )
		.on( 'submit', '#js_user_form__edit', 	this.edit )
		.on( 'submit', '#js_user_form__login', 	this.login )
		.on( 'submit', '#js_user_form__password', 	this.changePassword)
		.on( 'click', '.btn_login_as', 		this.loginAs )
		.on( 'click', '#btn_login_as_back', this.loginAsBack )

};

UserHandler.prototype.resetPassSecurity = function(e) {

	let data = {
		'action'   : 'Ajax_Admin_User_Action::resetPassword',
		'password' : $('#form-check-pass input[name="password"]').val(),
		'check'	   : check,
	};

	$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

	$jqxhr.done(function( response ) {

		if(response.status == 'success') {

			$('#form-check-pass').find('label').html('<span style="color:red">Mật khẩu mới</span>');

			$('#form-check-pass').find('input[name="password"]').val('');

			$('#form-check-pass').attr('id','form-reset-pass');

			check = true;
		}
		else {
			show_message(response.message, response.status);
		}
    });

    return false;
};
UserHandler.prototype.resetPass = function(e) {

	let data = {
		'action'	: 'Ajax_Admin_User_Action::resetPassword',
		'password' 	: $('#form-reset-pass input[name="password"]').val(),
		'id'	   	: userID,
		'check'	   	: check,
	};

	$.post(ajax, data, function(data) {}, 'json').done(function( response ) {
		show_message(response.message, response.status);
		if(response.status === 'success') {

			$('#modalreset').modal('hide');

			$('#form-reset-pass').find('label').html('Mật Khẩu của bạn');

			$('#form-reset-pass').find('input[name="password"]').val('');

			$('#form-reset-pass').attr('id','form-check-pass');

			check = false;

			userID = 0;
		}
    });

    return false;
};
UserHandler.prototype.login = function(e) {

	$('#js_user_loading').show();

	let data = {
		'action'	: 'Ajax_Admin_User_Action::login',
		'username' : $(this).find('input[name="username"]').val(),
		'password' : $(this).find('input[name="password"]').val(),
	};

	$.post(ajax, data, function(data) {}, 'json').done(function( response ) {
		$('#js_user_loading').hide();
		show_message(response.message, response.status);
		if(response.status === 'success') {
			$('#js_user_modal__login').modal('hide');
		}
	});

	return false;
};
UserHandler.prototype.loginAs = function(e) {
	let data = {
		'action'	: 'Ajax_Admin_User_Action::loginAs',
		'id'	   	: $(this).attr('data-id'),
	};

	$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

	$jqxhr.done(function( response ) {
		if(response.status === 'success') {
			window.location.replace("admin");
		}
		else {
			show_message(response.message, response.status);
		}
	});

	return false;
};
UserHandler.prototype.loginAsBack = function(e) {
	let data = { 'action'	: 'Ajax_Admin_User_Action::loginAsBackRoot', };
	$jqxhr   = $.post(ajax, data, function(data) {}, 'json');
	$jqxhr.done(function( response ) {
		if(response.status === 'success') {
			window.location.replace('admin/user');
		}
		else {
			show_message(response.message, response.status);
		}
	});
	return false;
};
UserHandler.prototype.edit = function(e) {
	let loading = $(this).find('.loading');
	let data = $(this).serializeJSON();
	loading.show();
	data.action = 'Ajax_Admin_User_Action::saveProfile';
	$.post(ajax, data, function(data) {}, 'json').done(function( response ) {
		show_message(response.message, response.status);
		loading.hide();
	});
	return false;
};
UserHandler.prototype.changePassword = function(e) {
	let loading = $(this).find('.loading');
	let data = $(this).serializeJSON();
	loading.show();
	data.action = 'Ajax_Admin_User_Action::changePassword';
	$.post(ajax, data, function(data) {}, 'json').done(function( response ) {
		show_message(response.message, response.status);
		loading.hide();
	});
	return false;
};

$(function() {
	new UserHandler();
});

$(document).on('click', '.btn-reset-pass', function(){
	userID  = $(this).attr('href');
	$('#modalreset').modal('show');
	return false;
});

$('.user-trash').bootstrap_confirm_delete({
	heading:'Xác nhận xóa',
	message:'Bạn muốn xóa trường dữ liệu này ?',
	callback:function ( event ) {
		let del_id = event.data.originalObject.attr('data-id');
		$.post(ajax, { 'action' : 'Ajax_Admin_User_Action::moveTrash', id : del_id }, function(data) {}, 'json').done(function(response) {
		    show_message(response.message, response.status);
		    if(response.status === 'success') {
				let button = event.data.originalObject;
        		button.closest( 'tr' ).remove();
		    }
		});
    },
});

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    let regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}