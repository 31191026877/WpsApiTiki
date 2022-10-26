<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Post extends MY_Controller {

	function __construct() {
		parent::__construct('frontend');
	}

	/*==================== DISPLAY ================*/
	public function index($slug = '') {

		$this->data['objects'] 	= [];

		$this->data['category'] = [];

		$category = PostCategory::get(Qr::set('slug', $slug));

		if(have_posts($category)) {

			$check_post_type = false;

			foreach ($this->taxonomy['list_post_detail'] as $postType) {
				if(in_array( $category->cate_type, $postType['taxonomies'] ) !== false ) { $check_post_type = true; break; }
			}

			if(!$check_post_type) {
				$this->template->error('404');
				return false;
			}

			$args  = Qr::set('public', 1)->whereByCategory($category);
			$args                    = apply_filters('post_controllers_index_args', $args);
			$total                   = apply_filters('post_controllers_index_count', Posts::count($args));
			# [Pagination]
            $url                      = Url::base().Url::permalink($category->slug).'?page={page}';
			$this->data['pagination'] = apply_filters('post_controllers_index_paging', pagination($total, $url, Cms::config('client_post_number')));

            # [Data]
            $args->limit(Cms::config('client_post_number'))->offset($this->data['pagination']->offset())->orderBy('post.order')->orderBy('post.created', 'desc');

			$args  = apply_filters('post_controllers_index_params', $args);
			
			$this->data['objects']    = apply_filters('post_controllers_index_objects', Posts::gets($args),  $args );
			
			$this->data['category']   = $category;
		}

		$this->template->render();
	}

	public function detail($slug = '') {
        $args  = apply_filters('post_controllers_detail_args', Qr::set('slug', $slug));
        $this->data['object']  = apply_filters('post_controllers_detail_objects', Posts::get($args),  $args);
		$this->data['category'] = [];
		if(have_posts($this->data['object'])) {
			$this->data['categories'] = PostCategory::getsByPost($this->data['object']->id);
			if(have_posts($this->data['categories'])) $this->data['category'] = $this->data['categories'][0];
			$this->template->render();
		}
		else {
			$this->template->error('404');
		}
	}
}