<?php Admin::partial('include/action_bar');?>
<?php if(have_posts($galleries)) { ?>
<?php $galleryActive = ((int)Request::get('id') != 0) ? (int) Request::get('id') : (isset($galleries[0]) ? $galleries[0]->id : 0);?>
<div class="gallery">
    <div class="row m-1">
        <div class="col-12 col-md-3" style="padding-left: 0">
            <div class="gallery-list">
                <a href="javascript:;" class="btn-icon btn-blue" data-fancybox="" data-src="#hidden-content"><i class="fal fa-plus"></i>Thêm Thể Loại</a>
                <ul class="root-list" id="js_gallery__list">
                    <?php foreach ($galleries as $key => $group): ?>
                        <li class="js-group-gallery">
                            <a href="<?php echo Url::admin('galleries?id='.$group->id);?>" data-id="<?php echo $group->id;?>" class="group-gallery <?php echo ($group->id == $galleryActive) ? 'active' : '';?>">
                                <label><i class="fas fa-folder"></i> <span class="gl-number"><?php echo Gallery::countItem(['where' => ['group_id' => $group->id]]);?></span></label>
                                <span><?= $group->name;?></span>
                            </a>
                            <?php if(Auth::hasCap('delete_gallery')) { ?>
                                <button class="btn-icon btn-red js_gallery_btn__delete" data-id="<?php echo $group->id;?>" type="button"><i class="fal fa-trash"></i></button>
                            <?php } ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
        <div class="col-12 col-md-6 gallery-item">
            <div class="gallery-item__action">
                <button class="gallery-check-all" type="button"><i class="fas fa-check-circle"></i></button>
                <button class="js_gallery_item__delete disabled-item" type="button"><i class="fa fa-trash"></i></button>
            </div>
            <?php Admin::loading();?>
            <ul id="js_gallery_item__sort" class="row m-1"></ul>
        </div>
        <div class="col-12 col-md-3 gallery-form" style="padding:0;">
            <div class="gallery-item__action" style="margin-bottom:0;"></div>
            <form id="js_gallery_item__form" data-edit="0">
                <div class="img-thumbnail js_gallery_review" style="display: block;">
                    <div class="camera-container iframe-btn" data-fancybox="iframe" data-id="value" href="<?php echo Url::base();?>scripts/rpsfmng/filemanager/dialog.php?type=2&amp;subfolder=&amp;editor=mce_0&amp;field_id=value&amp;callback=gallery_responsive_filemanager_callback">
                        <i class="fal fa-image"></i> Chọn Hình Ảnh
                    </div>
                    <!-- loading -->
                    <?php Admin::loading();?>
                </div>
                <div class="m-3">
                    <?php Admin::partial('include/ajax-page/gallery_form', $this->data);?>
                </div>
                <div class="clearfix"></div>
                <hr />
                <div class="box-content text-right">
                    <button type="submit" name="save" class="btn-icon btn-green"><?php echo Admin::icon('save');?>Lưu</button>
                    <button type="reset" class="btn-icon btn-default">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .page-content .ui-layout { overflow: hidden; max-width: 2200px;}
    .result-img { display:none;}
</style>
<?php } else { ?>
<div class="col-md-5 box-empty">
    <h2>Bạn chưa tạo bất kỳ gallery nào</h2>
    <h4>Bạn có thể tạo gallery để có thể tải lên hình ảnh, video hay các tài liệu khác!</h4>
    <a href="javascript:;" class="btn-icon btn-blue add-fast" data-fancybox="" data-src="#hidden-content"><i class="fa fa-plus-square"></i>Thêm Gallery</a>
</div>
<div class="col-md-7"><img src="//cdn.shopify.com/s/assets/admin/empty-states-fresh/emptystate-files-45034fe342d1a46109a82c6b91b6e46b99efd5580585721a2f57a784860f49ff.svg" alt="Emptystate files"></div>
<div id="js_gallery__list"></div>
<style>
    .box-empty { margin-top: 50px; }
    .box-empty h2 { font-size: 30px; font-weight: bold; }
    .box-empty h4 { font-size: 18px; line-height: 2.8rem;  font-weight: 400; color: #637381; }
</style>
<?php }?>

<!-- popup thêm menu -->
<div style="display: none; padding:0px; min-width: 350px;" id="hidden-content">
    <div class="header"><h4>THÊM GALLERY</h4></div>
    <form id="js_gallery_form__add" style="padding:20px 10px" autocomplete="off">
        <?php echo FormBuilder::render(['field' => 'name', 'label' => 'Tên Gallery', 'value'=>'','type' => 'text']);?>
        <div class="clearfix"></div>
        <div class="text-right">
            <button class="btn-icon btn-blue"><?php echo Admin::icon('add');?> Lưu</button>
        </div>
    </form>
</div>

<style>
    .page-content .page-body { padding-top: 0;}
    .page-content .action-bar { display: none; }
</style>