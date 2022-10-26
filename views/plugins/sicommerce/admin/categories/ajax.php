<?php
Class Product_Category_Admin_Ajax {
    static public function quickSave( $ci, $model ) {

        $result['status']  = 'error';

        $result['message'] = __('Lưu dữ liệu không thành công');

        if(Request::post()) {

            $data = Request::post('data');

            $parent = (int)Request::post('parent');

            $data = explode("\n", $data);

            if(!empty($parent) && ProductCategory::count($parent) == 0) {
                $result['message'] = __('Danh mục cha không tồn tại vui lòng kiểm tra lại.');
                echo json_encode($result);
                return true;
            }

            if(have_posts($data)) {
                foreach ($data as $key => $datum) {
                    $datum = trim(Str::clear($datum));
                    if(empty($datum)) { unset($data[$key]); continue; }
                    $data[$key] = $datum;
                }
            }

            if(have_posts($data)) {
                foreach ($data as $key => $datum) {
                    $category = ['name' => $datum, 'parent_id' => $parent];
                    ProductCategory::insert($category);
                }

                $result['status']  = 'success';

                $result['message'] = __('Lưu dữ liệu thành công');
            }
            else {
                $result['message'] = __('Không có dữ liệu để lưu');
            }
        }

        echo json_encode($result);

        return true;
    }
}
Ajax::admin('Product_Category_Admin_Ajax::quickSave');