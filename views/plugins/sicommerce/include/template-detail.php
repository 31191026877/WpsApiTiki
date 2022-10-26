<?php
/** PRODUCT-DETAIL ******************************************************************/
if(!function_exists( 'product_detail_layout' ) ) {
	function product_detail_layout() {
        $layout = option::get('layout_products','layout-products-1');
        if($layout == 'layout-products-1') $layout = 'product_detail';
        if($layout == 'layout-products-2') $layout = 'product_detail_layout_two';
        if($layout == 'layout-products-3') $layout = 'product_detail_layout_three';
        Prd::template( $layout );
	}
	add_action('content_products_detail', 'product_detail_layout', 10);
}

/** product slider **/
if(!function_exists('product_detail_slider')) {
	function product_detail_slider() {
		$product_gallery = option::get('product_gallery', 'product_gallery_vertical');
		$image_type_source = apply_filters('product_slider_image_type_source', 'source');
		$image_type_medium = apply_filters('product_slider_image_type_medium', 'medium');
		if( $product_gallery == 'product_gallery_vertical') {
            Prd::template('detail/product_thumb_vertical', ['image_type_source' => $image_type_source, 'image_type_medium' => $image_type_medium]);
        }
		else {
            Prd::template('detail/product_thumb_horizontal', ['image_type_source' => $image_type_source, 'image_type_medium' => $image_type_medium]);
        }
	}
	add_action('product_detail_slider','product_detail_slider', 10);
}

/** product brand **/
if(!function_exists('product_detail_brand')) {
    function product_detail_brand() {
        Prd::template('detail/brand');
    }
    add_action('product_detail_info', 'product_detail_brand', 3);
}

/** product name **/
if(!function_exists('product_detail_title')) {
    function product_detail_title() {
        Prd::template('detail/title');
    }
    add_action('product_detail_info', 'product_detail_title', 5);
}

/** product code **/
if(!function_exists('product_detail_code')) {
    function product_detail_code() {
        Prd::template('detail/code');
    }
    add_action('product_detail_info', 'product_detail_code', 8);
}

/** product price **/
if(!function_exists('product_detail_price')) {
	function product_detail_price() {
        Prd::template('detail/price');
	}
	add_action('product_detail_info', 'product_detail_price', 10);
}

/** product description **/
if(!function_exists('product_detail_description')) {
	function product_detail_description() {
        Prd::template('detail/description');
	}
	add_action('product_detail_info', 'product_detail_description', 20);
}

/** product social **/
if(!function_exists('product_detail_social')) {
    function product_detail_social() {
        Prd::template('detail/social-share-btn');
    }
    add_action('product_detail_info','product_detail_social', 30);
}

/** product item **/
if(!function_exists('product_detail_item')) {
	function product_detail_item() {
        $product_item  = option::get('product_item', array(
            'enable'    => 0,
            'title'     => '',
            'item'      => []
        ));
        if(empty($product_item['enable'])) return;
        if(Language::hasMulti() && Language::default() != Language::current()) {
            $product_item['title'] = (!empty($product_item['title_'.Language::current()])) ? $product_item['title_'.Language::current()] : $product_item['title'];
            foreach ($product_item['item'] as $key => $item) {
                $product_item['item'][$key]['title'] = (!empty($item['title_'.Language::current()])) ? $item['title_'.Language::current()] : $item['title'];
                $product_item['item'][$key]['description'] = (!empty($item['description_'.Language::current()])) ? $item['description_'.Language::current()] : $item['description'];
            }
        }
        Prd::template('sidebar/list-item', ['items' => $product_item['item'], 'title' => $product_item['title']]);
	}
	add_action('product_detail_support', 'product_detail_item', 10);
}

/** product support **/
if(!function_exists('product_detail_support')) {
    function product_detail_support() {
        $product_support    = option::get('product_support', array('enable' => 0, 'title' => '', 'image' => '', 'url' => 'lien-he'));
        if(empty($product_support['enable'])) return;
        if(Language::hasMulti() && Language::default() != Language::current()) {
            $product_support['title'] = (!empty($product_support['title_'.Language::current()])) ? $product_support['title_'.Language::current()] : $product_support['title'];
        }
        Prd::template('sidebar/support',$product_support);
    }
    add_action('product_detail_support', 'product_detail_support', 20);
}

