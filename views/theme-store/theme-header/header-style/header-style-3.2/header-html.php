<!-- header content -->

<div class="header-content">

	<div class="container">

		<div class="row row-flex-center">

			<div class="col-md-3 text-<?php echo option::get('logo_position'); ?> logo">

				<?php if (is_home()) { ?>

					<h1 style="display: none"><?php echo option::get('general_label'); ?></h1>

				<?php } ?>

				<a href="<?php echo Url::base(); ?>" title="<?php echo option::get('general_label'); ?>">

					<?php Template::img($logo, option::get('general_label')); ?>

				</a>

			</div>

			<div class="col-md-7">

				<?php do_action('cle_header_navigation'); ?>

			</div>

			<div class="col-md-2 text-center row-flex-center" style="justify-content: right">

				<a class="btn-search js_btn_panel__sidebar" href="#search-sidebar"><i class="fal fa-search"></i></a>
				<div class="group-account" style="justify-content: right">

					<?php if (!Auth::check()) { ?>

						<span class="account-icon"><i class="fal fa-user"></i></span>

						<!-- <span class="account-name">Tài khoản</span> <i class="fal fa-chevron-down"></i> -->

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

					<?php Template::img($iconCart, 'Giỏ hàng'); ?>

					<span class="wcmc-total-items"><?= (class_exists('SCart')) ? SCart::totalQty() : 0; ?></span>

				</a>
				

			</div>

		</div>

	</div>

</div>