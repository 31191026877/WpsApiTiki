<?php

Class Admin_Product_Options_Detail_Ajax {

    static public function loadAttribute($ci, $model) {

        $result['status'] 	= 'success';

        $result['data'] 	= '';

        if(Request::post()) {

            $product_id = (int)Request::post('object_id');

            if($product_id != 0) {

                $attributes = Attributes::gets( ['product_id' => $product_id] );

                if(have_posts($attributes)) {

                    foreach ($attributes as $key => $value) {

                        $result['data'] .= cart_include('admin/product/views/html-product-attribute-item', ['product_attribute' => (object)$value], true);

                    }

                }

            }

        }

        echo json_encode($result);

    }

    static public function addAttribute($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Thêm dữ liệu không thành công!';

        if(Request::post()) {

            $id 		= (int)Request::post('id');

            $product_id = (int)Request::post('object_id');

            $attribute = Attributes::get($id);

            if(have_posts($attribute)) {

                $result['status'] = 'success';

                $result['data'] = cart_include('admin/product/views/html-product-attribute-item',['product_attribute' => (object)array('id' => $id, 'title' => $attribute->title,'product_id' => $product_id, 'attributes_item' => [])],true);

            }

            else {

                $result['message'] = 'Thuộc tính không tồn tại.';

            }

        }

        echo json_encode($result);

    }

    static public function saveAttribute($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Lưu dữ liệu không thành công!';

        if(Request::post()) {

            $product_id 	= (int)Request::post('object_id');

            $data 			= Request::post();

            //nếu thêm

            if($product_id == 0) {

                $model->settable('session');

                if( !isset($data['attribute_names']) || !have_posts( $data['attribute_names'] ) ) {

                    $result['message'] = 'Bạn chưa chọn thuộc tính cho sản phẩm!';

                    echo json_encode($result);

                    return false;

                }

                $session_id 				= (int)Request::post('session_id');

                $session['session_value'] 	= serialize($data);

                $session['session_expiry'] 	= time() + 24*60*60;

                if(!$session_id ){

                    $session['session_key'] 	= md5($ci->data['user']->id.time().$ci->security->get_csrf_hash());

                    $session_id = $model->add($session);

                }

                else {

                    $model->update($session, Qr::set('session_id', $session_id));

                }

                $result['session_id'] 	= $session_id;

            }

            else {



                $attribute 		= [];



                $attribute_value = [];



                $attributeValueData = [];



                if(!empty($data['attribute_names']) && have_posts($data['attribute_names']) ) {

                    foreach ($data['attribute_names'] as $key => $value) {

                        if(isset($data['attribute_values'][$value]) && have_posts($data['attribute_values'][$value])) {

                            $option 							= Attributes::get($value);

                            $attribute['_op_'.$value]['name'] 	= $option->title;

                            $attribute['_op_'.$value]['id'] 	= $option->id;

                            foreach ($data['attribute_values'][$value] as $val) {

                                $attribute_value['attribute_op_'.$value][] = $val;

                                $attributeValueData[] = $val;

                            }

                            unset($data['attribute_values'][$value]);

                        }

                        unset($data['attribute_names'][$key]);

                    }

                }



                $metaBoxAttr['_product_attributes'] 		= serialize($attribute);



                $metaBoxAttr['_product_attributes_value'] 	= $attribute_value;



                $attributeValueData = $model->settable('relationships')->gets(Qr::set('object_id', $product_id)->where('object_type', 'attributes')->whereNotIn('value', $attributeValueData));



                if(have_posts($attributeValueData)) {



                    $productVariations = Variation::getsByProduct($product_id);



                    if(have_posts($productVariations)) {



                        foreach ($attributeValueData as $attributeValueDatum) {

                            foreach ($productVariations as $productVariation) {

                                if(in_array($attributeValueDatum->value, $productVariation->items) !== false) {

                                    $result['message'] 		= 'Không thể xóa thuộc tính đang đực sử dụng trong biến thể!';

                                    $result['status'] 		= 'error';

                                    echo json_encode($result);

                                    return false;

                                }

                            }

                        }

                    }

                }



                //product attributes

                Product::updateMeta($product_id, 'attributes', $metaBoxAttr['_product_attributes'] );



                foreach ($metaBoxAttr['_product_attributes_value'] as $meta_key => $meta_values) {



                    $model->settable('relationships')->delete(Qr::set('object_id', $product_id)->where('category_id', $meta_key)->where('object_type', 'attributes'));



                    foreach ($meta_values as $value) {

                        $model->add(array('object_id' => $product_id, 'category_id' => $meta_key, 'object_type' => 'attributes', 'value' =>  $value ));

                    }

                }

            }

            $result['message'] 		= 'Lưu dữ liệu thành công!';



            $result['status'] 		= 'success';

        }



        echo json_encode($result);

    }

    static public function deleteAttribute($ci, $model) {

        $result['status'] = 'error';

        $result['message'] = 'Xóa dữ liệu không thành công!';

        if(Request::post()) {



            $id 				= (int)Request::post('data');



            $product_id 		= (int)Request::post('product_id');



            //nếu thêm

            if($product_id == 0) {



                $model->settable('session');



                $session_id = (int)Request::post('session_id');



                $session = $model->get(Qr::set('session_id', $session_id));



                //chưa lưu vào session

                if(!have_posts( $session)) {

                    $result['status'] = 'success';

                    echo json_encode($result);

                    return false;

                }



                //đã lưu vào session

                $session->session_value 	= unserialize($session->session_value);



                if(isset($session->session_value['attribute_values'][$id])) {

                    unset($session->session_value['attribute_values'][$id]);

                }



                if(isset($session->session_value['attribute_names'][$id])) {

                    unset($session->session_value['attribute_names'][$id]);

                }



                if(!isset($session->session_value) || !have_posts($session->session_value)) $session->session_value = [];



                $session->session_value 	= serialize($session->session_value);



                $session = (array)$session;



                $model->update($session, Qr::set('session_id', $session_id));



                $result['status'] = 'success';



                $result['message'] = 'Xóa dữ liệu thành công!';

            }



            if($id != 0) {



                $metaBox = Product::getMeta($product_id, 'attributes', false);



                if(have_posts($metaBox->attributes)) {



                    if(isset($metaBox->attributes['_op_'.$id])) unset($metaBox->attributes['_op_'.$id]);



                    Product::updateMeta($product_id, 'attributes', $metaBox->attributes );



                    $model->settable('relationships');

                    

                    $model->delete(Qr::set('object_id', $product_id)->where('object_type', 'attributes')->where('category_id', 'attribute_op_'.$id));



                    $variations_metadata = $model->settable('products_metadata')->gets(Qr::set('meta_key','attribute_op_'.$id)->where('object_id', $product_id));



                    if( have_posts($variations_metadata) ) {



                        foreach ($variations_metadata as $meta) {



                            $model->settable('products_metadata');



                            Metadata::delete('products', $meta->object_id, $meta->meta_key );



                            $metadata = Metadata::get('products', $meta->object_id);



                            $count = 0;



                            foreach ($metadata as $key_meta => $meta_value) {



                                if(str_starts_with($key_meta, 'attribute_op_')) $count++;

                            }



                            if( $count == 0 ) {



                                Product::delete($meta->object_id);

                            }

                        }

                    }

                }



                $result['status'] = 'success';



                $result['message'] = 'Xóa dữ liệu thành công!';

            }

        }



        echo json_encode($result);

    }

    static public function loadVariations($ci, $model) {



        $result['status'] = 'success';



        $result['data'] = '';



        if(Request::post()) {



            $id = (int)Request::post('object_id');



            $attributes = [];



            if($id == 0) {



                $session_id = (int)Request::post('session_id');



                $session = $model->settable('session')->get(Qr::set('session_id', $session_id));



                if(have_posts($session)) {



                    $temp = @unserialize($session->session_value);



                    if( isset($temp['attribute_values']) && have_posts($temp['attribute_values']) ) {



                        foreach ($temp['attribute_values'] as $key => $value) {

                            $attributes['attribute'][$key]['value'] = $value;

                        }



                    }

                    else {



                        $result['data'] = notice('error', 'Vui lòng chọn chủng loại cho thuộc tính sau đó bấm lưu để có thể tạo các biến thể');



                        echo json_encode($result);



                        return false;

                    }



                    $id = $session_id;

                }

            }

            else {



                $result['status'] = 'success';



                $result['data'] = '';



                $attributes = Product::getMeta($id, 'attributes', true);



                if(have_posts($attributes)) {



                    $temp = [];



                    foreach ($attributes as $key => $value) {



                        $temp['attribute'][$value['id']]['id'] 		= $value['id'];



                        $temp['attribute'][$value['id']]['value'] 	= [];



                        $model->settable('relationships');

                        

                        $attributes_item = $model->gets(Qr::set('object_id',$id)

                            ->where('category_id', 'attribute_op_'.$value['id'])

                            ->where('object_type', 'attributes'));



                        if($attributes_item instanceof Illuminate\Support\Collection) {

                            $attributes_item = $attributes_item->all();

                        }



                        foreach ($attributes_item as $val) {

                            $temp['attribute'][$value['id']]['value'][] = $val->value;

                        }

                    }



                    $attributes = $temp;

                }

            }



            $variations = Product::gets(Qr::set('parent_id', $id)->where('type', 'variations'));



            if(have_posts($variations) && have_posts($attributes)) {



                foreach ($variations as $key => $value) {



                    $ci->data['variation']   	 = $value;



                    $ci->data['variations_id']   = $value->id;



                    $ci->data['variations_code'] = $value->code;



                    $result['data'] .= cart_include('admin/product/views/html-product-variation-item', $attributes, true);

                }

            }

        }



        echo json_encode($result);

    }

    static public function addVariations($ci, $model) {



        $result['status'] = 'error';



        $result['message'] = 'Thêm dữ liệu không thành công!';



        if(Request::post()) {



            $product_id = (int)Request::post('id');



            if($product_id == 0) {



                $session_id = (int)Request::post('session_id');



                $model->settable('session');

                

                $session = $model->get(Qr::set('session_id', $session_id));



                if(have_posts($session)) {



                    $session->session_value = unserialize($session->session_value);



                    foreach ($session->session_value['attribute_names'] as $key => $value) {



                        $attribute[$key] = array('value' => $session->session_value['attribute_values'][$key]);

                    }



                    $variations = [];



                    $variations['parent_id'] = $session_id;



                    $variations['status']    = 'draft';



                    $variations['type']    	 = 'variations';



                    $ci->data['variations_id'] = Product::insert($variations);



                    $result['status'] = 'success';



                    $result['data'] = cart_include('admin/product/views/html-product-variation-item', array('attribute' => $attribute),true);

                }

            }

            else {

                $attributes = Product::getMeta($product_id, 'attributes', true);



                if(have_posts($attributes)) {



                    $product = Product::get($product_id);



                    $temp = [];



                    foreach ($attributes as $key => $value) {



                        $temp['attribute'][$value['id']]['id'] 		= $value['id'];



                        $temp['attribute'][$value['id']]['value'] 	= [];



                        $attributes_item = $model->settable('relationships')->gets(Qr::set('object_id', $product_id)->where('category_id', 'attribute_op_'.$value['id'])->where('object_type', 'attributes'));



                        foreach ($attributes_item as $val) {

                            $temp['attribute'][$value['id']]['value'][] = $val->value;

                        }

                    }



                    $data['attribute'] = $temp['attribute'];



                    $variations = [];



                    $variations['price'] = $product->price;



                    $variations['price_sale'] = $product->price_sale;



                    $variations['parent_id'] = $product_id;



                    $variations['status']    = 'draft';



                    $variations['type']    	 = 'variations';



                    $data['variations_id'] = Product::insert($variations);



                    $model->settable('relationships');



                    if( $data['variations_id'] ) {



                        $data['variation'] = Product::get(Qr::set($data['variations_id'])->where('type', 'variations'));


                        $result['status'] = 'success';



                        $result['data'] = cart_include('admin/product/views/html-product-variation-item', $data, true);

                    }

                }

            }

        }



        echo json_encode($result);

    }

    static public function saveVariations($ci, $model) {



        $result['status'] = 'error';



        $result['message'] = 'Lưu dữ liệu không thành công!';



        if(Request::post()) {



            $product_id 	= (int)Request::post('object_id');



            $data 			= Request::post();



            unset($data['action']);



            //xử lý dữ liệu

            $data_format = [];



            $codes 		= $data['variable_code']; unset($data['variable_code']);



            $price 		= $data['variable_price']; unset($data['variable_price']);



            $price_sale = $data['variable_price_sale']; unset($data['variable_price_sale']);



            $image 		= $data['upload_image']; unset($data['upload_image']);



            foreach ($codes as $id => $code) {



                $product = [

                    'id' 		 => $id,

                    'code' 		 => Str::clear($code),

                    'image'		 => FileHandler::handlingUrl($image[$id]),

                    'price' 	 => $price[$id],

                    'price_sale' => $price_sale[$id],

                ];



                $product = apply_filters('admin_product_variation_data_save', $product, $id);



                Product::insert($product);

            }



            if(have_posts($data_format)) {

                foreach ($data_format as $object_id => $metadata) {

                    if(have_posts($metadata)) {

                        foreach ($metadata as $meta_key => $meta_value) {

                            Product::updateMeta($object_id, $meta_key, $meta_value);

                        }

                    }

                }

            }



            //ver 2.7.0

            do_action('admin_product_variation_save', $product_id, $data );


            $result['message'] 		= 'Lưu dữ liệu thành công!';



            $result['status'] 		= 'success';



        }



        echo json_encode($result);

    }

    static public function deleteVariations($ci, $model) {



        $result['status'] = 'error';



        $result['message'] = 'Xóa dữ liệu không thành công!';



        if(Request::post()) {



            $id 				= (int)Request::post('data');



            $product_id 		= (int)Request::post('product_id');



            $session_id 		= (int)Request::post('session_id');



            $where = Qr::set($id)->where('type', 'variations');



            if($product_id != 0) {

                $where->where('parent_id', $product_id);

            }

            else if($session_id != 0) {

                $where->where('parent_id', $session_id);

            }



            $variations = Product::count($where);



            if($variations != 0) {



                Product::delete($id);



                $result['status'] = 'success';



                $result['message'] = 'Xóa dữ liệu thành công!';

            }

        }



        echo json_encode($result);

    }

    static public function loadOptionImage($ci, $model) {

        $result['status']   = 'success';

        $result['data']     = '';

        if (Request::post()) {

            $id         = (int)Request::post('object_id');

            $session_id = (int)Request::post('session_id');

            if ($id == 0 && $session_id == 0) {

                $result['data'] = notice('error', 'Vui lòng chọn chủng loại cho thuộc tính sau đó bấm lưu để có thể tạo các biến thể');

                echo json_encode($result);

                return false;

            }

            if ($id == 0) {



                $model->settable('session');



                $session = $model->get(Qr::set('session_id', $session_id));



                if (have_posts($session)) {



                    $temp = @unserialize($session->session_value);



                    if (isset($temp['attribute_values']) && have_posts($temp['attribute_values'])) {



                        $attribute = [];



                        foreach ($temp['attribute_values'] as $key => $value) {

                            $attribute[$key] = $value;

                        }



                        $metaBox = [];



                        foreach ($attribute as $key => &$option) {



                            $metaBox[$key] = (object)Attributes::get($key);



                            $metaBox[$key]->items = Attributes::getsItem(Qr::set()->whereIn('id', $option));

                        }

                    } else {



                        $result['data'] = notice('error', 'Vui lòng chọn chủng loại cho thuộc tính sau đó bấm lưu để có thể tạo các biến thể');



                        echo json_encode($result);



                        return false;

                    }



                    $id = $session_id;

                }



                $img = [];

            }

            else {

                $metaBox = Attributes::getsByProduct($id);

                $img = Product::getMeta($id, 'variations_img', true);

            }

            if (isset($metaBox) && have_posts($metaBox)) {

                $result['data'] = cart_include('admin/product/views/html-product-tab-option-img-item', ['metaBox' => $metaBox, 'id' => $id, 'img' => $img], true);

            }

        }

        echo json_encode($result);



        return false;

    }

}

