<?php use Illuminate\Database\Capsule\Manager as DB;
//dùng cho toàn bộ table
class skd_object_list_table extends SKD_list_table {

    function column_public($item, $column_name, $module) {
        $this->column_boole($item, $column_name, $module);
    }

    function column_boole($item, $column_name, $module) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="'.$module.'"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function column_image($item) {
        echo Template::img($item->image, $item->title, ['style' => 'width:50px;', 'type' => 'medium']);
    }

    function column_created($item) {
        if(!empty($item->created)) echo date("d-m-Y", strtotime($item->created));
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url    = Url::admin().'/'.$module.'/edit/'.$item->slug;
        echo '<td class="'.$class.' text-center">';
        if(Request::get('status') == 'trash') {
            echo Admin::btnRestore(['id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn khôi phục trang <b>'.html_escape($item->title).'</b> ?']);
            echo Admin::btnDelete(['id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn xóa trang <b>'.html_escape($item->title).'</b> ?']);
        } else {
            echo '<a href="'.$url.'" class="btn-blue btn">'.Admin::icon('edit').'</a>';
            echo Admin::btnDelete(['trash' => 'enable', 'id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn xóa trang <b>'.html_escape($item->title).'</b> ?']);
        }
        echo "</td>";
    }

    //search box
    function display_search() {
        ?>
        <div class="box-heading-left"><?php $this->search_left();?></div>
        <div class="box-heading-right"><?php $this->search_right();?></div>
        <?php
    }

    function search_left() {
        $cms = &get_instance();
        $module = $cms->data['module'];
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
        <?php
        echo Admin::btnDelete(['trash' => $trash_enable, 'module' => $module, 'style' => 'display:none;']);
        if($status == 'trash') {
            echo Admin::btnRestore(['module' => $module, 'style' => 'display:none;']);
        }
    }

    function search_right() {

        $url        = Url::adminModule();

        if(Request::get('cate_type') != null) {
            $url .='?cate_type='.Request::get('cate_type');
        }

        $input = [];
        $input[] = ['field' => 'form_open', 'type'  => 'html', 'html'  => '<form class="search-box" action="'.$url.'">',];
        $input[] = [
            'field' => 'keyword',
            'type'  => 'text',
            'value' => Request::get('keyword'),
            'after' => '<div class="form-group-search">',
            'before' => '</div>',
            'placeholder'   => 'Từ khóa...'
        ];
        $input[] = ['field' => 'submit', 'type'  => 'html', 'html'  => '<button type="submit" class="btn" style="padding: 10px 10px;">'.Admin::icon('search').'</button>',];
        $input[] = ['field' => 'form_close', 'type'  => 'html', 'html'  => '</form>'];
        $input          = apply_filters('admin_table_object_form_search', $input);
        $FormBuilder    = new FormBuilder();
        $FormBuilder->add($input);
        echo $FormBuilder->html();
    }
}
//Page
class AdminPageTable extends skd_object_list_table {

    function get_columns() {
        $this->_column_headers = [
            'cb'        => 'cb',
            'title'     => 'Tiêu Đề',
            'public'    => 'Hiển Thị',
        ];

        $this->_column_headers = apply_filters( "manage_pages_columns", $this->_column_headers );
        $this->_column_headers['action'] = 'Hành Động';
        return $this->_column_headers;
    }

