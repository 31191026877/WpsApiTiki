function GalleryAjax_post (data, callback) {

	if(typeof data.csrf_test_name == 'undefined') {
		data.csrf_test_name = encodeURIComponent(getCookie('csrf_cookie_name'));
	}
	let $jqxhr = $.post(ajax, data, function(data) {}, 'json');
	$jqxhr.done(function( response ) {
		callback(response);
	});

	return $jqxhr;
}

let gallery_item_id = 0;

let gallery_id = 0;

let gallery_check_all = false;

const gallery_list 		= $('#js_gallery__list');

const gallery_list_item = $('.gallery-item');

const gallery_form_item = $('#js_gallery_item__form');

$(function() {
	let GalleryHandler = function() {
		$( document )
			.on('submit', 		'#js_gallery_form__add', 	this.add)
			.on('click', 		'#js_gallery__list li.js-group-gallery a', this.load)
			.on('click', 		'.gallery-item .gallery-check-all', this.checkAllItem)
			.on('ifChecked', 	'.gallery-item .gallery-box .gallery-item-checkbox', this.checkedItem)
			.on('ifUnchecked', 	'.gallery-item .gallery-box .gallery-item-checkbox', this.checkedItem)
			.on('click', 		'.gallery-item .gallery-box', 	this.loadItemEdit )
			.on('click', 		'.js_gallery_item__delete', 	this.deleteItem )
			.on('submit', 		'#js_gallery_item__form', 		this.saveItem)
			.on('reset', 		'#js_gallery_item__form', 		this.resetItemEdit)

			.on('keyup', '#js_gallery_item__form input[name=value]', this.inputLinkReview)

		GalleryHandler.prototype.loadItems();
	};

	GalleryHandler.prototype.inputLinkReview = function (e) {

		url = $(this).val();

		if (validateYouTubeUrl(url)) {

			url = 'https://img.youtube.com/vi/' + getYoutubeID(url) + '/0.jpg';

		}
		else if (url.search('http') == -1 || url.search(domain) != -1) {

			url = str_replace(url, domain + 'uploads/source/', '');

			url = domain + 'uploads/source/' + url;
		}

		$('.js_gallery_review').css('background-image', 'url("' + url + '")');

		return false;
	};

	GalleryHandler.prototype.load = function (e) {

		let th = $(this);

		gallery_list_item.find('.loading').show();

		gallery_list.find('li.js-group-gallery a').removeClass('active');

		th.addClass('active');

		GalleryHandler.prototype.loadItems();

		return false;
	};

	GalleryHandler.prototype.add = function(e) {

		let name 	= $(this).find('input[name="name"]').val();

		if(name.length === 0) { show_message('Không được bỏ trống tên gallery', 'error'); return false; }

		let data = {
			'action' 		: 'Ajax_Admin_Gallery_Action::add',
			'name' 			: name,
		};

		GalleryAjax_post(data, function( response ) {
			show_message(response.message, response.status);
			if(response.status === 'success') {
				window.location.reload();
			}
		});

		return false;
	};

	GalleryHandler.prototype.loadItems = function (e) {

		gallery_list_item.find('.loading').show();

		gallery_id = gallery_list.find('li.js-group-gallery a.active').attr('data-id');

		GalleryAjax_post({'action': 'Ajax_Admin_Gallery_Action::load', id: gallery_id, }, function (response) {
			gallery_list_item.find('.loading').hide();
			if (response.status === 'success') {
				$('#js_gallery_item__sort').html(response.data);
				gallery_form_item.trigger('reset');
				checkbox_style();
			}
		});

		return false;
	};

	GalleryHandler.prototype.checkAllItem = function (e) {

		$(this).toggleClass('color-green');

		if (gallery_check_all === false) {
			$('.gallery-item-checkbox').iCheck('check');
			gallery_check_all =  true;
		}
		else {
			$('.gallery-item-checkbox').iCheck('uncheck');
			gallery_check_all = false;
		}

		return false;
	};

	GalleryHandler.prototype.checkedItem = function (e) {

		let selected = [];

		let item_delete = '.js_gallery_item__delete';

		$('.gallery-item .gallery-box .gallery-item-checkbox:checked').each(function () {
			selected.push($(this).val());
		});

		if (selected.length === 0) {
			$(item_delete).addClass('disabled-item');
			$(item_delete).removeClass('color-red');
			$(item_delete).removeClass('del-img');
		}
		else {
			$(item_delete).addClass('del-img');
			$(item_delete).addClass('color-red');
			$(item_delete).removeClass('disabled-item');
		}

		return false;
	};

	GalleryHandler.prototype.loadItemEdit = function(e) {

		let th = $(this);

		if(th.hasClass('active')) {

			th.removeClass('active');

			gallery_form_item.trigger('reset');
		}
		else {

			$('.gallery-item .gallery-box').removeClass('active');

			gallery_form_item.find('.loading').show();

			gallery_item_id = th.attr('data-id');

			th.addClass('active');

			GalleryAjax_post({ 'action': 'Ajax_Admin_Gallery_Action::loadItemInfo', id : gallery_item_id }, function( response ) {

				gallery_form_item.find('.loading').hide();

				if(response.status === 'success') {

					gallery_form_item.find('input[name="value"]').val(response.data.value);

					gallery_form_item.find('.camera-container-link input').val(response.data.value);

					let url = response.data.value;

					if (validateYouTubeUrl(url)) {
						url = 'https://img.youtube.com/vi/' + getYoutubeID(url) + '/0.jpg';
					}
					else if (url.search('http') == -1 || url.search(domain) != -1) {
						url = str_replace(url, domain + 'uploads/source/', '');
						url = domain + 'uploads/source/' + url;
					}

					$('.js_gallery_review').css('background-image', 'url("'+ url +'")');

					let options = response.data.options;

					for (let option in options) {
						// skip loop if the property is from prototype
						if(!options.hasOwnProperty(option)) continue;
						gallery_form_item.find('input[name="option['+option+']"]').val(options[option]);
					}

					gallery_form_item.attr('data-edit', gallery_item_id);

					image_review($('#value'));

					video_review();
				}
			});
		}
		return false;
	};

	GalleryHandler.prototype.saveItem = function (e) {

		let th = $(this);

		let data = $(this).serializeJSON();

		data.action 	= 'Ajax_Admin_Gallery_Action::saveItem';

		data.id 		= gallery_item_id;

		data.group_id 	= gallery_id;

		GalleryAjax_post(data, function (response) {
			show_message(response.message, response.status);
			if (response.status === 'success') {
				th.trigger('reset');
				gallery_item_id = 0;
				GalleryHandler.prototype.loadItems();
			}
		});

		return false;
	};

	GalleryHandler.prototype.resetItemEdit = function(e) {

		$this = $(this);

		$this.find('input, select, textarea').each(function(index, el) {
			if (/radio|checkbox/i.test($(this).attr('type')) === false) {
				$(this).val('');
			}
		});

		gallery_item_id = 0;

		$('.result-img').remove();

		$('.result-img-info').remove();

		$('.js_gallery_review').css('background-image', '');

		return false;
	};

	GalleryHandler.prototype.deleteItem = function(e) {

		let data = [], i = 0, item_delete = '.js_gallery_item__delete';

		$('input.gallery-item-checkbox:checked').each(function () {
			data[i++] = $(this).val();
		});

		GalleryAjax_post({  'action': 'Ajax_Admin_Gallery_Action::deleteItem', id : gallery_id, data : data },function( response ) {
			show_message(response.message, response.status);
			if(response.status === 'success') {
				gallery_form_item.trigger('reset');
				$(item_delete).addClass('disabled-item');
				$(item_delete).removeClass('color-red');
				$(item_delete).removeClass('del-img');
				GalleryHandler.prototype.loadItems();
			}
		});

		return false;
	};

	if(typeof gallery_list.html() != 'undefined') {

		new GalleryHandler();

		$('.js_gallery_btn__delete').bootstrap_confirm_delete({
			heading:'Xác nhận xóa',
			message:'Bạn muốn xóa trường dữ liệu này ?',
			callback:function ( event ) {
				let button 	= event.data.originalObject;
				let id 		= button.attr('data-id');
				GalleryAjax_post({'action' : 'Ajax_Admin_Gallery_Action::delete', id : id},function( response ) {
					show_message(response.message, response.status);
					if(response.status === 'success') {
						window.location.reload();
					}
				});
			},
		});
	}

	if(typeof $('#js_gallery_item__sort').html() != 'undefined') {
		Sortable.create(js_gallery_item__sort, {
			animation: 200,
			// Element dragging ended
			onEnd: function (/**Event*/evt) {
				let o = 0;
				let d = {};
				let i;
				$.each($(".js_gallery_object_sort_item"), function(e) {
					i = $(this).attr("data-id");
					d[i] = o;
					o++;
				});

				GalleryAjax_post({'action' : 'Ajax_Admin_Gallery_Action::sortItem', 'data' : d},function( response ) {
					show_message(response.message, response.status);
				});
			},
		});
	}
});

