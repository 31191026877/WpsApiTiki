<?php do_action('page_cart_success_before');?>
<div class="page-cart-content page-cart-success row">
    <div class="col-md-8 offset-md-2">
        <div class="page-cart-box" style="padding:15px;">
            <div class="cart-success__heading text-center" style="overflow:hidden">
                <h2 class="header" style="text-align:center;font-size:30px;background-color:transparent;">ĐẶT HÀNG THÀNH CÔNG</h2>
                <div class="thankyou-message-text">
                    <h3>Cảm ơn bạn đã đặt hàng</h3>
                    <p>Một email xác nhận đã được gửi tới <?php echo $order->billing_email;?>. Xin vui lòng kiểm tra email của bạn</p>
                    <div style="font-style: italic;"></div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="cart-success__order" style="overflow:hidden">
                <h5>Mã đơn hàng của bạn: <strong>#<?php echo $order->code;?></strong></h5>
                <p><?php echo date('d/m/Y', strtotime($order->created));?> <?php echo date('h:s a', strtotime($order->created));?></p>
            </div>

            <div class="cart-success__customer" style="overflow:hidden">
                <h3 class="header">Thông Tin Đặt Hàng</b></h3>
                <table class="table table-bordered table-user-info">
                    <tbody>
                        <tr>
                            <th>Họ Tên</th>
                            <td><?php echo $order->billing_fullname;?></td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td><?php echo $order->billing_email;?></td>
                        </tr>

                        <tr>
                            <th>Số Điện Thoại</th>
                            <td><?php echo $order->billing_phone;?></td>
                        </tr>

                        <tr>
                            <th>Địa chỉ</th>
                            <td><?php echo $order->billing_address;?></td>
                        </tr>
                    </tbody>
                </table>

                <?php if( !empty($order->shipping_fullname) ) {?>
                <h3 class="header">Thông Tin Giao Hàng</b></h3>

                <table class="table table-bordered table-user-info">
                    <tbody>
                        <tr>
                            <th>Họ Tên</th>
                            <td><?php echo $order->shipping_fullname;?></td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td><?php echo $order->shipping_email;?></td>
                        </tr>

                        <tr>
                            <th>Số Điện Thoại</th>
                            <td><?php echo $order->shipping_phone;?></td>
                        </tr>

                        <tr>
                            <th>Địa chỉ</th>
                            <td><?php echo $order->shipping_address;?></td>
                        </tr>
                    </tbody>
                </table>
                <?php } ?>
            </div>

            <div class="page-cart cart-success__product">
                <div class="page-cart-box">
                    <div class="page-cart-tbody">
                        <?php $total = 0; ?>
                        <?php foreach ($order->items as $key => $item): ?>
                            <div class="cart__item">
                                <div class="cart_item__img">
                                    <?php Template::img($item->image,'',array('style'=>'height:70px;'));?>
                                </div>
                                <div class="cart_item__info">
                                    <div class="pr-name">
                                        <h3><?= $item->title;?></h3>
                                        <?php $item->option = @unserialize($item->option) ;?>
                                        <?php if(isset($item->option) && have_posts($item->option)) {
                                            $attributes = '';
                                            foreach ($item->option as $key => $attribute): $attributes .= $attribute.' / '; endforeach;
                                            $attributes = trim(trim($attributes), '/');
                                            echo '<p class="variant-title" style="color:#999;">'.$attributes.'</p>';
                                        } ?>
                                    </div>
                                    <div class="pr-price" style="padding-top: 5px">
                                        <span class="js_cart_item_price"><?= number_format($item->price);?></span><?php echo _price_currency();?> x <span class="number qty"><?= $item->quantity;?></span>
                                    </div>
                                </div>
                            </div>
                            <?php $total += $item->subtotal; ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>

            <br />
            <p><b>Ghi chú:</b> <?php echo $order->order_note;?></p>
            <br />

            <?php $totals = get_order_item_totals( $order ); ?>

            <div class="cart-success__total row">
                <div class="col-md-6 th-cart text-right">
                    <div class="title-total"> Tổng Tạm Tính </div>
                </div>
                <div class="col-md-6 th-cart text-right">
                    <p class=""><?php echo number_format($total);?>đ</p>
                </div>

                <?php do_action('page_cart_success_review', $order);?>

                <?php foreach ($totals as $total): ?>
                <div class="col-md-6 th-cart text-right">
                    <div class="title-total"> <?php echo $total['label'];?> </div>
                </div>
                <div class="col-md-6 th-cart text-right">
                    <p class="totalPrice"><?php echo $total['value'];?></p>
                </div>
                <?php endforeach ?>
                
            </div>
        </div>
    </div>
</div>
<?php do_action('page_cart_success_after');?>
<style>
    .wrapper {
		min-height:100vh;
		background-color:#F0F2F5!important;
	}
	h1, header, footer, .btn-breadcrumb { display:none; }
    .object-detail { border:0; background-color: transparent; overflow: inherit; margin-top: 100px; }
    .cart-success__heading h2 { margin:20px 0;color:#77B43F }
    .cart-success__heading .thankyou-message-icon img { width:40px;  }
    .cart-success__heading .thankyou-message-text {  }
    .cart-success__heading .thankyou-message-text h3 { margin-top:0; }
    .cart-success__heading .thankyou-message-text p { font-size:12px; }
    .cart-success__customer h3.header { text-align:left; margin:20px 0; font-size:15px; }
    .cart-success__order h5 { text-align:left; margin:20px 0; }
    .cart-success__order h5 strong { color:red; }
    .cart-success__product .page-cart-box .page-cart-tbody .page-cart__item .qty { color:#000; }
    .page-cart .page-cart-box .page-cart-tbody .page-cart__item:first-child { padding:0;}
    .cart-success__total .th-cart {
        padding-top:15px;
        padding-bottom:15px;
        font-size:15px;
    }
    .cart-success__total .th-cart:last-child .totalPrice {
        font-weight:bold; font-size:20px; color:#F15A5F;
    }
    .page-cart .page-cart-box .page-cart-tbody .page-cart__item {
        height: auto;
    }
    .page-cart-tbody .page-cart__item .cart_item__info:nth-of-type(2) {
        width: 50%;
        text-align: left;
    }
</style>