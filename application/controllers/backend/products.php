<?php defined('BASEPATH') OR exit('No direct script access allowed');

class products extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {

        $model      = model('products');

        $keyword    = Request::get('keyword');

        $categoryId = (Request::get('category') != null) ? (int)Request::get('category') : 0;

        $collection = (int)Request::get('collection');

        $trash      = (Request::get('status') == 'trash') ? 1 : 0;

        $args = Qr::set('trash', $trash);

        if($categoryId != 0) {
            $args->whereByCategory($categoryId);
        }

        if(!empty($collection) && $collection >= 1 && $collection <= 3) {
            $args->where('status'.$collection, 1);
        }
        if(!empty($keyword)) {
            $args->where('title', 'like',  '%'.$keyword.'%');
        }

        # [Total decoders]
        $args = apply_filters('admin_product_controllers_index_args_count', $args);

        $total = Product::count($args);

        # [Pagination]
        $url = Url::admin().$this->data['module'].'?page={page}';
        $url = apply_filters('admin_product_controllers_pagination_url', $url);
        $pagination = pagination($total, $url, Option::get('admin_pg_page',10));

        # [Data]
        $args = apply_filters('admin_product_controllers_index_args', $args->limit(Option::get('admin_pg_page',10))->offset($pagination->offset())->orderBy('products.order')->orderBy('products.created', 'desc'));

        $objects = Product::gets($args);

        if(have_posts($objects)) {
            foreach ($objects as $object) {
                $object->categories = ProductCategory::getsByProduct($object->id, Qr::set()->select('id', 'name', 'image', 'slug'));
            }
        }

        $trash    =  Product::count(Qr::set('trash', 1));

        $public   =  Product::count(Qr::set('trash', 0)->where('public', 1));

        $objects  = apply_filters('admin_product_controllers_index_object', $objects);

        # [Table]
        $table = new AdminProductTable(apply_filters('admin_product_table_data', [
            'items' => $objects,
            'table' => $model->getTable(),
            'model' => $model,
            'module'=> $this->data['module'],
        ]));

        # [Render]
        $this->data = array_merge($this->data, compact('table', 'objects', 'total', 'public', 'trash', 'pagination'));
        $this->template->render();
    }

    public function add() {
        $model      = model('products');
        if(Auth::hasCap('product_edit') ) {
            $this->creatForm();
            $this->template->render('products-save');
        }
        else $this->template->error('404');
    }

    public function edit($id = '') {

        $model = model('products');

        if(Auth::hasCap('product_edit')) {

            $this->data['object'] = Product::get(Qr::set('id', $id)); // lấy dữ liệu page

            if(have_posts($this->data['object']) ) {

                $model->settable('relationships');

                $categories = $model->settable('relationships')->gets(Qr::set('object_type', 'products')->where('object_id', $this->data['object']->id)->select('object_id', 'category_id', 'value'));

                foreach ($categories as $cate) {
                    if( $cate->value == null ||  $cate->value == 'products_categories') {
                        $this->data['object']->category_id[] = $cate->category_id;
                    }
                    else $this->data['object']->{'taxonomy['.$cate->value.']'}[] = $cate->category_id;
                }

                $model->settable('products');
                $this->creatForm();
                $this->setValueFields($this->data['object']);

                $this->template->render('products-save');
            }
            else {
                $this->template->set_message(notice('danger', 'Bạn đang muốn sửa một thứ không tồn tại. Có thể nó đã bị xóa?'));

                $this->template->error('error-404');
            }
        }
        else $this->template->error('404');
    }
}