/** product tabs **/
if(!function_exists('product_detail_display_tabs')) {
	function product_detail_display_tabs($object) {
        if(version_compare(get_instance()->data['template']->version, '3.0.0') < 0) {
            Prd::template('detail/tabs-v3');
        }
        else {
            Prd::template('detail/tabs');
        }
	}
	add_action('product_detail_tabs', 'product_detail_display_tabs', 10);
}

if(!function_exists('product_detail_tab_default')) {
	function product_detail_tab_default( $tabs ) {
		// thêm tab mới
		$tabs['content'] = array(
			'title' 	=> 'Nội dung Chi Tiết',
			'priority' 	=> 50,
			'callback' => 'product_detail_tab_content'
		);
		return $tabs;
	}
	add_filter('product_tabs', 	'product_detail_tab_default' );
}

if(!function_exists('product_detail_tab_content')) {
	function product_detail_tab_content($object) {
		do_action('product_detail_tab_content_before');
        Prd::template('detail/tab-content');
		do_action('product_detail_tab_content_after');
	}
}

/** product related:: sản phẩm bán chạy **/
if(!function_exists( 'product_page_detail_selling')) {

    function product_page_detail_selling() {

        if(Template::getData('object') === false) return;

        $product_current = Template::getData('object');

        $product_selling = option::get('product_selling');

        $args = [
            'data'              => (!empty($product_selling['data'])) ?  $product_selling['data'] : 'handmade',
            'style'             => (!empty($product_selling['style'])) ?  $product_selling['style'] : 'slider',
            'posts_per_page' 	=> (!empty($product_selling['posts_per_page'])) ?  $product_selling['posts_per_page'] : 12,
            'columns' 			=> (!empty($product_selling['columns'])) ?  $product_selling['columns'] : 4,
            'position' 			=> (!empty($product_selling['position'])) ?  $product_selling['position'] : 'sidebar',
        ];

        if($args['data'] == 'handmade') {

            $product_data =  (have_posts($product_current)) ? Product::getMeta($product_current->id, 'product_selling', true) : [];

            if(have_posts($product_data)) {
                $selling = Qr::set()->whereIn('id', $product_data)->limit(20)->orderByRaw('rand()');
            }
            else {
                $selling = Qr::set('status2', 1)->limit(20)->orderByRaw('rand()');
            }
        }
        else {
            $selling = Qr::set('status2', 1)->limit(20)->orderByRaw('rand()');
        }

        $selling = apply_filters( 'get_products_selling_args',  $selling );

        $products = Product::gets($selling);

        // Get visble related products then sort them at random.
        $products = apply_filters( 'gets_selling_products',  $products );

        if(have_posts($products)) {
            $args['products']   = $products;
            $args['heading']    = __('Sản Phẩm Bán Chạy', 'product_heading_selling');
            $args['id']         = 'selling';
            if($args['position'] == 'sidebar') Prd::template('sidebar/widget_product', $args);
            if($args['position'] == 'content' || $args['position'] == 'bottom') Prd::template('detail/widget_product_content', $args);
        }
    }

    function product_page_detail_selling_position() {
        $product_selling = Option::get('product_selling');
        $product_selling = (empty($product_selling['position'])) ? 'sidebar' : $product_selling['position'];
        if($product_selling == 'sidebar') add_action('product_detail_sidebar','product_page_detail_selling', 10);
        if($product_selling == 'content') add_action('product_detail_tabs','product_page_detail_selling', 30);
        if($product_selling == 'bottom')  add_action('product_detail_after','product_page_detail_selling', 30);
    }
    product_page_detail_selling_position();
}

