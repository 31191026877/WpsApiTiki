<?php
class AdminProductCategoryTable extends skd_object_list_table {

    function get_columns() {
        $this->_column_headers = [
            'cb'        => 'cb',
            'title'     => 'Tiêu Đề',
            'status'    => 'Nổi bật',
            'order'     => 'Thứ Tự',
            'public'    => 'Hiển Thị',
            'action'    => 'Hành Động',
        ];
        $this->_column_headers = apply_filters( "manage_product_category_columns", $this->_column_headers );
        return $this->_column_headers;
    }

    function column_default($column_name, $item, $global) {
        do_action( 'manage_product_category_custom_column', $column_name, $item, $global);
    }

    function column_title($item) {
        ?>
        <div class="" style="display:flex;">
            <div class="img" style="padding-right:10px;">
                <?php echo Template::img($item->image, '', ['style' => 'height:30px;']);?>
            </div>
            <?php do_action('admin_product_category_table_column_title', $item);?>
            <div class="title">
                <h3><?= str_repeat('|-----', (($item->level > 0)?($item->level - 1):0)).$item->name;?></h3>
                <div class="action-hide">
                    <span>ID : <?= $item->id;?></span> |
                    <a href="<?= Url::permalink($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
                </div>
            </div>
        </div>
        <?php
    }

    function column_status($item, $column_name, $module, $table) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="'.$table.'"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<p><a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a></p>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url    = Url::admin('products/products-categories/edit/').$item->slug.((!empty(Admin::getCateType())) ? '?cate_type='.Admin::getCateType() : '');
        echo '<td class="'.$class.' text-center">';
        if(Auth::hasCap('product_cate_delete')) echo '<button class="btn btn-red js_btn_confirm" data-trash="disable" data-action="delete" data-ajax="Cms_Ajax_Action::delete" data-id="'.$item->id.'" data-module="ProductCategory" data-heading="Xóa Dữ liệu" data-description="Bạn chắc chắn muốn xóa sản phẩm <b>'.html_escape($item->name).'</b> ?">'.Admin::icon('delete').'</button>';
        if(Auth::hasCap('product_cate_edit')) echo '<a href="'.$url.'" class="btn-blue btn">'.Admin::icon('edit').'</a>';
        echo "</td>";
    }

    function search_left() {
        if(Auth::hasCap('product_cate_delete')) {
            ?>
            <button class="btn btn-red js_btn_confirm" style="display: none;" data-action="delete" data-ajax="Cms_Ajax_Action::delete" data-trash="disable" data-module="ProductCategory" data-heading="Xóa Dữ liệu" data-description="Bạn chắc chắn muốn xóa trường dữ liệu này?" data-toggle="tooltip" data-placement="top" title="Xóa"><?php echo Admin::icon('delete'); ?></button><?php
        }
    }

    function search_right() {
        $url     = Url::admin('products/products-categories/edit/');
        $input   = [];
        $input[] = ['field' => 'form_open', 'type'  => 'html', 'html'  => '<form class="search-box" action="'.$url.'">',];
        $input[] = [
            'field' => 'keyword', 'type'  => 'text',
            'value' => Request::Get('keyword'),
            'after' => '<div class="form-group-search">', 'before' => '</div>',
            'placeholder'   => 'Từ khóa...'
        ];
        $input[] = [
            'field' => 'category',
            'type'  => 'product_categories',
            'value' => Request::Get('category'),
            'after' => '<div class="form-group-search">',
            'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...')
        ];
        $input[] = ['field' => 'submit', 'type'  => 'html', 'html'  => '<button type="submit" class="btn" style="margin-bottom: 3px;padding: 10px 10px;">'.Admin::icon('search').'</button>',];
        $input[] = ['field' => 'form_close', 'type'  => 'html', 'html'  => '</form>'];
        $input   = apply_filters('admin_table_product_category_form_search', $input);
        $FormBuilder = new FormBuilder();
        $FormBuilder->add($input);
        $FormBuilder->html(false);
    }
}