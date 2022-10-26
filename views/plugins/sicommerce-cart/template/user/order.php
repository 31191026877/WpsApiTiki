<?php
$user = Auth::user();

$orders = Order::gets(Qr::set('user_created', $user->id));
?>
	<table class="table">
		<thead>
			<tr>
				<th><?php echo __('Mã đơn hàng'); ?></th>
				<th><?php echo __('Tổng tiền'); ?></th>
				<th><?php echo __('Ngày mua'); ?></th>
				<th class="order_status text-right"><?php echo __('Tình trạng'); ?></th>
				<th><?php echo __('Chi tiết'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($orders as $order): ?>
			<tr>
				<td class="order_code"><a href="<?php echo my_account_url().'/order/detail?code='.$order->id;?>">#<?php echo $order->code;?></a></td>
				<td class="order_total"><b><?php echo number_format($order->total)._price_currency();?></b> / <?php echo $order->quantity;?> sản phẩm</td>
				<td class="order_created"><?php echo date('d/m/Y', strtotime($order->created));?></td>
				<td class="order_status text-right"><?php echo  '<span style="background-color:'.Order::status($order->status, 'color').'; border-radius:10px; padding:0px 15px; display:inline-block;">'.Order::status($order->status, 'label').'</span>';?></td>
				<td class="order_detail"><a href="<?php echo my_account_url().'/order/detail?code='.$order->id;?>" class="btn btn-blue btn-effect-default">Chi Tiết</a></td>
			</tr>
			<?php endforeach ?>
			
		</tbody>
	</table>

<style type="text/css">
    strong, b { font-weight: bold; }
    .table {color:#4a4a4a;}
	.table>thead>tr>th {
		font-size: 16px;
		padding: 10px 0;
    	font-weight: bold;
        border-bottom: 1px solid #ececec;
	}
	.table>tbody>tr>td {
        border:0; padding:10px 0; font-size: 14px;
        border-bottom: 1px solid #ececec;
        color:#4a4a4a;
    }
	.table>tbody>tr>td.order_code {
        font-weight: bold; color: #4a4a4a;
    }
    .table>tbody>tr>td.order_code a {
        font-weight: bold; color: #4a4a4a;
    }
    .table>tbody>tr>td.order_status, .table>thead>tr>th.order_status {
        padding:10px 10px;
    }
    .table>tbody>tr>td.order_status span {
        display: inline-block;
        font-weight: bold; font-size: 11px; line-height: 20px;
        padding:0 20px;
    }
	.table>tbody>tr>td .btn {
        margin-top: 0;
        padding: 0px 20px;
        font-size: 11px;
        line-height: 25px;
    }
</style>