/** product related:: sản phẩm liên quan **/
if(!function_exists( 'product_page_detail_related')) {

	function product_page_detail_related() {

        if(Template::getData('object') === false) return;

        $product_current = Template::getData('object');

		$product_related = option::get('product_related');

		$args = [
		    'style'             => (!empty($product_related['style'])) ?  $product_related['style'] : 'slider',
			'posts_per_page' 	=> (!empty($product_related['posts_per_page'])) ?  $product_related['posts_per_page'] : 12,
			'columns' 			=> (!empty($product_related['columns'])) ?  $product_related['columns'] : 4,
		];

        $product_related_id =  (have_posts($product_current)) ? Product::getMeta($product_current->id, 'product_related', true) : [];

        if(have_posts($product_related_id)) {
            $related = Qr::set()->whereIn('id', $product_related_id)->limit(20)->orderByRaw('rand()');
        }
        else {
            $category = get_object_current('category');

            if(have_posts($category)) {
                $related = Qr::set()->whereByCategory($category)->limit($args['posts_per_page'])->orderByRaw('rand()');
            }
            else {
                $related = Qr::set()->limit($args['posts_per_page'])->orderByRaw('rand()');
                $related->related = $product_current->id;
            }
        }

        $related = apply_filters( 'get_products_related_args',  $related );

        $products = Product::gets($related);

        // Get visble related products then sort them at random.
        $products = apply_filters( 'gets_related_products',  $products );

        if(have_posts($product_related)) {
            $args['products']   = $products;
            $args['heading']    = apply_filters('product_detail_heading_related', __('Sản Phẩm Liên Quan', 'product_heading_related'));
            $args['id']         = 'related';
            Prd::template('detail/widget_product_content', $args);
        }
	}
    function product_page_detail_related_position() {
        $product_related = Option::get('product_related');
        $product_related = (empty($product_related['position'])) ? 'content' : $product_related['position'];
        if($product_related == 'sidebar') add_action('product_detail_sidebar','product_page_detail_related', 10);
        if($product_related == 'content') add_action('product_detail_tabs','product_page_detail_related', 20);
        if($product_related == 'bottom')  add_action('product_detail_after','product_page_detail_related', 20);
    }
    product_page_detail_related_position();
}

/** product viewd:: sản phẩm đã xem **/
if(!function_exists('product_page_detail_viewed_session')) {
	function product_page_detail_viewed_session(){
        if(Template::getData('object') === false) return;
        $product_current = Template::getData('object');
	    if(!isset($_SESSION['viewed_product'])) {  $_SESSION['viewed_product'] = []; }
	    if(!isset($_SESSION['viewed_product'][$product_current->id])) { $_SESSION['viewed_product'][$product_current->id] = $product_current->id; }
	}
	add_action('controllers_products_detail','product_page_detail_viewed_session', 20);
}

if(!function_exists('product_page_detail_viewed_sidebar')) {
	function product_page_detail_viewed_sidebar() {

		if(isset($_SESSION['viewed_product']) && have_posts($_SESSION['viewed_product'])) {

            $args = Option::get('product_watched');

            $args = (!have_posts($args)) ? [] : $args;

            $args = array_merge([
                'position'          => 'sidebar',
                'style'             => 'slider',
                'posts_per_page' 	=> 12,
                'columns' 			=> 4,
            ], $args);

            if($args['position'] != 'disabled') {

                $products = Product::gets(Qr::set()->whereIn('id', $_SESSION['viewed_product'])->limit($args['posts_per_page']));

                if(have_posts($products)) {
                    $args['products']   = $products;
                    $args['heading']    = apply_filters('product_detail_heading_watched', __('Sản Phẩm Đã Xem', 'product_heading_watched'));
                    $args['id']         = 'watched';
                    if($args['position'] == 'sidebar') Prd::template('sidebar/widget_product', $args);
                    if($args['position'] == 'content' || $args['position'] == 'bottom') Prd::template('detail/widget_product_content', $args);
                }
            }
		}
	}
    function product_page_detail_viewed_position() {
        $product_watched = Option::get('product_watched');
        $product_watched = (empty($product_watched['position'])) ? 'sidebar' : $product_watched['position'];
        if($product_watched == 'sidebar') add_action('product_detail_sidebar','product_page_detail_viewed_sidebar', 20);
        if($product_watched == 'content') add_action('product_detail_tabs','product_page_detail_viewed_sidebar', 30);
        if($product_watched == 'bottom') add_action('product_detail_after','product_page_detail_viewed_sidebar', 30);
    }
    product_page_detail_viewed_position();
}

/** product viewd:: Tin tức liên quan **/
if(!function_exists( 'product_page_detail_post_sidebar' )) {
    function product_page_detail_post_sidebar() {
        if(Template::getData('object') === false) return;
        $product_current = Template::getData('object');
        $product_related_id =  (have_posts($product_current)) ? Product::getMeta($product_current->id, 'post_related', true) : [];
        if(have_posts($product_related_id)) {
            $posts = (!empty($product_related_id)) ? Posts::gets(Qr::set()->whereIn('id', $product_related_id)->limit(10)) : [];
            if (have_posts($posts)) Prd::template('sidebar/post_related', ['posts' => $posts]);
        }
    }
    add_action('product_detail_sidebar','product_page_detail_post_sidebar', 10);
}