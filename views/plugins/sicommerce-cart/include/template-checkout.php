<?php
/** FIELD : ******************************************************************/
function get_checkout_fields_billing() {

    $prefix = 'billing_';

    $fields[$prefix . 'fullname'] = array(
        'field' => $prefix . 'fullname',
        'label' => __('Họ và tên', 'cart_fullname'),
        'label_error' => __('Họ và tên', 'cart_fullname'),
        'type' => 'text',
        'value' => '',
        'rules' => 'trim|required',
        'priority' => 0,
        'args' => array('placeholder' => __('Điền họ tên của bạn.', 'cart_dienhotencuaban'))
    );

    $fields[$prefix . 'email'] = array(
        'field' => $prefix . 'email',
        'label' => 'Email',
        'label_error' => 'Email',
        'type' => 'email',
        'value' => '',
        'rules' => 'trim|required',
        'priority' => 10,
        'args' => array('placeholder' => __('Địa chỉ email của bạn.', 'cart_diachiemailcuaban'))
    );

    $fields[$prefix . 'phone'] = array(
        'field' => $prefix . 'phone',
        'label' => __('Số điện thoại', 'cart_phone'),
        'label_error' => __('Số điện thoại', 'cart_phone'),
        'type' => 'text',
        'value' => '',
        'rules' => 'trim|required',
        'priority' => 20,
        'args' => array('placeholder' => __('Điện thoại liên lạc với bạn.', 'cart_dienthoailienlacvoiban'))
    );

    $fields[$prefix . 'address'] = array(
        'field' => $prefix . 'address',
        'label' => __('Địa chỉ', 'cart_address'),
        'label_error' => __('Địa chỉ', 'cart_address'),
        'type' => 'text',
        'value' => '',
        'rules' => 'trim|required',
        'priority' => 30,
        'args' => array('placeholder' => __('Địa chỉ của bạn.', 'cart_diachicuaban'))
    );

    $states[''] = 'Chọn tỉnh thành';

    $states = array_merge($states, Cart_Location::cities());

    $fields[$prefix . 'city'] = array(
        'field' => $prefix . 'city',
        'label' => __('Tỉnh / Thành Phố', 'checkout_city'),
        'type' => 'select',
        'rules' => 'trim|required',
        'options' => $states,
        'priority' => 40,
    );

    $fields[$prefix . 'districts'] = array(
        'field' => $prefix . 'districts',
        'label' => __('Quận Huyện', 'checkout_district'),
        'type' => 'select',
        'rules' => 'trim|required',
        'options' => [],
        'priority' => 50,
    );

	 $fields[$prefix.'ward'] = array(
	 	'field' => $prefix.'ward',
	 	'label' => __('Phường xã', 'checkout_ward'),
	 	'type'  => 'select',
	 	'rules' => 'trim|required',
	 	'options' => [],
	 	'priority' => 60,
	 );

    if(!Admin::is() && Auth::check()) {
        $user = Auth::user();
        if (have_posts($user)) {
            $fields[$prefix . 'fullname']['value'] = $user->firstname . ' ' . $user->lastname;
            $fields[$prefix . 'email']['value'] = $user->email;
            $fields[$prefix . 'phone']['value'] = $user->phone;
            $fields[$prefix . 'address']['value'] = User::getMeta($user->id, 'address', true);
        }
    }

    $fields = apply_filters('billing_fields', $fields); //ver 2.7.5

    return apply_filters('billing_fields', $fields);
}

