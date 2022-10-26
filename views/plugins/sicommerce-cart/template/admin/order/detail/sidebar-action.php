<div class="box" id="order_action">
	<div class="box-content">
		<header class="order__title">
			<div class="order__title_wrap">
				<h2>Hành Động</h2>
			</div>
		</header>

		<div class="order_cart__section">
			<?php
				$status = Order::status();
				$status_pay = Order::statusPay();
                if($order->status != ORDER_CANCELLED) {
			?>
            <h3 for="">Trạng thái</h3>
			<select name="status" class="form-control">
				<?php foreach ($status as $key => $action): if($key == ORDER_CANCELLED) continue; ?>
                    <option value="<?php echo $key;?>" <?php echo ($order->status == $key) ?'selected':'';?>><?php echo $action['label'];?></option>
				<?php endforeach ?>
			</select>
            <?php } ?>

            <h3 for="" style="margin-top: 10px;">Thanh toán</h3>
            <select name="status-pay" class="form-control">
                <?php foreach ($status_pay as $key => $action): ?>
                    <option value="<?php echo $key;?>" <?php echo ($order->status_pay == $key) ?'selected':'';?>><?php echo $action['label'];?></option>
                <?php endforeach ?>
            </select>
			<hr />
			<div class="text-right">
				<button type="submit" name="submit" class="btn btn-icon btn-blue">Cập nhật</button>
			</div>

		</div>
	</div>
</div>