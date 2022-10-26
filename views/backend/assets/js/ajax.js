$(function () {
	let buttonConfirm;
	let modelConfirm 		= $('#js_modal_confirm');
	let formInput 	 		= $('#form-input');
	let formLoading 		= formInput.find('.loading');
	let formCategoryInput 	= $('#form-input-category');
	let formCategoryLoading = formCategoryInput.find('.loading');
	$(document).on('ifClicked', '.up-boolean', function(event) {
		let data = {
			action 	: 'Ajax_Admin_Table_Form::saveInputBoolean',
			id 		: $(this).attr('data-id'),
			table 	: $(this).attr('data-model'),
			row 	: $(this).attr('data-row'),
		};
		$.post(ajax, data, function(data) {}, 'json').done(function(response) {
			show_message(response.message, response.status);
		});
	});
	$(document).on('click', 'button.js_admin_form_btn__save', function() {
		formInput.trigger('submit');
		return false;
	});
	$(document).on('submit','#form-input', function() {
		let data 	= formInput.serializeJSON();
		formInput.find('textarea.tinymce, textarea.tinymce-shortcut').each(function(index, el) {
			let textareaId 	= $(this).attr('id');
			data[$(this).attr('name')] = document.getElementById(textareaId+'_ifr').contentWindow.document.body.innerHTML;
		});
		data.action = 'Ajax_Admin_Table_Form::save';
		data.module = formInput.attr('data-module');
		console.log(data);
		formLoading.show();
		$.post(ajax + urlType, data, function(data) {}, 'json').done(function(response) {
			formLoading.hide();
			show_message(response.message, response.status);
			if(response.status === 'success') {
				if(typeof response.redirect != 'undefined') {
					location.href = response.redirect;
				}
			}
		});
		return false;
	});
	$(document).on('submit','#form-input-category', function () {
		formCategoryLoading.show();
		let $this = $(this);
		let data = $(this).serializeJSON();
		formCategoryInput.find('textarea.tinymce, textarea.tinymce-shortcut').each(function(index, el) {
			let textareaId 	= $(this).attr('id');
			data[$(this).attr('name')] = document.getElementById(textareaId+'_ifr').contentWindow.document.body.innerHTML;
		});
		data.action = 'Ajax_Admin_Table_Form::saveCategory';
		data.module = $this.attr('data-module');
		$.post(ajax + urlType, data, function (data) {}, 'json').done(function (response) {
			show_message(response.message, response.status);
			formCategoryLoading.hide();
			if (response.status === 'success') {
				let select = 0;
				if (isset(formCategoryInput.find('select#parent_id').val())) {
					select = $this.find('select#parent_id').val();
				}
				formCategoryInput.trigger('reset');
				if (isset(formCategoryInput.find('select#parent_id').val())) {
					formCategoryInput.find('select#parent_id').html(response.parent_id);
					formCategoryInput.find('select#parent_id').val(select);
				}
				$('#form-action').html(response.item);
				checkbox_style();
			}
		});

		return false;
	});
	$(document).on('click', '.js_btn_confirm', function() {
		buttonConfirm = $(this);
		if(buttonConfirm.data('trash') !== 'enable') {
			modelConfirm.find('label[for=toTrash]').hide();
		}
		else {
			modelConfirm.find('label[for=toTrash]').show();
			modelConfirm.find('input[name=toTrash]').iCheck('check');
		}
		modelConfirm.find('.js_model_confirm__heading').html(buttonConfirm.data('heading'));
		modelConfirm.find('.js_model_confirm__description').html(buttonConfirm.data('description'));
		modelConfirm.modal('show');
		return false;
	});
	$(document).on('click', '#js_modal_confirm_btn__save', function() {

		let listId = buttonConfirm.attr('data-id');

		let action = buttonConfirm.attr('data-action');

		if(!isset(listId)) {
			listId = []; let i = 0;
			$('.select:checked').each(function () { listId[i++] = $(this).val(); });
		}

		if(listId == null || listId.length === 0) {
			show_message('Bạn chưa chọn trường dữ liệu nào để xóa', 'error');
			return false;
		}

		let data = {
			action : buttonConfirm.data('ajax'),
			data   : listId,
			module : buttonConfirm.data('module'),
		};

		if(action == 'delete' && buttonConfirm.data('trash') == 'enable') {
			data.trash =  0;
			modelConfirm.find('input[name=toTrash]:checked').each(function () { data.trash = $(this).val(); });
		}

		$.post(ajax, data, function() {}, 'json').done(function(response) {
			modelConfirm.modal('hide');
			show_message(response.message, response.status);
			if(response.status === 'success') {
				if (typeof response.data != 'undefined') {
					let count = response.data.length;
					for (let i = 0; i < count; i++) {
						$('.tr_'+response.data[i]).hide('fast').remove();
					}
					buttonConfirm.hide();
				}
				else {
					buttonConfirm.closest('.js_column').remove();
				}
			}
			if(response.status === 'reload') { location.reload();}
		});

		return false;
	});
	//upload datatable
	$('.edittable-dl-number').editable({
		type 	: 'number',
		params: function(params) {
			// add additional params from data-attributes of trigger element
			params.action = 'Ajax_Admin_Table_Form::saveTableEdit';
			params.table = $(this).editable().attr('data-table');
			return params;
		},
		url 	: ajax,
	});
	$('.edittable-dl-text').editable({
		type: 'text',
		params: function(params) {
			// add additional params from data-attributes of trigger element
			params.action = 'Ajax_Admin_Table_Form::saveTableEdit';
			params.table = $(this).editable().attr('data-table');
			return params;
		},
		url: ajax,
	});
});