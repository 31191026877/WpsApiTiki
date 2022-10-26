<?php
$headingService = SKDService::heading()->all();

$headingWidgets = [];

if(isset($headingService->status) && $headingService->status == 'success') {

    $headingDownload = array_merge(ThemeSidebar::registerHeading(), ThemeWidget::registerHeading());

    $headingService = $headingService->data;

    foreach ($headingService as $value) {

        $temp = [
            'id' => $value->id,
            'name' => $value->name,
            'image' => $value->image,
            'slug' => $value->slug,
            'download' => (!empty($headingDownload[$value->slug])) ? false : true,
        ];

        if(in_array('widget', $value->tags) !== false) {
            $headingWidgets[$value->slug] = $temp;
        }
    }
}
?>
<div class="modal fade" id="js_widget_heading_modal">
    <div class="modal-dialog modal-lg">
        <div id="">
            <div class="modal-content">
                <div class="header"><h2>Widget Heading</h2></div>
                <div class="modal-body">
                    <?php echo Admin::loading();?>
                    <div class="box-content scrollbar">
                        <div class="element_list">
                            <div class="element_item js_heading_service_item" data-type="widget" data-id="none" style="background-image: url('<?php echo Template::imgLink('heading/heading-style-none.png');?>'); background-repeat: no-repeat;">
                                <a class="element_item__heading name" href="#">Mặc định</a>
                                <div class="element_item__action">
                                    <button type="button" class="btn-green btn btn-block btn-active" data-id="none"><i class="fal fa-power-off"></i></button>
                                </div>
                            </div>
                            <?php foreach ($headingWidgets as $item) { $item = (object)$item; ?>
                                <div class="element_item js_heading_service_item" data-type="widget" data-id="<?php echo $item->slug;?>" style="background-image: url('<?php echo $item->image;?>'); background-repeat: no-repeat;">
                                    <a class="element_item__heading name" href="#"><?php echo $item->name;?></a>
                                    <div class="element_item__action">
                                        <button type="button" class="btn-green btn btn-block btn-active" data-id="<?php echo $item->slug;?>" style="display:<?php echo ($item->download == true) ? 'none' : 'block';?>"><i class="fal fa-power-off"></i></button>
                                        <button type="button" class="btn-blue btn btn-block btn-download" style="display:<?php echo ($item->download == false) ? 'none' : 'block';?>"><i class="fal fa-long-arrow-down"></i></button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-icon btn btn-white" data-bs-dismiss="modal"> <i class="fal fa-times"></i> Đóng</button>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #js_widget_heading_modal .box-content {
        overflow: auto; max-height: 600px;
    }
    .element_list {
        display: flex; flex-wrap: wrap; gap: 10px;
    }
    .element_list .element_item {
        flex: 0 0 calc(100%/4 - 10px); width:calc(100%/4 - 10px);
        height: 255px;
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

    .element_list .element_item:hover, .element_list .element_item.active {
        border-color: #0d6efd;
        box-shadow: 0px 1px 5px 0px rgba(13, 110, 253, 0.15);
        -webkit-box-shadow: 0px 1px 5px 0px rgba(13, 110, 253, 0.15);
    }
    .element_list .element_item.active .btn-active { display: none!important; }

    .element_list .element_item:hover .element_item__btn {
        display: block;
    }
</style>
<script type="text/javascript">
    $(function(){

        let ThemeHeadingHandler = function() {
            $( document )
                .on('click', '.js_heading_service_item .btn-download', this.download)
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

        ThemeHeadingHandler.prototype.install = function(item, button) {

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
                    $('.js_heading_service_item[data-id="'+id+'"]').find('.element_item__action .btn-active').show();
                    $('.js_heading_service_item[data-id="'+id+'"]').find('.element_item__action .btn-download').hide();
                }
            });

            return false;
        };

        new ThemeHeadingHandler();
    });
</script>