    public function single_row( $item ) {
        echo apply_filters('single_row_page', '<tr class="tr_'.$item->id.'">', $item);
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    function column_default($column_name, $item, $global) {
       do_action('manage_pages_custom_column', $column_name, $item, $global);
    }

    function column_title($item) {
        ?>
        <h3><?= $item->title;?></h3>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> | <a href="<?php echo Url::permalink($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }

    function _column_action( $item, $column_name, $module, $table, $class) {
        $urlEdit = URL_ADMIN.'/'.$module.'/edit/'.$item->id;
        $trash  = (Request::get('status') == 'trash') ? 'disable' : 'enable';
        echo '<td class="'.$class.' text-center">';
        if($trash == 'disable') {
            echo Admin::btnRestore(['id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn khôi phục trang <b>'.html_escape($item->title).'</b> ?']);
            if(Auth::hasCap('delete_pages')) {
                echo Admin::btnDelete(['trash' => $trash, 'id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn xóa trang <b>'.html_escape($item->title).'</b> ?']);
            }
        } else {
            echo '<a href="'.$urlEdit.'" class="btn-blue btn">'.Admin::icon('edit').'</a>';
            if(Auth::hasCap('delete_pages')) echo Admin::btnDelete(['trash' => $trash, 'id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn xóa trang <b>'.html_escape($item->title).'</b> ?']);
        }
        echo "</td>";
    }
}
//category
class AdminPostCategoriesTable extends skd_object_list_table {
    function get_columns() {
        $cateType = Taxonomy::getCategory(Admin::getCateType());
        $this->_column_headers = [
            'cb'        => 'cb',
            'title'     => 'Tiêu Đề',
            'order'     => 'Thứ Tự',
            'public'    => 'Hiển Thị',
        ];
        $this->_column_headers['cb'] = 'cb';
        if( in_array('image', $cateType['supports'] ) !== false ) $this->_column_headers['image'] = 'Ảnh';
        $this->_column_headers['title'] = 'Tiêu đề';
        if( in_array('excerpt', $cateType['supports'] ) !== false ) $this->_column_headers['excerpt'] = 'Mô tả';
        $this->_column_headers['public'] = 'Hiển Thị';
        $this->_column_headers['action'] = 'Hành Động';
        $this->_column_headers = apply_filters( 'manage_categories_'.Admin::getCateType().'_columns', $this->_column_headers );
        return $this->_column_headers;
    }
    function single_row( $item ) {
        echo apply_filters('single_row_category_'.$item->cate_type, '<tr class="tr_'.$item->id.'">', $item);
        $this->single_row_columns( $item );
        echo '</tr>';
    }
    function column_default($column_name, $item, $global) {
       do_action('manage_categories_'.Admin::getCateType().'_custom_column', $column_name, $item, $global);
    }
    function column_title($item) {
        ?>
        <h3><?= str_repeat('|-----', (($item->level > 0)?($item->level - 1):0)).$item->name;?></h3>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> |
            <a href="<?= Url::permalink($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }
    function column_image($item, $column_name = '', $module = '', $table = '') {
        echo Template::img($item->image, $item->name, ['style' => 'width:50px;', 'type' => 'medium']);
    }
    function column_order($item, $column_name, $module, $table) {
        echo '<p><a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a></p>';
    }
    function _column_action($item, $column_name, $module, $table, $class) {
        $url    = Url::admin('post/post-categories/edit/'.$item->id);
        $url   .= (!empty(Admin::getCateType())) ? '?cate_type='.Admin::getCateType() : '';
        echo '<td class="'.$class.' text-center">';
        if(Admin::getCateType() != 'post_categories' || Auth::hasCap('delete_categories')) {
            echo Admin::btnDelete(['id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn xóa danh mụ <b>'.html_escape($item->name).'</b> ?']);
        }
        echo '<a href="'.$url.'" class="btn-blue btn">'.Admin::icon('edit').'</a>';
        echo "</td>";
    }
    function search_left() {
        $cms = &get_instance();
        $module = $cms->data['module'];
        $trash_enable = 'disable';
        echo Admin::btnDelete(['trash' => $trash_enable, 'module' => $module, 'style' => 'display:none;']);
    }
}
//post
class AdminPostTable extends skd_object_list_table {

    function get_columns() {

        $this->_column_headers = [
            'cb'        => 'cb',
            'image'     => 'Hình',
            'title'     => 'Tiêu Đề',
        ];

        $postType = Admin::getPostType();

        $taxonomies = Taxonomy::getCategoryByPost( $postType, 'objects' );

        $taxonomies = apply_filters( "manage_taxonomies_for_{$postType}_columns", $taxonomies, $postType );

        foreach ($taxonomies as $taxonomy_key => $taxonomy_value) {

            if(!$taxonomy_value->show_in_nav_admin) continue;

            if ('post_categories' === $taxonomy_key) {
                $column_key = 'categories';
            }
            else {
                $column_key = 'taxonomy-' . $taxonomy_key;
            }

            $this->_column_headers[$column_key] = $taxonomy_value->labels['name'];
        }

        $this->_column_headers['order']     = 'Thứ Tự';
        $this->_column_headers['status']    = 'Nổi bật';
        $this->_column_headers['public']    = 'Hiển Thị';
        $this->_column_headers['action']    = 'Hành Động';

        $this->_column_headers = apply_filters( "manage_post_".$postType."_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    function single_row( $item ) {
        echo apply_filters('single_row_post_'.$item->post_type, '<tr class="tr_'.$item->id.'">', $item);
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    function column_default($column_name, $item, $global) {

       do_action( 'manage_post_'.Admin::getPostType().'_custom_column', $column_name, $item, $global);

       if(str_contains($column_name, 'taxonomy-')) {

            $taxonomy = substr( $column_name, 9);

            if(Taxonomy::getCategory($taxonomy)['show_admin_column']) {

                $str = '';

                $categories = PostCategory::gets(Qr::set('post_id', $item->id)
                    ->where('post_type', $item->post_type)
                    ->where('cate_type', $taxonomy)
                    ->select('categories.id', 'categories.name', 'categories.slug'));

                foreach ($categories as $key => $value) {
                    $str .= sprintf('<a href="%s">%s</a>, ', URL_ADMIN.'/post/post_categories/edit/'.$value->slug.get_instance()->urlType, $value->name);
                }

                echo trim($str,', ');
            }
       }
    }

    function column_title($item) {
        ?>
        <h3><?php echo $item->title;?></h3>
        <div style="color:#ddd;padding:5px 0;"><?= Str::limit(Str::clear($item->excerpt),10);?></div>
        <div class="action-hide">
            <span>ID : <?= $item->id;?></span> |
            <a href="<?= Url::permalink($item->slug);?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Xem"><i class="fa fa-eye"></i></a>
        </div>
        <?php
    }

    function column_categories( $item ) {

        $str = '';

        $categories = PostCategory::getsByPost($item->id, Qr::set()->select('categories.id', 'categories.name', 'categories.slug'));

        foreach ($categories as $value) {
            $str .= sprintf('<a href="%s">%s</a>, ', URL_ADMIN.'/post/post_categories/edit/'.$value->id.'?cate_type='.Admin::getCateType(), $value->name);
        }

        echo trim($str,', ');
    }

    function column_order($item, $column_name, $module, $table) {
        echo '<p><a href="#" data-pk="'.$item->id.'" data-name="order" data-table="'.$table.'" class="edittable-dl-text" >'.$item->order.'</a></p>';
    }

    function column_status($item, $column_name, $module, $table) {
        echo '<input type="checkbox" class="icheck up-boolean" data-row="'.$column_name.'" data-model="'.$table.'"  data-id="'.$item->id.'"'.(($item->$column_name == 1)?'checked':'').'/>';
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $cms        = get_instance();
        $numberPage = (int)Request::get('page');
        $numberPage = ($numberPage != 0 && $numberPage != 1)  ? '&page='.$numberPage : '';
        $urlEdit    = Url::admin().'/'.$module.'/edit/'.$item->id.$cms->urlType.$numberPage;
        $editRole   = !(!empty($cms->post['capabilities']['edit'])) || (((Auth::hasCap($cms->post['capabilities']['edit'])) ? true : false));
        $deleteRole = !(!empty($cms->post['capabilities']['delete'])) || (((Auth::hasCap($cms->post['capabilities']['delete'])) ? true : false));
        $trash  = (Request::get('status') == 'trash') ? 'disable' : 'enable';
        echo '<td class="'.$class.' text-center">';
        if($trash == 'disable') {
            echo Admin::btnRestore(['id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn khôi phục bài viết <b>'.html_escape($item->title).'</b> ?']);
            if($deleteRole) echo Admin::btnDelete(['trash' => $trash, 'id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn xóa bài viết <b>'.html_escape($item->title).'</b> ?']);
        }
        else {
            echo $editRole ? '<a href="'.$urlEdit.'" class="btn-blue btn">'.Admin::icon('edit').'</a>' : '';
            if($deleteRole) echo Admin::btnDelete(['trash' => $trash, 'id' => $item->id, 'module' => $module, 'des' => 'Bạn chắc chắn muốn xóa bài viết <b>'.html_escape($item->title).'</b> ?']);
        }
        echo "</td>";
    }

    function search_right() {

        $cms = &get_instance();

        $url = Url::adminModule().$cms->urlType;

        $cateTypeDetail = Taxonomy::getCategoryByPost(Admin::getPostType());

        $cateType       = Admin::getCateType();

        if(empty($cateType) && have_posts($cateTypeDetail)) $cateType = $cateTypeDetail[0];

        $input = [];

        $input[] = ['field' => 'form_open', 'type'  => 'html', 'html'  => '<form class="search-box" action="'.$url.'">'];

        if(Taxonomy::hasPost(Admin::getPostType())) {
            $input[] = ['field' => 'post_type', 'type'  => 'text', 'value' => Str::clear(Admin::getPostType()), 'after' => '<div style="display:none;">', 'before' => '</div>'];
        }

        $input[] = ['field' => 'keyword', 'type'  => 'text', 'value' => Request::get('keyword'), 'after' => '<div class="form-group-search">', 'before' => '</div>', 'placeholder'   => 'Từ khóa...'];

        if(have_posts($cateTypeDetail)) {
            $input[] = ['field' => 'category', 'type'  => 'cate_'.$cateType, 'value' => Request::get('category'), 'after' => '<div class="form-group-search">', 'before' => '</div>', 'args' => array('placeholder' => 'Từ khóa...')];
        }

        if(have_posts($cateTypeDetail))  {
            $input[] = ['field' => 'cate_type', 'type'  => 'text', 'value' => Str::clear($cateType), 'after' => '<div style="display:none;">', 'before' => '</div>'];
        }

        $input[] = ['field' => 'submit', 'type'  => 'html', 'html'  => '<button type="submit" class="btn" style="padding: 10px 10px;">'.Admin::icon('search').'</button>',];
        $input[] = ['field' => 'form_close', 'type'  => 'html', 'html'  => '</form>'];
        $input          = apply_filters('admin_table_post_form_search', $input);
        $FormBuilder    = new FormBuilder();
        $FormBuilder->add($input);
        $FormBuilder->html(false);
    }
}
//user
class AdminUserTable extends skd_object_list_table {

    function get_columns() {

        $this->_column_headers = array(
            'cb'       => 'cb',
            'username' => 'Tên đăng nhập',
            'fullname' => 'Tên hiển thị',
            'email'    => 'Email',
            'phone'    => 'Số điện thoại',
            'role'     => 'Chức vụ',
            'action'   => 'Hành động',
        );

        $this->_column_headers = apply_filters( "manage_user_columns", $this->_column_headers );

        return $this->_column_headers;
    }

    public function single_row( $item ) {
        echo apply_filters('single_row_user','<tr class="tr_'.$item->id.'">', $item);
        $this->single_row_columns( $item );
        echo '</tr>';
    }

    function _column_username($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->username;
        echo "</td>";
    }

    function _column_fullname($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->firstname.' '.$item->lastname;
        echo "</td>";
    }

    function _column_email($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->email;
        echo "</td>";
    }

    function _column_phone($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo $item->phone;
        echo "</td>";
    }

    function _column_role($item, $column_name, $module, $table, $class) {
        echo '<td class="'.$class.'">';
        echo User::getRoleName($item->id);
        echo "</td>";
    }

    function _column_action($item, $column_name, $module, $table, $class) {
        $url = URL_ADMIN.'/'.$module.'/';
        $class .= ' text-center';
        echo '<td class="'.$class.'" style="width:200px;">';
        if( Admin::isRoot() && !empty($item->username)) {
            echo '<button data-id="'.$item->id.'" class="btn btn_login_as" style="background-color: #FBC208;"><img src="'.Admin::imgLink('login-as.png').'" style="width:20px;" alt=""></button>';
        }
        echo '<a href="'.$url.'edit/?view=profile&id='.$item->id.'" class="btn-blue btn">'.Admin::icon('edit').'</a>';
        if( Auth::hasCap('remove_users') ) {
            echo '<button data-id="'.$item->id.'" class="btn-red btn user-trash">'.Admin::icon('delete').'</button>';
        }
        if( Admin::isRoot()) {
            echo '<a href="'.$item->id.'" class="btn-green btn btn-reset-pass"><i class="fa fa-key" aria-hidden="true"></i></a>';
        }
        echo "</td>";
    }

    function column_default($column_name, $item, $global) {
       do_action('manage_user_custom_column', $column_name, $item, $global);
    }

    function search_left() {
    }

    function search_right() {
        $url = Url::adminModule();
        if(Request::get('cate_type') != null) {
            $url .='?cate_type='.Request::get('cate_type');
        }
        $input = [];
        $input[] = ['field' => 'form_open', 'type'  => 'html', 'html'  => '<form class="search-box" action="'.$url.'">',];
        $input[] = [
            'field' => 'keyword',
            'type'  => 'text',
            'value' => Request::get('keyword'),
            'after' => '<div class="form-group-search">',
            'before' => '</div>',
            'placeholder'   => 'Họ tên...'
        ];
        $input[] = [
            'field' => 'phone',
            'type'  => 'tel',
            'value' => Request::get('phone'),
            'after' => '<div class="form-group-search">',
            'before' => '</div>',
            'placeholder'   => 'Điện thoại...'
        ];
        $input[] = [
            'field' => 'email',
            'type'  => 'text',
            'value' => Request::get('email'),
            'after' => '<div class="form-group-search">',
            'before' => '</div>',
            'placeholder'   => 'Email...'
        ];
        $input[] = ['field' => 'submit', 'type'  => 'html', 'html'  => '<button type="submit" class="btn" style="padding: 10px 10px;">'.Admin::icon('search').'</button>',];
        $input[] = ['field' => 'form_close', 'type'  => 'html', 'html'  => '</form>'];
        $input          = apply_filters('admin_table_user_form_search', $input);
        $FormBuilder    = new FormBuilder();
        $FormBuilder->add($input);
        $FormBuilder->html(false);
    }
}