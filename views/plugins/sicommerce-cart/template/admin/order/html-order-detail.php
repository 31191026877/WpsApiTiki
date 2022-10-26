<form name="order" action="" method="post" id="order">
	<?php echo form_open(); ?>
	<div class="order-ui order-header">
		<h1>#<?php echo $order->code;?> <span class="order-time-created"><?php echo date('d/m/Y H:s:i', strtotime($order->created));?></span></h1>
		<?php do_action('order_detail_header_action', $order);?>
	</div>
    <?php if($order->status == ORDER_CANCELLED) {?>
    <div class="order-ui order-header"><?php echo notice('error', 'Đơn hàng #'.$order->code.' đã bị huỷ lúc <b>'.date('d/m/Y h:i', $order->time_cancelled).'</b>.', false, 'Đơn hàng đã bị huỷ' );?></div>
    <?php } ?>
	<div class="order-ui order-sections">
        <div class="row">
            <div class="col-md-8">
                <?php
                /**
                 * order_detail_sections_primary
                 * @hook order_detail_primary_content - 10
                 */
                echo do_action('order_detail_sections_primary', $order );
                ?>
            </div>
            <div class="col-md-4">
                <?php
                /**
                 * order_detail_sections_secondary
                 * @hook order_detail_secondary_action   - 10
                 * @hook order_detail_secondary_customer - 20
                 */
                echo do_action('order_detail_sections_secondary', $order );?>
            </div>
        </div>
	</div>
</form>

<div class="modal fade" id="js_order__modal_cancelled">
    <form action="" id="js_order__form_cancelled">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Hủy đơn hàng</h4>
                </div>
                <div class="modal-body" style="overflow: hidden">
                    <p>Bạn nên hủy đơn hàng khi thấy đơn hàng có vấn đề, khi khách hàng thay đổi quyết định hoặc khi kho hàng hết sản phẩm. Thao tác này không thể khôi phục.</p>
                    <div class="col-md-12">
                        <label for="">Lý do hủy đơn hàng</label>
                        <select name="reason" class="form-control">
                            <?php foreach ($order_cancelled_reason as $reason) { ?>
                                <option value="<?php echo $reason;?>"><?php echo $reason;?></option>
                            <?php } ?>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-md-6 text-left">
                        <div class="radio">
                            <label style="padding-left:0;">
                                <input type="radio" name="SendNotificationEmail" value="1" class="icheck">
                                Gửi Email thông báo tới khách hàng
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Không hủy</button>
                        <button type="submit" class="btn btn-red">Hủy</button>
                    </div>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </form>
</div><!-- /.modal -->

<style type="text/css">
	.order-ui {
		box-sizing: border-box;
	    max-width: 1100px;
	    margin-right: auto;
	    margin-left: auto;
	    font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
	}
	.order-header { margin-bottom: 10px; }

	.order-header h1 {
		font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif;
		font-weight: 600;
	    margin-right: .8rem;
	    overflow: hidden;
	    overflow-wrap: break-word;
	    word-wrap: break-word;
	    white-space: normal;
		font-size: 2.8rem;
    	line-height: 3.4rem;
	}
	.order-header h1 span.order-time-created {
		font-size: 1.4rem;
		color: #798c9c;
		font-weight: 400;
		padding-left: 0;
	    -webkit-box-flex: 0;
	    -webkit-flex: 0 1 auto;
	    -ms-flex: 0 1 auto;
	    flex: 0 1 auto;
	    -webkit-align-self: flex-end;
	    -ms-flex-item-align: end;
	    align-self: flex-end;
	    line-height: 2.5rem;
	}

	.order-sections .box {
		border-radius: 3px;
		-webkit-box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
    	box-shadow: 0 0 0 1px rgba(63,63,68,.05), 0 1px 3px 0 rgba(63,63,68,.15);
	}

	#order_data h3, #order_data h4 {
	    color: #333;
	    margin: 1.33em 0 0;
	}
	#order_data h3 { font-size: 14px; margin-bottom: 20px; font-weight: bold; }

	#order_data p {
	    color: #777;
	}
	#order_data .order_data_column .address strong {
	    display: block;
	}
	

	#order_items .wc-order-data-row {
	    border-bottom: 1px solid #dfdfdf;
	    padding: 1.5em 2em;
	    background: #f8f8f8;
	    line-height: 2em;
	    text-align: right;
	}
	#order_items .wc-order-data-row::after, #order_items .wc-order-data-row::before {
	    content: ' ';
	    display: table;
	}

	#order_items .wc-order-data-row  .wc-order-totals {
	    float: right;
	    width: 50%;
	    margin: 0;
	    padding: 0;
	    text-align: right;
	}
	
	#order_items .wc-order-data-row  .wc-order-totals .label{
	    color:#333;
	    font-size: 15px;
	}

	table.customer tr {
		border-bottom: 1px dotted #ccc;
	}
	table.customer tr td {
		padding:15px 5px;
		/*padding-left: 10px;*/
	}
	table.customer tr td:first-child {
		padding-left:10px;
		padding-right:5px;
		/*padding-left: 10px;*/
	}
	table.customer tr td i.icon {
		padding: 5px;
		margin-right:5px;
		display: inline-block;
		color:#fff;
		background-color: #000;
		text-align: center;
	}

	table.order_detail tr {
		border-bottom: 1px dotted #ccc;
	}
	table.order_detail td {
		padding:10px;
	}
	.loading, .success, .error, #order_cancel_alert {
		display: none;
	}
    .page-content .action-bar {display: none; }
</style>