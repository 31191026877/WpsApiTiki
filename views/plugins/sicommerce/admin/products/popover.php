<?php

Class Product_Popover {

    static public function registerSearch($search) {

       if($search == 'products') return 'Product_Popover::search';

       return $search;

    }

    static public function registerLoad($search) {

        if($search == 'products') return 'Product_Popover::load';

        return $search;

    }

    static public function search($ci, $model) {

        $result['message'] 	= 'Không có kết quả nào.';

        $result['status'] 	= 'error';

        $result['items']     = [];

        if(Request::post()) {

            $keyword    = Str::ascii(trim(Request::post('keyword')));

            $page       = (int)Request::post('page') - 1;

            $limit      = (int)Request::post('limit');



            $objects    = Product::gets(Qr::set('trash',0)->where('title', 'like', '%'.$keyword.'%')

                ->select('id', 'title', 'image', 'price', 'price_sale')

                ->limit($limit)->offset($page*$limit));

            if(have_posts($objects)) {

                foreach ($objects as $value) {

                    $item = [

                        'id'    => $value->id,

                        'image' => Template::imgLink($value->image),

                        'name'  => $value->title,

                    ];

                    $item['data'] = htmlentities(json_encode($item));

                    $result['items'][]  = $item;

                }

                $result['status'] 	= 'success';

            }

            $result['total'] = count($result['items']);

        }

        echo json_encode($result);

    }

    static public function load($listID, $taxonomy) {

        $items = [];

        if(have_posts($listID)) {

            $objects    = Product::gets(Qr::set('trash',0)->whereIn('id', $listID)

                ->select('id', 'title', 'image', 'price', 'price_sale'));



            foreach ($objects as $value) {

                $item = ['id' => $value->id, 'image' => Template::imgLink($value->image), 'name' => $value->title];

                $items[]  = $item;

            }

        }

        return $items;

    }

}

add_filter('popover_advance_search_custom', 'Product_Popover::registerSearch');

add_filter('popover_advance_load_custom', 'Product_Popover::registerLoad');

Ajax::admin('Product_Popover::search');



Class Product_Variable_Popover {

    static public function registerSearch($search) {

        if($search == 'products-variable') return 'Product_Variable_Popover::search';

        return $search;

    }

    static public function registerLoad($search) {

        if($search == 'products-variable') return 'Product_Variable_Popover::load';

        return $search;

    }

    static public function search($ci, $model) {

        $result['message'] 	= 'Không có kết quả nào.';

        $result['status'] 	= 'error';

        $result['items']     = [];

        if(Request::post()) {

            $keyword    = Str::ascii(trim(Request::post('keyword')));

            $page       = (int)Request::post('page') - 1;

            $limit      = (int)Request::post('limit');

            $objects    = Product::gets(Qr::set('trash',0)->where('title', 'like', '%'.$keyword.'%')

                ->select('id', 'title', 'image', 'price', 'price_sale')

                ->limit($limit)->offset($page*$limit));

            if(have_posts($objects)) {

                foreach ($objects as $value) {

                    $variables = Variation::gets(Qr::set('parent_id', $value->id));

                    if(have_posts($variables)) {

                        foreach ($variables as $variable) {

                            $attr_name = '';

                            foreach ($variable->items as $attr_id) {

                                $attr = Attributes::getItem($attr_id);

                                if( have_posts($attr)) {

                                    $attr_name .= ' - <span style="font-weight: bold">'.$attr->title.'</span>';

                                }

                            }

                            $variable->title .= $attr_name;

                            $item = [

                                'id'    => $variable->id,

                                'image' => (!empty($variable->image)) ? Template::imgLink($variable->image) : Template::imgLink($value->image),

                                'name'  => $variable->title,

                            ];

                            $item['data'] = htmlentities(json_encode($item));

                            $result['items'][]  = $item;

                        }

                    }

                    else {

                        $item = [

                            'id'    => $value->id,

                            'image' => Template::imgLink($value->image),

                            'name'  => $value->title,

                        ];

                        $item['data'] = htmlentities(json_encode($item));

                        $result['items'][]  = $item;

                    }

                }

                $result['status'] 	= 'success';

            }

            $result['total'] = count($result['items']);

        }

        echo json_encode($result);

    }

    static public function load($listID, $taxonomy) {

        $items = [];

        if(have_posts($listID)) {

            $objects    = Product::gets(Qr::set('trash',0)->where('type', '<>', 'trash')->whereIn('id', $listID)

                ->select('id', 'title', 'image', 'price', 'price_sale'));



            foreach ($objects as $value) {

                if($value->type == 'product') {

                    $item = [

                        'id'    => $value->id,

                        'image' => Template::imgLink($value->image),

                        'name'  => $value->title,

                    ];

                    $items[]  = $item;

                }

                else {

                    $value = Variation::get($value->id);

                    if(empty($value->image)) {

                        $product = Product::get(Qr::set($value->parent_id)->select('id', 'title', 'image'));

                    }

                    $attr_name = '';

                    if(!empty($value->items)) {

                        foreach ($value->items as $attr_id) {

                            $attr = Attributes::getItem($attr_id);

                            if( have_posts($attr)) {

                                $attr_name .= ' - <span style="font-weight: bold">'.$attr->title.'</span>';

                            }

                        }

                    }

                    $value->title .= $attr_name;

                    $item = [

                        'id'    => $value->id,

                        'image' => (!empty($value->image)) ? Template::imgLink($value->image) : Template::imgLink($product->image),

                        'name'  => $value->title,

                    ];

                    $items[]  = $item;

                }

            }

        }

        return $items;

    }

}

