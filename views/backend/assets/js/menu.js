$(function() {

	let id = 0;

	let itemID = 0;

	let menu;

	let menuLocation = [];

	let menuItem = [];

	let menuSelect = '<option value=""> -- Chọn danh mục cha --</option>';

	const menuList 		= $('#js_theme_menu__list');

	const menuItemsList = $('#js_theme_menu_items__list');

	let menuItemsListData;

	let MenuHandler = function() {
		$(document)
			.on('submit','#js_menu_form__add', this.addMenu)
			.on('click', '#js_theme_menu__list li.js-group-menu', this.changeMenu)
			.on('click', '.js_theme_menu_items__add', this.addItem)
			.on('click', '#js_theme_menu_items__list li .icon-edit', this.editItem)
			.on('submit','#js_menu_item_form__save', this.saveItem)
			.on('click', '#js_theme_menu_items__list li .icon-delete', this.deleteItem)
			.on('ifClicked', 'input.js_theme_menu_location', this.addLocation);

		$('li.js-group-menu a').each(function (index) {
			let menu = $(this).attr('data-menu');
			menu 	= JSON.parse(menu);
			menuLocation[menu.id] = menu.options;
		});

		MenuHandler.prototype.load();
	};

	MenuHandler.prototype.load = function(e) {

		menu 	= menuList.find('li.js-group-menu.active a').attr('data-menu');

		if(typeof menu !== 'undefined') {

			menu = JSON.parse(menu);

			menuItemsList.attr('data-id', menu.id);

			id = menuList.find('li.js-group-menu.active a').attr('data-id');

			$('#js_menu_name').text(menu.name);

			$('.menu-position').attr('data-id', menu.id);

			$('input.js_theme_menu_location').iCheck('uncheck');

			if (typeof menuLocation[id] != 'undefined' && menuLocation[id] !== null && menuLocation[id] !== '') {
				$.each( menuLocation[id], function( index, value ) {
					$('input.js_theme_menu_location[value="' + value + '"]').iCheck('check');
				});
			}

			if (typeof menuItem[id] != 'undefined' && menuItem[id] !== null && menuItem[id] !== '') {
				if(menuItem[id].length != 0) {
					menuItemsList.html(menuItem[id]);
					menuItemsList.promise().done(function () {
						init_table();
						MenuHandler.prototype.loadMenuSelect();
					});
					return true;
				}
			}

			$('#js_theme_menu_items_loading').show();

			let jqxhr = $.post(ajax, { 'action': 'Ajax_Admin_Menu_Action::load', 'menu_id': id, 'csrf_test_name': encodeURIComponent(getCookie('csrf_cookie_name')) }, function (data) {}, 'json');

			jqxhr.done(function (response) {

				$('#js_theme_menu_items_loading').hide();

				if (response.status === 'success') {

					menuItem[id] = MenuHandler.prototype.renderItems(response.menuItems);

					menuItemsList.html(menuItem[id]);

					menuItemsList.promise().done(function () {
						init_table();
						MenuHandler.prototype.loadMenuSelect();
					});
				} else {
					show_message(response.message, response.status);
				}
			});
		}

		return true;
	};

	MenuHandler.prototype.loadMenuSelect = function(e) {

		menu = menuList.find('li.js-group-menu.active a').attr('data-menu');

		if(typeof menu !== 'undefined') {

			let menuListTemp = $('#js_theme_menu_items__list>ol>li');
			if(typeof menuListTemp !== 'undefined') {
				menuSelect = '<option value=""> -- Chọn danh mục cha --</option>';
				menuListTemp.each(function(){
					let menuSelectID  = $(this).attr('data-id');
					let menuSelectTxt = $(this).find('.panel-group[data-id="'+menuSelectID+'"] h4').text();
					menuSelect += '<option value="'+ menuSelectID +'">'+ menuSelectTxt +'</option>';
					MenuHandler.prototype.loadMenuSelectChild($(this), 1);
				});
			}

			$('.js_theme_menu_parent').html(menuSelect);

			$('.js_theme_menu_parent').promise().done(function () {
				menuSelect = '<option value=""> -- Chọn danh mục cha --</option>';
			});
		}
	};

	MenuHandler.prototype.loadMenuSelectChild = function(e, level) {
		let id = e.attr('id');
		let menuChild = $('#'+id+'>ol>li');
		if(typeof menuChild !== 'undefined') {
			menuChild.each(function(){
				let strLevel= '|---'.repeat(level);
				let menuSelectID 	= $(this).attr('data-id');
				let menuSelectTxt 	= $(this).find('.panel-group[data-id="'+menuSelectID+'"] h4').text();
				menuSelect += '<option value="'+ menuSelectID +'">'+ strLevel + menuSelectTxt +'</option>';
				MenuHandler.prototype.loadMenuSelectChild($(this), level+1);
			});
		}
	};

	MenuHandler.prototype.renderItems = function(menuItems) {
		let str = '<ol class="dd-list">';
		for (const [key, items_tmp] of Object.entries(menuItems)) {
			let items = [items_tmp];
			items.map(function(item) {
				str += '<li id="menuItem_'+ item.id +'" data-id="'+ item.id +'" class="dd-item">';
				str += $('#js_menu_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
				if(item.child.length !== 0) str += MenuHandler.prototype.renderItems(item.child);
				str += '</li>';
			});
		}
		str += '</ol>';
		return str;
	};

	MenuHandler.prototype.addMenu = function(e) {

		let name = $(this).find('input[name="name"]').val();

		if(name.length === 0) {
			show_message('Không được bỏ trống tên menu', 'error');
			return false;
		}

		let data = {
			'action' 		: 'Ajax_Admin_Menu_Action::add',
			'name' 			: name,
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function(response) {
			show_message(response.message, response.status);
			if(response.status === 'success') {
				$('#js_theme_menu__list').prepend(response.menu);
				$.fancybox.close();
			}
		});
		return false;
	};

	MenuHandler.prototype.changeMenu = function(e) {

		$('#js_theme_menu__list li.js-group-menu').removeClass('active');

		$(this).addClass('active');

		MenuHandler.prototype.load();

		return false;
	};

	MenuHandler.prototype.addItem = function(e) {

		let th 			= $(this);

		let type 		= $(this).attr('data-type');

		let input 		= [];

		let object_type = null;

		let parent_id 	= $(this).closest('.js_theme_menu_action').find('.js_theme_menu_parent').val();

		if(type !== 'link') {
			th.closest('.panel-body').find('input[type="checkbox"]:checked').each(function() {
				input.push($(this).val());
				object_type = $(this).attr('name');
			});
		}
		else {
			input = {
				'link':$('#link_box input[name="url"]').val(),
				'name':$('#link_box input[name="title"]').val(),
			};
			object_type = 'link';
		}

		let data = {
			action		: 'Ajax_Admin_Menu_Action::addItem',
			menu_id 	: id,
			data 		: input,
			type    	: object_type,
			object_type : type,
			parent_id   : parent_id,
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function(response){

			show_message(response.message, response.status);

			if(response.status === 'success') {

				if(typeof parent_id === 'undefined' || parent_id === 0 || parent_id === '') {

					let itemAdd = '';

					for (const [key, items_tmp] of Object.entries(response.fields)) {
						let items = [items_tmp];
						items.map(function(item) {
							itemAdd += '<li id="menuItem_'+ item.id +'" class="dd-item" data-id="'+ item.id +'">';
							itemAdd += $('#js_menu_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
							itemAdd += '</li>';
						});
					}

					$('#js_theme_menu_items__list>ol.dd-list').prepend(itemAdd);

					if(type !== 'link') {
						th.closest('.panel-body').find('input[type="checkbox"]:checked').iCheck('uncheck');
					}

					MenuHandler.prototype.loadMenuSelect();
				}
				else {

					if (typeof menuItem[id] != 'undefined' && menuItem[id] !== null && menuItem[id] !== '') {
						menuItem[id] = null;
					}

					MenuHandler.prototype.load();
				}
			}
		});

		return false;
	};

	MenuHandler.prototype.editItem = function(e) {

		$('#js_theme_menu_item_edit_loading').show();

		itemID 	= $(this).attr('href');

		var data = {
			'action': 'Ajax_Admin_Menu_Action::editItem',
			id 	: itemID,
		};

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done(function(response) {

			$('#js_theme_menu_item_edit_loading').hide();

			if(response.status === 'success') {

				$('#js_menu_item_form__save .result').html(response.data);

				load_image_review();

				checkbox_style();
			}
		});

		return false;
	};

	MenuHandler.prototype.saveItem = function(e) {

		$('#js_theme_menu_item_edit_loading').show();

		let th = $(this);

		let data = th.serializeJSON();

		data.id = itemID;

		data.action = 'Ajax_Admin_Menu_Action::saveItem';

		$.post(ajax, data, function(){}, 'json').done(function(response){

			$('#js_theme_menu_item_edit_loading').hide();

			show_message(response.message, response.status);

			if(response.status === 'success') {
				if(typeof data.name !== 'undefined') {
					$('#menuItem_'+itemID+'>.panel-title>a').text(data.name);
				}
			}
		});

		return false;
	};

	MenuHandler.prototype.deleteItem = function(e) {

		let data = {
			'action': 'Ajax_Admin_Menu_Action::deleteItem',
			id 	: $(this).attr('href'),
		};

		$jqxhr = $.post(ajax, data, function(){}, 'json');

		$jqxhr.done(function(data){

			show_message(data.message, data.status);

			if(data.status === 'success') {

				menuItem[id] = [];

				MenuHandler.prototype.load();
			}
		});

		return false;
	};

	MenuHandler.prototype.addLocation = function(e) {

		setTimeout(function(){

			let data = $( ':input:checked' , $('#js_theme_menu_items__location')).serializeJSON();

			data.action = 'Ajax_Admin_Menu_Action::saveLocation';

			data.id = id;

			let jqxhr = $.post(ajax, data, function(){}, 'json');

			jqxhr.done(function(response) {

				show_message(response.message, response.status);

				if(response.status === 'success') {

					if(typeof response.locations != 'undefined') {
						$.each( response.locations, function(index, value ) {
							menuLocation[index] = value;
						});
					}
				}
			});

		}, 100);

		return false;
	};

	MenuHandler.prototype.sortItem = function(order, element) {
		let data = {
			'action': 'Ajax_Admin_Menu_Action::sort',
			id 		: id,
			data 	: order
		};
		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');
		$jqxhr.done(function(response) {
			show_message(response.message, response.status);
			if(response.status === 'success') {
				menuItem[id] = $('#js_theme_menu_items__list').html();
			}
		});
		return false;
	};

	function init_table() {
		menuItemsList.nestable({ group: 1, expandBtnHTML:"",collapseBtnHTML:"" });
		menuItemsListData = JSON.stringify(menuItemsList.nestable('serialize'));
	}

	if(typeof menuList.html() != 'undefined') {

		new MenuHandler();

		init_table();

		$(document).bind('DOMNodeInserted', function(e) {
			$('.js_menu_btn__delete').bootstrap_confirm_delete({
				heading:'Xác nhận xóa',
				message:'Bạn có muốn xóa menu này ? ( thao tác này không thể khôi phục )',
				callback:function ( event ) {
					var del_id = event.data.originalObject.attr('data-id');

					if(del_id == null || del_id.length == 0) {
						show_message('Không có dữ liệu nào được xóa ?', 'error');
					}
					else {

						let data ={
							'action' : 'Ajax_Admin_Menu_Action::delete',
							'data'   : del_id
						};

						$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

						$jqxhr.done(function(response) {
							show_message(response.message, response.status);
							if(response.status === 'success') { location.reload(); }
						});
					}
				},
			});
		});

		$(document).on('click', '.fancybox-close-small, .btn-close', function() {
			$.fancybox.close();
		});

		$('#js_theme_menu_items__list').on('change', function (e) {
			let list   = e.length ? e : $(e.target), output = list.data('output');
			let data = list.nestable('serialize');
			if(menuItemsListData !== JSON.stringify(data)) {
				MenuHandler.prototype.sortItem(data, e);
				MenuHandler.prototype.loadMenuSelect();
			}
		});
	}

});