
    <div class="col-md-12">
        <div class="box">
            <div class="header"><h2>Sidebar</h2></div>
            <div class="box-content" style="padding:10px;">
                <div class="row">
                    <div class="col-md-5">
                        <form id="sidebar_heading_form">
                            <div class="sidebar_form_setting"></div>
                            <div class="text-center">
                                <button class="btn btn-green btn-block"><?php echo Admin::icon('save');?> Lưu</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-7">
                        <div class="element_list scrollbar">
                            <div class="element_item js_heading_service_item <?php echo ($headingSidebarActive == 'none') ? 'active' : '';?>" data-type="sidebar" data-id="none" style="background-image: url('<?php echo Template::imgLink('heading/heading-style-none.png');?>'); background-repeat: no-repeat;">
                                <a class="element_item__heading name" href="#">Mặc định</a>
                                <div class="element_item__action">
                                    <?php $active = ($headingSidebarActive == 'none') ? true : false;?>
                                    <button type="button" class="btn-green btn btn-block btn-active" style="display:<?php echo ($active == true) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                    <button type="button" class="btn-red btn btn-block btn-delete" style="display:<?php echo ($active == true) ? 'none' : 'block';?>"><i class="fal fa-trash"></i></button>
                                    <button type="button" class="btn-white btn btn-block btn-deactivate" style="display:<?php echo ($active == false) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                </div>
                            </div>
                            <?php foreach ($headingSidebar as $item) { $item = (object)$item; ?>
                                <div class="element_item js_heading_service_item <?php echo ($headingSidebarActive == $item->slug) ? 'active' : '';?>" data-type="sidebar" data-id="<?php echo $item->slug;?>" style="background-image: url('<?php echo $item->image;?>'); background-repeat: no-repeat;">
                                    <a class="element_item__heading name" href="#"><?php echo $item->name;?></a>
                                    <div class="element_item__action">
                                        <?php $active = ($headingSidebarActive == $item->slug) ? true : false;?>
                                        <button type="button" class="btn-green btn btn-block btn-active" style="display:<?php echo ($item->download == true || $active == true) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                        <button type="button" class="btn-red btn btn-block btn-delete" style="display:<?php echo ($item->download == true || $active == true) ? 'none' : 'block';?>"><i class="fal fa-trash"></i></button>
                                        <button type="button" class="btn-white btn btn-block btn-deactivate" style="display:<?php echo ($active == false) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                        <button type="button" class="btn-blue btn btn-block btn-download" style="display:<?php echo ($item->download == false) ? 'none' : 'block';?>"><i class="fal fa-long-arrow-down"></i></button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box">
            <div class="header"><h2>Widget</h2></div>
            <div class="box-content" style="padding:10px;">
                <div class="row">
                    <div class="col-md-5">
                        <div class="widget_form_setting"></div>
                    </div>
                    <div class="col-md-7">
                        <div class="element_list scrollbar">
                            <div class="element_item js_heading_service_item <?php echo ($headingWidgetActive == 'none') ? 'active' : '';?>" data-type="widget" data-id="none" style="background-image: url('<?php echo Template::imgLink('heading/heading-style-none.png');?>'); background-repeat: no-repeat;">
                                <a class="element_item__heading name" href="#">Mặc định</a>
                                <div class="element_item__action">
                                    <?php $active = ($headingWidgetActive == 'none') ? true : false;?>
                                    <button type="button" class="btn-green btn btn-block btn-active" style="display:<?php echo ($active == true) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                    <button type="button" class="btn-red btn btn-block btn-delete" style="display:<?php echo ($active == true) ? 'none' : 'block';?>"><i class="fal fa-trash"></i></button>
                                    <button type="button" class="btn-red btn btn-block btn-deactivate" style="display:<?php echo ($active == false) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                </div>
                            </div>
                            <?php foreach ($headingWidgets as $item) { $item = (object)$item; ?>
                                <div class="element_item js_heading_service_item <?php echo ($headingWidgetActive == $item->slug) ? 'active' : '';?>" data-type="widget" data-id="<?php echo $item->slug;?>" style="background-image: url('<?php echo $item->image;?>'); background-repeat: no-repeat;">
                                    <a class="element_item__heading name" href="#"><?php echo $item->name;?></a>
                                    <div class="element_item__action">
                                        <?php $active = ($headingWidgetActive == $item->slug) ? true : false;?>
                                        <button type="button" class="btn-green btn btn-block btn-active" style="display:<?php echo ($item->download == true || $active == true) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                        <button type="button" class="btn-red btn btn-block btn-delete" style="display:<?php echo ($item->download == true || $active == true) ? 'none' : 'block';?>"><i class="fal fa-trash"></i></button>
                                        <button type="button" class="btn-red btn btn-block btn-deactivate" style="display:<?php echo ($active == false) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                        <button type="button" class="btn-blue btn btn-block btn-download" style="display:<?php echo ($item->download == false) ? 'none' : 'block';?>"><i class="fal fa-long-arrow-down"></i></button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
<style>
    .element_list {
        display: flex; flex-wrap: wrap; gap: 10px;
        max-height: 500px; overflow-y: auto;
    }
    .element_list .element_item {
        flex: 0 0 calc(100%/3 - 10px); width:calc(100%/3 - 10px);
        height: 280px;
        position: relative;
        text-align: center;
        font-weight: normal;
        font-size: 11px;
        color: #000;
        background-repeat: no-repeat;
        padding-top: 0px;
        display: block;
        border-color: #ddd;
        border-style: solid;
        border-width: 1px;
        border-radius: 0px;
        background-position: center;
        box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.15);
        -webkit-box-shadow: 0px 1px 5px 0px rgba(0, 0, 0, 0.15);
        background-size: 100%;
        z-index: 100;
        background-color: #fff;
        opacity: 1;
        flex-direction: column-reverse;
        padding-bottom: 7px;
        -webkit-animation: showSlowlyElement 700ms;
        animation: showSlowlyElement 700ms;
        border-radius: 5px;
    }
    .element_list .element_item:before {
        content: " ";
        background: #fff;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        transition: opacity 0.7s;
    }
    .element_list .element_item .element_item__heading {
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        padding: 0.5rem 0;
        position: absolute;
        bottom: 0px;
        width: 100%;
        left: 0;
        font-size: 12px;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
    .element_list .element_item .element_item__action {
        position: absolute;
        cursor: pointer;
        bottom: 35px;
        left: 25%;
        width: 50%;
        text-align: center;
    }
    .element_list .element_item .element_item__btn {
        position: relative;
        bottom: 0px;
        background: #4285f4;
        color: #fff;
        border-radius: 24px;
        cursor: pointer;
        box-shadow: 0px 3px 7px 1px rgba(0, 0, 0, 0.1), 1px 2px 7px 1px rgba(255, 255, 255, 0.1) inset;
        font-size: 12px;
        width: 36px;
        line-height: 36px;
        height: 36px;
        display: none;
        top: 50%;
        left: 50%;
        margnin: auto;
        margin-top: -18px;
        margin-left: -18px;
        border-color: #0d6efd;
    }
    .element_list .element_item .element_item__btn.status {
        width: 50%;
        top: 39%;
        left: 25%;
    }

    .element_list .element_item:hover {
        border-color: #0d6efd;
        box-shadow: 0px 1px 5px 0px rgba(13, 110, 253, 0.15);
        -webkit-box-shadow: 0px 1px 5px 0px rgba(13, 110, 253, 0.15);
    }
    .element_list .element_item:hover:before {
        opacity: 0.3;
    }
    .element_list .element_item:hover .element_item__btn {
        display: block;
    }
</style>
<script type="text/javascript">
    $(function(){

        $.ajaxSetup({
            beforeSend: function(xhr, settings) {
                if (settings.data.indexOf('csrf_test_name') === -1) {
                    settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
                }
            }
        });

        let ThemeHeadingHandler = function() {
            $( document )
                //cài widget
                .on('click', '.js_heading_service_item .btn-download', this.download)
                .on('click', '.js_heading_service_item .btn-active', this.active)
                .on('click', '.js_heading_service_item .btn-deactivate', this.deactivate)
                .on('click', '.js_heading_service_item .btn-delete', this.delete)
                .on('submit', '#sidebar_heading_form', this.sidebarSave)
                .on('submit', '#widget_heading_form', this.widgetSave)

            this.sidebarSetting();
            this.widgetSetting();
        };

        ThemeHeadingHandler.prototype.download = function(e) {

            let button = $(this);

            let item   = $(this).closest('.js_heading_service_item');

            let id 		= item.attr('data-id');

            button.html('Đang download');

            let data = {
                'action' 		: 'Theme_Ajax_Element::download',
                'id' 			: id,
                'type' 			: 'heading',
            };

            jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function( data ) {

                show_message(data.message, data.status);

                if(data.status === 'success') {

                    button.html('Đang cài đặt');

                    setTimeout( function()  {
                        ThemeHeadingHandler.prototype.install( item, button );
                    }, 500);
                }
            });

            return false;
        }

        ThemeHeadingHandler.prototype.install = function( item, button ) {

            let id 		= item.attr('data-id');

            let data = {
                'action' 		: 'Theme_Ajax_Element::install',
                'id' 			: id,
                'type' 			: 'heading',
            };

            let jqxhr  = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function( data ) {

                show_message(data.message, data.status);

                if( data.status === 'success' ) {
                    $('.js_heading_service_item[data-id="'+id+'"]').each(function () {
                        $(this).find('.element_item__action').html('<button type="button" class="btn-green btn btn-block btn-active"><i class="fal fa-power-off"></i></button> <button type="button" class="btn-red btn btn-block btn-delete"><i class="fal fa-trash"></i></button>');
                    });
                }

            });

            return false;
        };

        ThemeHeadingHandler.prototype.active = function( e ) {

            let item   = $(this).closest('.js_heading_service_item');

            let type 	= item.attr('data-type');

            let id 	= item.attr('data-id');

            let data = {
                'action' 		: 'Theme_Ajax_Element::active',
                'id' 		    : id,
                'type' 			: 'heading',
                'object_type'   : type
            };

            let jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function( data ) {

                show_message(data.message, data.status);

                if( data.status === 'success' ) {
                    $('.js_heading_service_item.active').find('.element_item__action').html('<button type="button" class="btn-green btn btn-block btn-active"><i class="fal fa-power-off"></i></button> <button type="button" class="btn-red btn btn-block btn-delete"><i class="fal fa-trash"></i></button>');
                    $('.js_heading_service_item.active').removeClass('active');
                    item.find('.element_item__action').html('<button type="button" class="btn-white btn btn-block btn-deactivate"><i class="fal fa-power-off"></i></button>');
                    item.addClass('active');
                    if(type == 'sidebar') {
                        ThemeHeadingHandler.prototype.sidebarSetting();
                    }
                    else {
                        ThemeHeadingHandler.prototype.widgetSetting();
                    }
                }
            });

            return false;
        };

        ThemeHeadingHandler.prototype.deactivate = function( e ) {

            let item   = $(this).closest('.js_heading_service_item');

            let type   = item.attr('data-type');

            let id 	   = item.attr('data-id');

            let data = {
                'action' 		: 'Theme_Ajax_Element::unActive',
                'id' 		    : id,
                'type' 			: 'heading',
                'object_type' 	: type,
            };

            let jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function( data ) {

                show_message(data.message, data.status);

                if( data.status === 'success' ) {
                    item.removeClass('active');
                    item.find('.element_item__action').html('<button type="button" class="btn-green btn btn-block btn-active"><i class="fal fa-power-off"></i></button> <button type="button" class="btn-red btn btn-block btn-delete"><i class="fal fa-trash"></i></button>');
                }
            });

            return false;
        };

        ThemeHeadingHandler.prototype.sidebarSetting = function(e) {

            let data = {
                'action' : 'Theme_Ajax_Element::headingSetting',
                'type' 	 : 'sidebar',
            };

            let jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function(response) {

                show_message(response.message, response.status);

                if(response.status === 'success') {
                    $('.sidebar_form_setting').html(response.html);
                    formBuilderReset();
                }
            });

            return false;
        }

        ThemeHeadingHandler.prototype.widgetSetting = function(e) {

            let data = {
                'action' : 'Theme_Ajax_Element::headingSetting',
                'type' 	 : 'widget',
            };

            let jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function(response) {

                show_message(response.message, response.status);

                if(response.status === 'success') {
                    $('.widget_form_setting').html(response.html);
                    formBuilderReset();
                }
            });

            return false;
        }

        ThemeHeadingHandler.prototype.sidebarSave = function(e) {

            let data = $(this).serializeJSON();

            data.action = 'Theme_Ajax_Element::headingSave';

            data.headingType = 'sidebar';

            let jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function(response) {

                show_message(response.message, response.status);

                if(response.status === 'success') {
                }
            });

            return false;
        }

        ThemeHeadingHandler.prototype.widgetSave = function(e) {

            let data = $(this).serializeJSON();

            data.action = 'Theme_Ajax_Element::headingSave';

            data.headingType = 'widget';

            let jqxhr   = $.post(ajax, data, function(data) {}, 'json');

            jqxhr.done(function(response) {

                show_message(response.message, response.status);

                if(response.status === 'success') {
                }
            });

            return false;
        }

        new ThemeHeadingHandler();
    });
</script>

