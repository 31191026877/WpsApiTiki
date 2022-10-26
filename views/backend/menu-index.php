<?php Admin::partial('include/action_bar');?>
<div class="theme-menu">
    <div class="sidebar sidebar-menu-list">
        <div class="header-bar">
            <a href="javascript:;" class="btn-icon btn-blue add-fast" data-fancybox="" data-src="#hidden-content"><i class="fad fa-plus"></i>Thêm menu</a>
        </div>
        <div class="list-menu">
            <ul id="js_theme_menu__list">
                <?php foreach ($menus as $key => $menu) {?>
                    <li class="group-menu js-group-menu <?php echo ($key == 0) ? 'active' : '';?>">
                        <a href="#" data-id="<?php echo $menu->id;?>" data-menu="<?php echo htmlentities(json_encode($menu));?>">
                            <i class="fad fa-check-circle"></i> <?php echo $menu->name;?>
                            <button class="btn btn-red js_menu_btn__delete" data-id="<?php echo $menu->id;?>" type="button"><i class="fal fa-trash"></i></button>
                        </a>
                    </li>
                <?php } ?>
                <li class="group-menu add">
                    <a href="#" data-fancybox="" data-src="#hidden-content"><i class="fad fa-plus-circle"></i> Thêm menu</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar sidebar-object-list">
        <div class="header-bar"><h2>Chọn lựa</h2></div>
        <div class="menu-items">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <?php foreach ($this->list_object as $obj_key => $obj) {?>
                    <div class="card panel panel-default">
                        <!-- title data item -->
                        <div class="card-header panel-heading" role="tab">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-bs-toggle="collapse" href="#<?=$obj_key;?>_box"><?=$obj['label'];?></a>
                            </h4>
                        </div>
                        <!-- /title data item -->
                        <div id="<?=$obj_key;?>_box" class="panel-collapse collapse" data-bs-parent="#accordion">
                            <div class="card-body panel-body">
                                <div role="tabpanel">
                                    <div class="tab-content">
                                        <!-- danh sách dữ liệu -->
                                        <div role="tabpanel" class="tab-pane active" id="<?=$obj_key;?>_all">
                                            <?php if(have_posts($obj['data'])){?>
                                                <?php foreach ($obj['data'] as $key => $val): ?>
                                                    <?php
                                                    $id     = $val->id;
                                                    $value  = (isset($val->title))?$val->title:$val->name;
                                                    ?>
                                                    <div class="checkbox">
                                                        <label> <input name="<?=$obj['type'];?>" type="checkbox" value="<?=$id;?>" class="icheck"> &nbsp;<?= $value ;?> </label>
                                                    </div>
                                                <?php endforeach ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="js_theme_menu_action" style="padding:10px 0px; display: flex">
                                    <button class="js_theme_menu_items__add btn-icon btn-blue pull-left" data-type="<?=$obj_key;?>">Thêm vào menu</button>
                                    <select name="parent_id" class="form-control js_theme_menu_parent">
                                        <option value=""> -- Chọn danh mục cha --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <!-- đường link -->
                <div class="card panel panel-default">
                    <div class="card-header panel-heading" role="tab">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-bs-toggle="collapse" href="#link_box">Liên kết</a>
                        </h4>
                    </div>
                    <div id="link_box" class="panel-collapse collapse" data-bs-parent="#accordion">
                        <div class="card-body panel-body">
                            <div class="form-horizontal">
                                <div class="form-group" style="margin: 0;padding:10px;">
                                    <label class="control-label">Url</label>
                                    <div class="">
                                        <input name="url" type="url" class="form-control" value="" placeholder="http://">
                                    </div>
                                </div>
                                <div class="form-group" style="margin: 0;padding:0 10px;">
                                    <label class="control-label">Tiêu đề</label>
                                    <div class="">
                                        <input name="title" type="text" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="js_theme_menu_action" style="padding:10px 10px; overflow: hidden">
                                <button class="js_theme_menu_items__add btn-icon btn-blue pull-left" data-type="link">Thêm vào menu</button>
                                <div class="loading pull-right"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /link -->
            </div>
        </div>
    </div>
    <div class="content">
        <div class="header-bar"> <h2 id="js_menu_name">Main menu</h2> </div>
        <div class="content-menu-items">
            <?php Admin::loading('js_theme_menu_items_loading');?>
            <label>Cấu trúc menu</label>
            <div id="js_theme_menu_items__list" class="sort-menu dd" data-id=""></div>
            <hr/>
            <div id="js_theme_menu_items__location">
                <label>Vị trí hiển thị</label>
                <?php foreach ($this->navigation as $key => $value): ?>
                    <div class="checkbox">
                        <label>
                            <input name="menu_location[]" class="icheck js_theme_menu_location" type="checkbox" value="<?= $key; ?>"> <?= $value; ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <div class="sidebar sidebar-menu-item-edit">
        <div class="header"><h3 class="pull-left">Cập Nhật Menu</h3></div>
        <div class="box-content" id="modal-edit-menu" style="padding:10px;position: relative">
            <?php Admin::loading('js_theme_menu_item_edit_loading');?>
            <form id="js_menu_item_form__save">
                <div class="result"></div>
                <button type="submit" class="btn btn-blue"><?php echo Admin::icon('save');?> Lưu</button>
            </form>
        </div>
        <div class="clearfix"> </div>
    </div>
</div>
<style>
    .page-content .action-bar { display: none;}
    .page-content .page-body { padding-top: 0;}
    .page-content .ui-layout { overflow: hidden; max-width: 2200px;}
</style>

<!-- popup thêm menu -->
<div style="display: none;min-width: 350px; padding:0;" id="hidden-content">
    <div class="header"><h4>THÊM MENU</h4></div>
    <form id="js_menu_form__add" style="padding:20px 10px">
        <?php echo FormBuilder::render(['field' => 'name', 'label' => 'Tên menu', 'value'=>'','type' => 'text']);?>
        <div class="clearfix"></div>
        <div class="text-right">
            <button class="btn-icon btn-green add-fast"><?php echo Admin::icon('add');?> Lưu</button>
        </div>
    </form>
</div>

<script id="js_menu_item_template" type="text/x-custom-template">
    <div class="dd-handle dd3-handle"></div>
    <div class="panel-group" data-id="${id}">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title pull-left ">${name}</h4>
            </div>
        </div>
    </div>
    <div class="panel-action d-flex">
        <p style="padding:5px 20px;margin:0;text-transform:capitalize;">${type}</p>
        <a href="${id}" class="btn btn-red  btn-xs icon-delete color-white"><?php echo Admin::icon('delete');?></a>
        <a href="${id}" class="btn btn-blue btn-xs icon-edit color-white"><?php echo Admin::icon('edit');?></a>
    </div>
</script>

<script defer>
    $(function() {
        $('.menu .panel-collapse').first().addClass('in');
    });
</script>