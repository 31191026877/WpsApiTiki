<div class="col-md-12">
    <div class="box list-sliders">
        <!-- .box-content -->
        <div class="box-content" style="padding: 0;">
            <h4 class="header" style="margin-top: 0;">Slider</h4>
            <div class="box-list-sliders">
                <?php foreach ($sliders as $key => $slider): $sliderType = Slider::list($slider->options); ?>
                    <a href="<?php echo Url::admin('plugins?page=slider&view=detail&id='.$slider->id);?>">
                        <div class="item tls-addnewslider">
                            <span class="tls-firstslideimage mini-transparent" style="background-size: inherit; background-repeat: repeat;;background-image:url(https://vignette.wikia.nocookie.net/animal-jam-clans-1/images/5/57/Transparent_Background.png) "></span>
                            <span class="tls-title-wrapper"><span class="tls-title"><?= $slider->name;?></span></span>
                            <div class="tls-button">
                                <button class="btn btn-red js_slider__delete" data-id="<?php echo $slider->id;?>" style="position: relative;top:5px;"><?php echo Admin::icon('delete');?></button>
                                <?php if($sliderType['options'] == 'true') {?>
                                <button class="btn btn-green js_slider__options" data-id="<?php echo $slider->id;?>" style="position: relative;top:5px;"><i class="fa-thin fa-gear"></i></button>
                                <?php } ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach ?>
                <a href="javascript:;" data-fancybox="" data-src="#hidden-content">
                    <div class="item tls-addnewslider">
                        <span class="tls-new-icon-wrapper"><span class="slider_list_add_buttons add_new_slider_icon"></span></span>
                        <span class="tls-title-wrapper"><span class="tls-title">New Slider</span></span>
                    </div>
                </a>
            </div>
        </div>
        <!-- /.box-content -->
    </div>
</div>

<!-- popup thêm menu -->
<div style="display: none; padding:0px; min-width: 400px;" id="hidden-content">
    <div class="box">
        <h4 class="header" style="margin:0 0 10px 0; border-radius: 0;">THÊM SLIDER</h4>
        <form id="js_slider_form__add" autocomplete="off">
            <div class="m-3">
                <?php echo Admin::loading();?>
                <?php echo FormBuilder::render(['name' => 'name', 'label' => 'Tên Slider', 'value'=>'','type' => 'text']);?>
                <?php
                $options = [];
                foreach (Slider::list() as $key => $slider) {
                    $options[$key] = [ 'label' => $slider['name'], 'img' => $slider['thumb']];
                }
                echo FormBuilder::render(['name' => 'type', 'label' => 'Loại Slider', 'value'=>'', 'type' => 'select-img', 'options' => $options])
                ?>
            </div>
            <div class="slider-type"></div>
            <div class="text-right" style="margin-bottom: 10px">
                <button class="btn-icon btn-blue"><i class="fa fa-plus-square"></i>Thêm</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="sliderOptionsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="" id="js_slider_form__options" autocomplete="off">
                    <div class="row" id="sliderOptionsModal_content"></div>
                    <div class="text-right">
                        <button class="btn-icon btn-blue"><i class="fa fa-plus-square"></i>Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .wrapper .content .page-content { margin-top: 5px; }
    .box-list-sliders { padding:10px; }
    .list-sliders .item {
        position: relative;
        display: inline-block;
        margin-bottom: 10px;
        width: 220px;
        height: 160px;
        margin-right: 10px;
    }
    .tls-addnewslider {
        border: 1px dashed #ddd;
        background: transparent;
        box-sizing: border-box;
        overflow: hidden;
    }
    .tls-title-wrapper {
        vertical-align: middle;
        position: absolute;
        bottom: 0px;
        color: #fff;
        padding: 5px 10px;
        width: 100%;
        line-height: 20px;
        background: #eee;
        box-sizing: border-box;
    }
    .tls-title, .tls-title a {
        color: #555;
        text-decoration: none;
        font-size: 11px;
        line-height: 20px;
        font-weight: 600;
    }
    .tls-addnewslider .tls-new-icon-wrapper {
        position: absolute;
        top: 0px;
        width: 100%;
        height: 100%;
        display: block;
        text-align: center;
        font-size: 35px;
    }
    .slider_list_add_buttons {
        display: block;
        position: absolute;
        left: 0px,top:0px;
        width: 100%;
        height: 100%;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: 40px 40px;
        margin-top: -10px;
    }
    .tls-firstslideimage {
        position: absolute;
        top: 0px: left:0px;
        width: 100%;
        height: 100%;
    }
    .add_new_slider_icon {
        background-image: url('<?php echo plugin_dir_path('slider').'assets/images/new_slider.png';?>');
    }
    .tls-addnewslider:hover, .tls-addnewslider.active {
        border: 1px solid #242424;
    }
    .tls-addnewslider:hover .tls-title-wrapper, .tls-addnewslider.active .tls-title-wrapper {
        background: #252525;
    }
    .tls-addnewslider:hover .tls-title, .tls-addnewslider.active .tls-title {
        color:#fff;
    }

    .select-img .checkbox {
        width: 32%; cursor: pointer;
    }
    .select-img .checkbox img {
        max-width: 100%!important; width: 400px;
    }
</style>