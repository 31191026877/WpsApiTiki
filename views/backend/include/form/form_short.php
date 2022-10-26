<?php if (!empty($FormLeftTopHtml)) {
    foreach ($FormLeftTopHtml as $id => $form) { ?>
        <div class="box-content" id="<?= $id;?>">
            <?php if(Metabox::has($id)) { Metabox::render($id); } ?>
            <?php echo $form['html'];?>
            <div class="clearfix"></div>
        </div>
    <?php }
}
if(!empty($FormLangHtml)) {
    foreach ($FormLangHtml as $id => $form) { ?>
        <div class="box-content" id="<?= $id;?>">
            <!-- tab language -->
            <ul class="nav nav-tabs form-tabs" role="tablist">
                <?php if (!empty($FormTabsHtml) || Language::hasMulti()) {
                    foreach (Language::list() as $key => $val) { ?>
                        <li class="nav-item"><a class="nav-link <?php echo ($key == Language::default()) ? 'active' : '';?>" href="#lang_<?php echo $key;?>_panel" id="lang_<?php echo $key;?>" data-bs-toggle="tab" role="tab" aria-controls="lang_<?php echo $key;?>_panel" aria-selected="true"><?php echo $val['label'];?></a></li>
                    <?php }
                } ?>
                <?php if (!empty($FormTabsHtml)) {
                    foreach ($FormTabsHtml as $idTab => $formTab) { ?>
                        <li class="nav-item"><a class="nav-link" href="#tab_<?php echo $idTab;?>_panel" id="tab_<?php echo $idTab;?>" data-bs-toggle="tab" role="tab" aria-controls="tab_<?php echo $idTab;?>_panel" aria-selected="true"><?php echo $formTab['name'];?></a></li>
                    <?php }
                } ?>
            </ul>
            <!-- hiển thị các field -->
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
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php }
}
if (!empty($FormLeftBottomHtml)) {
    foreach ($FormLeftBottomHtml as $id => $form) { ?>
        <div class="box-content" id="<?= $id;?>">
            <div class="row m-1">
                <?php if(Metabox::has($id)) { Metabox::render($id); } ?>
                <?php echo $form['html'];?>
            </div>
        </div>
    <?php }
}
if (!empty($FormRightHtml)) {
    foreach ($FormRightHtml as $id => $form) { ?>
        <div class="box-content" id="<?= $id;?>">
            <div class="row m-1">
                <?php if(Metabox::has($id)) { Metabox::render($id); } ?>
                <?php echo $form['html'];?>
            </div>
        </div>
    <?php }
}