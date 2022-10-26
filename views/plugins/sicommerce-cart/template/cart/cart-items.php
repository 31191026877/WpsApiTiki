<div class="cart__item productid-<?= $item->rowid;?>">
	<div class="cart_item__img">
		<?php Template::img($item->option['product_image'],'',array('style'=>'height:70px;'));?>
	</div>
	<div class="cart_item__quantity quantity">
		<div class="plus btn-plus"><div class="icon"><svg viewBox="0 0 100 54" data-radium="true" style="width: 100%; height: 100%;"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-123.000000, -352.000000)" fill="#4D4E4F"><path d="M221.976822,353.043478 C220.598555,351.652174 218.358871,351.652174 216.980603,353.043478 L173.048332,397.478261 L129.02992,353.043478 C127.651652,351.652174 125.411968,351.652174 124.0337,353.043478 C122.655433,354.434783 122.655433,356.695652 124.0337,358.086957 L170.464081,404.956522 C171.153215,405.652174 172.014632,406 172.962191,406 C173.823608,406 174.771166,405.652174 175.4603,404.956522 L221.890681,358.086957 C223.35509,356.695652 223.35509,354.434783 221.976822,353.043478 L221.976822,353.043478 Z" transform="translate(173.000000, 379.000000) rotate(-180.000000) translate(-173.000000, -379.000000) "></path></g></g></svg></div></div>
		<div class="quantity-number"><div class="number qty"><?= $item->qty;?></div></div>
		<div class="minus btn-minus"><div class="icon"><svg viewBox="0 0 100 54" data-radium="true" style="width: 100%; height: 100%;"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-286.000000, -352.000000)" fill="#4D4E4F"><path d="M384.976822,353.043478 C383.598555,351.652174 381.358871,351.652174 379.980603,353.043478 L336.048332,397.478261 L292.02992,353.043478 C290.651652,351.652174 288.411968,351.652174 287.0337,353.043478 C285.655433,354.434783 285.655433,356.695652 287.0337,358.086957 L333.464081,404.956522 C334.153215,405.652174 335.014632,406 335.962191,406 C336.823608,406 337.771166,405.652174 338.4603,404.956522 L384.890681,358.086957 C386.35509,356.695652 386.35509,354.434783 384.976822,353.043478 L384.976822,353.043478 Z"></path></g></g></svg></div></div>
		<input type="hidden" name="qty[<?= $item->rowid;?>]" class="form-control qty" value="<?= $item->qty;?>">
		<input type="hidden" name="rowid" class="form-control" value="<?= $item->rowid;?>">
	</div>
	<div class="cart_item__info">
		<div class="pr-name">
			<h3><?= (!empty($item->option['title_'.$ci->language['current']])) ? $item->option['title_'.$ci->language['current']] : $item->name;?></h3>
			<?php if(isset($item->option['attribute']) && have_posts($item->option['attribute'])) {
				$attributes = '';
				foreach ($item->option['attribute'] as $key => $attribute): $attributes .= $attribute.' / '; endforeach;
				$attributes = trim( trim($attributes), '/' );
				echo '<p class="variant-title">'.$attributes.'</p>';
			} ?>
		</div>
		<div class="pr-price" style="padding-top: 5px">
			<span class="js_cart_item_price"><?= number_format($item->price);?></span><?php echo _price_currency();?>
		</div>
	</div>
	<div class="cart_item__trash trash-container">
		<div class="icon">
			<svg viewBox="0 0 100 100" data-radium="true" style="width: 18px; height: auto;"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-123.000000, -515.000000)" fill="#CCCCCC"><path d="M126.528399,536.360896 L130.401033,536.360896 L139.437177,611.899225 C139.609294,613.70801 141.158348,615 142.965577,615 L203.034423,615 C204.841652,615 206.304647,613.70801 206.562823,611.899225 L215.598967,536.360896 L219.471601,536.360896 C221.450947,536.360896 223,534.810508 223,532.829457 C223,530.848407 221.450947,529.298019 219.471601,529.298019 L212.414802,529.298019 L192.449225,529.298019 L192.449225,518.531438 C192.449225,516.550388 190.900172,515 188.920826,515 L157.079174,515 C155.099828,515 153.550775,516.550388 153.550775,518.531438 L153.550775,529.298019 L133.585198,529.298019 L126.528399,529.298019 C124.549053,529.298019 123,530.848407 123,532.829457 C123,534.810508 124.635112,536.360896 126.528399,536.360896 L126.528399,536.360896 Z M160.607573,522.062877 L185.392427,522.062877 L185.392427,529.298019 L160.607573,529.298019 L160.607573,522.062877 L160.607573,522.062877 Z M208.45611,536.360896 L199.936317,608.023256 L146.063683,608.023256 L137.54389,536.360896 L208.45611,536.360896 L208.45611,536.360896 Z M161.296041,597.256675 C163.275387,597.256675 164.824441,595.706288 164.824441,593.725237 L164.824441,551.434109 C164.824441,549.453058 163.275387,547.90267 161.296041,547.90267 C159.316695,547.90267 157.767642,549.453058 157.767642,551.434109 L157.767642,593.725237 C157.767642,595.706288 159.316695,597.256675 161.296041,597.256675 Z M184.703959,597.256675 C186.683305,597.256675 188.232358,595.706288 188.232358,593.725237 L188.232358,551.434109 C188.232358,549.453058 186.683305,547.90267 184.703959,547.90267 C182.724613,547.90267 181.175559,549.453058 181.175559,551.434109 L181.175559,593.725237 C181.175559,595.706288 182.810671,597.256675 184.703959,597.256675 Z"></path></g></g></svg>
		</div>
	</div>
	<div class="cart_item__trash_popover">
		<div style="font-size: 14px; line-height: 24px; font-family: avenir-next-regular, arial; margin-bottom: 3px;"><?php echo __('Xác nhận xoá sản phẩm?', 'cart_confirm_delete_product_question');?></div>
		<div style="display: flex; justify-content: space-between;">
			<div class="submit-button js_cart_item__trash_agree">
				<div style="font-size: 13px; font-family: Oswald, sans-serif; letter-spacing: 1.1px; text-transform: uppercase;"><?php echo __('Xoá', 'cart_delete');?></div>
			</div>
			<div class="submit-button js_cart_item__trash_close" style="background-color:#fff; color:#000;">
				<div style="font-size: 13px; font-family: Oswald, sans-serif; letter-spacing: 1.1px; text-transform: uppercase;"><?php echo __('Hủy', 'cart_cancel');?></div>
			</div>
		</div>
	</div>
</div>
