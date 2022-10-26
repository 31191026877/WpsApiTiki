<?php
$styles = Quick_view::style();
?>
<div class="quick-view-box">
    <div class="row">
        <div class="col-md-6">
            <div class="quick-view__select_box">
                <label for="">Sử dụng mẫu có sẳn</label>
                <hr style="margin: 5px 0;">
                <div class="clearfix"> </div>
                <?php foreach ($styles as $item_key => $item): ?>
                    <label class="quick-view__select <?php echo (!empty($item['active'])) ? 'active' : '';?>" data-tab="#tab-<?php echo $item_key;?>">
                        <?php include Path::plugin(QV_NAME).'/styles/item-'.$item_key.'.php';?>
                    </label>
                <?php endforeach ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="quick-view__select_box">
                <label for="">Cấu hình</label>
                <hr style="margin: 5px 0;">
                <div class="clearfix"> </div>
                <?php foreach ($styles as $item_key => $item): ?>
                    <div class="tabs-option <?php echo (!empty($item['active'])) ? 'active' : '';?>" id="tab-<?php echo $item_key;?>">
                        <?php include Path::plugin(QV_NAME).'/styles/tab-'.$item_key.'.php';?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
<?php include Path::plugin(QV_NAME).'/styles/style.css.php'; ?>
<style>
    .quick-view__select_box { background-color: #fff; padding:10px; overflow:hidden;}
    .quick-view__select {
        position: relative; overflow: hidden; height: auto; width:32%;
        border:2px solid #E3E7FB; border-radius: 5px;
    }
    .quick-view__select img {
        width: 100%;
    }
    .quick-view__select.active {
        border:2px solid #263A53;
    }
    .tabs-option { display: none; }
    .tabs-option.active { display: block; }
</style>
<script type="text/javascript">
    $(function() {
        let id = $('.quick-view__select.active').attr('data-tab');
        $('.quick-view__select').click( function () {
            id = $(this).attr('data-tab');
            $('.quick-view__select.active').removeClass('active');
            $(this).addClass('active');
            $('.tabs-option.active').removeClass('active');
            $(id).addClass('active');
        });
        $('#mainform').submit( function () {
            let data = $( ':input' , $('.tabs-option.active')).serializeJSON();
            data.action = 'admin_quick_view_ajax_object_save';
            data.object_key = $('#object_key').val();
            $.post(base+'/ajax', data, function(data) {}, 'json').done(function(response) {
                show_message(response.message, response.status);
            });
            return false;
        });
    });
</script>
