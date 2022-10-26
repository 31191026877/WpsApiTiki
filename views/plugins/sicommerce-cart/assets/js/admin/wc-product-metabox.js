let productOptionsBox = $('#product_options');
$(document).ready(function() {
	let sel = $('.attribute_values').select2();
	if( isset($('#result-attributes-items').html()) ) product_attributes_load();
	if( isset($('#result-variations-items').html()) ) product_variations_load();
	if( isset($('#result-option-img-items').html()) ) product_option_img_load();
});

/**
 * [ATTRIBUTE]
 */
$(document).on('click', '#add-group-attributes .save-attributes', function() {

	let id = $('#add-group-attributes').find('select#product_option_id').val();

	let check = true;

	$('#result-attributes-items .attributes-item .attribute-del').each(function (){
		let groupId = $(this).data('id');
		if(typeof groupId != "undefined" && groupId == id) {
			show_message('Nhóm thuộc tính này đã tồn tại', 'error');
			check = false;
			return false;
		}
	});
	if(check == false) return false;

	let data = {
		'action' : 'Admin_Product_Options_Detail_Ajax::addAttribute',
		id : id,
		object_id : productOptionsBox.attr('data-object-id'),
	};

	let jqxhr   = $.post(ajax, data, function() {}, 'json');

	jqxhr.done(function(response) {
		show_message(response.message, response.status);
		if(response.status === 'success') {
			$('#result-attributes-items').append(response.data);
			$('.attribute_values').select2();
		}
	});

	return false;
});

$(document).on('select2:select', 'select.attribute_values', function () {

	let id = productOptionsBox.attr('data-object-id');

	let panel = $(this).closest('.attributes-group');

	let data = $(':input', panel).serializeJSON();

	data.action = 'Admin_Product_Options_Detail_Ajax::saveAttribute';

	data.object_id = id;

	data.session_id = productOptionsBox.attr('data-session-id');

	let jqxhr = $.post(ajax, data, function () { }, 'json');

	jqxhr.done(function (response) {
		if (response.status === 'success') {
			if (id == 0) {
				productOptionsBox.attr('data-session-id', response.session_id);
				productOptionsBox.find('input[name="product_options_session_id"]').val(response.session_id);
			}
			product_variations_load();
			product_option_img_load();
		}
		else {
			show_message(response.message, response.status);
		}
	});

	return false;
});

$(document).on('select2:unselect', 'select.attribute_values', function (event) {

	let id = productOptionsBox.attr('data-object-id');

	let sel = $(this);

	let panel = $(this).closest('.attributes-group');

	let data = $(':input', panel).serializeJSON();

	data.action = 'Admin_Product_Options_Detail_Ajax::saveAttribute';

	data.object_id = id;

	data.session_id = productOptionsBox.attr('data-session-id');

	let jqxhr = $.post(ajax, data, function () { }, 'json');

	jqxhr.done(function (response) {
		if (response.status === 'success') {
			if (id == 0) {
				productOptionsBox.attr('data-session-id', response.session_id);
				productOptionsBox.find('input[name="product_options_session_id"]').val(response.session_id);
			}
			product_variations_load();
			product_option_img_load();
		}
		else {
			show_message(response.message, response.status);
			let selValue = sel.val();
			if(selValue == null) {
				selValue = [];
			}
			selValue.push(event.params.data.id);
			sel.val(selValue);
			sel.trigger('change');
		}
	});

	return false;
});

$(document).on('click', '.save-group-attributes', function() {

	let id = productOptionsBox.attr('data-object-id');

	let panel = $('.tab-pane.active .panel');

	let data = $( ':input', panel.find('.panel-body') ).serializeJSON();

	data.action 	= 'Admin_Product_Options_Detail_Ajax::saveAttribute';
	data.object_id 	= id;
	data.session_id = productOptionsBox.attr('data-session-id');

	$jqxhr   = $.post(ajax, data, function() {}, 'json');

	$jqxhr.done(function(response) {
		show_message(response.message, response.status);
		if(response.status === 'success') {
			if(id === 0) {
				productOptionsBox.attr('data-session-id', response.session_id);
				productOptionsBox.find('input[name="product_options_session_id"]').val(response.session_id);
			}
			product_variations_load();
		}
	});

	return false;
});

let confirmType, confirmButton, confirmId = 0;

let confirmModel 		= $('#js_product_options_modal_confirm');

$(document).on('click', '.attribute-del', function(event)  {
	confirmType = 'attributes';
	confirmId   = $(this).attr('data-id');
	confirmButton = $(this);
	confirmModel.find('.js_model_confirm__heading').html('Xác nhận xóa');
	confirmModel.find('.js_model_confirm__description').html('Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b>');
	confirmModel.modal('show');
	return false;
});

$(document).on('click', '.variations-del', function(event)  {
	confirmType = 'variations';
	confirmId   = $(this).attr('data-id');
	confirmButton = $(this);
	confirmModel.find('.js_model_confirm__heading').html('Xác nhận xóa');
	confirmModel.find('.js_model_confirm__description').html('Bạn muốn xóa vĩnh viển trường dữ liệu này ? <b>thao tác này không thể khôi phục</b>');
	confirmModel.modal('show');
	return false;
});

