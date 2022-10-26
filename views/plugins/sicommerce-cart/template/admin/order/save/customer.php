<?php
	$users = User::gets(Qr::set()->select('id', 'firstname', 'lastname', 'username', 'email', 'phone')->limit(20));
?>
<div class="box" id="order_customer_search" style="display:<?php echo (isset($order) && have_posts($order)) ? 'none' : 'block';?>">
    <div class="box-content">

        <section class="ui-layout__section">
            <header class="ui-layout__title"><h2>Tìm kiếm hay thêm khách hàng</h2></header>
        </section>
        <!-- Tìm kiếm khách hàng -->
        <section class="ui-layout__section">
            <div class="col-md-12" id="box_order_search_user" style="padding:0;">
                <label for="">Chọn khách hàng</label>
                <div class="group input-popover-group" data-name="order_search_user" id="order_search_user" data-module="order_search_user" data-key-type="user">
                    <input type="text" class="form-control input-popover-search" placeholder="Tìm kiếm khách hàng">
                    <div class="popover-content">
                        <div class="popover__tooltip"></div>
                        <div class="popover__scroll">
                            <ul class="popover__ul" style="display: block;">
                                <?php echo popover_order_user_template_item([]);?>
                                <?php foreach ($users as $key => $item) { echo popover_order_user_template_item($item); } ?>
                            </ul>
                            <div class="popover__loading text-center" style="display: none;">
                                Đang tải…
                            </div>
                        </div>
                    </div>
                    <div class="collections hidden">
                        <ul class="collection-list"></ul>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </section>
    </div>
</div>

<div class="box" id="order_customer_infomation">
    <div class="box-content">
        <section class="ui-layout__section" style="border-bottom: 1px solid #ebeef0;">
            <header class="ui-layout__title"><h2>Thông tin</h2></header>
        </section>
        <div id="order_customer_infomation_result">
            <?php
            if(isset($order) && have_posts($order)) {
                $customer = User::get($order->user_created);
            }
            include 'customer-infomation.php';
            ?>
        </div>
    </div>
</div>