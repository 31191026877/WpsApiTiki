<?php
//================================ search products ===========================================//
function popover_order_product_search($object, $keyword) {
	return Product::gets(Qr::set('title','like', '%'.$keyword.'%')->orWhere('code', 'like', '%'.$keyword.'%'));
}

add_filter('input_popover_order_search_product_search', 'popover_order_product_search', 10, 2);


function popover_order_product_template_item($item, $active = '') {

    $item->variation = 0;

    $products_variations = Variation::gets(Qr::set('parent_id', $item->id));

    $object = [];

    if( have_posts($products_variations) ) {

        foreach ($products_variations as $variation) {

            $attr_name = '';

            foreach ($variation->items as $attr_id) {

                $attr = Attributes::getItem($attr_id);

                if( have_posts($attr)) {
                    $attr_name .= $attr->title .' / ';
                }
            }

            $variation->attr_name = trim( $attr_name, ' / ');

            if( empty($variation->_stock ) ) $variation->_stock = 0;
            
            if( $variation->_stock > 0 ) {

                $variation->_status = 'instock';
            }
            else {
                
                $variation->_status = 'outofstock';
            }

            $item->variation = $variation->id;
            
            $variation->id   = $item->id;

            $object[] = (object)array_merge((array)$item, (array)$variation);
        }

    } else {
        $object[] = $item;
    }

    $str = '';

    foreach ($object as $item) {

        $item->image = get_img_fontend_link($item->image);

        $str .= '
        <li class="option option-'.$item->id.' '.$active.'" data-key="'.$item->id.'" data-product="'.htmlentities(json_encode($item)).'">
            <div class="item-pr">
                <div class="item-pr__img">
                    <img src="'.$item->image.'">
                </div>
                <div class="item-pr__title">
                    <span>'.$item->title.((!empty($item->attr_name)) ? ' <small style="font-size:11px;color: #29bc94;">'.$item->attr_name.'</small>' : '').'</span>
                </div>
                <div class="item-pr__price">';
                if($item->price_sale == 0) {
                    $str .= '<span>'.number_format($item->price).'đ</span>';
                } else {
                    $str .= '<span style="padding-right:10px;">'.number_format($item->price_sale).'đ</span>';
                    $str .= '<span><del>'.number_format($item->price).'đ</del></span>';
                }
        $str .= '</div>
            </div>
        </li>';
    }

    return $str;
}

function popover_order_product_search_template($str, $item, $active) {
    return popover_order_product_template_item($item, $active);
}

add_filter('input_popover_order_search_product_search_template', 'popover_order_product_search_template', 10, 3);