add_filter('popover_advance_search_custom', 'Product_Variable_Popover::registerSearch');

add_filter('popover_advance_load_custom', 'Product_Variable_Popover::registerLoad');

Ajax::admin('Product_Variable_Popover::search');

/**

 * INPUT POPOVER product

 */

Class Product_Admin_Popover {

    static public function search($object, $keyword) {

        return Product::gets(Qr::set('trash',0)->where('public', 1)->where('title', 'like', '%'.$keyword.'%')->limit(50));

    }

    static public function templateItem($item, $active = '') {



        $item->image = Template::imgLink($item->image);



        $price = '';



        if($item->price_sale == 0) {

            $price .= '<span>'.number_format($item->price).'đ</span>';

        } else {

            $price .= '<span style="padding-right:10px;">'.number_format($item->price_sale).'đ</span>';

            $price .= '<span><del>'.number_format($item->price).'đ</del></span>';

        };



        $str = '

        <li class="option option-'.$item->id.' '.$active.'" data-key="'.$item->id.'" data-product="'.htmlentities(json_encode($item)).'">

            <div class="item-pr">

                <div class="item-pr__img"><img src="'.$item->image.'"></div>

                <div class="item-pr__title">

                    <span class="label-option">'.$item->title.'</span>

                </div>

                <div class="item-pr__price">'.$price.'</div>

            </div>

        </li>';



        return $str;

    }

    static public function template($str, $item, $active) {

        return Product_Admin_Popover::templateItem($item, $active);

    }

    static public function value($value, $data) {

        if(isset($data['image']) && $data['image'] == true) {

            if(!empty($data['value']) && is_array($data['value'])) {

                $products = Product::gets(Qr::set('public', 1)->where('type', '<>', 'block')->whereIn('id', $data['value'])->select('id', 'title', 'image', 'parent_id'));

                foreach ($products as $product) {

                    if($product->parent_id != 0) {

                        $product = Variation::get($product->id);

                        $parent  = Product::get($product->parent_id);

                        $attr_name = '';

                        foreach ($product->items as $attr_id) {

                            $attr = Attributes::getItem($attr_id);

                            if( have_posts($attr)) {

                                $attr_name .= $attr->title .' / ';

                            }

                        }

                        $product->image = $parent->image;

                        $product->title .= ' - <small style="font-weight:bold;font-size:11px;color: #29bc94;">'.trim( $attr_name, ' / ').'</small>';

                    }



                    $value[$product->id] = [

                        'label' => $product->title,

                        'image' => $product->image

                    ];

                }

            }

            if(!empty($data['value']) && is_string($data['value'])) {

                $product = Product::get(Qr::set($data['value'])->where('public', 1)->where('type', '<>', 'block')->select('id', 'title', 'image', 'parent_id'));

                if(have_posts($product)) {

                    if($product->parent_id != 0) {

                        $product = Variation::get($product->id);

                        $parent  = Product::get($product->parent_id);

                        $attr_name = '';

                        foreach ($product->items as $attr_id) {

                            $attr = Attributes::getItem($attr_id);

                            if( have_posts($attr)) {

                                $attr_name .= $attr->title .' / ';

                            }

                        }

                        $product->image = $parent->image;

                        $product->title .= ' - <small style="font-weight:bold;font-size:11px;color: #29bc94;">'.trim( $attr_name, ' / ').'</small>';

                    }

                    $value[$product->id] = [

                        'label' => $product->title,

                        'image' => $product->image

                    ];

                }

            }

        }

        else {

            if(!empty($data['value']) && is_array($data['value'])) {

                foreach ($data['options'] as $op_id => $op_value ) {

                    if(in_array($op_id, $data['value']) === true) {

                        $value[$op_id] = ['label' => $op_value];

                        unset($data['value'][array_search($op_id, $data['value'])]);

                    }

                }

                if(have_posts($data['value'])) {

                    $products = Product::gets(Qr::set()->whereIn('id', $data['value'])->select('id', 'title'));

                    foreach ($products as $product) {

                        $value[$product->id] = ['label' => $product->title];

                    }

                }

            }



            if(!empty($data['value']) && is_string($data['value'])) {

                foreach ($data['options'] as $op_id => $op_value ) {

                    if($op_id == $data['value']) {

                        $value[$op_id] = ['label' => $op_value];

                        unset($data['value']);

                    }

                }

                if(!empty($data['value'])) {

                    $product = Product::get(Qr::set($data['value'])->select('id', 'title'));

                    if(have_posts($product)) {

                        $value[$product->id] = ['label' => $product->title];

                    }

                }

            }

        }



        return $value;

    }

}

