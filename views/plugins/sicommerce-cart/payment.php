<?php
function register_payment_gateways($gateways, $config) {
    $gateways['bacs'] = [
        'label'         => 'Chuyển khoản ngân hàng',
        'icon'          => Url::base(CART_PATH.'assets/images/bank.png'),
        'description'   => 'Thanh toán qua hình thức chuyển khoản ngân hàng',
        'class'         => 'Payment_Bacs',
        'bank'          => (!empty($config['bacs']['bank'])) ? $config['bacs']['bank'] : []
    ];
    $gateways['cod'] = [
        'label'         => 'Trả tiền khi nhận hàng',
        'icon'          => Url::base(CART_PATH.'assets/images/cod.png'),
        'description'   => 'Quý khách sẽ thanh toán bằng tiền mặt khi shipper giao hàng cho quý khách',
        'class'         => 'Payment_Cod'
    ];
    return $gateways;
}
add_filter('payment_gateways', 'register_payment_gateways', 10, 2);

Class Payment_Bacs {
    static public function config($result) {

        $data = Request::post();

        $payments = option::get('payments', []);

        if(!have_posts($payments)) $payments = [];

        $payments['bacs']['enabled'] = (empty(Request::post('bacs[enabled]'))) ? 0 : 1;

        $payments['bacs']['title'] = Request::post('bacs[title]');

        $payments['bacs']['description'] = Request::post('bacs[description]');

        if(Language::hasMulti()) {
            foreach (Language::list() as $language_key => $language) {
                if($language_key == Language::default()) continue;
                $payments['bacs']['title_'.$language_key] = Request::post('bacs[title_'.$language_key.']');
                $payments['bacs']['description_'.$language_key] = Request::post('bacs[description_'.$language_key.']');
            }
        }

        $payments['bacs']['img']    = FileHandler::handlingUrl(Request::post('bacs[img]'));

        $banks = Request::post('bacs_account_name');

        $payments['bacs']['bank'] = [];

        //ngân hàng
        if(have_posts($banks)) {
            foreach ($banks as $key => $name) {
                if(empty($name) ) continue;
                $payments['bacs']['bank'][$key]['bacs_account_name']   = $name;
                $payments['bacs']['bank'][$key]['bacs_account_number'] = $data['bacs_account_number'][$key];
                $payments['bacs']['bank'][$key]['bacs_bank_name']      = $data['bacs_bank_name'][$key];
                $payments['bacs']['bank'][$key]['bacs_bank_branch']    = $data['bacs_bank_branch'][$key];
            }
        }

        if(have_posts($payments['bacs'])) {
            option::update( 'payments', $payments );
        }

        return $result;
    }
    static public function form($key, $payment) {
        include_once 'admin/setting/views/html-payment-bacs.php';
    }
    static public function process($order, $result) {
        return $result;
    }
    static public function webhook($order) {
    }
}

Class Payment_Cod {
    static public function config($result) {

        $payments = option::get('payments', []);

        if(!have_posts($payments)) $payments = [];

        $payments['cod']['enabled'] = (empty(Request::post('cod[enabled]'))) ? 0 : 1;

        $payments['cod']['title']   = Request::post('cod[title]');

        $payments['cod']['description'] = Request::post('cod[description]');

        if(Language::hasMulti()) {
            foreach (Language::list() as $language_key => $language) {
                if($language_key == Language::default()) continue;
                $payments['cod']['title_'.$language_key] = Request::post('cod[title_'.$language_key.']');
                $payments['cod']['description_'.$language_key] = Request::post('cod[description_'.$language_key.']');
            }
        }

        $payments['cod']['img']     = FileHandler::handlingUrl(Request::post('cod[img]'));

        if(have_posts($payments['cod'])) {
            option::update( 'payments', $payments );
        }

        return $result;
    }
    static public function form($key, $payment) {
        include_once 'admin/setting/views/html-payment-cod.php';
    }
    static public function process($order, $result) {
        return $result;
    }
    static public function webhook($order) {}
}