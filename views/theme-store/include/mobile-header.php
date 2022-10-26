<?php echo Option::get('body_script'); ?>
<!-- MOBILE -->
<div class="header-mobile hidden-md hidden-lg">
	<div class="container">
		<div class="row">
			<div class="col-3">
				<a href="#menu" class="btn-menu-mobile"><i class="fal fa-bars"></i></a>
			</div>
			<div class="col-6 logo text-center">
				<a href="<?php echo Url::base(); ?>" title="<?php echo option::get('general_label'); ?>">
					<?php Template::img(option::get('logo_header'), option::get('general_label')); ?>
				</a>
			</div>
			<div class="col-3 flexList">
				<a class="js_btn_panel__sidebar btn-search-mobile" href="#search-sidebar"><i class="fal fa-search"></i></a>
				<div class="group-account">

					<?php if (!Auth::check()) { ?>

						<span class="account-icon"><i class="fal fa-user"></i></span>

						<!-- <span class="account-name"></span> <i class="fal fa-chevron-down"></i> -->

						<div class="account-popup">

							<a class="btn btn-effect-default btn-theme" href="<?php echo Url::login(); ?>"><?php echo __('Đăng nhập'); ?></a>

							<a class="btn btn-effect-default btn-theme" href="<?php echo Url::register(); ?>"><?php echo __('Đăng ký'); ?></a>

						</div>

					<?php } else { ?>

						<?php $user = Auth::user(); ?>

						<span class="account-icon"><i class="fas fa-user-plus"></i></span>

						<!-- <span class="account-name">Xin chào, <?php echo (!empty($user->lastname)) ? ' ' . $user->lastname : ''; ?> </span> -->

						<div class="account-popup">

							<a class="btn btn-effect-default btn_account" href="<?php echo my_account_url(); ?>">Tài khoản</a>

							<!-- <?php if (class_exists('sicommerce_cart')) { ?>

								<a class="btn btn-effect-default btn-theme" href="<?php echo my_account_url('order/history'); ?>">Đơn hàng</a>

							<?php } ?> -->

							<a class="btn btn-effect-default btn-logout" href="<?php echo Url::logout(); ?>"><?php echo __('Đăng xuât'); ?></a>

						</div>

					<?php } ?>

				</div>

				<a href="gio-hang" class="btn-cart-top">

					<?php Template::img(Option::get('header_icon_cart'), 'Giỏ hàng'); ?>

					<span class="wcmc-total-items"><?= (class_exists('SCart')) ? SCart::totalQty() : 0; ?></span>

				</a>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<!-- <div class="search">
		<form class="navbar-form form-search" action="search" method="get" role="search" style="margin:0;padding:0;">
			<div class="form-group" style="margin:0;padding:0;width: calc( 100% - 50px);float:left;">
				<input class="form-control search-field" type="text" value="" name="keyword" placeholder="<?php echo __('Tìm kiếm', 'theme_timkiem'); ?>" style="width: 100%;">
				<input type="hidden" value="products" name="type">
			</div>
			<button type="submit" class="btn btn-search btn-default" style="width:50px;float:left;"><i class="fa fa-search" aria-hidden="true"></i></button>
		</form>
	</div> -->
</div>
<style>
	.header-mobile .btn-cart-top {

		margin-top: 0;
		position: relative;
		display: inline-block;

	}

	.header-mobile .btn-cart-top img {

		max-width: 19px;

	}
	.header-mobile .flexList{
		display: flex;
		align-items: center;
	}

	.header-mobile .btn-cart-top span.wcmc-total-items {
		display: block;
		position: absolute;
		top: -10px;
		right: -10px;
		width: 20px;
		height: 20px;
		border-radius: 50%;
		line-height: 20px;
		text-align: center;
		background-color: #ef4562;
		color: #fff;
	}
	.header-mobile .btn-search-mobile{
		font-size: 19px !important;
		margin-right: 5px;
	}

	.header-mobile .group-account {

		overflow: hidden;

		line-height: 50px;

		cursor: pointer;

		display: flex;

		align-items: center;

	}

	.header-mobile .group-account i {

		width: fit-content;
		margin-right: 10px;

	}

	.header-mobile .group-account span {

		font-size: 15px;

		color: #000;

		display: -webkit-box;

		-webkit-line-clamp: 1;

		-webkit-box-orient: vertical;

		overflow: hidden;

	}

	.header-mobile .group-account .account-popup {

		display: none;

		position: absolute;

		top: 60px;

		right: 1px;

		border: none;

		margin: 0;

		padding: 17px;

		z-index: 999;

		min-width: 100px;

		background-color: #fff;
		border: 1px solid var(--theme-color);

		box-shadow: 0px 17px 10px 0px rgba(81, 81, 81, 0.23);

		border-radius: 5px;

	}

	.header-mobile .group-account .account-popup:before {

		border: 12px solid transparent;

		border-bottom: 12px solid var(--theme-color);

		bottom: 100%;

		right: 46px;

		content: " ";

		height: 0;

		width: 0;

		position: absolute;

		pointer-events: none;

	}

	.header-mobile .group-account .account-popup:after {

		top: -20px;

		right: 0;

		content: " ";

		height: 20px;

		width: 100%;

		position: absolute;

	}

	.header-mobile .group-account .account-popup .btn-logout::before {
		background-color: #fff;

	}

	.header-mobile .group-account .account-popup .btn-logout {
		color: #000;
		border: solid 1px #ebebeb;
	}

	.header-mobile .group-account .account-popup .btn-logout:hover {
		background-color: var(--theme-color);
		color: #fff;
	}

	.header-mobile .group-account .account-popup .btn_account::before {
		background: #91ad41;

		background-image: -webkit-linear-gradient(35deg, #91ad41 0%, #ff8a6c 100%) !important;
	}


	.header-mobile .group-account .account-popup a {

		display: block;

		font-size: 15px;

		text-align: center;

		margin-bottom: 10px;
		text-transform: unset;
		border-radius: 25px;
		color: #fff;
		border: none;
		padding: 5px 11px;

	}

	.header-mobile .group-account .account-popup a:hover {
		background: #fff;
		color: var(--theme-color);
		border: solid 1px var(--theme-color);
	}

	.header-mobile .group-account .account-popup a:last-child {
		margin-bottom: 0;
	}

	.header-mobile .group-account .account-popup .btn_account:hover {
		background-color: var(--theme-color);
		color: #fff;
	}

	.header-mobile .group-account:hover .account-popup {

		display: block;

	}
</style>
<?php
$mobile_category_icon = Option::get('mobile_category_icon');
if (!empty($mobile_category_icon)) {
	Template::partial('include/mobile-category-icon');
}
Template::partial('include/mobile-search');
Template::partial('include/mobile-menu');
if (class_exists('sicommerce_cart')) {
	Template::partial('include/mobile-cart');
	Template::partial('include/mobile-navigation');
}
