<?php
if(Template::isPage('products_categories_index')) {
    function product_category_admin_quick_add() {
        ?>
        <div class="modal fade" id="product_categories_quick_add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Thêm nhanh danh mục</h4>
                    </div>
                    <div class="modal-body">
                        <?php echo Admin::loading();?>
                        <?php echo FormBuilder::render(['name' => 'product_categories_quick_add_parent', 'type' => 'select', 'label' => 'Danh mục cha', 'options' => ProductCategory::gets(Qr::set()->categoryType('options'))]);?>
                        <?php echo FormBuilder::render(['name' => 'product_categories_quick_add_data', 'type' => 'textarea', 'rows' => 10, 'placeholder' => 'Nhập danh sách danh mục sản phẩm muốn thêm mới', 'note' => 'Mỗi danh mục nhập trên một dòng, Mỗi lần nhập tối đa 50 tên danh mục']);?>
                        <div class="form-group group text-right">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-blue" id="js_product_categories_quick_save"><?php echo Admin::icon('save');?> Lưu</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <script>
            $(function () {
                $('#js_product_categories_quick_save').click(function () {
                    $('#product_categories_quick_add .loading').show();
                    let data = {
                        'action': 'Product_Category_Admin_Ajax::quickSave',
                        'data' : $('#product_categories_quick_add_data').val(),
                        'parent' : $('#product_categories_quick_add_parent').val()
                    };

                    $.post(ajax, data, function () { }, 'json').done(function (data) {
                        show_message(data.message, data.status);
                        if (data.status === 'success') {
                            location.reload();
                        }
                        else {
                            $('#product_categories_quick_add .loading').hide();
                        }
                    });
                    return false;
                })
            })
        </script>
        <?php
    }
    add_action('admin_footer', 'product_category_admin_quick_add');
}