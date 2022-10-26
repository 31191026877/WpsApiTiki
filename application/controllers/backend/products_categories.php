<?php defined('BASEPATH') OR exit('No direct script access allowed');

class products_categories extends MY_Controller {

	function __construct() {
		parent::__construct();
        $this->data['group']    = 'products';
	}

	public function index() {

		$model = model('products_categories');

        $this->creatForm();

		$keyword    = Request::get('keyword');

        $categoryId = (!empty(Request::get('category'))) ? (int)Request::get('category') : 0;

        $args = Qr::set()->orderBy('order')->orderBy('created', 'desc');

        if(!empty($keyword)) {
            $args->where('name', 'like', '%'.$keyword.'%');
        }
        else {
            $args->categoryType('tree', $categoryId);
        }

        $this->data['objects'] =  ProductCategory::gets($args);

        $this->data['total']   =  ProductCategory::count($args);

		/* tạo table */
        $args = array(
            'items' => $this->data['objects'],
            'table' => 'products_categories',
            'model' => $model,
            'module'=> $this->data['module'],
        );

        $class_table = 'AdminProductCategoryTable';

        $this->data['table'] = new $class_table(apply_filters('admin_products_categories_table_data',$args));

		$this->template->render();
	}

    public function add() {

        if( Auth::hasCap('product_cate_edit') ) {
            $this->creatForm();
            $this->template->render('products_categories-save');
        }
        else $this->template->error('404');
    }

	public function edit($slug = '') {

        if( Auth::hasCap('product_cate_edit') ) {

    		$this->data['object'] = ProductCategory::get(array('slug' => $slug)); // lấy dữ liệu page

            if(have_posts($this->data['object'])) {
                $this->creatForm();
                $this->setValueFields($this->data['object']);
                $this->template->render('products_categories-save');
            }
            else {
                $this->template->set_message(notice('danger', 'Bạn đang muốn sửa một thứ không tồn tại. Có thể nó đã bị xóa?'));
                $this->template->render('error-404');
            }
        }
        else $this->template->error('404');
    }
}