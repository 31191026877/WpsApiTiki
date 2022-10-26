<?php
Class ThemeAjax {
    static function productSearch( $ci, $model ) {

        $result['message'] 	= 'Không tìm thấy.';

        $result['status'] 	= 'error';

        if(Request::post()) {

            $keyword = Str::clear(Request::post('keyword'));

            $objects =  Product::gets(Qr::set('public', 1)
                ->where('trash', 0)
                ->where('title', 'like', '%'.$keyword.'%')
                ->limit(5));

            if( have_posts($objects) ) {
                $result['status'] 	= 'success';
                $result['data'] = '<div class="product-slider-vertical">';
                foreach ($objects as $object) {
                    $result['data'] .= scmc_template('loop/item_product_vertical', array('val' => $object), true );
                }
                $result['data'] .= '</div>';
            }
            else {

                $result['data'] = '<div class="result-msg no-result">Không có kết quả tìm kiếm</div>';
            }

        }

        echo json_encode($result);
    }
    static function contactSend($ci, $model) {

        $result['message'] 	= 'Gửi thông tin không thành công.';

        $result['status'] 	= 'error';

        if( Request::post()) {

            $name       = trim(Request::Post('name'));
            if(empty($name)) {
                $result['message'] 	= __('Họ tên không được để trống');
                echo json_encode($result);
                return false;
            }

            $email      = trim(Request::Post('email'));
            if(empty($email)) {
                $result['message'] 	= __('Email không được để trống');
                echo json_encode($result);
                return false;
            }

            $phone      = trim(Request::Post('phone'));
            if(empty($phone)) {
                $result['message'] 	= __('Ghi chú không được để trống');
                echo json_encode($result);
                return false;
            }

            $content    = trim(Request::Post('content'));

            $template = '
            <p>Họ tên: <strong>'.$name.'</strong></p>
			<p>Email: <strong>'.$email.'</strong></p>
			<p>Phone: <strong>'.$phone.'</strong></p>
			<p>Ghi chú: <strong>'.$content.'</strong></p>
        ';

            $error = EmailHandler::send($template, $name.' đã yêu cầu liên hệ từ '.Url::base(), [
                'name' => $name,
                'from' => Option::get('contact_mail'),
                'address'   => Option::get('contact_mail'),
            ]);

            if($error) {
                $result['message'] 	= 'Gửi thông tin liên hệ thành công.';
                $result['status'] 	= 'success';
            }
        }

        echo json_encode($result);

        return false;
    }
}
Ajax::client('ThemeAjax::productSearch');
Ajax::client('ThemeAjax::contactSend');
