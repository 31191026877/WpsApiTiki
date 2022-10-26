<form action="" autocomplete="off">
    <div class="box">
        <div class="box-content">
            <?php echo FormBuilder::render([
                'name' => 'time', 'type' => 'daterange', 'placeholder' => '', 'label' => 'Thời gian', 'autocomplete' => 'off'
            ], Request::get('time'));?>
        </div>
    </div>
</form>
<h4 style="padding:10px 0;">Doanh thu <span id="js_order_report_product__heading"></span></h4>

<div class="box">
    <div class="box-content">
        <table class="display table table-striped">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá (hiện tại)</th>
                    <th>Số lượng bán</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>
            <tbody id="js_order_report_product__tbody">

            </tbody>
        </table>
    </div>
</div>
<style>
    .cart_dashboard_order {
        overflow: hidden;
    }
    .cart_dashboard_order .cart_dashboard_order_item {
        padding: 10px 10px 10px 100px;
        border-right: 1px dashed #B9C4DA;
        color: #B9C4DA;
        position: relative;
        min-height: 70px;
        width:calc(25% - 10px); float: left;
        margin:5px 5px;
    }
    .cart_dashboard_order .cart_dashboard_order_item:last-child {
        border-right: 0px dashed #B9C4DA;
    }
    .cart_dashboard_order .cart_dashboard_order_item .heading {
        font-size: 15px;
        margin-bottom: 10px;
        font-weight: bold;
        color: #000;
    }
    .cart_dashboard_order .cart_dashboard_order_item .icon {
        position: absolute;
        left: 20px;
        top: 15px;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        background-color: #F5F8FD;
        color: #fff;
        border-radius: 50%;
    }
    .cart_dashboard_order .cart_dashboard_order_item .number {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 0px;
        color: #323232;
    }

    .cart_dashboard_order .cart_dashboard_order_item.revenue .icon {
        background-color:#0188FF;
    }
    .cart_dashboard_order .cart_dashboard_order_item.revenue .number {
        color: #0188FF;
    }

    .cart_dashboard_order .cart_dashboard_order_item.order-new .icon {
        background-color:#26D692;
    }
    .cart_dashboard_order .cart_dashboard_order_item.order-new .number {
        color: #26D692;
    }

    .cart_dashboard_order .cart_dashboard_order_item.order-refunded .icon {
        background-color:#FFAF07;
    }
    .cart_dashboard_order .cart_dashboard_order_item.order-refunded .number {
        color: #FFAF07;
    }

    .cart_dashboard_order .cart_dashboard_order_item.order-cancelled .icon {
        background-color:#FF5663;
    }
    .cart_dashboard_order .cart_dashboard_order_item.order-cancelled .number {
        color: #FF5663;
    }
    table.table tr:nth-child(odd) > td {
        background-color: #f9f9f9;
    }
</style>
<script id="js_order_report_item_template" type="text/x-custom-template">
    <tr>
        <td style="color:#0188FF">${title}</td>
        <td>${price}</td>
        <td>${total_quantity}</td>
        <td><b>${subtotal}</b></td>
    </tr>
</script>
<script defer>
    $(function () {
        let time;
        function order_report_load() {
            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    if (settings.data.indexOf('csrf_test_name') === -1) {
                        settings.data += '&csrf_test_name=' + encodeURIComponent(getCookie('csrf_cookie_name'));
                    }
                }
            });
            let data = {
                'action': 'Admin_Cart_Ajax_Report::reportProduct',
                'time' : time
            };
            $jqxhr = $.post(ajax, data, function () { }, 'json');
            $jqxhr.done(function (response) {
                if (response.status === 'success') {

                    let str = '';

                    for (const [key, items_tmp] of Object.entries(response.list)) {
                        let items = [items_tmp];
                        items.map(function(item) {
                            str += $('#js_order_report_item_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                        });
                    }

                    $('#js_order_report_product__tbody').html(str);
                    $('#js_order_report_product__heading').html(response.heading);
                }
            });
            $jqxhr.fail(function (data) {});
            $jqxhr.always(function (data) { });
        }
        order_report_load();
        $('#time').on('apply.daterangepicker', function (ev, picker) {
            time = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');
            order_report_load();
        });
    });
</script>