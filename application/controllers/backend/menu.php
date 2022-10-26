<?php defined('BASEPATH') OR exit('No direct script access allowed');

class menu extends MY_Controller {

	public $list_object = ['page' => ['label' => 'Trang Nội Dung', 'type' => 'page']];

	function __construct() {
		parent::__construct();
		$this->data['group'] 	= 'theme';
	}

	public function index() {

		if(Auth::hasCap('edit_theme_menus')) {

            $model = model('categories');

			//lấy toàn bộ menu
			$this->data['menus'] = ThemeMenu::gets();

			$this->data['menu'] = [];

            //get dữ liệu cho item menu
            $this->list_object['page']['data'] = Pages::gets(Qr::set()->select('id', 'title'));

            //get dữ liệu cho item categories
            $model->setTable('categories');

            $cateType = Taxonomy::getCategoryDetail();

            if(have_posts($cateType)) {

                foreach ($cateType as $key => $value) {

                    if($value['show_in_nav_menus']) {

                        $this->list_object[$key] = ['label' => $value['labels']['name'], 'type' => 'categories', 'data' => []];

                        $data = PostCategory::gets(Qr::set('cate_type', $key)->categoryType('options'));

                        foreach ($data as $idData => $datum) {
                            $this->list_object[$key]['data'][] = (object)[
                                'id' => $idData, 'name' => $datum
                            ];
                        }

                        unset($this->list_object[$key]['data'][0]);

                        $this->list_object[$key]['data'] = (object)$this->list_object[$key]['data'];
                    }
                }
            }

            //get dữ liệu cho item post
            $model->settable('post');

            $postType = Taxonomy::getPostDetail();

            if(have_posts($postType)) {

                foreach ($postType as $key => $value) {
                    if($value['show_in_nav_menus']) {
                        $this->list_object[$key] = ['label' => $value['labels']['name'], 'type' => 'post'];
                        $this->list_object[$key]['data'] = $model->gets(Qr::set('post_type', $key)->select('id', 'title')->where('trash', 0)->limit(30));
                    }
                }
            }

            $this->list_object = apply_filters('admin_menu_list_object', $this->list_object);

			$this->template->render();
		}
		else $this->template->error('404-error');
	}
}