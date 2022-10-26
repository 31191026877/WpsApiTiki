<?php
class AdminAjaxSlider {
    static function add($ci, $model): void {

        $result['message'] 	= 'Thêm mới slider không thành công!';

        $result['status'] 	= 'error';

        if(Request::Post()) {

            $name = Request::Post('name');

            $type = Request::Post('type');

            $data = [
                'name'          => $name,
                'options'       => $type,
                'object_type'   => 'slider'
            ];

            $id = Gallery::insert($data);

            if(!is_skd_error($id)) {

                $result['status'] 	= 'success';

                $result['message'] 	= 'Thêm slider thành công!';
            }
        }

        echo json_encode($result);
    }
    static function delete($ci, $model): void {

        $result['message'] 	= 'Xóa slider không thành công!';
        $result['status'] 	= 'error';

        if(Request::Post()) {

            $id = Request::Post('id');

            $id = Gallery::delete($id);

            if(!is_skd_error($id)) {
                $result['status'] 	= 'success';
                $result['message'] 	= 'Xóa slider thành công!';
            }
        }

        echo json_encode($result);
    }
    static function optionsLoad($ci, $model) {

        $result['message'] 	= 'Load dữ liệu không thành công!';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $id = (int)Request::post('id');

            $slider = Gallery::get(Qr::set($id)->where('object_type', 'slider'));

            if(have_posts($slider)) {
                $sliderType = Slider::list($slider->options);
                if(empty($sliderType)) {
                    $result['message'] 	= 'Loại slider không tồn tại';
                    echo json_encode($result);
                    return false;
                }
                ob_start();
                $sliderType['class']::optionsForm($slider);
                $result['data'] = ob_get_contents();
                ob_clean();
                $result['status'] 	= 'success';
                $result['message'] 	= 'Cập nhật thành công';
            }
        }
        echo json_encode($result);
    }
    static function optionsSave($ci, $model) {

        $result['message'] 	= 'Lưu dữ liệu không thành công!';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $id = (int)Request::post('id');

            $slider = Gallery::get(Qr::set($id)->where('object_type', 'slider'));

            if(empty($slider)) {
                $result['message'] 	= 'slider không tồn tại';
                echo json_encode($result);
                return false;
            }

            $sliderType = Slider::list($slider->options);

            if(empty($sliderType)) {
                $result['message'] 	= 'Loại slider không tồn tại';
                echo json_encode($result);
                return false;
            }

            $error = $sliderType['class']::optionsSave($slider);

            if(!is_skd_error($error)) {

                CacheHandler::delete('gallery_', true);

                $result['status'] 	= 'success';

                $result['message'] 	= 'Cập nhật thành công';
            }
        }

        echo json_encode($result);
    }
    static function itemAdd($ci, $model) {

        $result['message'] 	= 'Thêm dữ liệu không thành công!';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $post = Request::post();

            if(have_posts($post)) {

                //Lấy id slider
                $id = (int)Request::post('sliderId');

                $gallery = Gallery::get(Qr::set($id)->where('object_type', 'slider'));

                if(have_posts($gallery)) {

                    $id = $model->settable('galleries')->add([
                        'group_id'      => $id,
                        'object_type'   => 'slider'
                    ]);

                    if($id) {
                        CacheHandler::delete('gallery_', true);
                        $result['status'] 	= 'success';
                        $result['id'] 		= $id;
                        $result['message'] 	= 'Cập nhật thành công';
                    }
                }
            }
        }
        echo json_encode($result);
    }
    static function itemLoad($ci, $model) {

        $result['message'] 	= 'Load dữ liệu không thành công!';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $id = (int)Request::post('id');

            $sliderType = Request::post('sliderType');

            $slider = Slider::list($sliderType);

            if(empty($slider)) {
                $result['message'] 	= 'Loại slider không tồn tại';
                echo json_encode($result);
                return false;
            }

            $item = Gallery::getItem($id);

            if(have_posts($item)) {
                ob_start();
                $slider['class']::itemForm($item);
                $result['data'] = ob_get_contents();
                ob_clean();
                $result['status'] 	= 'success';
                $result['message'] 	= 'Cập nhật thành công';
            }
        }
        echo json_encode($result);
    }
    static function itemSave($ci, $model) {

        $result['message'] 	= 'Lưu dữ liệu không thành công!';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $id = (int)Request::post('id');

            $sliderType = Request::post('type');

            $slider = Slider::list($sliderType);

            if(empty($slider)) {
                $result['message'] 	= 'Loại slider không tồn tại';
                echo json_encode($result);
                return false;
            }

            $item = Gallery::getItem($id);

            if(have_posts($item)) {

                $error = $slider['class']::itemSave($item);

                if(!is_skd_error($error)) {

                    CacheHandler::delete('gallery_', true);

                    $result['status'] 	= 'success';

                    $result['message'] 	= 'Cập nhật thành công';
                }
            }
        }

        echo json_encode($result);
    }
    static function itemSort($ci, $model) {

        $result['message'] 	= 'Lưu dữ liệu không thành công!';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $slider_item_order = Request::post('slider_item_order', ['type' => 'int']);

            if(have_posts($slider_item_order)) {

                $model->settable('galleries');

                foreach ($slider_item_order as $id => $order) {
                    $model->update(['order' => $order], Qr::set($id));
                }

                CacheHandler::delete('gallery_', true);

                $result['status'] 	= 'success';

                $result['message'] 	= 'Cập nhật thành công';
            }
        }
        echo json_encode($result);
    }
    static function itemDelete($ci, $model) {

        $result['message'] 	= 'Lưu dữ liệu không thành công!';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $id = (int)Request::post('id');

            $item = Gallery::getItem($id);

            if(have_posts($item)) {

                $error = Gallery::deleteItem($id);

                if(!is_skd_error($error)) {

                    CacheHandler::delete('gallery_', true);

                    $result['status'] 	= 'success';

                    $result['message'] 	= 'Cập nhật thành công';
                }
            }
        }
        echo json_encode($result);
    }
}
Ajax::admin('AdminAjaxSlider::add');
Ajax::admin('AdminAjaxSlider::delete');
Ajax::admin('AdminAjaxSlider::optionsLoad');
Ajax::admin('AdminAjaxSlider::optionsSave');
Ajax::admin('AdminAjaxSlider::itemAdd');
Ajax::admin('AdminAjaxSlider::itemLoad');
Ajax::admin('AdminAjaxSlider::itemSave');
Ajax::admin('AdminAjaxSlider::itemSort');
Ajax::admin('AdminAjaxSlider::itemDelete');