var Builder = {};

$(function () {
    let widget_list 		 = $('#js_widget_list');

    let widget_sidebar_list  = $('#js_widget_sidebar_list');

    let widget_edit = $('#widget_editor');

    let widget_heading_modal = $('#js_widget_heading_modal');

    let widget_element_modal = $('#js_widget_element_modal');

    let widget_height = 0;

    let widget_list_key = [];

    let widgetID = 0;

    let widget_action = '';

    let panel_right_loading = $('#right-panel .loading');

    Builder.main = {
        highlightEnabled : false,
        selectPadding: 0,
        init: function(callback) {
            var self = this;
            self.documentFrame = $("#iframe-wrapper > iframe");
            self.canvas = $("#canvas");

            self.selectedEl = null;
            self.highlightEl = null;
            self.highlightEnabled = true;

            self._loadIframe(domainReview);
            self._initBox();
        },
        _loadIframe : function(url) {

            var self = this;

            self.iframe = this.documentFrame.get(0);

            self.iframe.src = url;

            $(".loading-message").addClass("active");

            return this.documentFrame.on("load", function() {

                window.FrameWindow = self.iframe.contentWindow;

                window.FrameDocument = self.iframe.contentWindow.document;

                let highlightBox = $("#highlight-box").hide();

                if(widget_action === 'add' || widget_action === 'edit') {
                    self.selectedEl = $(window.FrameDocument).find(".js_widget_builder[data-id='"+ widgetID +"']");
                    self.selectNode(self.selectedEl);
                    self.goToNode(".js_widget_builder[data-id='"+ widgetID +"']");
                    widget_action = '';
                }

                if (self.selectedEl) {
                    widgetID = self.selectedEl.attr('data-id');
                    self.selectNode(self.selectedEl);
                    self.goToNode(".js_widget_builder[data-id='"+ widgetID +"']");
                }

                $(window.FrameWindow).on("scroll resize", function(event) {
                    if (typeof self.selectedEl != 'undefined' && self.selectedEl != null) {
                        widgetID = self.selectedEl.attr('data-id');
                        self.selectedEl = $(window.FrameDocument).find(".js_widget_builder[data-id='"+widgetID+"']");
                        self.selectNode(self.selectedEl);
                    }
                    if (typeof self.highlightEl != 'undefined' && self.highlightEl != null) {
                        let offset = self.highlightEl.offset();
                        highlightBox.css({
                            "top": offset.top - self.frameDoc.scrollTop() ,
                            "left": offset.left - self.frameDoc.scrollLeft() ,
                            "width" : self.highlightEl.outerWidth(),
                            "height": self.highlightEl.outerHeight(),
                        });
                    }
                });

                return self._frameLoaded();
            });
        },
        _frameLoaded : function() {

            var self = Builder.main;

            self.frameDoc = $(window.FrameDocument);
            self.frameHtml = $(window.FrameDocument).find("html");
            self.frameBody = $(window.FrameDocument).find("body");
            self.frameHead = $(window.FrameDocument).find("head");
            self.frameHeader = $(window.FrameDocument).find("header");
            self.frameWidget = $(window.FrameDocument).find(".js_widget_builder");
            const date = new Date();
            //insert editor helpers like non editable areas
            self.frameBody.find('script[src="views/theme-store/assets/js/script.min.js"]').attr('src', 'views/theme-store/assets/js/script.min.js?v='+date.getTime());
            self._initHighlight();

            $(".loading-message").removeClass("active");
        },
        _initHighlight: function() {

            var self = Builder.main;

            self.frameWidget.on("mousemove dragover touchmove", function(event) {
                if (self.highlightEnabled === true && event.target && isElement(event.target) && event.originalEvent) {
                    self.highlightEl = target = $(this);
                    var offset = target.offset();
                    var height = target.outerHeight();
                    var halfHeight = Math.max(height / 2, 50);
                    var width = target.outerWidth();
                    var halfWidth = Math.max(width / 2, 50);
                    var prepend = true;

                    var x = event.originalEvent.x;
                    var y = event.originalEvent.y;

                    if (self.isResize) {
                        if (!self.initialPosition) {
                            self.initialPosition = {x,y};
                        }

                        var deltaX = x - self.initialPosition.x;
                        var deltaY = y - self.initialPosition.y;

                        offset = self.selectedEl.offset();

                        width = self.selectedEl.outerWidth();
                        height = self.selectedEl.outerHeight();

                        switch (self.resizeHandler) {
                            // top
                            case "top-left":
                                height -= deltaY;
                                width -= deltaX;
                                break;

                            case "top-center":
                                height -= deltaY;
                                break;

                            case "top-right":
                                height -= deltaY;
                                width += deltaX;
                                break;

                            // center
                            case "center-left":
                                width -= deltaX;
                                break;

                            case "center-right":
                                width += deltaX;
                                break;

                            // bottom
                            case "bottom-left":
                                width -= deltaX;
                                height += deltaY;
                                break;

                            case "bottom-center":
                                height += deltaY;
                                break;

                            case "bottom-right":
                                width += deltaX;
                                height += deltaY;
                                break;
                        }

                        self.selectedEl.attr({width, height});
                        $("#select-box").css({
                            "top": offset.top - self.frameDoc.scrollTop() ,
                            "left": offset.left - self.frameDoc.scrollLeft() ,
                            "width" : width,
                            "height": self.selectedEl.outerHeight(),
                        });

                    }
                    else if (self.isDragging) {

                        var parent = self.highlightEl;

                        try {
                            if ((offset.top  < (y - halfHeight)) || (offset.left  < (x - halfWidth))) {
                                self.dragElement.appendTo(parent);
                                prepend = true;
                            }
                            else {
                                prepend = false;
                                self.dragElement.prependTo(parent);
                            }
                        } catch(err) {
                            return false;
                        }
                    }
                    else  {

                        $("#highlight-box").css({"top": offset.top - self.frameDoc.scrollTop() ,
                            "left": offset.left - self.frameDoc.scrollLeft() ,
                            "width" : width,
                            "height": height,
                            "display" : event.target.hasAttribute('contenteditable')?"none":"block",
                            "border":self.isDragging ? "1px dashed #0d6efd":"",//when dragging highlight parent with green
                        });

                        if (height < 50) {
                            $("#section-actions").addClass("outside");
                        }
                        else {
                            $("#section-actions").removeClass("outside");
                        }

                        $("#highlight-name").html($('.js_widget_item[data-id="'+target.attr('data-id')+'"] .widget-name').text());
                    }
                }
            });

            self.frameWidget.on("mouseup dragend touchend", function(event) {
                self.isResize = false;
                $("#section-actions, #highlight-name, #select-box").show();
            });

            self.frameHtml.off('click').on("dblclick", function(event) {});

            self.frameHeader.off('click').on("click", function(event) {
                if (event.currentTarget) {
                    $('#right-panel .nav-item a[href="#theme"]').tab('show');
                    $('#right-panel #themeOption #themeOption_header').collapse('show');
                }
                event.preventDefault();
                return false;
            });

            self.frameWidget.off('click').on("click", function (event) {

                if (event.currentTarget) {
                    //if component properties is loaded in left panel tab instead of right panel show tab
                    self.selectNode($(window.FrameDocument).find(event.currentTarget));
                    self.loadNodeComponent(event.currentTarget);
                }

                $("#add-section-box").hide();

                event.preventDefault();

                return false;
            });
        },
        _initBox: function() {
            let self = this;
            let addSectionBox = $("#widget-box-add");
            let addSectionElement = {};

            $(document).on('click',"#js_add_widget", function(event) {
                addSectionElement = self.highlightEl;
                let offset = $(addSectionElement).offset();
                let top = (offset.top - self.frameDoc.scrollTop()) + addSectionElement.outerHeight();
                let left = (offset.left - self.frameDoc.scrollLeft()) + (addSectionElement.outerWidth() / 2) - (addSectionBox.outerWidth() / 2);
                let outerHeight = $(window.FrameWindow).height() + self.frameDoc.scrollTop();

                //check if box is out of viewport and move inside
                if (left < 0) left = 0;
                if (top < 0) top = 0;
                if ((left + addSectionBox.outerWidth()) > self.frameDoc.outerWidth()) left = self.frameDoc.outerWidth() - addSectionBox.outerWidth();
                if (((top + addSectionBox.outerHeight()) + self.frameDoc.scrollTop()) > outerHeight) top = top - addSectionBox.outerHeight();

                addSectionBox.css({"top": top, "left": left, "display": "block"});
                event.preventDefault();
                return false;
            });

            $("#js_widget_iframe__close").on("click", function(event) {
                addSectionBox.hide();
            });

            $(document).on('click',"#js_delete_widget", function(event) {
                widgetID =  self.selectedEl.attr('data-id');
                return Builder.Widget.delete($('#menuItem_' + widgetID + ' a[data-builder-action="deleteWidget"]'));
            });
        },
        selectNode:  function(node) {

            let self = this;

            if (!node) {
                $("#select-box").hide(); return;
            }

            let target = node;

            if (target) {
                self.selectedEl = target;
                try {
                    var offset = target.offset();
                    $("#select-box").css({
                        "top": offset.top - self.frameDoc.scrollTop() - self.selectPadding,
                        "left": offset.left - self.frameDoc.scrollLeft() - self.selectPadding,
                        "width" : target.outerWidth() + self.selectPadding * 2,
                        "height": target.outerHeight() + self.selectPadding * 2,
                        "display": "block",
                    });
                } catch(err) {
                    console.log(err);
                    return false;
                }
            }
        },
        loadNodeComponent:  function(node) {

            if (!node) return;

            var target = $(node);

            if (target) {
                widgetID = target.attr('data-id');
                Builder.Widget.loadEdit();
            }
        },
        goToNode : function () {
            var self = this;

            if (self.selectedEl) {
                try {
                    Builder.main.frameHtml.animate({
                        scrollTop: self.selectedEl.offset().top - (self.selectedEl.height() / 2)
                    }, 500);
                } catch(err) {
                    console.log(err);
                    return false;
                }
            }
        }
    };

    Builder.Gui = {
        init: function() {
            $("[data-builder-action]").each(function () {
                let on = "click";
                if (this.dataset.builderOn) on = this.dataset.builderOn;
                $(this).on(on, Builder.Gui[this.dataset.builderAction]);
            });

            $(document)
                .on( 'click', '[data-builder-action="addWidget"]', this.addWidget )
                .on( 'click', '[data-builder-action="editWidget"]', this.editWidget )
                .on( 'click', '[data-builder-action="copyWidget"]', this.copyWidget )
                .on( 'click', '[data-builder-action="deleteWidget"]', this.deleteWidget )
                .on( 'click', '[data-builder-action="downloadWidget"]', this.downloadWidget )
                .on( 'click', '[data-builder-action="downloadElement"]', this.downloadElement )
                .on( 'click', '[data-builder-action="activeElement"]', this.activeElement )
                .on( 'click', '[data-builder-action="deactivateElement"]', this.deactivateElement )
                .on( 'click', '#js_widget_heading_style', this.headingWidget )
                .on( 'click', '.js_heading_service_item .btn-active', this.headingSelect )
                .on( 'click', '#js_widget_heading_form_setting', this.headingSetting );
        },
        viewport : function () {
            $("#canvas").attr("class", this.dataset.view);
        },
        fullscreen : function () {
            launchFullScreen(document); // the whole page
        },
        reload : function () {
            Builder.main._loadIframe(domainReview);
        },
        togglePanel: function (panel, cssVar) {
            panel = $(panel);
            let prevValue = document.body.style.getPropertyValue(cssVar);
            if (prevValue !== "0px") {
                panel.data("layout-toggle", prevValue);
                document.body.style.setProperty(cssVar, "0px");
                panel.hide();
                return false;
            } else {
                prevValue= panel.data("layout-toggle");
                document.body.style.setProperty(cssVar, '');
                panel.show();
                return true;
            }
        },

        toggleOptions: function (panel) {
            $('.js_theme_options_content').removeClass('show');
            $($(this).attr('href')).addClass('show');
            return false;
        },

        closeOptions: function (panel) {
            $(this).closest('.js_theme_options_content').removeClass('show');
            return false;
        },

        toggleWidgetLocal: function () {
            Builder.Gui.togglePanel("#widget-local", "--builder-widget-local-height");
        },

        toggleLeftColumn: function () {
            Builder.Gui.togglePanel("#left-panel", "--builder-left-panel-width");
        },

        toggleRightColumn: function (rightColumnEnabled = null) {
            rightColumnEnabled = Builder.Gui.togglePanel("#right-panel", "--builder-right-panel-width");
            $("#skilldo-builder").toggleClass("no-right-panel");
        },
        downloadWidget : function () {
            return Builder.Widget.download($(this));
        },
        addWidget : function () {
            return Builder.Widget.addById(Builder.main.highlightEl.attr('data-id'), $(this));
        },
        editWidget : function () {
            widgetID = $(this).closest('.js_widget_item').attr('data-id');
            return Builder.Widget.loadEdit();
        },
        copyWidget : function () {
            return Builder.Widget.addById($(this).closest('.js_widget_item').attr('data-id'), $(this));
        },
        deleteWidget : function () {
            return Builder.Widget.delete($(this));
        },
        searchWidget : function () {
            return Builder.Widget.search($(this).val());
        },
        showElement : function () {
            let element = $(this).attr('href');
            widget_element_modal.find('.js_element_box').hide();
            widget_element_modal.find(element).show();
            widget_element_modal.modal('show');
            return false;
        },
        headingWidget : function() {
            let style = widget_edit.find('input#heading_style').val();
            widget_heading_modal.find('.js_heading_service_item').removeClass('active');
            widget_heading_modal.find('.js_heading_service_item[data-id="' + style + '"]').addClass('active');
            widget_heading_modal.modal('show');
            return false;
        },
        headingSelect : function() {
            return Builder.Widget.headingSelect($(this).attr('data-id'));
        },
        headingSetting : function() {
            widget_edit.find('#widget_heading_form').toggle();
            $('.input-tabs .tab.active').each(function(){
                let inputBox = $(this).closest('.input-tabs');
                inputTabsAnimation(inputBox, $(this));
            });
            return false;
        },
        save : function () {
            let tab = $('#right-panel .nav-item .nav-link.active').attr('href');
            if(tab === '#widget_editor') {
                Builder.Widget.save();
            }
            if(tab === '#theme') {
                Builder.Style.save();
                $('.js_theme_options_content').removeClass('show');
            }
            if(tab === '#fonts') {
                Builder.Fonts.save();
            }
            return false;
        },
        downloadElement : function () {
            return Builder.Element.download($(this));
        },
        activeElement : function () {
            return Builder.Element.active($(this));
        },
        deactivateElement : function () {
            return Builder.Element.deactivate($(this));
        },
    };

    Builder.Widget = {
        init: function() {
            this.load();
            this.loadSidebar();
            this.serviceLoad();
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
                        Builder.Widget.add( $(evt.item) , evt.to.id )
                    }
                },
            });
        },

        serviceLoad : function( e ) {
            $jqxhr   = $.post( base +'/ajax', { 'action' : 'Builder_Ajax::widgetLoad' }, function(data) {}, 'json');
            $jqxhr.done(function(response) {
                show_message(response.message, response.status);
                if(response.status === 'success') {
                    let str = '';
                    for (const [key_category, category] of Object.entries(response.data)) {
                        str += '<div class="panel panel-default">\n' +
                            '                <a role="button" data-bs-toggle="collapse" data-bs-parent="#widgetService" href="#widgetService_'+ category.id +'"><div class="panel-heading"><h4 class="panel-title">'+ category.name +'</h4></div></a>\n' +
                            '                <div id="widgetService_'+ category.id +'" class="panel-collapse collapse" role="tabpanel">\n' +
                            '                    <div class="panel-body">\n' +
                            '                        <div class="element_box element_box_3">';

                        for (const [key, items_tmp] of Object.entries(category.widgets)) {
                            let items = [items_tmp];
                            items.map(function(item) {
                                str += $('#js_widget_service_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                            });
                        }

                        str += '</ol>\n' + '</div>\n' + '</div>\n' + '</div>';
                    }
                    $('#widgetService').html(str).promise().done(function(){});
                }

            });
        },

        load : function () {
            let data = {
                'action' :'Ajax_Admin_Widget_Action::load',
                'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
            };
            let jqxhr = $.post(ajax, data, function(){}, 'json');
            jqxhr.done(function(response) {
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

                    let strIframe = '';

                    for (const [key, items_tmp] of Object.entries(response.data)) {
                        let items = [items_tmp];
                        items.map(function(item) {
                            strIframe += $('#js_iframe_widget_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                        });
                    }

                    $('#js_iframe_widget_list').html(strIframe);

                    checkbox_style();

                    widget_list.find('.js_widget_item').each( function(index) {
                        if( widget_list_key.indexOf($(this).attr('data-key')) === -1 ) {
                            $(this).addClass('widget-just-added');
                        }
                    });
                }
            });
        },

        loadSidebar : function () {

            let data = {
                'action' :'Ajax_Admin_Widget_Action::loadBySidebar',
                'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
            };

            jqxhr = $.post(ajax, data, function(){}, 'json');

            jqxhr.done(function(response) {
                let str = '';
                let i = 0;

                if(response.status === 'success') {

                    for (const [key_sidebar, sidebar] of Object.entries(response.data)) {

                        str += '<div class="box js_widget_sidebar_item" id="box_'+key_sidebar+'" data-key="'+key_sidebar+'">\n' +
                            '<a class="btn-collapse" id="btn-'+key_sidebar+'" data-bs-toggle="collapse" href="#widget-sidebar-content_'+key_sidebar+'"><div class="header">\n' +
                            '<h3 class="pull-left">'+sidebar.name+'</h3>\n' +
                            '<i class="pull-right fal fa-plus-square"></i>\n' +
                            '</div></a>\n' +
                            '<div class="box-content widget-sidebar-content collapse '+ ((i === 0) ? 'show' : '') +'" id="widget-sidebar-content_'+key_sidebar+'">\n' +
                            '<ul class="js_widget_sidebar_content_item" id="'+key_sidebar+'">';

                        i++;
                        if(typeof sidebar.widget != 'undefined') {
                            for (const [key, items_tmp] of Object.entries(sidebar.widget)) {
                                let items = [items_tmp];
                                items.map(function (item) {
                                    str += $('#js_widget_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                                });
                            }
                        }

                        str += '</ul>\n' + '</div>\n' + '</div>';
                    }

                    widget_sidebar_list.html(str).promise().done(function () {
                        if(widget_action === 'add') {
                            $('#menuItem_'+ widgetID +' .icon-edit').trigger('click');
                        }
                    });

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
                                    Builder.Widget.move( $(evt.item), evt.to.id );
                                }
                                else if( evt.oldIndex !== evt.newIndex )	{
                                    Builder.Widget.sort( evt.to.id );
                                }
                            },
                        });
                    });
                }
            });
        },

        loadEdit : function() {

            let data = {
                'action': 'Ajax_Admin_Widget_Action::info',
                'id'	: widgetID,
            };

            widget_edit.find('.loading').show();

            $jqxhr = $.post(ajax, data, function(){}, 'json');

            $jqxhr.done(function(response){

                if(response.status === 'success') {
                    $('#right-panel .nav-item a[href="#widget_editor"]').tab('show');
                    widget_edit.find('.js_widget_editor_content').html(response.data).promise().done(function(){
                        widget_edit.find('.loading').hide();
                        formBuilderReset();
                    });
                }
            });

            return false;
        },

        download : function(button) {

            let name 	= button.attr('data-url');

            if(name.length === 0) { show_message('Widget chưa đúng!', 'error'); return false; }

            button.closest('.widget_service_item').find('.status').css('display', 'block').text('Đang download');

            let data = {
                'action' 		: 'Ajax_Admin_Widget_Service_Action::download',
                'name' 			: name,
            };

            $jqxhr = $.post(ajax, data, function(data) {}, 'json');

            $jqxhr.done(function(response) {

                show_message(response.message, response.status);

                if(response.status === 'success') {

                    button.closest('.widget_service_item').find('.status').text('Đang cài đặt');

                    setTimeout( function()  {
                        Builder.Widget.install( button );
                    }, 500);
                }
            });

            return false;
        },

        install : function( button ) {

            let name = button.attr('data-url');

            let data = {
                'action' 		: 'Ajax_Admin_Widget_Service_Action::install',
                'name' 			: name,
            };

            $jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            $jqxhr.done(function(response) {
                show_message(response.message, response.status);
                button.closest('.widget_service_item').find('.status').text('Đã cài đặt');
                Builder.Widget.load();
            });

            return false;
        },

        addById : function(widget_id, button) {

            let data = {
                'action' :'Builder_Ajax::widgetAdd',
                widget_id: widget_id,
                widget_add: button.closest('.iframe_widget').attr('data-key'),
            };

            $jqxhr = $.post(ajax, data, function(){}, 'json');

            $jqxhr.done(function(response){

                show_message(response.message, response.status);

                if(response.status === 'success') {
                    widgetID = response.id;
                    widget_action = 'add';
                    Builder.Widget.loadSidebar();
                    Builder.main._loadIframe(domainReview);
                    $("#js_widget_iframe__close").trigger('click');
                }
            });

            return false;
        },

        add : function( t, s ) {

            let widget_id  = t.attr('data-key');

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
                    widgetID = response.id;
                    widget_action = 'add';
                    Builder.Widget.sort(s);
                    Builder.main._loadIframe(domainReview);
                }
            });
        },

        move : function( t, s ) {

            let data = {
                action : 'Ajax_Admin_Widget_Action::move',
                widget_id : t.attr('data-id'),
                sidebar_id: s,
            };

            jqxhr = $.post(ajax, data, function(){}, 'json');

            jqxhr.done( function(response) {
                if( response.status === 'success' ) {
                    Builder.Widget.sort( s );
                    Builder.main._loadIframe(domainReview);
                }
                else {
                    show_message(response.message, response.status);
                }
            });

            return false;
        },

        sort : function( s ) {

            o = [];

            $('#'+s).find('.js_widget_item').each(function(index) {
                o.push($(this).attr('data-id'));
            });

            $('#box_' + s ).find('.loading').show();

            let data = {
                'action' : 'Ajax_Admin_Widget_Action::sort',
                data: o
            };

            jqxhr = $.post(ajax, data, function(){}, 'json');

            jqxhr.done(function(response){
                $('#box_'+ s ).find('.loading').hide();
                if(response.status === 'success') {
                    show_message(response.message, response.status);
                    Builder.main._loadIframe(domainReview);
                }
            });

            return false;
        },

        copy : function( s ) {

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

                    Builder.main._loadIframe(domainReview);
                }
            });


            return false;
        },

        delete : function(t) {

            let button = t;

            widgetID = button.closest('.js_widget_item').attr('data-id');

            let data = {
                'action' : 'Ajax_Admin_Widget_Action::delete',
                'id'	: widgetID,
            };

            $jqxhr = $.post(ajax, data, function(){}, 'json');

            $jqxhr.done(function(response){

                if(response.status === 'success') {

                    if(Builder.main.selectedEl) {
                        if(Builder.main.selectedEl.attr('data-id') == widgetID) {
                            $('#select-box').hide();
                            Builder.main.selectedEl = null;
                            widget_edit.find('.js_widget_editor_content').html('');
                        }
                    }

                    button.closest('.js_widget_item').remove();

                    widgetID = 0;

                    Builder.main._loadIframe(domainReview);
                }
                else show_message(response.message, response.status);
            });

            return false;
        },

        save : function() {

            panel_right_loading.show();

            let data = $( ':input' , $('.js_widget_editor_content')).serializeJSON();

            $('.js_widget_editor_content').each(function(index, el) {
                $(this).find('textarea.tinymce').each(function(index, el) {
                    data[$(this).attr('name')] = tinyMCE.get($(this).attr('id')).getContent();
                });
                $(this).find('textarea[type="code"]').each(function(index, el) {
                    data[$(this).attr('name')] = editor[$(this).attr('name')].getValue();
                });
            });

            data.id = widgetID;

            data.action = 'Ajax_Admin_Widget_Action::save';

            jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function(response) {

                panel_right_loading.hide();

                show_message(response.message, response.status);

                if(response.status === 'error') {
                    if(isset($('input#'+data.field).val())) {
                        $('#'+data.field).focus();
                    }
                } else {
                    widget_action = 'edit';
                    widget_sidebar_list.find('#menuItem_' + widgetID + ' .widget-name').html(data.name);
                    Builder.main._loadIframe(domainReview);
                }
            });

            return false;
        },

        search : function(keyword) {

            widget_list.find('.js_widget_item').hide();

            widget_list.find('.js_widget_item .widget-name').each(function(){
                if($(this).text().toLowerCase().indexOf(""+keyword+"") !== -1 ){
                    $(this).closest('.js_widget_item').show();
                }
            });
            return false;
        },

        headingSelect : function(style) {

            widget_heading_modal.find('.loading').show();

            let data = {
                id : widgetID,
                widget_heading_style : style,
                action : 'Ajax_Admin_Widget_Action::heading'
            };

            jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function( response ) {

                widget_heading_modal.find('.loading').hide();

                if(response.status === 'error') {
                    show_message(response.message, response.status);
                } else {

                    widget_edit.find('input#heading_style').val(data.widget_heading_style);

                    $('#widget_heading_form').html(response.form).promise().done(function(){
                        formBuilderReset();
                        widget_heading_modal.modal('hide');
                    });
                }
            });
            return false;
        }
    };

    Builder.Element = {
        btnActive : '<button type="button" class="btn-green btn btn-block btn-active" data-builder-action="activeElement"><i class="fal fa-power-off"></i></button>',
        btnDelete : '<button type="button" class="btn-red btn btn-block btn-delete" data-builder-action="deleteElement"><i class="fal fa-trash"></i></button>',
        btnDeactivate : '<button type="button" class="btn-black btn btn-block btn-deactivate" data-builder-action="deactivateElement"><i class="fal fa-power-off"></i></button>',
        init: function() {
            this.loadHeader();
            this.loadNavigation();
            this.loadTopBar();
        },
        loadHeader: function () {
            let data = {
                'action' :'Builder_Ajax::elementLoad',
                'type' : 'header',
                'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
            };
            $jqxhr = $.post(ajax, data, function(){}, 'json');
            $jqxhr.done(function(response) {
                if(response.status === 'success') {
                    let str = '';
                    for (const [key, element] of Object.entries(response.element)) {
                        let items = [element];
                        items.map(function(item) {
                            str += $('#js_element_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                        });
                    }
                    $('#headerService').html(str).promise().done(function(){});
                }
            });
        },
        loadNavigation: function () {
            let data = {
                'action' :'Builder_Ajax::elementLoad',
                'type' : 'navigation',
                'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
            };
            $jqxhr = $.post(ajax, data, function(){}, 'json');
            $jqxhr.done(function(response) {
                if(response.status === 'success') {
                    let str = '';
                    for (const [key, element] of Object.entries(response.element)) {
                        let items = [element];
                        items.map(function(item) {
                            str += $('#js_element_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                        });
                    }
                    $('#navigationService').html(str).promise().done(function(){});
                }
            });
        },
        loadTopBar: function () {
            let data = {
                'action' :'Builder_Ajax::elementLoad',
                'type' : 'top-bar',
                'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
            };
            jqxhr = $.post(ajax, data, function(){}, 'json');
            jqxhr.done(function(response) {
                if(response.status === 'success') {
                    let str = '';
                    for (const [key, element] of Object.entries(response.element)) {
                        let items = [element];
                        items.map(function(item) {
                            str += $('#js_element_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                        });
                    }
                    $('#topBarService').html(str).promise().done(function(){});
                }
            });
        },
        loadOptions: function () {
            let data = {
                'action' :'Builder_Ajax::optionsLoad',
                'csrf_test_name':encodeURIComponent(getCookie('csrf_cookie_name')),
            };
            jqxhr = $.post(ajax, data, function(){}, 'json');
            jqxhr.done(function(response) {
                if(response.status === 'success') {
                    $('#themeOption').html(response.html).promise().done(function(){
                        formBuilderReset();
                    });
                }
            });
        },
        download : function(button) {

            let item   = button.closest('.element_item');

            let id 		= item.attr('data-id');

            let type 	= item.attr('data-type');

            button.text('Đang download');

            let data = {
                'action' 		: 'Theme_Ajax_Element::download',
                'id' 			: id,
                'type' 			: type,
            };

            $jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            $jqxhr.done(function( data ) {

                show_message(data.message, data.status);

                if(data.status === 'success') {

                    button.text('Đang cài đặt');

                    setTimeout( function()  {
                        Builder.Element.install( item, button );
                    }, 500);


                }
            });

            return false;
        },
        install : function( item, button ) {

            let id 	= item.attr('data-id');

            let type = item.attr('data-type');

            let action = button.closest('.element_item__action');

            let data = {
                'action' 		: 'Theme_Ajax_Element::install',
                'id' 			: id,
                'type' 			: type,
            };

            $jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            $jqxhr.done(function( data ) {

                show_message(data.message, data.status);

                if( data.status === 'success' ) {

                    button.text('Đã cài đặt');

                    action.html(Builder.Element.btnActive+Builder.Element.btnDelete);
                }

            });

            return false;
        },
        active : function(button) {

            let item   = button.closest('.element_item');

            let type 	= item.attr('data-type');

            let folder 	= item.attr('data-folder');

            let action = button.closest('.element_item__action');

            let data = {
                'action' 		: 'Theme_Ajax_Element::active',
                'folder' 		: folder,
                'type' 			: type,
            };

            jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function(response) {

                show_message(response.message, response.status);

                if( response.status === 'success' ) {

                    $('.btn-deactivate').each(function(){
                        $(this).remove();
                        $(this).closest('.element_item__action').html(Builder.Element.btnActive+Builder.Element.btnDelete);
                    });

                    action.html(Builder.Element.btnDeactivate);

                    Builder.main._loadIframe(domainReview);

                    $('#right-panel .nav-item a[href="#theme"]').tab('show');

                    Builder.Element.loadOptions();

                    if(type === 'header') {
                        $('#right-panel #themeOption #themeOption_header').collapse('show');
                    }
                    if(type === 'navigation') {
                        $('#right-panel #themeOption #themeOption_navigation').collapse('show');
                    }
                    if(type === 'top-bar') {
                        $('#right-panel #themeOption #themeOption_topBar').collapse('show');
                    }
                }
            });

            return false;
        },
        deactivate : function(button) {

            let item   = button.closest('.element_item');

            let type 	= item.attr('data-type');

            let folder 	= item.attr('data-folder');

            let action = button.closest('.element_item__action');

            let data = {
                'action' 		: 'Theme_Ajax_Element::unActive',
                'folder' 		: folder,
                'type' 			: type,
            };

            $jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            $jqxhr.done(function(response) {

                show_message(response.message, response.type);

                if(response.type === 'success') {
                    action.html(Builder.Element.btnActive+Builder.Element.btnDelete);
                    Builder.main._loadIframe(domainReview);
                }
            });

            return false;
        },
    };

    Builder.Style = {
        save : function() {

            panel_right_loading.show();

            let data = $( ':input' , $('#themeOption')).serializeJSON();

            $('#themeOption').each(function(index, el) {
                $(this).find('textarea.tinymce').each(function(index, el) {
                    data[$(this).attr('name')] = tinyMCE.get($(this).attr('id')).getContent();
                });
                $(this).find('textarea[type="code"]').each(function(index, el) {
                    data[$(this).attr('name')] = editor[$(this).attr('name')].getValue();
                });
            });

            data.action = 'Ajax_Admin_Theme_Action::saveOption';

            $jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            $jqxhr.done(function(response) {

                panel_right_loading.hide();

                show_message(response.message, response.status);

                if(response.status === 'success'){
                    tinymce_load();
                    Builder.main._loadIframe(domainReview);
                }
            });

            return false;
        }
    };

    Builder.Fonts = {
        save : function() {

            panel_right_loading.show();

            let data = $( ':input' , $('#system_fonts_family__content')).serializeJSON();

            data.action     =  'ajax_system_save';

            data.system_tab_key = 'cms-fonts';

            let load = $(this).find('.loading');

            load.show();

            $.post(ajax, data, function() {}, 'json').done(function(response) {

                panel_right_loading.hide();

                show_message(response.message, response.status);

                if(response.status === 'success') {
                    Builder.Element.loadOptions();
                }
            });

            return false;
        }
    };
});

function launchFullScreen(document) {
    if(document.documentElement.requestFullScreen) {

        if (document.FullScreenElement)
            document.exitFullScreen();
        else
            document.documentElement.requestFullScreen();

        //mozilla
    } else if(document.documentElement.mozRequestFullScreen) {

        if (document.mozFullScreenElement)
            document.mozCancelFullScreen();
        else
            document.documentElement.mozRequestFullScreen();
        //webkit
    } else if(document.documentElement.webkitRequestFullScreen) {

        if (document.webkitFullscreenElement)
            document.webkitExitFullscreen();
        else
            document.documentElement.webkitRequestFullScreen();
        //ie
    } else if(document.documentElement.msRequestFullscreen) {

        if (document.msFullScreenElement)
            document.msExitFullscreen();
        else
            document.documentElement.msRequestFullscreen();
    }
}

function isElement(obj){
    return (typeof obj==="object") &&
        (obj.nodeType===1) && (typeof obj.style === "object") &&
        (typeof obj.ownerDocument ==="object")/* && obj.tagName != "BODY"*/;
}

$(document).on('click', '.wg-box-item', function() {
    $('.wg-box-item').removeClass('active');
    $(this).addClass('active');
    $(this).closest('.wg-container-box').find('input').val($(this).attr('data-value'));
});
