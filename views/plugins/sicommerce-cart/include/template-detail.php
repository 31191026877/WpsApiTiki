<?php
if(!function_exists('product_detail_cart_data')) {
    function product_detail_cart_data($object){

        $metaOptions        = apply_filters('product_data_variations', Attributes::getsByProduct($object->id));

        $variations_options = [];

        $variations         = Variation::gets(Qr::set('parent_id', $object->id)->where('status', 'public'));

        $product_default    = [];

        $product_default_id = (int)Product::getMeta($object->id, 'default', true);

        if(have_posts($variations)) {

            $item_variations = [];

            $attributeRelationships = [];

            if($product_default_id == 0 && isset($variations[0]->items)) {
                $product_default = $variations[0]->items;
            }

            foreach ($variations as $variation) {

                unset($variation->seo_title);
                unset($variation->seo_description);
                unset($variation->seo_keywords);
                unset($variation->supplier_id);
                unset($variation->brand_id);
                unset($variation->user_created);
                unset($variation->user_updated);
                unset($variation->created);
                unset($variation->updated);
                unset($variation->theme_layout);
                unset($variation->theme_view);
                unset($variation->status1);
                unset($variation->status2);
                unset($variation->status3);
                unset($variation->order);
                unset($variation->trash);
                unset($variation->excerpt);
                unset($variation->content);
                unset($variation->image);
                unset($variation->code);

                if(!isset($variation->items)) continue;

                if($product_default_id == $variation->id) {
                    $product_default = $variation->items;
                }

                foreach ($variation->items as $id_option => $id_item ) {
                    if(isset($attributeRelationships[$id_item])) {
                        $attributeRelationships[$id_item] = [];
                    }
                    else {
                        $attributeRelationships[$id_item][] = $id_item;
                    }
                    $item_variations[$id_option][] = $id_item;
                }
            }

            foreach ($metaOptions as $key_options => &$options ) {
                foreach ($options['items'] as $key_item => $items ) {
                    if(!isset($item_variations[$options['id']]) || in_array( $items->id, $item_variations[$options['id']]) === false ) {
                        unset($options['items'][$key_item]);
                    }
                }
            }

            if(have_posts($metaOptions)) {
                foreach ($metaOptions as $key_meta_options => $meta_option ) {
                    $variations_options[$meta_option['id']] = [];
                    foreach ($meta_option['items'] as $key => $item) {
                        foreach ($variations as $variation) {
                            if(!isset($variation->items)) continue;
                            if(in_array($item->id, $variation->items) !== false) {
                                foreach ($variation->items as $id_option => $id_item ) {
                                    //if( $id_item == $item->id ) continue;
                                    if(!empty($variations_options[$meta_option['id']][$item->id][$id_option]) && in_array($id_item, $variations_options[$meta_option['id']][$item->id][$id_option]) !== false) continue;
                                    $variations_options[$meta_option['id']][$item->id][$id_option][] = $id_item;
                                }
                            }
                        }
                    }
                }
            }
        }

        $ci = &get_instance();
        $ci->data['meta_options']        = $metaOptions;
        $ci->data['variations']          = $variations;
        $ci->data['variations_options']  = $variations_options;
        $ci->data['product_default']     = $product_default;
        $ci->data['product_default_id']  = $product_default_id;
    }
    add_action('product_detail_before', 	'product_detail_cart_data', 1);
}

if(!function_exists('product_detail_cart_options')) {
    function product_detail_cart_options($object){
        cart_template('detail/cart-options');
    }
    add_action('product_detail_info', 	'product_detail_cart_options', 45);
}

if(!function_exists('product_detail_cart_button')) {
    function product_detail_cart_button($object){
        cart_template('detail/cart-button');
    }
    add_action('product_detail_info', 	'product_detail_cart_button', 50);
}

if(!function_exists('product_data_variations')) {
    function product_data_variations( $object = [] ){
        $ci =& get_instance();
        if(!have_posts($object)) $object  = $ci->data['object'];
        return apply_filters( 'product_data_variations', Attributes::getsByProduct($object->id));
    }
}

if(!function_exists('product_detail_variations')) {
    function product_detail_variations($option, $product_default = []){
        if(have_posts($option) && isset($option['items']) && have_posts($option['items'])) {
            cart_template( 'detail/cart-variations', array( 'option' => $option, 'default' => $product_default ) );
        }
    }
}

function product_attribute_image_type_color( $attr, $option, $attribute, $object ) {
    $img = Product::getMeta($object->id, 'variations_img', true );
    if(!empty($img[$attribute->id])) {
        $attr['style'] 		= 'background:url('.Template::imgLink($img[$attribute->id]).'); background-size:100% 100%;';
        $attr['data-image'] = Url::base().Template::imgLink($img[$attribute->id]);
    }
    return $attr;
}

function product_attribute_image_type_image( $attr, $option, $attribute, $object ) {
    $img = Product::getMeta($object->id, 'variations_img', true );
    if(!empty($img[$attribute->id])) {
        $attr['style'] 		= 'background:url('.Template::imgLink($img[$attribute->id]).'); background-size:100% 100%;';
        $attr['data-image'] = Url::base().Template::imgLink($img[$attribute->id]);
    }
    return $attr;
}

function product_attribute_image_type_label( $attr, $option, $attribute, $object ) {
    $img = Product::getMeta($object->id, 'variations_img', true );
    if(!empty($img[$attribute->id])) {
        $attr['style'] 		= 'background:url('.Template::imgLink($img[$attribute->id]).'); background-size:100% 100%;';
        $attr['data-image'] = Url::base().Template::imgLink($img[$attribute->id]);
    }
    return $attr;
}
add_filter( 'product_detail_attribute_type_color', 'product_attribute_image_type_color', 10, 4);
add_filter( 'product_detail_attribute_type_image', 'product_attribute_image_type_image', 10, 4);
add_filter( 'product_detail_attribute_type_label', 'product_attribute_image_type_label', 10, 4);