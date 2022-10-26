<div class="box">
	<div class="box-content">

        <section class="ui-layout__section">
            <header class="ui-layout__title">
                <h2>Đơn hàng gần đây</h2>
                <div class="pull-right">
                    <a href="<?php echo Url::admin(sicommerce_cart::url('order').'&customer_id='.$customer->id);?>">Xem toàn bộ</a>
                </div>
            </header>
        </section>

        <section class="ui-layout__section">
            <div class="customer-order-list">
                <?php foreach ($orders as $key => $order) {?>
                <div class="order-item">
                    <div class="order-item__left text-left">
                        <p><a href="<?php echo Url::admin(sicommerce_cart::url('order').'&view=detail&id='.$order->id);?>">Đơn hàng #<?php echo $order->code;?></a> - <?php echo number_format($order->total);?> ₫</p>
                        <p><span style="background-color:<?php echo Order::status($order->status, 'color');?>; border-radius:10px; padding:0px 5px; display:inline-block;"><?php echo Order::status($order->status, 'label');?></span></p>
                    </div>
                    <div class="order-item__right text-right">
                        <p><?php echo date('d/m/Y',strtotime($order->created));?></p>
                        <p><?php echo date('h:s',strtotime($order->created));?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </section>

	</div>
</div>