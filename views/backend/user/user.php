<?php
function admin_user_profile( $user, $model ) {

	$ci =& get_instance();

	$user_current 	= Auth::user();

	$Form = new FormBuilder();

    $Form->add('', 'html', '<div class="row">')
        ->add('firstname', 'text', ['placeholder' => 'Họ', 'after' => '<div class="form-group col-md-6"><label for="">Họ</label>', 'before' => '</div>'], $user->firstname)
        ->add('lastname', 'text', ['placeholder' => 'Tên', 'after' => '<div class="form-group col-md-6"><label for="">Tên</label>', 'before' => '</div>'], $user->lastname)
        ->add('', 'html', '</div>');

    $Form->add('', 'html', '<div class="row">')
        ->add('email', 'email', ['placeholder' => 'Email', 'after' => '<div class="form-group col-md-6"><label for="">Email</label>', 'before' => '</div>'], $user->email)
        ->add('phone', 'tel', ['placeholder' => 'Điện thoại', 'after' => '<div class="form-group col-md-6"><label for="">Điện thoại</label>', 'before' => '</div>'], $user->phone)
        ->add('', 'html', '</div>');

    $Form->add('', 'html', '<div class="row">')
        ->add('address', 'text', ['label' => 'Địa chỉ', 'placeholder' => 'Địa chỉ'], User::getMeta($user->id, 'address', true))
        ->add('', 'html', '</div>');


    $Form = apply_filters('admin_user_profile_form', $Form);

	include 'html/user-profile.php';
}

function admin_user_password( $user, $model ) {

	$ci =& get_instance();

	include 'html/user-password.php';
}

if(!empty($_SESSION['user_after'])) {

    function login_as_bar() {

        $users_login_as = User::gets(Qr::set('username', '<>', ''));

        $user_current   = Auth::user();

        include 'html/user-login-as-bar.php';
    }

    add_action('cle_footer', 'login_as_bar');
}