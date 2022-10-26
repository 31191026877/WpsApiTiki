<?php
$font_family = Template::fonts();
if(have_posts($font_family)) {
    foreach ($font_family as $key => &$item) {
        $item['id'] = $key;
        $item['type_default']   = ($item['type'] == 'default') ? 'selected' : '';
        $item['type_theme']     = ($item['type'] == 'theme') ? 'selected' : '';
        $item['type_google']    = ($item['type'] == 'google') ? 'selected' : '';
        if(empty($item['load'])) $item['load'] = '';
    }
}
else $font_family = [];
?>
<div id="js_system_fonts_family__data" data-fonts="<?php echo htmlentities(json_encode($font_family));?>"></div>

<div class="box">
    <div class="box-content" style="padding:10px;">
        <div class="form-group">
            <div class="col-sm-12">
                <div id="system_fonts_family">
                    <div class="box-content" style="padding:10px 15px;">
                        <div class="text-right" style="margin-bottom: 20px;">
                            <button type="button" class="btn btn-icon btn-green" id="js_fonts_family_btn__add" style="margin-left: 0;"><?php echo Admin::icon('add');?> Thêm Fonts</button>
                            <a class="btn btn-blue" data-bs-toggle="modal" href="#modal-google-fonts"><img src="https://cdn.worldvectorlogo.com/logos/google-fonts-2021-2.svg" style="width: 20px;"> Thêm Google Font</a>
                        </div>
                        <div id="system_fonts_family__box">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="">Font Key</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="">Font Type</label>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="">Font Label</label>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="">Font Load</label>
                                </div>
                                <div class="form-group col-md-2 text-center">
                                    <label for="">#</label>
                                </div>
                            </div>
                            <div id="system_fonts_family__content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="js_fonts_family_item_template" type="text/x-custom-template">
    <div class="row js_fonts_family__item">
        <div class="form-group col-md-3">
            <input type="text" name="fonts_family[${id}][key]" class="form-control" value="${key}" required>
        </div>
        <div class="form-group col-md-2">
            <select name="fonts_family[${id}][type]" class="form-control" required>
                <option value="default" ${type_default}>Mặc định</option>
                <option value="theme" ${type_theme}>Template</option>
                <option value="google" ${type_google}>Google Font</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <input type="text" name="fonts_family[${id}][label]" class="form-control" value="${label}" required>
        </div>
        <div class="form-group col-md-2">
            <input type="text" name="fonts_family[${id}][load]" class="form-control" value="${load}">
        </div>
        <div class="form-group col-md-2 text-center">
            <button type="button" class="font-delete btn btn-red">Xóa</button>
        </div>
    </div>
</script>

<script id="js_fonts_family_item_add_template" type="text/x-custom-template">
    <div class="row js_fonts_family__item">
        <div class="form-group col-md-3">
            <input type="text" name="fonts_family[${id}][key]" class="form-control" value="" required>
        </div>
        <div class="form-group col-md-2">
            <select name="fonts_family[${id}][type]" class="form-control" required>
                <option value="default">Mặc định</option>
                <option value="theme">Template</option>
                <option value="google">Google Font</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <input type="text" name="fonts_family[${id}][label]" class="form-control" value="" required>
        </div>
        <div class="form-group col-md-2">
            <input type="text" name="fonts_family[${id}][load]" class="form-control" value="">
        </div>
        <div class="form-group col-md-2 text-center">
            <button type="button" class="font-delete btn btn-red">Xóa</button>
        </div>
    </div>
</script>

<script>
    $(function () {
        let fonts_family_box = $('#system_fonts_family__content');

        let fonts_family_data = JSON.parse($('#js_system_fonts_family__data').attr('data-fonts'));

        let str = '';

        for (const [key, items_tmp] of Object.entries(fonts_family_data)) {
            let items = [items_tmp];
            items.map(function(item) {
                str += $('#js_fonts_family_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
            });
        }

        fonts_family_box.html(str);

        $(document).on( 'click', '#js_fonts_family_btn__add', function() {

            let size = fonts_family_box.find('.js_fonts_family__item').length + 1;

            let str = '';

            let item_add = [{ 'id' : size }];

            for (const [key, items_tmp] of Object.entries(item_add)) {
                let items = [items_tmp];
                items.map(function(item) {
                    console.log(item);
                    str += $('#js_fonts_family_item_add_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                });
            }

            $(str).appendTo('#system_fonts_family__content');

            return false;
        });

        $(document).on( 'click', '.js_google_font_item', function() {

            let size = fonts_family_box.find('.js_fonts_family__item').length + 1;

            let str = '';

            let item_add = [{ 'id' : size, 'label' : $(this).data('label'), 'key': $(this).data('family'), 'load': $(this).data('load'), 'type_google' : 'selected=selected' }];

            for (const [key, items_tmp] of Object.entries(item_add)) {
                let items = [items_tmp];
                items.map(function(item) {
                    str += $('#js_fonts_family_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                });
            }

            $(str).appendTo('#system_fonts_family__content');

            $('#modal-google-fonts').modal('hide');

            return false;
        });

        $(document).on( 'keyup', '#js_google_font_search', function() {

            let keyword = $(this).val();

            let googleFonts = $('#js_google_font_list');

            googleFonts.find('.js_google_font_item').hide();

            googleFonts.find('.js_google_font_item').each(function(){
                if($(this).data('label').toLowerCase().indexOf(""+keyword+"") !== -1 ){
                    $(this).closest('.js_google_font_item').show();
                }
            });
            return false;
        });

        fonts_family_box.on( 'click', 'button.font-delete', function(){
            $(this).closest('.js_fonts_family__item').remove();
            return false;
        });
    });
</script>

<div class="modal fade" id="modal-google-fonts">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Google Fonts</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" placeholder="search fonts.." id="js_google_font_search">
                <ol class="google-font-list scrollbar" id="js_google_font_list">
                    <?php
                    $fonts = json_decode(file_get_contents('http://cdn.sikido.vn/fonts'));
                    $orderFont = 1;
                    foreach ($fonts->google as $key => $item) { ?>
                        <li class="google-font-item js_google_font_item" data-family="<?php echo $item->family;?>" data-load="<?php echo $item->url;?>" data-label="<?php echo $key;?>">
                            <img src="<?php echo $item->img;?>" alt="">
                        </li>
                    <?php $orderFont++; } ?>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
    .google-font-list {
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        overflow: auto;
        max-height: 800px;
    }
    .google-font-item {
        width: calc(33% - 10px);
        margin-right: 10px;
        margin-bottom: 10px;
        border-radius: 10px;
        cursor: pointer;
    }
    .google-font-item img {
        width: 100%;border-radius: 10px;
    }
    .google-font-item:hover img {
        box-shadow: 0 1px 2px 0 rgba(60,64,6, 0.3), 0 2px 6px 2px rgba(60,64,67,0.15);
    }
</style>


