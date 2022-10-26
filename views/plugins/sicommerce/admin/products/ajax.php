<?php
Class Product_Admin_Ajax {
    static public function saveCollection($ci, $model) {

        $result['status']  = 'error';

        $result['message'] = __('Lưu dữ liệu không thành công');

        if(Request::post()) {

            $collections = Request::post('value');

            $id          = (int)Request::post('pk');

            $product = Product::get($id);

            if(have_posts($product)) {

                $product_update = ['id' => $id, 'status1' => 0, 'status2' => 0, 'status3' => 0];

                if(have_posts($collections)) {
                    foreach ($collections as $collection) {
                        $collection = removeHtmlTags($collection);
                        if ($collection == 'status1') $product_update['status1'] = 1;
                        if ($collection == 'status2') $product_update['status2'] = 1;
                        if ($collection == 'status3') $product_update['status3'] = 1;
                    }
                }

                $error = Product::insert($product_update);

                if(is_skd_error($error)) {
                    $result['status']  = 'error';
                    foreach ($error->errors as $key => $er) {
                        $result['message'] = $er;
                    }
                }
                else {
                    $result['status']  = 'success';
                    $result['message'] = __('Lưu dữ liệu thành công.');
                }
            }
        }

        echo json_encode($result);

        return true;
    }
    static public function savePublic($ci, $model) {

        $result['status']  = 'error';

        $result['message'] = __('Lưu dữ liệu không thành công');

        if(Request::post()) {

            $public = (int)Request::post('value');

            $id     = (int)Request::post('pk');

            $product = Product::get($id);

            if(have_posts($product)) {

                $product_update = ['id' => $id, 'public' => $public];

                $error = Product::insert($product_update);

                if(is_skd_error($error)) {
                    $result['status']  = 'error';
                    foreach ($error->errors as $key => $er) {
                        $result['message'] = $er;
                    }
                }
                else {
                    $result['status']  = 'success';
                    $result['message'] = __('Lưu dữ liệu thành công.');
                }
            }
        }

        echo json_encode($result);

        return true;
    }
    static public function updatePrice( $ci, $model ) {

        $result['status']  = 'error';

        $result['message'] = __('Lưu dữ liệu không thành công');

        if(Request::post()) {

            $name  = Request::post('name');

            $value = Str::price(Request::post('value'));

            $id   = (int)Request::post('pk');

            $product = Product::get($id);

            if(have_posts($product)) {

                $productDefaulID = (int)Product::getMeta($id, 'default', true);

                if(!empty($productDefaulID)) {
                    $product_update = ['id' => $productDefaulID, $name => $value];
                    Product::insert($product_update);
                }

                $product_update = ['id' => $id, $name => $value];

                $error = Product::insert($product_update);

                if(is_skd_error($error)) {
                    $result['status']  = 'error';
                    foreach ($error->errors as $key => $er) {
                        $result['message'] = $er;
                    }
                }
                else {
                    $result['status']  = 'success';
                    $result['message'] = __('Lưu dữ liệu thành công.');
                }
            }
        }

        echo json_encode($result);

        return true;
    }
}
Ajax::admin('Product_Admin_Ajax::saveCollection');
Ajax::admin('Product_Admin_Ajax::savePublic');
Ajax::admin('Product_Admin_Ajax::updatePrice');