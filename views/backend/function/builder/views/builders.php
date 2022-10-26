<!doctype html>
<html lang="en">
    <head>
        <base href="<?php echo Url::base();?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="shortcut icon" href="<?php echo Admin::imgLink('logo.png');?>">
        <title>Admin - sikido</title>
        <?php Admin::asset()->location('header')->styles();?>
        <?php Admin::asset()->location('header')->scripts();?>
        <script>
            $( document ).ready(function() {
                domainReview = '<?= Url::base();?>?builder=review';
                domain      = '<?= Url::base();?>';
                base        = '<?= Url::admin();?>';
                path        = '<?= Url::adminModule();?>';
                ajax        = '<?= Url::admin('ajax');?>';
                cateType   = '<?= $ci->cateType;?>';
                postType   = '<?= $ci->postType;?>';
                language    = '<?= Language::default();?>';
                urlType = '';
                if(cateType.length > 0  && postType.length > 0)     urlType = '?cate_type='+cateType+'&post_type='+postType;
                if(cateType.length > 0  && postType.length === 0)   urlType = '?cate_type='+cateType;
                if(cateType.length === 0 && postType.length > 0) 	urlType = '?post_type='+postType;
            });
        </script>
        <style>
            :root {
                --menu-bg:#043169;
                --menu-active-bg:#194895;
                --font-main:-apple-system, BlinkMacSystemFont, "San Francisco", "Segoe UI", sans-serif;
                --body-bg:#e6e8ea;
                --content-bg:#f5f5fb;
                --theme-color:#214171;
                --header-title-bg:#214171;
                --header-title-color:#fff;
                --header-height:50px;
                --blue:#416DEA;
                --red:#f82222;
                --green:#08e783;
            }
        </style>
    </head>
    <body style="">
        <div id="skilldo-builder">
            <?php include_once 'builder-top.php';?>
            <?php include_once 'builder-left.php';?>
            <?php include_once 'builder-iframe.php';?>
            <?php include_once 'builder-right.php';?>
        </div>
        <?php Admin::asset()->location('footer')->styles();?>
        <?php Admin::asset()->location('footer')->scripts();?>
        <?php tinymceEditor();?>
        <div class="modal fade" id="js_gallery_input_modal">
            <div class="modal-dialog">
                <div id="js_gallery_input_form">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                            <?php echo Admin::loading();?>
                            <?php
                            $FormBuilder = new FormBuilder();
                            $FormBuilder->add('', 'file', ['id' => 'gallery_input_value', 'label' => 'File dữ liệu']);
                            $FormBuilder->add('', 'text', ['id' => 'gallery_input_title', 'label' => 'Tiêu đề (alt)']);
                            $FormBuilder->html(false);
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-icon btn btn-white" data-bs-dismiss="modal"> <i class="fal fa-times"></i> Đóng</button>
                            <button id="js_gallery_input_btn__save" data-action="save" class="btn-icon btn-green" type="button"><?php echo Admin::icon('save');?> Lưu</button>
                            <button id="js_gallery_input_btn__save_close" data-action="save-close" class="btn-icon btn-green" type="button"><?php echo Admin::icon('save');?> Lưu & đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'modal-heading.php';?>
        <?php include 'modal-font.php';?>
        <?php include 'modal-element.php';?>
        <script defer>
            $(function () {
                Builder.Gui.init();
                Builder.Widget.init();
                Builder.Element.init();
                Builder.main.init();
                $(document).ready(function(){
                    $('[data-toggle="tooltip"]').tooltip();
                });
            })
        </script>
    </body>
</html>