function gallery_responsive_filemanager_callback( id ) {

	let url = $('#' + id).val();

	if (validateYouTubeUrl(url)) {
		url = 'https://img.youtube.com/vi/' + getYoutubeID(url) + '/0.jpg';
	}
	else if (url.search('http') == -1 || url.search(domain) != -1) {
		url = str_replace(url, domain + 'uploads/source/', '');
		url = domain + 'uploads/source/' + url;
	}

	$('.js_gallery_review').css('background-image', 'url("' + url + '")');

	try{
		parent.jQuery.fancybox.close();
	} catch(err) {
		parent.$('#fancybox-overlay').hide();
		parent.$('#fancybox-wrap').hide();
	}
}
/**
 * GALLERY OBJECT
 */
let gallery_obj_id 		= 0;

let gallery_obj_item_id = 0;

let gallery_obj_key 	= '';

const gallery_object_box 	= $('#gallery_object_box');

const gallery_object_list 	= $('#js_gallery_object_sort');

const gallery_object_form 	= $('#js_gallery_object_form');

const gallery_object_modal 	= $('#js_gallery_object_modal');

let GalleryObjectHandler = function() {

	$(document)
		.on('click', '#js_gallery_object_btn_add_item', 	this.onModal)
		.on('click', '.js_gallery_object_sort_item', 		this.onEdit )
		.on('click', '#js_gallery_object_btn__save', 	    this.onSave )
		.on('click', '#js_gallery_object_btn__save_close', this.onSave )
		.on('click', '#js_gallery_object_btn__del', 	    this.onDel )
		.on('click', '#js_gallery_object_btn__checkall', 	this.onCheckAll )
		.on('ifChanged', '#js_gallery_object_sort input.gallery-item-checkbox', this.toggelDel)

	gallery_obj_id  =  parseInt($('#gallery_object_id').val());

	gallery_obj_key =  $('#gallery_object_key').val();

	if(gallery_obj_id !== 0) {
		GalleryObjectHandler.prototype.load();
	}
};

