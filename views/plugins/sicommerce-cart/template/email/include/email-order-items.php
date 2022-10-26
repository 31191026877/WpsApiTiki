<?php
foreach ( $order->items as $item_id => $item ) : ?>
<tr style="border-bottom: 1px solid #ccc;">
	<td style="padding:10px; font-size:13px;"><?php echo $item->title;?></td>
	<td style="padding:10px; font-size:13px;"><?php echo $item->quantity;?></td>
	<td style="padding:10px; font-size:13px;"><?php echo number_format($item->price);?>₫</td>
	<td style="padding:10px; font-size:13px;"><?php echo number_format($item->quantity*$item->price);?>₫</td>
</tr>
<?php endforeach; ?>