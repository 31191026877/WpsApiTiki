<?php do_action('admin_product_setting_object'); ?>
<style>
	.product { margin-bottom: 10px; }
	.product .img img { width: 100%; }
	.product .title { text-transform: uppercase; font-size: 10px; margin-bottom: 5px; }
	.product.ih-item.square { width: 100%; height: auto; }
    .box-ef-option-container {
        display: flex; gap:10px; flex-wrap: wrap;
    }
    .box-ef-option-container .ef-option-item {
        width: calc(100%/3 - 10px);
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-align: center;
        align-items: center;
        -ms-flex-pack: center;
        justify-content: center;
        height: 74px;
        box-sizing: border-box;
        color: #bec9d6;
        transition: color .2s ease;
        border: 3px solid #fff;
        border-radius: 3px;
        cursor: pointer;
        text-align: center;
        margin-top: 10px;
    }
    .box-ef-option-container .ef-option-item input {
        display: none;
    }
    .ef-option-item-content {
        position: relative;
        display: block;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        width: 100%;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        height: 100%;
        -ms-flex-pack: center;
        justify-content: center;
        background: #f1f5f9;
    }
    .ef-option-item-icon {
        width: 70%;
        height: 60%;
        line-height: 40px;
        font-size: 18px;
    }
    .ef-option-item-effect {
        width: 70%;
        height: 60%;
        background: #fff;
        transition: box-shadow .3s;
        border-radius: 3px;
        opacity: 0.5;
    }
    .ef-option-item.hover {
        box-shadow: 0 0 10px 1px #2b87da;
    }
    .ef-option-item.hover .ef-option-item-effect {
        opacity: 1;
    }
</style>

<script type="text/javascript">
    $(function() {
        let form = $('#mainform');

        let productObjectHandler = function() {
            this.onLoad();
        };

        productObjectHandler.prototype.onLoad = function (e) {
            let data = $( ':input', form).serializeJSON();
        };

        new productObjectHandler();

        $('.ef-option-item').click(function () {
            $(this).closest('.box-ef-option-container').find('.ef-option-item').removeClass('hover');
            $(this).addClass('hover');
        });
    })
</script>