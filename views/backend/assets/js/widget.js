$(function(){

	let sidebarWidget;

	let widgetID = 0;

	let widget_list 		 = $('#js_widget_list');

	let widget_sidebar_list  = $('#js_widget_sidebar_list');

	let widget_service_modal = $('#js_widget_service_modal');

	let widget_edit_modal 	 = $('#js_widget_modal__edit');

	let widget_edit_form 	 = $('#js_widget_edit_form');

	let widget_created_modal = $('#js_widget_modal__created');

	let widget_created_form  = $('#js_widget_created__form');

	let widget_heading_modal  = $('#js_widget_heading_modal');

	let widget_new = false;

	let widget_list_key = [];

	let widget_height = 0;

	let WidgetHandler = function() {

		$( document )
			/************* Serivce ********************/
			//Load widget từ server về
			.on('click', '#js_service_widget', this.serviceLoad)
			//Submit license key
			.on('submit', '#service-widget-license', this.serviceLicense)
			//Load widget theo danh mục từ server về
			.on('click', '.widget-cat-item', this.serviceLoadWithCate )
			//cài widget
			.on('click', '.wg-install', this.download )

			/************* In Website ********************/
			.on( 'click', '#js_widget_local #js_widget_btn__reload', this.load )
			.on( 'click', '#widget .widget_add_sidebar', this.add )
			.on( 'click', '.js_widget_item .js_widget_item_btn__edit', this.loadEdit )
			.on( 'click', '.js_widget_item .js_widget_item_btn__copy', this.copy )
			.on( 'click', '.js_widget_item .js_widget_item_btn__delete', this.delete )
			.on( 'click', '#js_widget_modal__edit .js_widget_btn__close', this.closeEdit )
			.on( 'click', '#js_widget_heading_style', this.showHeading )
			.on( 'click', '#js_widget_heading_modal .btn-active', this.loadHeading )
			.on( 'click', '#js_widget_heading_form_setting', this.showHeadingSetting )
			.on('submit', '#js_widget_edit_form', this.save)
			.on('submit', '#js_widget_created__form', this.created);

		WidgetHandler.prototype.load();
		WidgetHandler.prototype.loadSidebar();
	};
	/**
	 * show modal service widget
	 */
	WidgetHandler.prototype.serviceLoad = function( e ) {
		widget_service_modal.modal('show');
		let loading = widget_service_modal.find('.loading');
		loading.show();
		$jqxhr   = $.post( ajax, { 'action' : 'Ajax_Admin_Widget_Service_Action::load' }, function(data) {}, 'json');
		$jqxhr.done(function(response) {
			loading.hide();
			show_message(response.message, response.status);
			widget_service_modal.find('#widget-view-content').html(response.data).promise().done(function(){
				let widget_service_item_width = $('#js_widget_service_modal').width();
				let widget_service_sidebar_width = $('.widget-service-kho-sidebar').width();
				widget_service_item_width = (widget_service_item_width-widget_service_sidebar_width-15*5)/5;
				$('.widget-service-kho-list .wg-item').each(function (index) {
					$(this).css('height', widget_service_item_width+'px');
				});
			});
		});
	};

	WidgetHandler.prototype.serviceLicense = function( e ) {

		let form_license = $(this);

		let data        = form_license.serializeJSON();

		data.action     =  'ajax_service_license_save';

		let form_edit = $('#modal-service-widget');

		let loading = form_edit.find('.loading-model');

		loading.show();

		$jqxhr   = $.post( ajax, data, function(data) {}, 'json');


		$jqxhr.done(function( data ) {

			loading.hide();

			show_message(data.message, data.status);

			WidgetHandler.prototype.serviceLoad();

		});

		return false;

	};

	WidgetHandler.prototype.serviceLoadWithCate = function( e ) {

		let loading =widget_service_modal.find('.loading');

		loading.show();

		$('.widget-cat-item').parent().removeClass('active');

		$(this).parent().addClass('active');

		$jqxhr   = $.post( ajax, { 'action' : 'Ajax_Admin_Widget_Service_Action::loadByCategory', 'cate': $(this).attr('data-id') }, function(data) {}, 'json');

		$jqxhr.done(function(response) {
			loading.hide();
			show_message(response.message, response.status);
			$('#widget-service-kho-list__item').html(response.data);
		});

		return false;
	};

	WidgetHandler.prototype.download = function(e) {

		let name 	= $(this).attr('data-url');

		let button  = $(this);

		if(name.length === 0) { show_message('Không được bỏ trống tên menu', 'error'); return false; }

		$(this).text('Đang download');

		let data = {
			'action' 		: 'Ajax_Admin_Widget_Service_Action::download',
			'name' 			: name,
		};

		$jqxhr = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function(response) {

			show_message(response.message, response.status);

			if(response.status === 'success') {

				button.text('Đang cài đặt');

				widget_new = true;

				setTimeout( function()  {
					WidgetHandler.prototype.install( button );
				}, 500);
			}
		});

		return false;
	};

	WidgetHandler.prototype.install = function( button ) {

		let name = button.attr('data-url');

		let data = {
			'action' 		: 'Ajax_Admin_Widget_Service_Action::install',
			'name' 			: name,
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function(response) {
			show_message(response.message, response.status);
			button.text('Đã cài đặt');
			widget_new = true;
		});

		return false;
	};

	//Load widget
	WidgetHandler.prototype.load = function(e) {

		$('#js_widget_btn__reload i').addClass('fa-spin');

		widget_list.find('.js_widget_item').each( function(index) {
			widget_list_key.push( $(this).attr('data-key') );
		});

		let data = {
			'action' :'Ajax_Admin_Widget_Action::load',
			'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
		};

		$jqxhr = $.post( ajax, data, function(){}, 'json');

		$jqxhr.done(function(response) {

			$('#js_widget_btn__reload i').removeClass('fa-spin');

			if(response.status === 'success') {

				let str = '';

				for (const [key, items_tmp] of Object.entries(response.data)) {
					let items = [items_tmp];
					items.map(function(item) {
						str += $('#js_widget_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
					});
				}

				widget_list.html(str);

				widget_list.find('.js_widget_item .box').each(function( index ) {
					if($(this).height() > widget_height ) widget_height = $(this).height();
				});

				widget_list.find('.js_widget_item .box').height( widget_height );

				checkbox_style();

				widget_list.find('.js_widget_item').each( function(index) {
					if( widget_list_key.indexOf($(this).attr('data-key')) === -1 ) {
						$(this).addClass('widget-just-added');
					}
				});
			}
		});
	};

	WidgetHandler.prototype.loadSidebar = function(e) {

		let data = {
			'action' :'Ajax_Admin_Widget_Action::loadBySidebar',
			'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
		};

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done(function(response) {

			console.log(response);

			if(response.status === 'success') {

				let str = '';

				for (const [key_sidebar, sidebar] of Object.entries(response.data)) {

					str += '<div class="col-md-6">\n' +
						'<div class="box js_widget_sidebar_item" id="box_'+key_sidebar+'" data-key="'+key_sidebar+'">\n' +
						'<div class="header">\n' +
						'<h3 class="pull-left">'+sidebar.name+'</h3>\n' +
						'<a class="pull-right btn-collapse" id="btn-'+key_sidebar+'" data-toggle="collapse" data-target="#widget-sidebar-content_'+key_sidebar+'"><i class="fal fa-plus-square"></i></a>\n' +
						'</div>\n' +
						'<div class="box-content widget-sidebar-content collapse in" id="widget-sidebar-content_'+key_sidebar+'">\n' +
						'<ul class="js_widget_sidebar_content_item" id="'+key_sidebar+'">';
					if(typeof sidebar.widget != 'undefined') {
						for (const [key, items_tmp] of Object.entries(sidebar.widget)) {
							let items = [items_tmp];
							items.map(function(item) {
								str += $('#js_widget_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
							});
						}
					}

					str += '</ul>\n' +
						'</div>\n' +
						'</div>\n' +
						'</div>';
				}

				widget_sidebar_list.html(str);

				widget_sidebar_list.find('.js_widget_sidebar_content_item').each(function(e) {
					Sortable.create( document.getElementById($(this).attr('id')), {
						sort: true,
						group: {
							name: 'advanced',
							pull: true,
							put: true
						},
						animation: 150,
						onEnd: function (/**Event*/evt) {
							if( evt.to.id !== evt.from.id ) {
								WidgetHandler.prototype.move( $(evt.item), evt.to.id );
							}
							else if( evt.oldIndex !== evt.newIndex )	{
								WidgetHandler.prototype.sort( evt.to.id );
							}
						},
					});
				});
			}
		});
	};

	WidgetHandler.prototype.loadEdit = function(e) {

		widgetID = $(this).closest('.js_widget_item').attr('data-id');

		let data = {
			'action': 'Ajax_Admin_Widget_Action::info',
			'id'	: widgetID,
		};

		widget_edit_modal.addClass('open');

		widget_edit_modal.find('.loading').show();

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done(function(response){

			if(response.status === 'success') {

				widget_edit_modal.find('.widget_header h2').text(response.widget.name);

				widget_edit_modal.find('.box-edit-widget').html(response.data).promise().done(function(){
					widget_edit_modal.find('.loading').hide();
					formBuilderReset();
				});
			}
		});

		return false;
	};

	//Add widget to sidebar
	WidgetHandler.prototype.add = function( t, s ) {

		var widget_id  = t.attr('data-key');

		let data = {
			'action' :'Ajax_Admin_Widget_Action::addToSidebar',
			widget_id: widget_id,
			sidebar_id: s,
		};

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done(function(response){

			show_message(response.message, response.status);

			if(response.status === 'success') {
				t.attr('data-id', response.id);
				t.find('.action .icon-edit').attr('href', response.id);
				t.find('.action .icon-copy').attr('href', response.id);
				t.find('.action .icon-delete').attr('href', response.id);
				t.find('.action .icon-edit[href='+response.id+']').trigger('click');

				WidgetHandler.prototype.sort(s);
			}
		});
	};

	WidgetHandler.prototype.move = function( t, s ) {

		let data = {
			action : 'Ajax_Admin_Widget_Action::move',
			widget_id : t.attr('data-id'),
			sidebar_id: s,
		};

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done( function(response) {
			if( response.status === 'success' ) {
				WidgetHandler.prototype.sort( s );
			}
			else {
				show_message(response.message, response.status);
			}
		});

		return false;
	};

	WidgetHandler.prototype.sort = function( s ) {

		o = [];

		$('#'+s).find('.js_widget_item').each(function(index) {
			o.push($(this).attr('data-id'));
		});

		$('#box_' + s ).find('.loading').show();

		let data = {
			'action' : 'Ajax_Admin_Widget_Action::sort',
			data: o
		};

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done(function(response){
			$('#box_'+ s ).find('.loading').hide();
			if(response.status === 'success') show_message(response.message, response.status);
		});

		return false;
	};

	WidgetHandler.prototype.copy = function( s ) {

		let button = $(this);

		widgetID = $(this).closest('.js_widget_item').attr('data-id');

		let data = {
			'action': 'Ajax_Admin_Widget_Action::copy',
			'id'	: widgetID,
		};

		button.html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Nhân bản</a>');

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done(function(response){

			if(response.status === 'success') {

				let str = '';

				for (const [key, items_tmp] of Object.entries([response.data])) {
					let items = [items_tmp];
					items.map(function(item) {
						str += $('#js_widget_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
					});
				}

				$('#'+response.sidebar_id).append(str);

				button.html('<i class="fal fa-clone"></i>');
			}
		});


		return false;
	};

	WidgetHandler.prototype.delete = function( s ) {

		let button = $(this);

		widgetID = $(this).closest('.js_widget_item').attr('data-id');

		let data = {
			'action' : 'Ajax_Admin_Widget_Action::delete',
			'id'	: widgetID,
		};

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done(function(response){

			if(response.status === 'success') {

				button.closest('.js_widget_item').remove();

				widgetID = 0;
			}
			else show_message(response.message, response.status);
		});

		return false;
	};

	WidgetHandler.prototype.closeEdit = function( e ) {
		widget_edit_modal.removeClass();
		widget_edit_modal.find('.box-edit-widget').html('');
		return false;
	};

	WidgetHandler.prototype.save = function(e) {

		widget_edit_modal.find('.loading').show();

		let data = $(this).serializeJSON();

		data.id = widgetID;

		data.action = 'Ajax_Admin_Widget_Action::save';

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			widget_edit_modal.find('.loading').hide();

			show_message(response.message, response.status);

			if(response.status === 'error'){

				if(isset($('input#'+data.field).val())) {
					$('#'+data.field).focus();
				}

			} else {

				widget_sidebar_list.find('#menuItem_' + widgetID + ' .widget-name').html(response.name);

				WidgetHandler.prototype.closeEdit();
			}
		});
		return false;
	};

	WidgetHandler.prototype.created = function(e) {

		widget_created_modal.find('.loading').show();

		let data = $(this).serializeJSON();

		data.action = 'Ajax_Admin_Widget_Action::created';

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			widget_created_modal.find('.loading').hide();

			show_message(response.message, response.status);

			if(response.status === 'success'){

				widget_created_modal.modal('hide');

				WidgetHandler.prototype.load();
			}
		});
		return false;
	};

	//Heading
	WidgetHandler.prototype.showHeading = function(e) {
		let style = widget_edit_modal.find('input#heading_style').val();
		widget_heading_modal.find('.js_heading_service_item').removeClass('active');
		widget_heading_modal.find('.js_heading_service_item[data-id="' + style + '"]').addClass('active');
		widget_heading_modal.modal('show');
		return false;
	};

	WidgetHandler.prototype.loadHeading = function(e) {

		widget_heading_modal.find('.loading').show();

		let data = {
			id : widgetID,
			widget_heading_style : $(this).data('id'),
			action : 'Ajax_Admin_Widget_Action::heading'
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			widget_heading_modal.find('.loading').hide();

			if(response.status === 'error'){
				show_message(response.message, response.status);
			} else {
				widget_edit_modal.find('input#heading_style').val(data.widget_heading_style);
				$('#widget_heading_form').html(response.form).promise().done(function(){
					formBuilderReset();
					widget_heading_modal.modal('hide');
				});
			}
		});
		return false;
	};

	WidgetHandler.prototype.showHeadingSetting = function(e) {

		widget_edit_form.find('#widget_heading_form').toggle();

		$('.input-tabs .tab.active').each(function(){
			let inputBox = $(this).closest('.input-tabs');
			inputTabsAnimation(inputBox, $(this));
		});

		return false;
	};

	if(typeof $('#js_widget_list').html() != 'undefined') {

		new WidgetHandler();

		Sortable.create( js_widget_list, {
			sort: false,
			group: {
				name: 'advanced',
				pull: 'clone',
				put: false
			},
			animation: 150,
			onEnd: function (/**Event*/evt) {
				if( evt.to.id !== evt.from.id ) {
					WidgetHandler.prototype.add( $(evt.item) , evt.to.id )
				}
			},
		});

		widget_service_modal.on('hidden.bs.modal', function (e) {
			if( widget_new === true ) WidgetHandler.prototype.load();
		});
	}


	$(document).on('click', '.wg-box-item', function() {
		$('.wg-box-item').removeClass('active');
		$(this).addClass('active');
		$(this).closest('.form-group').find('input').val($(this).attr('data-value'));
	});

	$(document).on('click', '.input-col-wrap .col-item', function() {
		let col = $(this).attr('data-col');
		$(this).closest('.input-col-wrap').removeClass().addClass('input-col-wrap input-col-' + col);
		$(this).closest('.input-cols').find('input').val(col);
	});
});