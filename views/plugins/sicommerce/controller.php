<?php
use Illuminate\Database\Capsule\Manager as DB;
/** PRODUCT-INDEX ******************************************************************/
if (!function_exists('controllers_product_index')) {
	/**
	 * @Load thư viện cần cho slider hình ảnh trong trang chi tiết sản phẩm
	 */
	function controllers_product_index($slug = '') {

	    $ci =&get_instance();

		$data = call_user_func(apply_filters('controllers_product_index_data', 'productControllerIndex'), 'get',$slug);

		$ci->data['slug']       = $slug;

        $ci->data['category']   = $data['category'];

        $ci->data['pagination'] = $data['pagination'];

		$ci->data['objects']    = $data['objects'];

        $ci->data['brand']      = $data['brand'];

        $ci->data['supplier']   = $data['supplier'];
	}

	add_action('controllers_products_index', 'controllers_product_index', 10, 1);
}
if (!function_exists('productControllerIndex')) {
    function productControllerIndex($type = 'get', $slug = ''): array {

        if ($type == 'post') $_GET = Request::post();

        $url = Url::base(URL_PRODUCT);

        if (empty($slug)) $slug = Request::get('slug');

        $status = (int)Request::get('status');

        $page = (int)Request::get('page');

        $product_pr_page = (int)Option::get('product_pr_page');

        $args = Qr::set('trash', 0)->where('public', 1);

        $brand = [];

        $supplier = [];

        $category = ProductCategory::get(Qr::set('slug', $slug));

        if (!have_posts($category)) {
            $brand = Brands::get(Qr::set('slug', $slug));

            if (!have_posts($brand)) {
                $supplier = Suppliers::get(Qr::set('slug', $slug));
            }
        }

        if ($status >= 1 && $status <= 3) {
            $args->where('status' . $status, 1);
        }

        if (have_posts($category)) {
            $url = Url::base(Url::permalink($category->slug) . '?page={page}');
            $args->whereByCategory($category);
        }

        if (have_posts($brand)) {
            $url = Url::base(Url::permalink($brand->slug) . '?page={page}');
            $args->where('brand_id', $brand->id);
        }

        if (have_posts($supplier)) {
            $url = Url::base(Url::permalink($supplier->slug) . '?page={page}');
            $args->where('supplier_id', $supplier->id);
        }

        if (empty($slug)) {
            $url = Url::base(Url::permalink(URL_PRODUCT) . '?page={page}');
            $dataUrl = Request::get();
            foreach ($dataUrl as $key => $value) {
                if ($key == 'action' || $key == 'page') {
                    unset($dataUrl[$key]);
                    continue;
                }
                if (empty($value)) {
                    unset($dataUrl[$key]);
                    continue;
                }
            }
            if (have_posts($dataUrl)) $url .= '&' . http_build_query($dataUrl);
        }

        $args = apply_filters('controllers_product_index_args', $args);

        $total = apply_filters('controllers_product_index_count', Product::count(clone $args));

        $pagination = pagination($total, $url, $product_pr_page, ($page != 0) ? $page : 1);

        $pagination = apply_filters('controllers_product_index_paging', $pagination);

        //$args->orderBy('products.order')->orderBy('products.created', 'desc');

        $orderType = Request::get('orderby');

        if ($orderType == 'price-desc') {
            $args->orderByRaw(DB::raw('(CASE WHEN `price_sale` = 0 THEN `price` ELSE `price_sale` END) DESC'));
        }
        else if ($orderType == 'price-asc') {
            $args->orderByRaw(DB::raw('(CASE WHEN `price_sale` = 0 THEN `price` ELSE `price_sale` END) ASC'));
        }
        else if ($orderType == 'best-selling') {
            $args->orderBy('products.status2', 'desc');
        }
        else if ($orderType == 'hot') {
            $args->orderBy('products.status3', 'desc');
        }

        $args->orderBy('products.order')->orderBy('products.created', 'desc');

        if (is_object($pagination)) {
            $args->limit($product_pr_page)->offset($pagination->offset());
        }

        $objects = apply_filters('controllers_product_index_objects', Product::gets($args), $args);

        $result = [];
        $result['pagination'] = $pagination;
        $result['total'] = $total;
        $result['objects'] = $objects;
        $result['category'] = $category;
        $result['brand'] = $brand;
        $result['supplier'] = $supplier;
        return $result;
    }
}
/** PRODUCT-DETAIL ******************************************************************/
if(!function_exists('controllers_product_detail')) {
	/**
	 * @Lấy data trang chi tiết sản phẩm
	 */
	function controllers_product_detail( $slug = '' ) {
		$ci = &get_instance();
        $object = Product::get(Qr::set('slug', $slug)->where('public', 1)->where('trash', 0));
        if(have_posts($object)) {
			$ci->data['categories'] = ProductCategory::getsByProduct($object->id, Qr::set()->select('id', 'name', 'slug', 'image', 'excerpt'));
            if(have_posts($ci->data['categories'])) {
				$ci->data['category']  = ProductCategory::get($ci->data['categories'][0]->id);
			}
		}
        $ci->data['object'] = $object;
	}

	add_action('controllers_products_detail', 'controllers_product_detail', 10, 1);
}