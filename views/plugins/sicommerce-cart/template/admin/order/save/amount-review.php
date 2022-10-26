<div class="box-content wc-order-data-row" style="overflow:hidden">
    <table class="wc-order-totals">
        <tbody>
            <tr>
                <td class="">Tạm tính</td>
                <td width="1%"></td>
                <td class="total" id="order_provisional"><?php echo number_format($order_provisional);?></td>
            </tr>
            <?php if(!empty($shipping)) { ?>
            <tr>
                <td class="">Phí giao hàng</td>
                <td width="1%"></td>
                <td class="total" id="order_provisional"><?php echo number_format($shipping);?></td>
            </tr>
            <?php } ?>
            <?php do_action('order_add_total_review'); ?>
            <tr>
                <td class="label" style="padding-right:0;">tổng cộng</td>
                <td width="1%"></td>
                <td class="total"id="order_total" style="font-weight:bold;"><?php echo number_format(order_add_total($total));?></td>
            </tr>
        </tbody>
    </table>
    <div class="clearfix"> </div>
</div>