Class Admin_Product_Table_Ajax {

    static public function priceSave($ci, $model) {

        $result['status'] 	= 'error';

        $result['message'] 	= 'Cập nhật dữ liệu thất bại';

        if(Request::post('productPrice')) {



            $id = Request::post('id');



            $product = Product::get($id);



            if(have_posts($product)) {



                $productPrice = Request::post('productPrice');



                $defaultId = Product::getMeta( $product->id, 'default', true);



                foreach ($productPrice as $id => $dataPrice) {

                    Product::insert(['id' => $id, 'price' => $dataPrice['price'], 'price_sale' => $dataPrice['price_sale']]);

                    if(!empty($defaultId) && $defaultId == $id) {

                        Product::insert(['id' => $product->id, 'price' => $dataPrice['price'], 'price_sale' => $dataPrice['price_sale']]);

                    }

                }



                $result['status']   = 'success';



                $result['message']  = 'Cập nhật dữ liệu thành công';



                $result['data']     = $productPrice;

            }



        }

        echo json_encode($result);

    }

}

/**

 * =====================================================================================================================

 * THUỘC TÍNH SẢN PHẨM

 * =====================================================================================================================

 */

Ajax::admin('Admin_Product_Options_Detail_Ajax::addAttribute');

Ajax::admin('Admin_Product_Options_Detail_Ajax::loadAttribute');

Ajax::admin('Admin_Product_Options_Detail_Ajax::saveAttribute');

Ajax::admin('Admin_Product_Options_Detail_Ajax::deleteAttribute');

/**

 * =====================================================================================================================

 * SẢN PHẨM BIẾN THỂ

 * =====================================================================================================================

 */

Ajax::admin('Admin_Product_Options_Detail_Ajax::loadVariations');

Ajax::admin('Admin_Product_Options_Detail_Ajax::addVariations');

Ajax::admin('Admin_Product_Options_Detail_Ajax::saveVariations');

Ajax::admin('Admin_Product_Options_Detail_Ajax::deleteVariations');

Ajax::admin('Admin_Product_Options_Detail_Ajax::loadOptionImage');

Ajax::admin('Admin_Product_Table_Ajax::priceSave');