function get_checkout_fields_shipping() {

    $prefix = 'shipping_';

    $fields[$prefix . 'fullname'] = array(
        'field' => $prefix . 'fullname',
        'label' => __('Họ và tên', 'cart_fullname'),
        'type' => 'text',
        'value' => '',
        'priority' => 0,
    );

    $fields[$prefix . 'email'] = array(
        'field' => $prefix . 'email',
        'label' => 'Email',
        'type' => 'email',
        'value' => '',
        'priority' => 10,
    );

    $fields[$prefix . 'phone'] = array(
        'field' => $prefix . 'phone',
        'label' => __('Số điện thoại', 'cart_phone'),
        'type' => 'text',
        'value' => '',
        'priority' => 20,
    );

    $fields[$prefix . 'address'] = array(
        'field' => $prefix . 'address',
        'label' => __('Địa chỉ', 'cart_address'),
        'type' => 'text',
        'value' => '',
        'priority' => 30,
    );

    $states[] = 'Chọn tỉnh thành';

    $states = array_merge($states, Cart_Location::cities());

    $fields[$prefix . 'city'] = array(
        'field' => $prefix . 'city',
        'label' => __('Tỉnh / Thành Phố', 'checkout_city'),
        'type' => 'select',
        'rules' => 'trim',
        'options' => $states,
        'priority' => 40,
    );

    $fields[$prefix . 'districts'] = array(
        'field' => $prefix . 'districts',
        'label' => __('Quận Huyện', 'checkout_district'),
        'type' => 'select',
        'rules' => 'trim',
        'options' => [],
        'priority' => 50,
    );

    $fields[$prefix.'ward'] = array(
     	'field' => $prefix.'ward',
     	'label' => __('Phường xã', 'checkout_ward'),
     	'type'  => 'select',
     	'rules' => 'trim',
     	'options' => [],
     	'priority' => 60,
     );

    return apply_filters('shipping_fields', $fields);
}

function get_checkout_fields_order() {

    $prefix = 'order_';

    $fields[$prefix . 'note'] = array(
        'field' => $prefix . 'note',
        'label' => __('Ghi chú', 'cart_note'),
        'type' => 'textarea',
        'value' => '',
        'rules' => 'trim',
    );

    return apply_filters('woocommerce_order_fields', $fields);
}

function get_checkout_fields() {

    $fields = [];

    $fields['billing'] = get_checkout_fields_billing();

    $fields['shipping'] = get_checkout_fields_shipping();

    $fields['order'] = get_checkout_fields_order();

    $fields = apply_filters('checkout_fields', $fields); //ver 2.7.5

    $fields = apply_filters('woocommerce_checkout_fields', $fields);

    //sort billing

    $priority = [];

    foreach ($fields['billing'] as $key => $value) {
        if (isset($value['priority'])) $priority[$value['priority']][$key] = $value;
        else $priority[1000][$key] = $value;
    }

    ksort($priority);

    $fields['billing'] = [];

    foreach ($priority as $value) {
        foreach ($value as $key => $val) {
            $fields['billing'][$key] = $val;
        }
    }

    //sort shipping
    $priority = [];

    foreach ($fields['shipping'] as $key => $value) {
        if (isset($value['priority'])) $priority[$value['priority']][$key] = $value;
        else $priority[1000][$key] = $value;
    }

    ksort($priority);

    $fields['shipping'] = [];

    foreach ($priority as $value) {

        foreach ($value as $key => $val) {
            $fields['shipping'][$key] = $val;
        }
    }

    return $fields;
}

function checkout_fields_rules() {

    $billings = get_checkout_fields_billing();

    $shippings = get_checkout_fields_shipping();

    $rules = [
        'billings' => [],
        'shippings' => [],
    ];

    foreach ($billings as $key => $value) {
        if (!empty($value['rules'])) {
            $rules['billings'][$key] = ['field' => $key, 'label' => (isset($value['label_error'])) ? $value['label_error'] : $value['label'], 'rules' => $value['rules']];
        }
    }

    $rules['shippings']['shipping_fullname'] = [
        'field' => 'shipping_fullname',
        'label' => 'họ và tên người nhận',
        'rules' => 'required',
    ];

    $rules['shippings']['shipping_email'] = [
        'field' => 'shipping_email',
        'label' => 'email người nhận',
        'rules' => 'required',
    ];

    $rules['shippings']['shipping_phone'] = [
        'field' => 'shipping_phone',
        'label' => 'số điện thoại người nhận',
        'rules' => 'required',
    ];

    $rules['shippings']['shipping_address'] = [
        'field' => 'shipping_address',
        'label' => 'địa chỉ người nhận',
        'rules' => 'required',
    ];

    return apply_filters('checkout_fields_rules', $rules);
}

