$(function() {

	let plugin_service_license 	= $('#plugin_service_license');

	let plugin_service_list 	= $('#plugin_service_list');

	let plugin_list 			= $('#plugin_list');

	let plugin_name 			= '';

	let plugin_item 			= '';

	let PluginHandler = function() {

		$( document )
			.on( 'submit', '#plugin_service_license_form', this.saveLicense)
			.on( 'click', '.js_plugin_service__install', this.serviceDownload)
			.on( 'click', '.js_plugin_btn__active', this.active)
			.on( 'click', '.js_plugin_btn__deactivate', this.deactivate)
			.on( 'click', '.js_plugin_btn__upgrade', this.upgrade)
			.on( 'click', '.js_plugin_btn__remove', this.remove)
			.on( 'keyup', '#js_plugin_search', this.search);

		PluginHandler.prototype.loadService();

		PluginHandler.prototype.load();
	};

	PluginHandler.prototype.loadService = function(e) {

		$('.loading').show();

		let data = {
			'action'  : 'Ajax_Admin_Plugin_Action::loadService',
			'csrf_test_name': encodeURIComponent(getCookie('csrf_cookie_name'))
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			show_message(response.message, response.status);

			$('.loading').hide();

			if(response.status === 'success') {
				let str = '';
				for (const [key, items_tmp] of Object.entries(response.plugins)) {
					let items = [items_tmp];
					items.map(function(item) {
						str += $('#js_plugin_service_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
					});
				}
				plugin_service_list.html(str);
			}
			else {
				plugin_service_license.show();
			}
		});

		return false;
	};

	PluginHandler.prototype.saveLicense = function(e) {

		$('.loading').show();

		let data = $(this).serializeJSON();

		data.action = 'Ajax_Admin_Plugin_Action::saveLicense';

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			show_message(response.message, response.status);

			$('.loading').hide();

			if(response.status === 'success') {
				plugin_service_license.hide();
				PluginHandler.prototype.load();
			}
		});

		return false;
	};

	PluginHandler.prototype.serviceDownload = function(e) {

		let name 	= $(this).attr('data-url');

		let button = $(this);

		if(name.length === 0) { show_message('Plugin không tồn tại', 'error'); return false; }

		$(this).text('Install ...');

		let data = {
			'action' 		: 'Ajax_Admin_Plugin_Action::download',
			'name' 			: name,
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

	        show_message(response.message, response.status);

	        if(response.status === 'success') {
	        	setTimeout( function()  {
	        		PluginHandler.prototype.install( button );
	        	}, 500);
	        }
			else {
				button.html('<i class="fad fa-cloud-download-alt"></i> Install');
			}
	    });

	    return false;
	};

	PluginHandler.prototype.install = function( button ) {

		let name 	= button.attr('data-url');

		let data = {
			'action' 		: 'Ajax_Admin_Plugin_Action::install',
			'name' 			: name,
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

	        show_message(response.message, response.status);

	        if(response.status === 'success') {
				button.closest('.plugin-item').find('.plugin-action').append('<button class="btn btn-green js_plugin_btn__active" data-name="'+response.name+'"><i class="fad fa-check-circle"></i> Active</button>');
				button.remove();
				PluginHandler.prototype.load();
			}
	        else {
				button.html('<i class="fad fa-cloud-download-alt"></i> Install');
			}
	    });

	    return false;
	};

	PluginHandler.prototype.load = function(e) {

		let data = {
			'action'  : 'Ajax_Admin_Plugin_Action::load',
			'csrf_test_name': encodeURIComponent(getCookie('csrf_cookie_name'))
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			if(response.status === 'success') {
				let str = '';
				for (const [key, items_tmp] of Object.entries(response.plugins)) {
					let items = [items_tmp];
					items.map(function(item) {
						str += $('#js_plugin_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
					});
				}
				plugin_list.html(str);
			}
			else {
				show_message(response.message, response.status);
			}
		});

		return false;
	};

	PluginHandler.prototype.active = function(e) {

		$(this).text('Run Active ... ');

		plugin_name = $(this).closest('.plugin-item').attr('data-name');

		plugin_item = $('.plugin-item[data-name="'+plugin_name+'"]');

		let data = {
			'action'  : 'Ajax_Admin_Plugin_Action::active',
			'name'	  : plugin_name
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			show_message(response.message, response.status);

			if(response.status === 'success') {
				plugin_item.find('button.js_plugin_btn__active').remove();
				plugin_item.find('button.js_plugin_btn__remove').remove();
				plugin_item.find('.plugin-action').append('<a href="admin/plugins" class="btn btn-white" data-name="'+plugin_name+'">RESTART CMS</a>');
			}
			else {
				$('button.js_plugin_btn__active[data-name="'+plugin_name+'"]').html('<i class="fad fa-check-circle"></i> Active');
			}
		});

		return false;
	};

	PluginHandler.prototype.deactivate = function(e) {

		$(this).text('Run deactivate ... ');

		plugin_name = $(this).closest('.plugin-item').attr('data-name');

		plugin_item = $('.plugin-item[data-name="'+plugin_name+'"]');

		let data = {
			'action'  : 'Ajax_Admin_Plugin_Action::deactivate',
			'name'	  : plugin_name
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			show_message(response.message, response.status);

			if(response.status === 'success') {
				plugin_item.find('button.js_plugin_btn__deactivate').remove();
				plugin_item.find('.plugin-action').append('<button class="btn btn-green js_plugin_btn__active" data-name="'+plugin_name+'"><i class="fad fa-check-circle"></i> Active</button>');
				plugin_item.find('.plugin-action').append('<button class="btn btn-red js_plugin_btn__remove" data-name="'+plugin_name+'"><i class="fad fa-trash"></i> Delete</button>');
			}
			else {
				plugin_item.find('button.js_plugin_btn__deactivate').text('Deactivate');
			}
		});

		return false;
	};

	PluginHandler.prototype.upgrade = function(e) {

		plugin_name = $(this).closest('.plugin-item').attr('data-name');

		plugin_item = $('.plugin-item[data-name="'+plugin_name+'"]');

		let button = $(this);

		let data = {
			'action' 		: 'Ajax_Admin_Plugin_Action::upgrade',
			'name' 			: plugin_name,
		};

		button.html('<i class="fad fa-sync fa-spin"></i> Updating');

		$.post(ajax, data, function(data) {}, 'json').done(function( response ) {

			show_message(response.message, response.status);

			if(response.status === 'success') {
				plugin_item.find('.plugin-action').html('<a href="admin/plugins" class="btn btn-white" data-name="'+plugin_name+'">RESTART CMS</a>');
			}
			else {
				button.html('<i class="fad fa-sync"></i> Update');
			}
		});

		return false;
	};

	PluginHandler.prototype.remove = function(e) {

		$(this).text('Run remove ... ');

		plugin_name = $(this).closest('.plugin-item').attr('data-name');

		plugin_item = $('.plugin-item[data-name="'+plugin_name+'"]');

		let plugin_item_local = plugin_list.find('.plugin-item[data-name="'+plugin_name+'"]');

		let data = {
			'action'  : 'Ajax_Admin_Plugin_Action::remove',
			'name'	  : plugin_name
		};

		$jqxhr   = $.post(ajax, data, function(data) {}, 'json');

		$jqxhr.done(function( response ) {

			show_message(response.message, response.status);

			if(response.status === 'success') {
				plugin_item_local.remove();
			}
			else {
				plugin_item.find('button.js_plugin_btn__remove').text('Delete');
			}
		});

		return false;
	};

	PluginHandler.prototype.search = function(e) {

		let keyword = $(this).val();

		$('#plugin_service_list .plugin-item-box').hide();

		$('#plugin_service_list .plugin-item-box .title h3').each(function(){
			if($(this).text().toLowerCase().indexOf(""+keyword+"") !== -1 ){
				$(this).closest('.plugin-item-box').show();
			}
		});
		return false;
	};

	/**
	 * Init AddToCartHandler.
	 */
	if(typeof plugin_list.html() != 'undefined') new PluginHandler();
});