GalleryObjectHandler.prototype.load = function(e) {

	let data = {
		'object_id'	: gallery_obj_id,
		'key'		: gallery_obj_key,
		'action'	: 'Ajax_Admin_Gallery_Action::loadObjectItem',
	};

	let $jqxhr = GalleryAjax_post(data, function (response) {

		if (response.status === 'success') {

			gallery_object_list.prepend(response.data);

			Sortable.create(js_gallery_object_sort, {
				animation: 200,
				// Element dragging ended
				onEnd: function (/**Event*/evt) {
					o = 0;
					$.each($(".js_gallery_object_sort_item"), function(e) {
						i = $(this).attr("data-id");
						$('#gallery_'+i+'_order').val(o);
						o++;
					});
				},
			});

			checkbox_style();
		}
		else {
			show_message(response.message, response.status);
		}
	});

	return false;
};

GalleryObjectHandler.prototype.onEdit 	= function(e) {

	if($(this).hasClass('active')) {

		$(this).removeClass('active');

		GalleryObjectHandler.prototype.onReset();
	}
	else {

		$(this).removeClass('active');

		gallery_obj_item_id 	= $(this).attr('data-id');

		$(this).find('input, select, textarea').each(function(index, el) {
			if (/radio|checkbox/i.test($(this).attr('type')) === false) {
				let name = $(this).attr('data-name');
				gallery_object_form.find('#'+name+'').val($(this).val());
			}
		});

		$('.gallery-object-item').removeClass('active');

		$(this).addClass('active');

		gallery_object_modal.modal('show');
	}
	return false;
};

