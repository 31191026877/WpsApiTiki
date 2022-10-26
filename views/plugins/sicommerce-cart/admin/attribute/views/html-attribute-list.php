<div class="swatches-container">
    <div class="header clearfix">
        <div class="swatch-title"> Title </div>
        <div class="swatch-color"> Color </div>
        <div class="swatch-image"> Image </div>
        <div class="remove-item">Remove</div>
    </div>
    <ul class="swatches-list ui-sortable" id="js_attribute_main_list"></ul>
    <button type="button" class="btn btn-blue js_attribute_btn_add">Thêm mới thuộc tính</button>
    <p style="padding:10px; margin-bottom: 0;" class="pull-right">Kéo thả các thuộc tính con để sắp xếp vị trí</p>
</div>
<style>
    .page-content .box .box-content.collapse { padding-top:0;}
    .swatches-container .header {
        display: flex;
        flex-direction: row;
        font-weight: 700;
        background-color:var(--blue);
        color: #fff;
        padding:0;
        border-radius: 0;
    }
    .swatches-container .header>* {
        float: left;
        padding: 10px;
        text-align: center;
        line-height: 25px;
    }
    .swatches-container .header:before {
        content: "#";
        display: inline-block;
        width: 50px;
        text-align: center;
        line-height: 40px;
    }
    .swatches-container .header .swatch-slug, .swatches-container .header .swatch-title, .swatches-container .header .swatch-color {
        flex: 1;
    }
    .swatches-container .header .swatch-image, .swatches-container .header .swatch-is-default {
        width: 220px;
    }
    .swatches-container .header .remove-item {
        width: 100px;
    }

    .swatches-container .swatches-list li {
        padding-left: 50px;
        display: flex;
        flex-direction: row;
        align-items: center;
        position: relative;
        counter-increment: swatches-list;
    }
    .swatches-container .swatches-list li:nth-child(odd) {
        background-color: #f0f0f0;
    }
    .swatches-container .swatches-list li:before {
        content: counter(swatches-list);
        width: 50px;
        position: absolute;
        height: 100%;
        top: 0;
        left: 0;
        cursor: move;
        background-color: #bbb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .swatches-container .swatches-list li>* {
        float: left;
        padding: 10px;
        text-align: center;
    }
    .swatches-container .swatches-list li .swatch-slug, .swatches-container .swatches-list li .swatch-title, .swatches-container .swatches-list li .swatch-color {
        flex: 1;
    }
    .swatches-container .swatches-list li .swatch-image, .swatches-container .swatches-list li .swatch-is-default {
        width: 220px;
    }
    .swatches-container .swatches-list li .remove-item {
        width: 100px;
    }

    .swatches-container .swatches-list li .swatch-image .fileupload-image {
        width:120px!important; display: inline-block;
    }
    .swatches-container .swatches-list li .swatch-image .fileupload-image img {
        height:28px!important; margin:4px 0 2px 0!important;
    }
    .swatches-container .swatches-list li .swatch-image .fileupload-image p {
        margin-bottom: 3px!important;
    }
    .swatches-container .swatches-list li .form-group {
        margin-bottom: 0px!important;
    }
</style>
<?php $input = new FormBuilder();?>
<script id="product_attribute_template" type="text/x-custom-template">
    <li data-id="${id}" class="attribute-item clearfix">
        <div class="swatch-title">
            <input name="attribute[${id}][title]" type="text" class="form-control" value="${title}">
        </div>
        <div class="swatch-color">
            <?php
                $input->add('attribute[${id}][color]', 'color', ['value' => '${color}'])->html(false);
            ?>
        </div>
        <div class="swatch-image">
            <?php
                $input->add('attribute[${id}][image]', 'image', ['display' => 'inline','id' => "image_\${id}"], '${image}')->html(false);
            ?>
        </div>
        <div class="remove-item text-center">
            <button type="button" class="btn btn-red js_attribute_btn_delete"><?php echo Admin::icon('delete');?></button>
        </div>
    </li>
</script>

<script>
    $(function () {
        <?php if(!empty($attribute_items_tmp)) {?>
        let attribute_items_tmp = <?php echo json_encode($attribute_items_tmp); ?>;
        for (const [key, items_tmp] of Object.entries(attribute_items_tmp)) {
            let items = [items_tmp];
            $('.swatches-list').append(items.map(function(item) {
                return $('#product_attribute_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
            }));
        }
        Sortable.create(js_attribute_main_list, {
            sort: true,
            group: {
                name: 'advanced',
                pull: 'clone',
                put: false
            },
            animation: 150,
        });
        <?php } ?>
        $('.js_attribute_btn_add').click(function () {

            let items = [{ id: uniqid(), title: '', color: '', image: '' }];

            let product_attribute_template = $('#product_attribute_template').html().split(/\$\{(.+?)\}/g);

            $('.swatches-list').append(items.map(function(item) {
                return product_attribute_template.map(render(item)).join('');
            }));

            $('.iframe-btn').fancybox({
                'type':'iframe',
                'width': 600, //or whatever you want
                'height': 300
            });

            $('.item-color input').spectrum({
                type: "color",
                showInput: true,
                showInitial: true,
                chooseText: "Chọn", cancelText: "Hủy"
            });
            $('.item-color-hexa input').spectrum({
                type: "component",
                showInput: true,
                showInitial: true,
                chooseText: "Chọn"
            });

            return false;
        });
        $(document).on('click', '.js_attribute_btn_delete', function () {
            $(this).closest('li.attribute-item').remove();
            return false;
        });
        function uniqid(a = "", b = false) {
            const c = Date.now()/1000;
            let d = c.toString(16).split(".").join("");
            while(d.length < 14) d += "0";
            let e = "";
            if(b){
                e = ".";
                e += Math.round(Math.random()*100000000);
            }
            return a + d + e;
        }
        function render(props) {
            return function(tok, i) {
                return (i % 2) ? props[tok] : tok;
            };
        }
    });

</script>