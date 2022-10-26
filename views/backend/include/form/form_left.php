<!-- các field ở vị trí left - top -->
<?php if (!empty($FormLeftTopHtml)) { ?>
    <?php foreach ($FormLeftTopHtml as $id => $form) { $cookie = get_cookie("boxCollapse_".$id); ?>
        <div class="box">
            <a class="btn-collapse js_btn_collapse <?php echo (empty($cookie)) ? '' : 'active';?>" id="js_btn_collapse_<?php echo $id;?>" data-bs-toggle="collapse" href="#<?php echo $id;?>"><?php echo (get_cookie("boxCollapse_".$id) == null) ? '<i class="fal fa-chevron-up"></i>' : '<i class="fal fa-chevron-down"></i>';?></a>
            <div class="header" style="<?php echo (empty($cookie)) ? 'display:none;':'display:block;';?>"><h3 class="pull-left"><?php echo $form['name'];?></h3></div>
            <div class="box-content collapse <?php echo (empty($cookie)) ? 'show' : '';?>" id="<?php echo $id;?>">
                <?php if(Metabox::has($id)) { Metabox::render($id, (isset($object)) ? $object : []); } ?>
                <?php echo $form['html'];?>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php } ?>
<?php } ?>

<!-- các field ở vị trí language -->
<?php if(!empty($FormLangHtml)) { ?>
    <?php foreach ($FormLangHtml as $id => $form): $cookie = get_cookie("boxCollapse_".$id); ?>
        <div class="box">
            <a class="btn-collapse js_btn_collapse <?php echo (empty($cookie)) ? '' : 'active';?>" id="js_btn_collapse_<?php echo $id;?>" data-bs-toggle="collapse" href="#<?php echo $id;?>"><?php echo (get_cookie("boxCollapse_".$id) == null) ? '<i class="fal fa-chevron-up"></i>' : '<i class="fal fa-chevron-down"></i>';?></a>
            <div class="header" style="<?php echo (empty($cookie)) ? 'display:none;' : 'display:block;';?>"><h3 class="pull-left"><?php echo $form['name'];?></h3></div>
            <div class="box-content collapse <?php echo (empty($cookie)) ? 'show' : '';?>" id="<?php echo $id;?>">
                <ul class="nav nav-tabs form-tabs" role="tablist">
                    <?php
                    if (!empty($FormTabsHtml) || Language::hasMulti()) {
                        foreach (Language::list() as $key => $val) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($key == Language::default()) ? 'active' : '';?>" href="#lang_<?php echo $key;?>_panel" id="lang_<?php echo $key;?>" data-bs-toggle="tab" role="tab" aria-controls="lang_<?php echo $key;?>_panel" aria-selected="true"><?php echo $val['label'];?></a>
                            </li>
                        <?php }
                    } ?>
                    <?php
                    if (!empty($FormTabsHtml)) {
                        foreach ($FormTabsHtml as $idTab => $formTab) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab_<?php echo $idTab;?>_panel" id="tab_<?php echo $idTab;?>" data-bs-toggle="tab" role="tab" aria-controls="tab_<?php echo $idTab;?>_panel" aria-selected="true"><?php echo $formTab['name'];?></a>
                            </li>
                        <?php }
                    } ?>
                </ul>
                <div class="tab-content">
                    <?php echo $form['html']; ?>
                    <?php if (!empty($FormTabsHtml)) { ?>
                        <?php foreach ($FormTabsHtml as $idTab => $formTab) { ?>
                            <div class="tab-pane fade" id="tab_<?php echo $idTab;?>_panel" role="tabpanel" aria-labelledby="tab_<?php echo $idTab;?>" tabindex="0">
                                <div class="box-content">
                                    <div class="row m-1">
                                        <?php if(Metabox::has($idTab)) { Metabox::render($idTab, (isset($object)) ? $object : []); } ?>
                                        <?php echo $formTab['html']; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        <style>
            .box .form-tabs {
                margin-top: -10px;
                background-color: #F7FAFC;
            }
            .box .form-tabs li {
                padding:0;
            }
            .box .form-tabs li a {
                padding:10px; border-bottom: 0!important;
            }
        </style>
    <?php endforeach ?>
<?php } ?>

<!-- các field ở vị trí left - bottom -->
<?php if (!empty($FormLeftBottomHtml)) { ?>
    <?php foreach ($FormLeftBottomHtml as $id => $form): $cookie = get_cookie("boxCollapse_".$id); ?>
        <div class="box">
            <a class="btn-collapse js_btn_collapse <?php echo (empty($cookie)) ? '':'active';?>" id="js_btn_collapse_<?php echo $id;?>" data-bs-toggle="collapse" href="#<?php echo $id;?>"><?php echo (get_cookie("boxCollapse_".$id) == null) ? '<i class="fal fa-chevron-up"></i>' : '<i class="fal fa-chevron-down"></i>';?></a>
            <div class="header" style="<?php echo (empty($cookie)) ? 'display:none;':'display:block;';?>"><h3 class="pull-left"><?php echo $form['name'];?></h3></div>
            <div class="box-content collapse <?php echo (empty($cookie)) ? 'show' : '';?>" id="<?php echo  $id;?>">
                <div class="row m-1">
                <?php if(Metabox::has($id)) { Metabox::render($id, (isset($object)) ? $object : []); } ?>
                <?php echo $form['html'];?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php endforeach ?>
<?php } ?>

<?php do_action('before_admin_form_left'); ?>

<div class="box box-fix">
    <div class="box-content">
        <div class="row m-1">
            <div class="button-action text-end">
                <button name="save" class="btn-icon btn-green mr-0 js_admin_form_btn__save" style="margin-right: 0;"><i class="fad fa-hdd"></i> Lưu</button>
            </div>
        </div>
    </div>
</div>