GalleryObjectHandler.prototype.onSave 	= function(e) {

	gallery_object_modal.find('.loading').show();

	let data 	= $( ':input' , gallery_object_form).serializeJSON();

	let action 	= $(this).attr('data-action');

	data.value		= gallery_object_form.find('input#value').val();
	data.action		= 'Ajax_Admin_Gallery_Action::saveObjectItem';
	data.object_id	= gallery_obj_id;
	data.key 		= gallery_obj_key;
	data.id			= gallery_obj_item_id;

	GalleryAjax_post(data, function( response ) {
		gallery_object_modal.find('.loading').hide();
		if(response.status === 'success') {

			if(gallery_obj_id === 0) {
				if(gallery_obj_item_id === response.id) {
					gallery_object_list.find('.js_gallery_object_sort_item_item[data-id='+gallery_obj_item_id+']').before(response.data);
					gallery_object_list.find('.js_gallery_object_sort_item_item[data-id='+gallery_obj_item_id+']').last().remove();
				}
				else {
					gallery_object_list.prepend(response.data);
				}
				checkbox_style();
			}
			else {
				if(gallery_obj_item_id === 0) {
					gallery_object_list.prepend(response.data);
				}
				else {
					gallery_object_list.find('.gallery-object-item.active').before(response.data);
					gallery_object_list.find('.gallery-object-item.active').hide().remove();
					gallery_object_modal.modal('hide');
				}
				checkbox_style();
			}

			GalleryObjectHandler.prototype.onReset();

			if(action === 'save-close') {
				gallery_object_modal.modal('hide');
			}
		}
		else {
			show_message(response.message, response.status);
		}
	});

	return false;
};

GalleryObjectHandler.prototype.onDel 	= function(e) {

	let data = [], i = 0;

	if(gallery_obj_id === 0) {
		gallery_object_box.find('input.gallery-item-checkbox:checked').each(function () {
			$(this).closest('.js_gallery_object_sort_item').remove();
		});
	}
	else {
		gallery_object_box.find('input.gallery-item-checkbox:checked').each(function () {
			data[i++] = $(this).val();
		});

		let post = {
			id 	 : gallery_obj_id,
			data : data,
			action : 'Ajax_Admin_Gallery_Action::deleteObjectItem',
		};

		$jqxhr   = $.post(ajax, post, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			show_message(response.message, response.status);

			if(response.status === 'success') {

				data.forEach(function(element) {
					gallery_object_list.find('li.js_gallery_object_sort_item[data-id="' + element + '"]').remove();
				});

				checkbox_style();

				GalleryObjectHandler.prototype.onReset();

				Sortable.create(js_gallery_object_sort_item, {
					animation: 200,
					onEnd: function (/**Event*/evt) {
						o = 0;
						$.each($(".js_gallery_object_sort_item"), function(e) {

							i = $(this).attr("data-id");
							$('#gallery_'+i+'_order').val(o);
							o++;
						});
					},
				});
			}
		});
	}
	return false;
};

GalleryObjectHandler.prototype.onReset 	= function(e) {

	gallery_obj_item_id = 0;

	gallery_object_form.find('input, select, textarea').each(function(index, el) {
		if (/radio|checkbox/i.test($(this).attr('type')) === false) {
			$(this).val('');
		}
	});

	$('.js_gallery_object_sort_item_item').removeClass('active');

	gallery_object_modal.find('.result-img').parent().remove();

	gallery_object_modal.find('.result-img-info').remove();
};

GalleryObjectHandler.prototype.onModal 	= function(e) {
	$('#js_gallery_object_modal').modal('show');
	$('#js_gallery_object_modal .iframe-btn').trigger('click');
	return false;
};

GalleryObjectHandler.prototype.toggelDel 	= function(e) {

	$('#js_gallery_object_btn__del').hide();

	$('.js_gallery_object_sort_item input.gallery-item-checkbox').each(function (index) {
		if($(this).prop("checked")) {
			$('#js_gallery_object_btn__del').show();
			return false;
		}
	});

	return false;
};

GalleryObjectHandler.prototype.onCheckAll 	= function(e) {
	gallery_object_list.find('input.gallery-item-checkbox').iCheck('toggle');
	return false;
};


