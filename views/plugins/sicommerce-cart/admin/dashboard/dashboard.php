<?php
include "ajax.php";
Class Admin_Cart_Dashboard {
    static function register() {
        Dashboard::add('cart_dashboard_order', 'Thống kê 1', ['callback' => 'Admin_Cart_Dashboard::order', 'col' => 12]);
    }
    static function order($widget) {
        ?>
        <div class="cart_dashboard_order">
            <div class="row">
                <div class="col-md-4">
                    <h3>Doanh thu</h3>
                    <div class="cart_dashboard_order_item today">
                        <?php echo Admin::loading();?>
                        <p class="time">Hôm nay</p>
                        <p class="icon"><i class="fad fa-clouds-sun"></i></p>
                        <p class="number"></p>
                        <p class="count"></p>
                    </div>
                    <div class="cart_dashboard_order_item yesterday">
                        <?php echo Admin::loading();?>
                        <p class="time">Hôm qua</p>
                        <p class="icon"><i class="fad fa-clouds-sun"></i></p>
                        <p class="number"></p>
                        <p class="count"></p>
                    </div>
                    <div class="cart_dashboard_order_item week">
                        <?php echo Admin::loading();?>
                        <p class="time">Tuần trước</p>
                        <p class="icon"><i class="fad fa-clouds-sun"></i></p>
                        <p class="number"></p>
                        <p class="count"></p>
                    </div>
                    <div class="cart_dashboard_order_item month">
                        <?php echo Admin::loading();?>
                        <p class="time">Tháng trước</p>
                        <p class="icon"><i class="fad fa-clouds-sun"></i></p>
                        <p class="number"></p>
                        <p class="count"></p>
                    </div>
                </div>
                <div class="col-md-8">
                    <h3>Bán chạy tháng này</h3>
                    <div class="cart_dashboard_product_bestseller">
                        <?php echo Admin::loading();?>
                        <div class="cart_dashboard_product_item item-header">
                            <div class="col title">
                                Sản phẩm
                            </div>
                            <div class="col img">

                            </div>
                            <div class="col price">
                                Giá
                            </div>
                            <div class="col quantity">
                                Đã bán
                            </div>
                            <div class="col subtotal">
                                Tổng tiền
                            </div>
                        </div>
                        <div class="list_product__body"></div>
                    </div>
                </div>
            </div>
        </div>
        <script id="js_cart_dashboard_bestseller_template" type="text/x-custom-template">
            <div class="cart_dashboard_product_item">
                <div class="col img"><img src="${image}"></div>
                <div class="col title"><h4>${title}</h4></div>
                <div class="col price">${price}</div>
                <div class="col quantity">${total_quantity}</div>
                <div class="col subtotal">${subtotal}</div>
            </div>
        </script>
        <style>
            .cart_dashboard_order {
                position: relative;
                margin: 20px 0;
                overflow: hidden;
                border-radius: 10px;
                border:1px solid #B9C4DA;
                padding:35px 20px;
                background-color: #fff;
            }
            .cart_dashboard_order h3 {
                font-size: 18px; margin-bottom: 30px; margin-top: 0;
            }
            .cart_dashboard_order .cart_dashboard_order_item {
                padding:20px 20px 20px 100px;
                border:1px solid #B9C4DA;
                color:#B9C4DA;
                border-radius: 10px;
                margin-bottom: 20px;
                position: relative;
                min-height: 98px;
            }
            .cart_dashboard_order .cart_dashboard_order_item:last-child { margin-bottom: 0;}
            .cart_dashboard_order .cart_dashboard_order_item .time {
                position: absolute; left: 10px; top:-10px; background-color: #fff; padding: 0 5px;
                font-size: 13px; margin-bottom: 0; font-weight: bold; color:#000;
            }
            .cart_dashboard_order .cart_dashboard_order_item .icon {
                position: absolute; left: 20px; top:30px;
                width: 50px; height: 50px; line-height: 50px; text-align: center;
                background-color: #F5F8FD; color:#45D7C4;
                border-radius: 50%;
            }
            .cart_dashboard_order .cart_dashboard_order_item .number{
                font-size: 20px; font-weight: bold;
                margin-bottom: 10px; color:#323232;
            }
            .cart_dashboard_order .cart_dashboard_order_item .count{
                font-size: 13px; margin-bottom: 0; font-weight: bold;
            }

            .cart_dashboard_product_bestseller { min-height: 300px; }

            .cart_dashboard_product_bestseller .cart_dashboard_product_item {
                overflow: hidden;
            }
            .cart_dashboard_product_bestseller .cart_dashboard_product_item.item-header {
                font-weight: bold; background-color: #fff!important;color:#B9C4DA;
            }


            .cart_dashboard_product_bestseller .cart_dashboard_product_item:nth-child(2n+1) {
                background-color: var(--content-bg);
            }
            .cart_dashboard_product_bestseller .cart_dashboard_product_item .col {
                float: left; width: 20%; padding:10px;
            }
            .cart_dashboard_product_bestseller .cart_dashboard_product_item .img {
                width: 10%;
            }
            .cart_dashboard_product_bestseller .cart_dashboard_product_item .img img{
                width: 100%; border: 1px solid #C5CFDF; border-radius: 5px;
            }

            .cart_dashboard_product_bestseller .cart_dashboard_product_item .title {
                width: 30%;
            }
            .cart_dashboard_product_bestseller .cart_dashboard_product_item .title h4 {
                font-size: 13px; margin: 0; line-height: 20px;
            }
        </style>
        <script defer>
            $(function () {
                function cart_dashboard_order_load() {
                    $('.cart_dashboard_order_item .loading').show();
                    $('.cart_dashboard_product_bestseller .loading').show();
                    $.ajaxSetup({
                        beforeSend: function(xhr, settings) {
                            if (settings.data.indexOf('csrf_test_name') === -1) {
                                settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
                            }
                        }
                    });
                    let data = {
                        'action': 'Admin_Cart_Ajax_Dashboard::order'
                    };
                    $jqxhr = $.post(ajax, data, function () { }, 'json');
                    $jqxhr.done(function (response) {
                        $('.cart_dashboard_order_item .loading').hide();
                        $('.cart_dashboard_product_bestseller .loading').hide();
                        if (response.status === 'success') {
                            $('.cart_dashboard_order_item.today .number').html(response.today.total);
                            $('.cart_dashboard_order_item.today .count').html(response.today.count);

                            $('.cart_dashboard_order_item.yesterday .number').html(response.yesterday.total);
                            $('.cart_dashboard_order_item.yesterday .count').html(response.yesterday.count);

                            $('.cart_dashboard_order_item.week .number').html(response.week.total);
                            $('.cart_dashboard_order_item.week .count').html(response.week.count);

                            $('.cart_dashboard_order_item.month .number').html(response.month.total);
                            $('.cart_dashboard_order_item.month .count').html(response.month.count);

                            let str = '';

                            for (const [key, items_tmp] of Object.entries(response.bestseller)) {
                                let items = [items_tmp];
                                items.map(function(item) {
                                    str += $('#js_cart_dashboard_bestseller_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                                });
                            }

                            $('.cart_dashboard_product_bestseller .list_product__body').html(str);
                        }
                    });
                    $jqxhr.fail(function (data) {});
                    $jqxhr.always(function (data) { });
                }
                cart_dashboard_order_load();
            });
        </script>
        <?php
    }
}

add_action('cle_dashboard_setup', 'Admin_Cart_Dashboard::register');
