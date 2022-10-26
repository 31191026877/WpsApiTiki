$(function(){
    //active theme
	$(document).on('click', '.theme-active', function(event) {
		let $key = $(this).val();
		let data = {
			'action' 	: 'Ajax_Admin_Theme_Action::active',
			'value' 	: $key,
		};
		$.post(ajax, data, function(data) {}, 'json').done(function(response) {
            show_message(response.message, response.status);
            if(response.status === 'success') window.location.reload();
        });
	});
	$(document).on('click', '.theme-info', function(event) {
		let $key = $(this).val();
		let data = {
			'action' 	: 'Ajax_Admin_Theme_Action::info',
			'value' 	: $key,
		};
		$.post(ajax, data, function(data) {}, 'json').done(function( data ) {
            if(data.type === 'success') {
                $('#modal-theme-info .modal-body').html(data.data);
                $('#modal-theme-info').modal('show');
            }
        });
	});
	/*================================================================
	THEME OPTION
	================================================================*/
	//lưu cookie khi click vào các tap theme-option
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		$key = $(this).attr('aria-controls');
		setCookie('of_current_opt',$key,7);
	});

	//save data theme option
	$(document).on('click', '#item-data-save', function(event) {

		$('#ajax_item_save_loader').show();

    	$this = $(this);

    	var datas = {};

		var panel = $('.system-tab-content');

		datas = $( ':input', panel ).serializeJSON();

    	//input
    	$('.system-tab-content').each(function(index, el) {
			$(this).find('textarea.tinymce').each(function(index, el) {
				datas[$(this).attr('name')] = tinyMCE.get($(this).attr('id')).getContent();
			});
			$(this).find('textarea[type="code"]').each(function(index, el) {
				datas[$(this).attr('name')] = editor[$(this).attr('name')].getValue();
			});
    	});

    	datas.action = 'Ajax_Admin_Theme_Action::saveOption';

    	$jqxhr   = $.post(ajax, datas, function(data) {}, 'json');

		$jqxhr.done(function(response) {
			show_message(response.message, response.status);
		    $('#ajax_item_save_loader').hide();
		    tinymce_load();
		});
        return false;
    });
	/*================================================================
	THEME EDITOR
	================================================================*/
	$(document).on('click', '#result-editor .nav-tabs li a[role="tab"]', function(event) {
		id 		= $(this).attr('href');
		id 		= id.substr(1);
		path 	= $(this).attr('path');
	});

	let filemanager = $('.filemanager'),
		breadcrumbs = $('.breadcrumbs'),
		fileList 	= filemanager.find('.data');

	let ed = {};

	if (isset($('.filemanager').html())) {

		$.get(base+'/theme/editor_scan', function(data) {

			var response = [data],
				currentPath = '',
				breadcrumbsUrls = [];

			var folders = [],
				files = [];

			// This event listener monitors changes on the URL. We use it to
			// capture back/forward navigation in the browser.

			$(window).on('hashchange', function(){
				goto(window.location.hash);

				// We are triggering the event. This will execute 
				// this function on page load, so that we show the correct folder:

			}).trigger('hashchange');


			// Hiding and showing the search box

			filemanager.find('.search').click(function(){

				var search = $(this);

				search.find('span').hide();
				search.find('input[type=search]').show().focus();

			});


			// Listening for keyboard input on the search field.
			// We are using the "input" event which detects cut and paste
			// in addition to keyboard input.

			filemanager.find('input').on('input', function(e){

				folders = [];
				files = [];

				var value = this.value.trim();

				if(value.length) {

					filemanager.addClass('searching');

					// Update the hash on every key stroke
					window.location.hash = 'search=' + value.trim();

				}

				else {

					filemanager.removeClass('searching');
					window.location.hash = encodeURIComponent(currentPath);

				}

			}).on('keyup', function(e){

				// Clicking 'ESC' button triggers focusout and cancels the search

				var search = $(this);

				if(e.keyCode == 27) {

					search.trigger('focusout');

				}

			}).focusout(function(e){

				// Cancel the search

				var search = $(this);

				if(!search.val().trim().length) {

					window.location.hash = encodeURIComponent(currentPath);
					// search.hide();
					// search.parent().find('span').show();

				}

			});


			// Clicking on folders

			fileList.on('click', 'li.folders', function(e){
				e.preventDefault();

				var nextDir = $(this).find('a.folders').attr('href');

				if(filemanager.hasClass('searching')) {

					// Building the breadcrumbs

					breadcrumbsUrls = generateBreadcrumbs(nextDir);

					filemanager.removeClass('searching');
					filemanager.find('input[type=search]').val('').hide();
					filemanager.find('span').show();
				}
				else {
					breadcrumbsUrls.push(nextDir);
				}

				window.location.hash = encodeURIComponent(nextDir);
				currentPath = nextDir;
			});

			//clicking on files
			fileList.on('click', 'li.files', function(e){
				e.preventDefault();
				path = $(this).find('a.files').attr('href');

				id = path;
				id = id.replace(/\//g, '-');
				id = id.replace(/\./g, '-');

				if( $('#result-editor').find('.nav-tabs li a[href="#'+id+'"]').length == 0 ) {
					filename = $(this).find('a.files span.name').text();
					edit = {
						'action' : 'Ajax_Admin_Theme_Action::loadEditor',
						'path'	 : path,
					}
					$jqxhr   = $.post(ajax, edit, function() {}, 'json');

					$jqxhr.done(function( edit ) {
						if(edit.type == 'success') {

							$('#result-editor').find('.nav-tabs li').removeClass('active');
							$('#result-editor').find('.tab-content .tab-pane').removeClass('active');

							var tab = '<li role="presentation" class="active"><a href="#'+id+'" aria-controls="'+id+'" role="tab" data-toggle="tab" path="'+path+'">'+filename+'</a></li>'
							var content = '<div role="tabpanel" class="tab-pane active" id="'+id+'">'+edit.content+'</div>';

							$('#result-editor').find('.nav-tabs').append(tab);
							$('#result-editor').find('.tab-content').append(content);

							ed[id] = CodeMirror.fromTextArea(document.getElementById('editor-content'+id), {
								mode: edit.lang,
								theme: 'darkpastel',
								extraKeys: {
									"Ctrl-Space": "autocomplete",
									"Ctrl-F": "findPersistent",
									"Ctrl-S":function (){
										edit = {
											'action' : 'Ajax_Admin_Theme_Action::saveEditor',
											'path'	 : path,
											'content': ed[id].getValue(),
										}
										// console.log(edit);
										$jqxhr   = $.post(ajax, edit, function() {}, 'json');
										$jqxhr.done(function( edit ) {
											show_message(edit.message, edit.type);
										});
										return false;
									},
									"F11": function(cm) {
										cm.setOption("fullScreen", !cm.getOption("fullScreen"));
									},
									"Esc": function(cm) {
										if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
									}
								},
								keyMap: "sublime",
								tabSize: 2,
								lineNumbers: true,
								lineWrapping: true,
								styleActiveLine: true,
								styleSelectedText: true,
								matchBrackets: true,
								autoCloseBrackets: true,
							});
						}
					});
				}
				else {
					$('#result-editor').find('.nav-tabs li a[href="#'+id+'"]').tab('show');
				}
			});


			// Clicking on breadcrumbs

			breadcrumbs.on('click', 'a', function(e){
				e.preventDefault();

				var index = breadcrumbs.find('a').index($(this)),
					nextDir = breadcrumbsUrls[index];

				breadcrumbsUrls.length = Number(index);

				window.location.hash = encodeURIComponent(nextDir);

			});


			// Navigates to the given hash (path)

			function goto(hash) {

				hash = decodeURIComponent(hash).slice(1).split('=');
				if (hash.length) {
					var rendered = '';

					// if hash has search in it

					if (hash[0] === 'search') {

						filemanager.addClass('searching');
						rendered = searchData(response, hash[1].toLowerCase());

						if (rendered.length) {
							currentPath = hash[0];
							render(rendered);
						}
						else {
							render(rendered);
						}

					}

					// if hash is some path

					else if (hash[0].trim().length) {

						rendered = searchByPath(hash[0]);

						if (rendered.length) {

							currentPath = hash[0];
							breadcrumbsUrls = generateBreadcrumbs(hash[0]);
							render(rendered);

						}
						else {
							currentPath = hash[0];
							breadcrumbsUrls = generateBreadcrumbs(hash[0]);
							render(rendered);
						}

					}

					// if there is no hash

					else {
						currentPath = data.path;
						breadcrumbsUrls.push(data.path);
						render(searchByPath(data.path));
					}
				}
			}

			// Splits a file path and turns it into clickable breadcrumbs

			function generateBreadcrumbs(nextDir){
				var path = nextDir.split('/').slice(0);
				for(var i=1;i<path.length;i++){
					path[i] = path[i-1]+ '/' +path[i];
				}
				return path;
			}


			// Locates a file by path

			function searchByPath(dir) {

				var path = dir.split('/'),
					demo = response,
					flag = 0;
				
				for(var i=0;i<path.length;i++){
					for(var j=0;j<demo.length;j++){
						if(demo[j].name === path[i]){
							flag = 1;
							demo = demo[j].items;
							break;
						}
					}
				}
				
				demo = flag ? demo : [];
				return demo;
			}


			// Recursively search through the file tree

			function searchData(data, searchTerms) {

				data.forEach(function(d){
					if(d.type === 'folder') {

						searchData(d.items,searchTerms);

						if(d.name.toLowerCase().match(searchTerms)) {
							folders.push(d);
						}
					}
					else if(d.type === 'file') {
						if(d.name.toLowerCase().match(searchTerms)) {
							files.push(d);
						}
					}
				});
				return {folders: folders, files: files};
			}


			// Render the HTML for the file manager
			function render(data) {
				var scannedFolders = [],
					scannedFiles = [];

				if(Array.isArray(data)) {

					data.forEach(function (d) {

						if (d.type === 'folder') {
							scannedFolders.push(d);
						}
						else if (d.type === 'file') {
							scannedFiles.push(d);
						}

					});

				}
				else if(typeof data === 'object') {

					scannedFolders = data.folders;
					scannedFiles = data.files;

				}

				// Empty the old result and make the new one

				fileList.empty().hide();
				if(!scannedFolders.length && !scannedFiles.length) {
					filemanager.find('.nothingfound').show();
				}
				else {
					filemanager.find('.nothingfound').hide();
				}

				if(scannedFolders.length) {

					scannedFolders.forEach(function(f) {

						var itemsLength = f.items.length,
							name = escapeHTML(f.name),
							icon = '<span class="icon"><i class="fa fa-folder-o" aria-hidden="true"></i></span>';

						if(itemsLength) {
							icon = '<span class="icon"><i class="fa fa-folder-o" aria-hidden="true"></i></span>';
						}

						if(itemsLength == 1) {
							itemsLength += ' item';
						}
						else if(itemsLength > 1) {
							itemsLength += ' items';
						}
						else {
							itemsLength = 'Empty';
						}

						var folder = $('<li class="folders"><a href="'+ f.path +'" title="'+ f.path +'" class="folders">'+icon+'<span class="name">' + name + '</span> <span class="details">' + itemsLength + '</span></a></li>');
						folder.appendTo(fileList);
					});

				}

				if(scannedFiles.length) {

					scannedFiles.forEach(function(f) {

						var fileSize = bytesToSize(f.size),
							name = escapeHTML(f.name),
							fileType = name.split('.'),
							icon = '<span class="icon file"></span>';

						fileType = fileType[fileType.length-1];

						if( fileType == 'css') img = '<img src="https://image.flaticon.com/icons/svg/337/337928.svg" />';
						if( fileType == 'js') img = '<img src="https://image.flaticon.com/icons/svg/337/337941.svg" />';
						if( fileType == 'html') img = '<img src="https://image.flaticon.com/icons/svg/337/337937.svg" />';
						if( fileType == 'php') img = '<img src="https://image.flaticon.com/icons/svg/337/337947.svg" />';

						//hình ảnh
						if( fileType == 'png') img = '<img src="https://image.flaticon.com/icons/svg/337/337948.svg" />';
						if( fileType == 'jpg') img = '<img src="https://image.flaticon.com/icons/svg/337/337940.svg" />';
						


						icon = '<span class="icon">'+img+'</span>';

						var file = $('<li class="files"><a href="'+ f.path+'" title="'+ f.path +'" class="files">'+icon+'<span class="name">'+ name +'</span> <span class="details">'+fileSize+'</span></a></li>');
						file.appendTo(fileList);
					});

				}


				// Generate the breadcrumbs

				var url = '';

				if(filemanager.hasClass('searching')){

					url = '<span>Search results: </span>';
					fileList.removeClass('animated');

				}
				else {

					fileList.addClass('animated');

					breadcrumbsUrls.forEach(function (u, i) {

						var name = u.split('/');

						if (i !== breadcrumbsUrls.length - 1) {
							url += '<a href="'+u+'"><span class="folderName">' + name[name.length-1] + '</span></a> <span class="arrow">→</span> ';
						}
						else {
							url += '<span class="folderName">' + name[name.length-1] + '</span>';
						}

					});

				}

				breadcrumbs.text('').append(url);
				// Show the generated elements
				fileList.animate({'display':'inline-block'});
			}


			// This function escapes special html characters in names

			function escapeHTML(text) {
				return text.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;');
			}


			// Convert file sizes from bytes to human readable units

			function bytesToSize(bytes) {
				var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				if (bytes == 0) return '0 Bytes';
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			}
		});
	}
});