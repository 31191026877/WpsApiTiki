<?php
Class Product_Page_Search {
    static public function searchData($objects, $type, $keyword) {

        $ci =& get_instance();

        if($type == 'products') {

            $object = Product::get(Qr::set('code', $keyword)->where('type', '<>', 'trash'));

            if(have_posts($object) && $object->type != 'products') {
                $object = Product::get(Qr::set($object->parent_id));
            }

            $objects = [];

            if(have_posts($object)) $objects[] = $object;

            if(!have_posts($objects)) {

                if(Option::get('product_fulltext_search', false)) {

                    $keywordFull = '+' . $keyword;

                    $keywordFull = str_replace(' ', ' +', $keywordFull);

                    $args = Qr::set('public', 1)->where('type', 'product')->where('trash', 0)
                        ->whereRaw("MATCH(title) AGAINST('" . $keywordFull . "' IN BOOLEAN MODE) > 0")
                        ->selectRaw("*, MATCH(title) AGAINST('" . $keywordFull . "' IN BOOLEAN MODE) as score")
                        ->orderByDesc('score');
                }
                else {
                    $args = Qr::set('public', 1)->where('trash', 0)->where('title', 'like', '%'.$keyword.'%');
                }

                if (Request::get('category') != null && Request::get('category') != 0) {
                    $category = Request::get('category');
                    $args->whereByCategory($category);
                }

                $args = apply_filters('product_search_args', $args);

                $objects = apply_filters('product_search_data', Product::gets($args));
            }

            $ci->template->set_layout('template-full-width');
        }

        return $objects;
    }
    static public function searchHtml($objects, $type, $keyword) {
        if($type == 'products') {
            Prd::template( 'product_search', $objects );
        }
    }
}
add_filter('get_search_data','Product_Page_Search::searchData', 10, 3 );
add_action('get_search_view','Product_Page_Search::searchHtml', 10, 3 );