$(document).on('click', '#js_product_options_modal_confirm_btn__save', function() {

	if(confirmId == null || confirmId.length == 0) {
		show_message('Không có dữ liệu nào được xóa ?', 'error');
		return false;
	}

	let data ={
		'data'   		: confirmId,
		'product_id'   	: productOptionsBox.attr('data-object-id'),
		'session_id'	: productOptionsBox.attr('data-session-id'),
	};

	if(confirmType == 'attributes') {
		data.action =  'Admin_Product_Options_Detail_Ajax::deleteAttribute';
	}
	if(confirmType == 'variations') {
		data.action =  'Admin_Product_Options_Detail_Ajax::deleteVariations';
	}

	$.post(ajax, data, function() {}, 'json').done(function(response) {
		confirmModel.modal('hide');
		show_message(response.message, response.status);
		if(response.status === 'success') {
			if(confirmType == 'attributes') {
				confirmButton.closest('.attributes-item').remove();
				product_variations_load();
			}
			if(confirmType == 'variations') {
				confirmButton.closest('.variations-item').remove();
			}
		}
	});

	return false;
});


/**
 * [VARIATIONS]
 */
$(document).on('click', '.variations-item-heading', function() {
	$(this).closest('.variations-item').toggleClass('open');
	return false;
});

$(document).on('click', '#add-group-variations .save-variations', function() {

	let data = {
		'action' 	: 'Admin_Product_Options_Detail_Ajax::addVariations',
		id 			: productOptionsBox.attr('data-object-id'),
		session_id 	: productOptionsBox.attr('data-session-id'),
	};

	$jqxhr   = $.post(ajax, data, function() {}, 'json');

	$jqxhr.done(function(response) {

		show_message(response.message, response.status);

		if(response.status === 'success') {

			$('#result-variations-items').append(response.data);

			let check = false;

			$('input[name="variable_default"]').each(function (index) {
				if ($(this).prop("checked")) { check = true; return false; }
			});

			if(check == false) {
				$('input[name="variable_default"]').first().prop("checked", true);
			}

			$('.iframe-btn').fancybox({
				'type':'iframe',
			});
		}
	});

	return false;
});

$(document).on('click', '.save-group-variations', function() {

	let panel = $('.tab-pane.active .panel');

	let data = $( ':input', panel.find('.panel-body') ).serializeJSON();

	panel.find('.panel-heading select').each( function( index, element ) {
		var select = $( element );
		data[ select.attr( 'name' ) ] = select.val();
	});

	data.action 	= 'Admin_Product_Options_Detail_Ajax::saveVariations';
	data.id 		= panel.attr('data-variations-id');
	data.object_id 	= productOptionsBox.attr('data-object-id');
	data.session_id = productOptionsBox.attr('data-session-id');

	$jqxhr   = $.post(ajax, data, function() {}, 'json');

	$jqxhr.done(function(response) {
		show_message(response.message, response.status);
	});

	return false;
});

/**
 * [OPTIONS IMG]
 */
$(document).on('click', '#result-option-img-items span.attr_op_img_remove', function () {

	let box = $(this).closest('.form-group');

	let img 	= box.find('img');

	let input 	= box.find('input');

	img.attr('src', 'views/plugins/sicommerce-cart/assets/images/Placeholder.jpg');

	input.val('');
});

function product_attributes_load() {
	let data = {};
	data.action 	= 'Admin_Product_Options_Detail_Ajax::loadAttribute';
	data.object_id 	= productOptionsBox.attr('data-object-id');
	data.session_id = productOptionsBox.attr('data-session-id');

	let jqxhr   = $.post(ajax, data, function() {}, 'json');

	jqxhr.done(function(response) {
		if(response.status === 'success') {
			$('#result-attributes-items').html(response.data);
			$('.attribute_values').select2();
		}
	});
}

function product_variations_load() {

	let data = {};

	data.action     = 'Admin_Product_Options_Detail_Ajax::loadVariations';
	data.object_id  = productOptionsBox.attr('data-object-id');
	data.session_id = productOptionsBox.attr('data-session-id');

	$jqxhr   = $.post(ajax, data, function() {}, 'json');

	$jqxhr.done(function(response) {

		if(response.status === 'success') {

			$('#result-variations-items').html(response.data);

			let check = false;

			$('input[name="variable_default"]').each(function (index) {
				if ($(this).prop("checked")) { check = true; return false; }
			});

			if(check == false) {
				$('input[name="variable_default"]').first().prop("checked", true);
			}

			$('.iframe-btn').fancybox({
				'type':'iframe',
			});

			load_image_review();
		}
	});
}

function product_option_img_load() {

	let data = {};

	let metaBox = $('#product_options');

	data.action     = 'Admin_Product_Options_Detail_Ajax::loadOptionImage';

	data.object_id  = metaBox.attr('data-object-id');

	data.session_id = metaBox.attr('data-session-id');

	$.post(ajax, data, function() {}, 'json').done(function(response) {
		if(response.status === 'success') {
			$('#result-option-img-items').html(response.data);
		}
	});
}

function product_options_img_responsive_file_manager_callback(field_id) {

	let url = $('#'+field_id).val();

	if(url.length > 0) {
		$('#'+field_id).closest('.form-group').find('.field-btn-img').attr('src',url);
	}
	parent.$.fancybox.close();
}