<?php
//================================ search customer ===========================================//
function popover_order_user_search($object, $keyword) {

    $object = User::gets(Qr::set('CONCAT( firstname,  " ", lastname )', 'like', $keyword));
	return $object;
}
add_filter('input_popover_order_search_user_search', 'popover_order_user_search', 10, 2);

function popover_order_user_search_template($str, $item, $active) {
    return popover_order_user_template_item($item, $active);
}
add_filter('input_popover_order_search_user_search_template', 'popover_order_user_search_template', 10, 3);

function popover_order_user_template_item($item, $active = '') {
    $str = '';
    $image      = 'https://bizweb.dktcdn.net/assets/admin/images/create_customer.svg';
    $fullname   = 'Thêm mới khách hàng';
    $email      = '';
    $id         = 0;
    if( have_posts($item)) {
        $item->image = 'https://i0.wp.com/bizweb.dktcdn.net/assets/images/customper-noavatar.png?ssl=1';
        $image      = $item->image;
        $fullname   = $item->firstname.' '.$item->lastname;
        $email      = $item->email;
        $id         = $item->id;
    }
    $str .= '
    <li class="option option-'.$id.' '.$active.'" data-key="'.$id.'" data-customer="'.htmlentities(json_encode($item)).'">
        <div class="item-us">
            <div class="item-us__img">
                <img src="'.$image.'">
            </div>
            <div class="item-us__title">
                <span>'.$fullname.'</span>
                <p>'.$email.'</p>
            </div>
        </div>
    </li>';
    return $str;
}