<?php
$tabs = shipping_gateways();
reset($tabs);
$section = Request::get('section');
?>
<input type="hidden" name="shipping_key" class="form-control" value="<?php echo $section;?>">
<?php foreach ($tabs as $key => $tab):?>
<div class="shipping-section">
    <div class="shipping-heading">
        <div class="shipping-icon">
            <?php Admin::img($tab['icon']);?>
        </div>
        <div class="shipping-name">
            <h3><?php echo $tab['label'];?></h3>
            <p><?php echo $tab['description'];?></p>
        </div>
    </div>
    <div class="shipping-content">
        <div class="shipping-button">
            <a href="<?php echo Url::admin((version_compare(Cms::version(), '6.1.0', '<') ? Sicommerce_Cart::url('setting').'&tab=shipping&' : 'system/shipping?' ).'section='.$key);?>" class="btn btn-white js_shipping_btn__config" type="button">Cấu hình</a>
        </div>
        <?php if($key == $section) { ?>
        <div class="shipping-form" data-key="<?php echo $key;?>">
            <?php echo Admin::loading();?>
            <?php if(!empty($tab['callback']) && function_exists($tab['callback'])) call_user_func($tab['callback'], $key, $tab); ?>
            <?php if(class_exists($tab['class']) && method_exists($tab['class'], 'form')) call_user_func($tab['class'].'::form', $key, $tab); ?>
            <div class="clearfix"></div>
            <div class="shipping-button footer">
                <button class="btn btn-blue js_shipping_btn__save" type="button"><?php echo Admin::icon('save');?> Lưu cấu hình</button>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php endforeach ?>
<style>
    .shipping-section {
        overflow: hidden;
        background-color: #fff;
        margin-bottom: 10px;
    }
    .shipping-section .shipping-heading {
        overflow: hidden;
        border-bottom: 1px solid var(--content-bg);
    }
    .shipping-section .shipping-heading .shipping-icon { float: left; width: 100px; padding:10px; }
    .shipping-section .shipping-heading .shipping-icon img { width: 70px; }
    .shipping-section .shipping-heading .shipping-name { float: left; width:calc(100% - 100px); padding: 10px; }
    .shipping-section .shipping-heading .shipping-name h3 {
        font-size: 18px;
    }
    .shipping-content .shipping-button {
        padding:10px; text-align: right; border-bottom: 1px solid var(--content-bg);
    }
    .shipping-content .shipping-button.footer {
        border-top: 1px solid var(--content-bg);
        border-bottom: 0px solid var(--content-bg);
        margin-top: 20px;
    }
    .shipping-content .shipping-button .btn-white { border: 1px solid #ccc; }
    .shipping-content .shipping-form {
        padding:10px; overflow: hidden; position: relative;
    }
    .radio label, .checkbox label {
        padding-left:0;
    }
</style>
<script>
    $(function () {
        $('.js_shipping_btn__save').click(function() {
            let box = $(this).closest('.shipping-form');
            let data = $(':input', box).serializeJSON();
            let loading = box.find('.loading');
            loading.show();
            data.action = 'Admin_Cart_Ajax_Setting::saveShipping';
            data.shipping_key = box.attr('data-key');
            $.post(ajax, data, function() {}, 'json').done(function(response) {
                show_message(response.message, response.status);
                loading.hide();
            });
            return false;
        });
    })
</script>