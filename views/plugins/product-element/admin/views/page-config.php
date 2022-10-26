<?php
if(have_posts($tabs)) {
    reset($tabs);
    $section = (Request::get('section')) ? Request::get('section') : key($tabs);
    ?>
    <form id="js_product_element_form" method="post">
        <div class="action-bar">
            <div class="ui-layout">
                <div class="pull-right">
                    <button type="submit" class="btn-icon btn-green"><?php echo Admin::icon('save');?> Lưu</button>
                </div>
            </div>
        </div>
        <div class="ui-layout">
            <?php echo Admin::loading();?>
            <input type="hidden" name="module" id="module" class="form-control" value="<?php echo $section;?>" required>
            <div class="col-md-12">
                <div class="ui-title-bar__group" style="padding-bottom:5px;">
                    <h1 class="ui-title-bar__title">Product Element - <?php echo $tabs[$section]['label'];?></h1>
                    <div class="ui-title-bar__action">
                        <?php foreach ($tabs as $key => $tab): ?>
                            <a href="<?php echo URL_ADMIN;?>/plugins?page=<?php echo PR_EL_NAME;?>&section=<?= $key ?>" class="<?php echo ($key == $section)?'active':'';?> btn btn-default">
                                <?php echo (isset($tab['icon'])) ? $tab['icon'] : '<i class="fal fa-layer-plus"></i>';?>
                                <?php echo $tab['label'];?>
                            </a>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div role="tabpanel">
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding-top: 10px;">
                        <?php
                        if(isset($tabs[$section]['callback']) && function_exists($tabs[$section]['callback'])) {
                            call_user_func($tabs[$section]['callback'], $tabs[$section], $section);
                        }
                        if(isset($tabs[$section]['class'])) {
                            $tabs[$section]['class']::pageConfig();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $(function() {
            $('#js_product_element_form').submit(function() {
                let loading = $('.loading');
                let data = $(this).serializeJSON();
                data.action     =  'admin_ajax_product_element_save';
                loading.show();
                $.post(base+'/ajax', data, function() {}, 'json').done(function( data ) {
                    loading.hide();
                    show_message(data.message, data.status);
                    if (data.status == 'success') window.location.reload();
                });
                return false;
            });
        });
    </script>
<?php } ?>