if (!Admin::is()) {

    function cart_custom_field_billing($fields) {

        foreach ($fields as $key => &$field) {

            if (empty($field['id'])) $id = $field['field'];

            $id = str_replace('[', '_', $id);

            $id = str_replace(']', '', $id);

            $field['after'] = '<div class="col-md-6 input_checkout">';

            $field['before'] = '<div class="error_message" id="error_' . $id . '"></div></div>';
        }

        return $fields;
    }
    add_filter('billing_fields', 'cart_custom_field_billing', 40);

    function cart_custom_field_shipping($fields) {

        foreach ($fields as $key => &$field) {

            if (empty($field['id'])) $id = $field['field'];

            $id = str_replace('[', '_', $id);

            $id = str_replace(']', '', $id);

            $field['after'] = '<div class="col-md-6 input_checkout">';

            $field['before'] = '<div class="error_message" id="error_' . $id . '"></div></div>';
        }

        return $fields;
    }
    add_filter('shipping_fields', 'cart_custom_field_shipping', 40);
}

/** CONTENT : ******************************************************************/
function cart_checkout_field_billing() {
	$fields = get_checkout_fields();
	cart_template('checkout/form-billing', array( 'fields' => $fields ) );
}
add_action('checkout_content', 'cart_checkout_field_billing',20);

function cart_checkout_field_shipping() {
	$fields = get_checkout_fields();
	cart_template('checkout/form-shipping', array( 'fields' => $fields ) );
}
add_action('checkout_content', 'cart_checkout_field_shipping', 30);

function cart_checkout_field_order() {
	$fields = get_checkout_fields();
	cart_template('checkout/form-order', array( 'fields' => $fields ) );
}
add_action('checkout_content', 'cart_checkout_field_order', 50);

/** CHECKOUT : Phương thức thanh toán ******************************************************************/
function cart_checkout_payment() {

	$payments = payment_gateways();

	cart_template('checkout/payment', array( 'payments' => $payments ));
}
add_action('checkout_content', 'cart_checkout_payment', 40);

function payment_cod_view( $payment ) {
    cart_template('checkout/payment_cod', array( 'payment' => $payment ) );
}
add_action('checkout_payment_cod_view', 'payment_cod_view');

function payment_bacs_view( $payment ) {
    cart_template('checkout/payment_bacs', array( 'payment' => $payment ) );
}
add_action( 'checkout_payment_bacs_view', 'payment_bacs_view');

/** CHECKOUT : SHIPPING ******************************************************************/
function cart_checkout_shipping() {

	$ci =& get_instance();

	$count 					= 0;
	
	$shipping 				= shipping_gateways();

    foreach ($shipping as $key => $ship) {
        if(empty($ship['enabled']) || $ship['enabled']  == false) {
            unset($shipping[$key]);
        }
    }

	$data_cart_checkout 	= $ci->data['wcmc_cart_checkout'];

	$shipping_default 	    = Option::get('cart_shipping_default');

	if(empty($shipping_default)) $shipping_default = key($shipping);

	$shipping_type          = (isset($data_cart_checkout['shipping_type'])) ? $data_cart_checkout['shipping_type'] : $shipping_default;

	foreach ($shipping as $key => $ship) {

	    if(empty($ship['enabled']) || $ship['enabled']  == false) continue;

		$key_temp = str_replace('-','_', $key);

        if(isset($data_cart_checkout['shipping_price_'.$key_temp])) {
            $ship['price_default'] = $data_cart_checkout['shipping_price_'.$key_temp];
        }

        $count++;
		?>
		<tr class="ship">
			<td>
				<div class="checkbox" style="margin:0;">
					<label style="padding:0;">
						<input type="radio" value="<?php echo $key;?>" name="shipping_type" <?php echo ($shipping_type == $key) ? 'checked' : '';?>>
						<?php echo $ship['title'];?>
					</label>
				</div>
			</td>
			<td>
                <strong id="ship-<?php echo $key;?>">
                    <?php echo (is_numeric($ship['price_default'])) ? Prd::price($ship['price_default']) : $ship['price_default'];?>
                </strong>
            </td>
			<?php do_action('checkout_shipping_template', $key);?>
			<?php do_action('checkout_shipping_'.$key.'_template', $ship);?>
		</tr>
	<?php }
	if($count == 1) { ?>
	<style>
	</style>
	<?php }
}
add_action( 'checkout_review_order', 'cart_checkout_shipping', 10);
