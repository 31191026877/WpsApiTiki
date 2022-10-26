<div class="modal fade" id="js_google_fonts_modal">
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
                        <li class="google-font-item js_google_font_item" data-family="<?php echo $item->family;?>" data-load="<?php echo $item->url;?>" data-label="<?php echo $key;?>"><img src="<?php echo $item->img;?>" alt=""></li>
                        <?php $orderFont++; } ?>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>