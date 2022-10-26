<?php Admin::partial('include/action_bar');?>
<div class="widgets">
    <div class="sidebar" id="js_widget_local">
        <div class="header-bar">
            <div class="pull-left"><h2>Widget</h2></div>
            <div class="pull-right">
                <?php if(Admin::isRoot()) {?>
                <button class="btn btn-blue" id="js_widget_btn__reload"><i class="fad fa-sync fa-spin"></i></button>
                <button class="btn btn-green" id="js_widget_btn__created" data-bs-toggle="modal" href="#js_widget_modal__created"><?php echo Admin::icon('add');?></button>
                <button class="btn btn-red" id="js_service_widget"><i class="fad fa-cloud-download-alt"></i></button>
                <?php } ?>
            </div>
        </div>
        <div class="widgets-list scrollbar"><ul id="js_widget_list"></ul></div>
    </div>
    <div class="content">
        <div class="header-bar"> <h2>Widget Box</h2> </div>
        <div class="sidebar-list row m-1" id="js_widget_sidebar_list"></div>
    </div>
</div>

<script id="js_widget_item_template" type="text/x-custom-template">
    <li id="menuItem_${id}" class="js_widget_item" style="display: list-item;" data-id="${id}" data-key="${widget_id}">
        <div class="widget_sidebar">
            <div class="widget_sidebar_header">
                <span class="text-left pull-left">${widget_name}</span>
                <div class="action pull-right">
                    <a href="#" class="js_widget_item_btn__edit icon-edit"><i class="fas fa-wrench"></i></a> &nbsp;&nbsp;
                    <a href="#" class="js_widget_item_btn__copy icon-copy"><i class="fal fa-clone"></i></a> &nbsp;&nbsp;
                    <a href="#" class="js_widget_item_btn__delete icon-delete"><i class="fal fa-trash-alt"></i></a>
                </div>
            </div>
            <div class="title">
                <h3 class="widget-name">${name}</h3>
                <p style="margin:0" class="widget-key">${widget_id}</p>
            </div>
        </div>
    </li>
</script>

<div class="modal fade" id="js_widget_service_modal" role="dialog">
  	<div class="modal-dialog modal-lg" role="document" style="width: 100vw; max-width: 100vw; margin-top: 0;">
    	<div class="modal-content">
            <button type="button" class="close" data-bs-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
      		<?php echo Admin::loading();?>
      		<div class="modal-body" id="widget-view-content"></div>
    	</div>
  	</div>
</div>

<div id="js_widget_modal__edit">
    <form id="js_widget_edit_form">
        <?php echo Admin::loading('ajax_edit_widget_loader');?>
        <div class="header">
            <div class="widget_header">
                <h2 class="pull-left"></h2>
                <div class="pull-right text-right">
                    <button type="submit" name="save" class="btn btn-icon btn-green"><?php echo Admin::icon('save');?> Lưu</button>
                    <button type="button" class="btn btn-default js_widget_btn__close">Đóng</button>
                </div>
            </div>
        </div>
        <div class="box-edit-widget scrollbar"></div>
        <hr />
        <div class="box-content text-right">
            <button type="submit" name="save" class="btn btn-icon btn-green"><?php echo Admin::icon('save');?>Lưu</button>
            <button type="button" class="btn btn-default js_widget_btn__close">Đóng</button>
        </div>
    </form>
</div>

<div id="js_widget_modal__created" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="header-title" style="overflow: hidden;">
                <h4 class="header" style="margin: 0;">Tạo Widget</h4>
            </div>
            <?php echo Admin::loading();?>
            <div class="modal-body">
                <form id="js_widget_created__form" style="padding:20px 10px" autocomplete="off">
                    <?php
                    $Form = new FormBuilder();
                    $Form
                        ->add('widget_key', 'text', ['label' => 'Widget key'])
                        ->add('widget_name', 'text',['label' => 'Widget name'])
                        ->add('widget_type', 'select',['label' => 'Loại khung bao quanh', 'options' => [
                            'container' => 'container',
                            'grid' => 'grid',
                        ]])
                        ->add('widget_position', 'select', ['label' => 'Vị trí input khung', 'options' => [
                            'left' => 'left',
                            'right' => 'right',
                        ]])
                        ->html(false);
                    ?>
                    <div class="clearfix"></div>
                    <div class="text-right">
                        <button class="btn-icon btn-blue"><?php echo Admin::icon('add');?> Tạo widget</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .page-content .page-body { padding-top: 0;}
    .page-content .ui-layout { overflow: hidden; max-width: 2200px;}
    .page-content .action-bar { display: none; }
	[class*=col-md-] { padding:5px; }
	.fancybox-container { z-index:5000; }
    #js_widget_service_modal.modal.in .modal-dialog { margin: 0; width: 100%!important; }
	#js_widget_service_modal .modal-content { width: 100%; height:100vh; border-radius:0px; border:0; }
</style>