<?php
$tabs 		= payment_gateways();
?>
<?php foreach ($tabs as $key => $tab):?>
<div class="payment-section">
    <div class="payment-heading">
        <div class="payment-icon">
            <?php $icon = (!empty($tab['icon'])) ? $tab['icon'] : 'https://icons-for-free.com/iconfiles/png/512/money+payment+icon-1320165997481413640.png';?>
            <?php Admin::img($icon);?>
        </div>
        <div class="payment-name">
            <h3><?php echo $tab['label'];?></h3>
            <p><?php echo $tab['description'];?></p>
        </div>
    </div>
    <div class="payment-content">
        <div class="payment-button">
            <button class="btn btn-white js_payment_btn__config">Cấu hình</button>
        </div>
        <div class="payment-form" data-key="<?php echo $key;?>">
            <?php echo Admin::loading();?>
            <?php if(!empty($tab['callback']) && function_exists($tab['callback'])) call_user_func($tab['callback'], $key, $tab); ?>
            <?php if(class_exists($tab['class']) && method_exists($tab['class'], 'form')) call_user_func($tab['class'].'::form', $key, $tab); ?>
            <div class="payment-button">
                <button class="btn btn-blue js_payment_btn__save" type="button"><?php echo Admin::icon('save');?> Lưu cấu hình</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach ?>
<style>
    .page-content .action-bar button[type="submit"] { display: none;}
    .payment-section {
        overflow: hidden;
        background-color: #fff;
        margin-bottom: 10px;
    }
    .payment-section .payment-heading {
        overflow: hidden;
        border-bottom: 1px solid var(--content-bg);
    }
    .payment-section .payment-heading .payment-icon { float: left; width: 100px; padding:10px; }
    .payment-section .payment-heading .payment-icon img { width: 70px; }
    .payment-section .payment-heading .payment-name { float: left; width:calc(100% - 100px); padding: 10px; }
    .payment-section .payment-heading .payment-name h3 {
        font-size: 18px;
    }
    .payment-content .payment-button {
        padding:10px; text-align: right; border-bottom: 1px solid var(--content-bg);
    }
    .payment-content .payment-button .btn-white { border: 1px solid #ccc; }
    .payment-content .payment-form {
        padding:10px; overflow: hidden; display: none; position: relative;
    }
</style>
<script>
    $(function () {
        $('.js_payment_btn__config').click(function () {
            $(this).closest('.payment-section').find('.payment-form').toggle();
            return false;
        });
        $('.js_payment_btn__save').click(function() {

            let box = $(this).closest('.payment-form');

            let data 		    = $(':input', box).serializeJSON();

            let loading = box.find('.loading');

            loading.show();

            data.action         =  'Admin_Cart_Ajax_Setting::savePayment';

            data.payment_key    = box.attr('data-key');

            $.post(ajax, data, function() {}, 'json').done(function( data ) {
                show_message(data.message, data.status);
                loading.hide();
            });

            return false;
        });
    })
</script>


