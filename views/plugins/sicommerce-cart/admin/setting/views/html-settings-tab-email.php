<div class="row">
    <div class="col-md-3">
        <div class="ui-title-bar__group" style="padding-bottom:5px;">
            <h3 class="ui-title-bar__title" style="font-size:20px;">Email đơn hàng</h3>
            <p style="margin-top: 10px; margin-left: 1px; color: #8c8c8c">Gửi email đến quản trị viên và khách hàng</p>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box">
            <div class="box-content" style="padding:10px;">
                <div class="row">
                    <div class="row m-1">
                        <div class="col-md-5">
                            <label for="cart_email[customer_order_new]" class="control-label">Đơn hàng mới - Khách hàng</label>
                            <p style="color:#999;margin:5px 0 5px 0;">Gửi email xác nhận cho khách hàng khi đặt hàng thành công.</p>
                        </div>
                        <div class="col-md-7 text-right">
                            <div class="group">
                                <div class="radio">
                                    <label><input type="radio" name="cart_email[customer_order_new]" class="icheck " value="on" <?php echo ($cart_email['customer_order_new'] == 'on')?'checked':'';?>>&nbsp;&nbsp;Bật</label>
                                </div>
                                <div class="radio">
                                    <label> <input type="radio" name="cart_email[customer_order_new]" class="icheck " value="off" <?php echo ($cart_email['customer_order_new'] == 'off')?'checked':'';?>>&nbsp;&nbsp;Tắt</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row m-1">
                        <div class="col-md-5">
                            <label for="cart_email[admin_order_new]" class="control-label">Đơn hàng mới - Quản trị</label>
                            <p style="color:#999;margin:5px 0 5px 0;">Gửi email xác nhận cho admin khi đặt hàng thành công.</p>
                        </div>
                        <div class="col-md-7 text-right">
                            <div class="group">
                                <div class="radio">
                                    <label> <input type="radio" name="cart_email[admin_order_new]" class="icheck " value="on" <?php echo ($cart_email['admin_order_new'] == 'on')?'checked':'';?>>&nbsp;&nbsp;Bật</label>
                                </div>
                                <div class="radio">
                                    <label> <input type="radio" name="cart_email[admin_order_new]" class="icheck " value="off" <?php echo ($cart_email['admin_order_new'] == 'off')?'checked':'';?>>&nbsp;&nbsp;Tắt</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<hr />

<style>
	.group { margin-bottom:10px; }
	.radio, .checkbox {
		display: inline-block;
	}
</style>