add_filter('input_popover_products_search', 'Product_Admin_Popover::search', 10, 2);

add_filter('input_popover_products_search_template', 'Product_Admin_Popover::template', 10, 3);

add_filter('input_popover_products_value', 'Product_Admin_Popover::value', 10, 2);





/**

 * INPUT POPOVER product variable

 */

Class Product_Variable_Admin_Popover {

    static public function templateItem($item, $active = '') {



        $item->image = Template::imgLink($item->image);



        $item->price_html = '';



        if($item->price_sale == 0) {

            $item->price_html .= '<span>'.number_format($item->price).'đ</span>';

        } else {

            $item->price_html .= '<span style="padding-right:10px;">'.number_format($item->price_sale).'đ</span>';

            $item->price_html .= '<span><del>'.number_format($item->price).'đ</del></span>';

        }



        $item->variation = 0;



        $products_variations = Variation::gets(Qr::set('parent_id', $item->id));



        $object = [];



        if( have_posts($products_variations) ) {



            foreach ($products_variations as $variation) {



                if(empty($variation->image)) {

                    $variation->image = $item->image;

                }

                else {

                    $variation->image = Template::imgLink($variation->image);

                }



                $attr_name = '';



                foreach ($variation->items as $attr_id) {



                    $attr = Attributes::getItem($attr_id);



                    if( have_posts($attr)) {

                        $attr_name .= $attr->title .' / ';

                    }

                }



                $variation->attr_name = trim( $attr_name, ' / ');



                $item->variation = $variation->id;

                $variation->price_html = '';

                if($variation->price_sale == 0) {

                    $variation->price_html .= '<span>'.number_format($variation->price).Prd::priceUnit().'</span>';

                } else {

                    $variation->price_html .= '<span style="padding-right:10px;">'.number_format($variation->price_sale).Prd::priceUnit().'</span>';

                    $variation->price_html .= '<span><del>'.number_format($variation->price).Prd::priceUnit().'</del></span>';

                }

                $object[] = $variation;

            }



        } else {

            $object[] = $item;

        }



        $str = '';



        foreach ($object as $item) {

            $str .= '

            <li class="option option-'.$item->id.' '.$active.'" data-key="'.$item->id.'" data-product="'.htmlentities(json_encode($item)).'">

                <div class="item-pr">

                    <div class="item-pr__img">

                        <img src="'.$item->image.'">

                    </div>

                    <div class="item-pr__title">

                        <span class="label-option">'.$item->title.((!empty($item->attr_name)) ? ' <small style="font-size:11px;color: #29bc94;">'.$item->attr_name.'</small>' : '').'</span>

                    </div>

                    <div class="item-pr__price">'.$item->price_html.'</div>

                </div>

            </li>';

        }



        return $str;

    }

    static public function template($str, $item, $active) {

        return Product_Variable_Admin_Popover::templateItem($item, $active);

    }

}

add_filter('input_popover_products_variable_search', 'Product_Admin_Popover::search', 10, 2);

add_filter('input_popover_products_variable_search_template', 'Product_Variable_Admin_Popover::template', 10, 3);

add_filter('input_popover_products_variable_value', 'Product_Admin_Popover::value', 10, 2);

