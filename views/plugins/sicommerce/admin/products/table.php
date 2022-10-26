<?php
//product
class AdminProductTable extends skd_object_list_table {

    function get_columns() {

        $this->_column_headers = [
            'cb'        => 'cb',
            'image'     => 'Hình',
            'title'     => 'Tiêu Đề',
            'categories'=> 'Chuyên mục',
            'price'     => 'Giá',
            'price_sale'=> 'Giá khuyến mãi',
            'collection'=> 'Nhóm',
            'order'     => 'Thứ tự',
            'action'    => 'Hành Động',
        ];

        $this->_column_headers = apply_filters( "manage_product_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    function column_default($column_name, $item, $global) {
        do_action( 'manage_product_custom_column', $column_name, $item, $global );
    }

    function column_title($item) {
        ?>
        <h3><?= $item->title;?></h3>
        <?php do_action('admin_product_table_column_title', $item);?>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> |
            <a href="<?= Url::permalink($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }

    function column_categories($item) {
        $str = '';
        foreach ($item->categories as $key => $value) {
            $str .= sprintf('<a href="%s">%s</a>, ', URL_ADMIN.'/products/products-categories/edit/'.$value->slug, $value->name);
        }
        echo trim($str,', ');
    }

    function column_price($item, $column_name, $module, $table) {
        $str = number_format($item->price);
        echo '<a href="#" data-pk="'.$item->id.'" data-name="price" class="js_products_price__update" >'.$str.'</a>';
    }

    function column_price_sale($item, $column_name, $module, $table) {
        $str = number_format($item->price_sale);
        echo '<a href="#" data-pk="'.$item->id.'" data-name="price_sale" class="js_products_price__update" >'.$str.'</a>';
    }

    function column_collection($item, $column_name) {

        $source = [
            ['value' => 0, 'text' => 'Ẩn hiển thị'],
            ['value' => 1, 'text' => 'Hiển thị'],
        ];

        $value  = [];

        $text   = '';

        if($item->public == 1) {
            $value[] = '1'; $text = '<span class="label label-success">Hiển thị</span>';
        }
        if($item->public == 0) {
            $value[] = '0'; $text = '<span class="label label-danger">Ẩn hiển thị</span>';
        }
        $source = htmlentities(json_encode($source));
        $value  = htmlentities(json_encode($value));
        echo '<a href="#" data-source="'.$source.'" data-value="'.$value.'" data-pk="'.$item->id.'" class="editTable-product-public mb-1 d-block" >'.$text.'</a>';

        $source = [
            ['value' => 'status1', 'text' => 'Yêu thích'],
            ['value' => 'status2', 'text' => 'Bán chạy'],
            ['value' => 'status3', 'text' => 'Nổi bật'],
        ];

        $value  = [];

        $text   = '';

        if($item->status1 == 1) {
            $value[] = 'status1'; $text .= '<span class="label label-success">Yêu thích</span>';
        }
        if($item->status2 == 1) {
            $value[] = 'status2'; $text .= ', <span class="label label-primary">Bán chạy</span>';
        }
        if($item->status3 == 1) {
            $value[] = 'status3'; $text .= ', <span class="label label-danger">Nổi bật</span>';
        }
        $text = trim($text, ', ');
        $source = htmlentities(json_encode($source));
        $value  = htmlentities(json_encode($value));
        echo '<a href="#" data-source="'.$source.'" data-value="'.$value.'" data-pk="'.$item->id.'" class="editTable-product-collections" >'.$text.'</a>';
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {

        $url = Url::admin().'/'.$module.'/edit/'.$item->id.'?page='.((Request::get('page') != '')?Request::get('page'):1);

        if(!empty(Request::get('category'))) $url .= '&category='.Request::get('category');

        echo '<td class="'.$class.' text-center">';
        if(Request::get('status') == 'trash') {
            echo '<button class="btn btn-green js_btn_confirm" data-action="restore" data-ajax="Cms_Ajax_Action::restore" data-id="'.$item->id.'" data-module="products" data-heading="Khôi phục dữ liệu" data-description="Bạn chắc chắn muốn khôi phục sản phẩm <b>'.html_escape($item->title).'</b> ?">'.Admin::icon('undo').'</button>';
            if(Auth::hasCap('product_delete')) echo '<button class="btn btn-red js_btn_confirm" data-action="delete" data-ajax="Cms_Ajax_Action::delete" data-id="'.$item->id.'" data-module="products" data-heading="Xóa Dữ liệu" data-description="Bạn chắc chắn muốn xóa sản phẩm <b>'.html_escape($item->title).'</b> ?">'.Admin::icon('delete').'</button>';
        } else {
            echo '<a href="'.$url.'" class="btn-blue btn">'.Admin::icon('edit').'</a>';
            if(Auth::hasCap('product_delete')) echo '<button class="btn btn-red js_btn_confirm" data-action="delete" data-ajax="Cms_Ajax_Action::delete" data-id="'.$item->id.'" data-module="products" data-trash="enable" data-heading="Xóa Dữ liệu" data-description="Bạn chắc chắn muốn xóa sản phẩm <b>'.html_escape($item->title).'</b> ?">'.Admin::icon('delete').'</button>';
        }
        do_action('admin_product_table_button_action', $item);
        echo "</td>";
    }

    function search_left() {
        $cms = &get_instance();
        $public = $cms->data['public'];
        $trash  = $cms->data['trash'];
        $status = Request::get('status');
        $url       = Url::adminModule().((!empty($cms->urlType)) ? $cms->urlType : '');
        $urlTrash  = (!empty($cms->urlType)) ? Url::adminModule($cms->urlType.'&status=trash') : Url::adminModule('?status=trash');
        $text_status_normal = Admin::icon('edit').' <b class="number-count">'.$public.'</b>';
        $text_status_trash  = Admin::icon('delete').' <b class="number-count">'.$trash.'</b>';
        $active_status_normal = 'btn-white';
        $active_status_trash = 'btn-white';
        $trash_enable = 'enable';
        if($status == 'trash') {
            $active_status_trash = 'btn-theme';
            $trash_enable = 'disable';
        }
        else {
            $active_status_normal = 'btn-theme';
        }
        ?>
        <a href="<?= $url;?>" class="btn <?php echo $active_status_normal;?>" data-toggle="tooltip" data-placement="top" title="Đã đăng"><?= $text_status_normal; ?></a>
        <a href="<?= $urlTrash;?>" class="btn <?php echo $active_status_trash;?>" data-toggle="tooltip" data-placement="top" title="Thùng rác"><?= $text_status_trash; ?></a>
        <?php if(Auth::hasCap('product_delete')) { ?><button class="btn btn-red js_btn_confirm" style="display: none;" data-action="delete" data-ajax="Cms_Ajax_Action::delete" data-trash="<?php echo $trash_enable;?>" data-module="Product" data-heading="Xóa Dữ liệu" data-description="Bạn chắc chắn muốn xóa trường dữ liệu này?" data-toggle="tooltip" data-placement="top" title="Xóa"><?php echo Admin::icon('delete');?></button><?php } ?>
        <?php if($status == 'trash') { ?><button class="btn btn-green js_btn_confirm" style="display: none;" data-action="restore" data-ajax="Cms_Ajax_Action::restore" data-module="Product" data-heading="Khôi phục dữ liệu" data-description="Bạn chắc chắn muốn khôi phục trường dữ liệu này?" data-toggle="tooltip" data-placement="top" title="Khôi phục"><?php echo Admin::icon('undo');?></button><?php }
    }

    function search_right() {
        $url     = Url::adminModule();
        $input   = [];
        $input[] = ['field' => 'form_open', 'type'  => 'html', 'html'  => '<form class="search-box" action="'.$url.'">',];
        $input[] = [
            'field' => 'keyword', 'type'  => 'text',
            'value' => Request::Get('keyword'),
            'after' => '<div class="form-group-search">', 'before' => '</div>',
            'placeholder'   => 'Từ khóa...'
        ];
        $input[] = [
            'field' => 'collection', 'type'  => 'select',
            'options' => ['Tất cả', 'Yêu thích','Bán chạy', 'Nổi bật'],
            'value' => Request::Get('collection'),
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
        $input[] = ['field' => 'submit', 'type'  => 'html', 'html'  => '<button type="submit" class="btn" style="margin-bottom: 3px;padding: 5px 10px;">'.Admin::icon('search').'</button>',];
        $input[] = ['field' => 'form_close', 'type'  => 'html', 'html'  => '</form>'];
        $input   = apply_filters('admin_table_product_form_search', $input);
        $FormBuilder = new FormBuilder();
        $FormBuilder->add($input);
        $FormBuilder->html(false);
    }
}