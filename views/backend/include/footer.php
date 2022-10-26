<?php do_action('admin_footer');?>
<?php
include_once 'modal/modal-confirm.php';
include_once 'modal/modal-gallery-input.php';
if(Template::getPage() == 'widgets_index') include_once 'modal/modal-heading.php';
include_once 'modal/modal-hot-key.php';
include_once 'modal/modal-licensed.php';
?>
<?php Admin::asset()->location('footer')->styles();?>
<?php Admin::asset()->location('footer')->scripts();?>

<style>
    .CodeMirror-fullscreen { height: auto !important; z-index: 999!important; } .cm-tag { color: #21a500; } .cm-qualifier { color: #c00cd6; }
    .datepicker > div { display: block; }
    .tox .tox-tbtn--bespoke .tox-tbtn__select-label { width:60px; }
    .fancybox-slide {
        padding:10px!important;
    }
    .glyphicon.glyphicon-ok, .glyphicon.glyphicon-remove {
        position: relative;
        font-family: "Font Awesome 5 Pro";
        font-weight: 300;
    }
    .glyphicon.glyphicon-ok:before {
        content: "\f00c";
    }
    .glyphicon.glyphicon-remove:before {
        content: "\f00d";
    }
    @media (min-width: 768px) {
        .table-responsive { overflow-x: hidden; width: 100%; }
    }
</style>

<script id="popover_advance_search_template" type="text/x-custom-template">
    <div data-id="${id}" data-item="${data}" class="popover_advance__item clearfix">
        <div class="item">
            <div class="item__image"><img src="${image}"></div>
            <div class="item__name">${name}</div>
        </div>
    </div>
</script>
<script id="popover_advance_load_template" type="text/x-custom-template">
    <div data-id="${id}" class="popover_advance__item_result popover_advance__item_result_${id} clearfix">
        <div class="item">
            <input type="checkbox" name="${field}" value="${id}" checked>
            <div class="item__image"><img src="${image}"></div>
            <div class="item__name">${name}</div>
            <div class="item__action"><button class="btn btn-red item__btn_delete" type="button"><i class="fal fa-times"></i></button></div>
        </div>
    </div>
</script>
<script id="popover_advance_search_template_not_image" type="text/x-custom-template">
    <div data-id="${id}" data-item="${data}" class="popover_advance__item clearfix">
        <div class="item">
            <div class="item__name">${name}</div>
        </div>
    </div>
</script>
<script id="popover_advance_load_template_not_image" type="text/x-custom-template">
    <div data-id="${id}" class="popover_advance__item_result popover_advance__item_result_${id} clearfix">
        <div class="item">
            <input type="checkbox" name="${field}" value="${id}" checked>
            <div class="item__name">${name}</div>
            <div class="item__action"><button class="btn btn-red item__btn_delete" type="button"><i class="fal fa-times"></i></button></div>
        </div>
    </div>
</script>

<script>
    $(function () {
        $( document ).on('submit', '#js_licensed_form__save', function(e) {
            $('#js_licensed_modal__info .loading').show();
            let data = $(this).serializeJSON();
            data.action = 'Ajax_Admin_Plugin_Action::saveLicense';
            $.post(ajax, data, function(data) {}, 'json').done(function( response ) {
                show_message(response.message, response.status);
                $('#js_licensed_modal__info .loading').hide();
            });
            return false;
        });
    })
</script>
