<div class="box">
    <div class="header"> <h2>ITEM</h2> </div>
    <div class="box-content">
        <div class="row m-1">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3">
                        <?php  $input = array('field' => 'product_item[enable]', 'type' => 'switch', 'label' => 'Bật tắt item'); ?>
                        <?php echo _form($input, $product_item['enable']);?>
                    </div>
                    <div class="col-md-9">
                        <?php foreach (Language::list() as $key => $language) {
                            $item_key = '_'.$key;
                            if($key == Language::default()) $item_key = '';
                            $input = array('field' => 'product_item[title'.$item_key.']', 'type'	=> 'text', 'label' => 'Tiêu đề ('.$language['label'].')');
                            echo _form($input, (isset($product_item['title'.$item_key])) ? $product_item['title'.$item_key] : '');
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>
            <div class="col-md-12">
                <table class="table table-bordered" id="product_layout_item">
                    <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Tiêu đề</th>
                        <th>Mô tả</th>
                        <th>Url</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody class="accounts ui-sortable">
                    <?php if(isset($product_item['item']) && have_posts($product_item['item'])) {?>
                        <?php foreach ($product_item['item'] as $key => $item): ?>
                            <tr class="account">
                                <td style="width: 200px;">
                                    <div class="group">
                                        <div class="input-group image-group">
                                            <input type="images" name="item[<?php echo $key;?>][image]" value="<?php echo $item['image'];?>" id="item_<?php echo $key;?>_image" class="form-control ">
                                            <span class="input-group-addon iframe-btn" data-fancybox="" data-type="iframe" data-src="scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=item_<?php echo $key;?>_image&amp;callback=responsive_filemanager_callback" data-id="image" href="scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=item_<?php echo $key;?>_image&amp;callback=responsive_filemanager_callback"><i class="fas fa-upload"></i></span>
                                        </div>
                                        <p style="color:#999;margin:5px 0 5px 0;"></p>
                                        <div class="pull-left text-center" style="width:150px;"><img class="result-img" src="" style="max-width:150px;margin:10px 0;"></div>
                                        <div class="pull-left result-img-info" style="width: calc(100% - 160px);margin:10px 0 0 10px;"></div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    foreach (Language::list() as $languageKey => $language) {
                                        $item_key = '_'.$languageKey;
                                        if($languageKey == Language::default()) $item_key = '';
                                        if(Language::hasMulti()) echo '<label>'.$language['label'].'</label>';
                                        echo '<input type="text" class="form-control" name="item['.$key.'][title'.$item_key.']" value="'.((isset($item['title'.$item_key])) ? $item['title'.$item_key] : '').'">';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach (Language::list() as $languageKey => $language) {
                                        $item_key = '_'.$languageKey;
                                        if($languageKey == Language::default()) $item_key = '';
                                        if(Language::hasMulti()) echo '<label>'.$language['label'].'</label>';
                                        echo '<input type="text" class="form-control" name="item['.$key.'][description'.$item_key.']" value="'.((isset($item['description'.$item_key])) ? $item['description'.$item_key] : '').'">';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="item[<?php echo $key;?>][url]" value="<?php echo $item['url'];?>">
                                </td>
                                <td class="sort">
                                    <button class="btn-delete btn-icon btn-red">Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="7"><a href="#" class="add btn-white btn">+ Thêm Item</a></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#product_layout_item').on( 'click', 'a.add', function(){
            let size = $('#product_layout_item').find('tbody .account').length;
            $('<tr class="account">\
                    <td style="width: 200px;">\<div class="group"><div class="input-group image-group"><input type="images" name="item[' + size + '][image]" value="" id="item_' + size + '_image" class="form-control "><span class="input-group-addon iframe-btn" data-fancybox="" data-type="iframe" data-src="scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=item_' + size + '_image&amp;callback=responsive_filemanager_callback" data-id="image" href="scripts/rpsfmng/filemanager/dialog.php?type=1&amp;subfolder=&amp;editor=mce_0&amp;field_id=item_' + size + '_image&amp;callback=responsive_filemanager_callback"><i class="fas fa-upload"></i></span></div><p style="color:#999;margin:5px 0 5px 0;"></p><div class="pull-left text-center" style="width:150px;"><img class="result-img" src="" style="max-width:150px;margin:10px 0;"></div><div class="pull-left result-img-info" style="width: calc(100% - 160px);margin:10px 0 0 10px;"></div></div></td>\
                    <td><input type="text" class="form-control" name="item[' + size + '][title]"></td>\
                    <td><input type="text" class="form-control" name="item[' + size + '][description]"></td>\
                    <td><input type="text" class="form-control" name="item[' + size + '][url]"></td>\
                    <td class="sort">\
                        <button class="btn-delete btn-icon btn-red">Xóa</button>\
                    </td>\
                </tr>').appendTo('#product_layout_item tbody');
            return false;
        });
        $('#product_layout_item').on( 'click', 'button.btn-delete', function(){
            $(this).closest('tr.account').remove();
            return false;
        });
    });
</script>