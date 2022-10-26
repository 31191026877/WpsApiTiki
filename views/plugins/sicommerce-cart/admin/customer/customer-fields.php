<?php
function customer_fields() {

	$fields['firstname'] = array(
		'field' => 'firstname',
		'label' => __('Họ', 'cart_firstname'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 0,
        'args' => array( 'placeholder' => __('Nhập họ.','cart_dienhotencuaban') ),
        'after' => '<div class="col-md-6"><div class="group">',
        'before' => '</div></div>',
    );
    
    $fields['lastname'] = array(
		'field' => 'lastname',
		'label' => __('Tên', 'cart_lastname'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 0,
        'args' => array( 'placeholder' => __('Nhập tên.','cart_dienhotencuaban') ),
        'after' => '<div class="col-md-6"><div class="group">',
        'before' => '</div></div>',
	);

	$fields['email'] = array(
		'field' => 'email',
		'label' => 'Email',
		'type'  => 'email',
		'value' => '',
		'rules' => 'trim|required|email',
		'priority' => 10,
		'args' => array( 'placeholder' => __('Địa chỉ email của bạn.','cart_diachiemailcuaban') )
	);

	$fields['phone'] = array(
		'field' => 'phone',
		'label' => __('Số điện thoại', 'cart_phone'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 20,
		'args' => array( 'placeholder' => __('Điện thoại liên lạc với bạn.','cart_dienthoailienlacvoiban') )
	);

	$fields['address'] = array(
		'field' => 'address',
		'label' => __('Địa chỉ', 'cart_address'),
		'type'  => 'text',
		'value' => '',
		'rules' => 'trim|required',
		'priority' => 30,
		'metadata' => true,
		'args' => array( 'placeholder' => __('Địa chỉ của bạn.','cart_diachicuaban') )
	);

	$states[] 	= 'Chọn tỉnh thành';

	$states 	= array_merge( $states, Cart_Location::cities());

	$fields['city'] = array(
		'field' => 'city',
		'label' => 'Tỉnh / Thành Phố',
		'type'  => 'select',
		'rules' => 'trim',
		'options' => $states,
		'priority' => 40,
		'metadata' => true,
	);

	$fields['districts'] = array(
		'field' => 'districts',
		'label' => 'Quận Huyện',
		'type'  => 'select',
		'rules' => 'trim',
		'options' => [],
		'priority' => 50,
		'metadata' => true,
	);

	return apply_filters( 'customer_fields', $fields );
}