<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Post_Categories extends MY_Controller {

	public mixed $category 		= null;

    public $table = 'categories';

	function __construct() {

        parent::__construct();

        if(!Taxonomy::hasCategory($this->cateType)) {
            $this->template->set_view('404-error');
        }
        else if(!empty($this->postType) && !Taxonomy::hasPost($this->postType)) {
            if($this->postType != 'products') $this->template->set_view('404-error');
        }
		else {
            $this->data['group']    = $this->cateType;
            $this->category = Taxonomy::getCategory($this->cateType);
        }
	}

	public function index() {

		$model = model($this->table);

        $this->creatForm();

        $categoryID = (Request::get('category') != null) ? (int)Request::get('category') : 0;

        $args = Qr::set()->where('cate_type', $this->cateType)->orderBy('order')->orderBy('created', 'desc');

        if(!empty(Request::get('keyword'))) {
            $keyword    = Request::get('keyword');
            $args->where('name', 'like', '%'.$keyword.'%');
        }

        if(!empty($categoryID)) {
            $args->categoryType('tree', $categoryID);
        }
        else {
            $args->categoryType('tree');
        }

        $args     = apply_filters('admin_category_'.$this->cateType.'_controllers_index_args', $args);

        $objects  = apply_filters('admin_category_'.$this->cateType.'_controllers_index_objects', PostCategory::gets($args), $args);

        $total    =  PostCategory::count($args);

        # [List data]
        if(class_exists('skd_cate_'.$this->cateType.'_list_table')) {
        	$classTable = 'skd_cate_'.$this->cateType.'_list_table';
        } else {
            $classTable = 'AdminPostCategoriesTable';
        }

        $table = new $classTable(apply_filters('admin_post_categories_table_data',[
            'items' => $objects,
            'table' => 'post_categories',
            'model' => $model,
            'module'=> $this->data['module']
        ]));

        # [Render]
        $this->data = array_merge($this->data, compact('table', 'objects', 'total'));
		$this->template->render();
	}

    public function add() {
        $this->creatForm();
        $this->template->render('post_categories-save');
    }
	public function edit($id = '') {
        $this->data['object'] = apply_filters('admin_category_'.$this->postType.'_controllers_edit_objects', PostCategory::get($id), $id);
        if(have_posts($this->data['object'])) {
            $this->creatForm();
            $this->setValueFields($this->data['object']);
            $this->template->render('post_categories-save');
        }
        else {
            $this->template->render('error-404');
        }
    }
}