$(function () {

	let gallery_input_box;
	let gallery_input_modal = $('#js_gallery_input_modal');
	let gallery_input_form 	= $('#js_gallery_input_form');
	let gallery_input_id 	= 0;

	var GalleryInputHandler = function () {
		$(document)
			.on('click', '.js_gallery_input_btn_add', 	this.onModal)
			.on('click', '.js_gallery_input_item', 	this.onEdit)
			.on('click', '#js_gallery_input_btn__save', 	   this.onSave)
			.on('click', '#js_gallery_input_btn__save_close', this.onSave)
	};

	GalleryInputHandler.prototype.onModal 	= function(e) {
		gallery_input_modal.modal('show');
		gallery_input_box = $(this).closest('.js_gallery_input_box');
		//$('#js_gallery_input_form .iframe-btn').trigger('click');
		return false;
	};

	GalleryInputHandler.prototype.onEdit 	= function(e) {

		gallery_input_box = $(this).closest('.js_gallery_input_box');

		if($(this).hasClass('active')) {

			$(this).removeClass('active');

			GalleryInputHandler.prototype.onReset();
		}
		else {

			$(this).removeClass('active');

			gallery_input_id = $(this).attr('data-id');

			$(this).find('input, select, textarea').each(function(index, el) {
				if (/radio|checkbox/i.test($(this).attr('type')) === false) {
					let name = $(this).attr('data-name');
					gallery_input_form.find('#gallery_input_'+name+'').val($(this).val());
				}
			});

			$('.js_gallery_input_item').removeClass('active');

			$(this).addClass('active');

			gallery_input_modal.modal('show');
		}
		return false;
	};

	GalleryInputHandler.prototype.onSave 	= function(e) {

		let action 	= $(this).attr('data-action');

		let value = gallery_input_form.find('input#gallery_input_value').val();

		let title = gallery_input_form.find('input#gallery_input_title').val();

		let name = gallery_input_box.attr('data-name');

		if(value == '') {
			show_message('Bạn chưa chọn hình ảnh nào.', 'error');
			return false;
		}

		if(gallery_input_id == 0) {

			let id = Date.now();

			let item = '<li class="col-xs-6 col-sm-3 col-md-2 gallery-object-item js_gallery_input_item" data-id="'+id+'">\n' +
				'<div class="radio">\n' +
				'<input type="checkbox" name="select[]" value="'+id+'" class="icheck gallery-item-checkbox">\n' +
				'</div>\n' +
				'<div class="img"><img src="'+value+'" alt="" loading="lazy"></div>' +
				'<div class="hidden">\n' +
				'<input type="hidden" name="'+name+'['+id+'][value]" value="'+value+'" class="form-control" data-name="value">' +
				'<input type="hidden" name="'+name+'['+id+'][title]" value="'+title+'" class="form-control" data-name="title">' +
				'</div>\n' +
				'</li>';

			gallery_input_box.find('.js_gallery_input_list').prepend(item);

			if(action === 'save-close') {
				gallery_input_modal.modal('hide');
			}
		}
		else {
			gallery_input_box.find('.js_gallery_input_item[data-id="'+gallery_input_id+'"] input[data-name="value"]').val(value);
			gallery_input_box.find('.js_gallery_input_item[data-id="'+gallery_input_id+'"] input[data-name="title"]').val(title);
			gallery_input_box.find('.js_gallery_input_item[data-id="'+gallery_input_id+'"] img').attr('src', value);
			gallery_input_modal.modal('hide');
		}

		GalleryInputHandler.prototype.onReset();

		return false;
	};

	GalleryInputHandler.prototype.onReset 	= function(e) {

		gallery_input_id = 0;

		gallery_input_form.find('input, select, textarea').each(function(index, el) {
			if (/radio|checkbox/i.test($(this).attr('type')) === false) {
				$(this).val('');
			}
		});

		$('.js_gallery_input_item').removeClass('active');

		gallery_input_modal.find('.result-img').parent().remove();

		gallery_input_modal.find('.result-img-info').remove();
	};

	new GalleryInputHandler();

	if(typeof gallery_object_box.html() !== 'undefined') {
		GalleryObject = new GalleryObjectHandler();
		gallery_object_modal.on('hidden.bs.modal', function (e) {
			GalleryObject.onReset();
		});
	}

	if(typeof $('.js_gallery_input_box').html() !== 'undefined') {
		GalleryInput = new GalleryInputHandler();
		gallery_input_modal.on('hidden.bs.modal', function (e) {
			GalleryInput.onReset();
		});
	}
});