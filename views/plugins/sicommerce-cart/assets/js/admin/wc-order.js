$(function() {

    document.addEventListener("keydown", function(event) {
        let keyCode = event.keyCode || event.which;
        //F1
        if(keyCode === 112) {
            event.preventDefault();
            window.location = base + 'plugins?page=order&view=create';
            return false;
        }
        //F4
        if(keyCode === 115) {
            event.preventDefault();
            window.location = base + 'plugins?page=report';
            return false;
        }
    });

    let order_id = 0;

    let order_provisional = 0;

    let order_total  = 0;

    let order_detail = [];

    let customer_info = $('#order_customer_infomation');

    if(isset($('#order_amount').html())) admin_order_add_review();

    $('#order_product_items .order_product_list .order_product__item').each(function () { 

        let product_id = $(this).find('input.line_item_id').val()+'_'+$(this).find('input.line_item_variation').val();

        order_detail[product_id] = product_id;
    });

    $(document).on('click', '#box_order_search_product .popover__ul li.option', function(e) {

        let item = JSON.parse($(this).attr('data-product'));

        let product_id = item.id + '_' + item.variation;

        if (typeof order_detail != 'undefined' && order_detail.length == 0) {

            if (typeof order_detail[product_id] != 'undefined' && order_detail[product_id] == product_id) {

                $('.order_product__item_' + product_id + ' .order_product__item-quantity input').focus();

                return false;
            }
        }

        order_detail[product_id] = product_id;

        order_provisional += parseInt(item.price);

        pr = '<div class="order_product__item order_product__item_' + item.id + '_' + item.variation +'">';
        //input
        pr += '<div class="order_product__item-input">';
        pr += '<input name="line_items[' + product_id+'][productID]"      class="line_item_id" value="'+item.id+'"     type="hidden">';
        pr += '<input name="line_items[' + product_id+'][productName]"    class="line_item_name" value="'+item.title+'"  type="hidden">';
        pr += '<input name="line_items[' + product_id + '][productPrice]"   class="line_item_price" value="' + item.price + '"  type="hidden">';
        pr += '<input name="line_items[' + product_id + '][productPriceSale]"   class="line_item_price_sale" value="' +item.price_sale+'"  type="hidden">';
        pr += '<input name="line_items[' + product_id+'][productQuantity]"   class="line_item_quantity" value="1"  type="hidden">';
        pr += '<input name="line_items[' + product_id+'][productVariation]"   class="line_item_variation" value="'+item.variation+'"  type="hidden">';
        pr += '</div>';
        //img
        pr += '<div class="order_product__item-img -left -item"><img src="'+item.image+'" class="img-responsive" alt="Image"></div>';
        //title
        pr += '<div class="order_product__item-name -left -item"><span> ' + item.title;

        if (isset(item.attr_name))
            pr += ' <small style="font-size:11px;color: #29bc94;" > '+ item.attr_name +'</small >';

        pr += '</span></div>';
        //price
        if (item.price_sale == 0) {
            pr += '<div class="order_product__item-price -left -item"><span>' + item.price + '₫</span> </div>';
        }
        else {
            pr += '<div class="order_product__item-price -left -item"><span>' + item.price_sale + '₫</span> <span><del>' + item.price + '₫</del></span> </div>';
        }
        //input quantity
        pr += '<div class="order_product__item-quantity -left -item"><input value="1" style="display:inline-block; text-align: center;min-width: 5em;width:0" id="line_items_0_.quantity" class="form-control" min="1" type="number"></div>';
        //input delete
        pr += '<div class="order_product__item-delete -right -item"><a><i class="fal fa-trash"></i></a></div>';
        //total
        if (item.price_sale == 0) {
            pr += '<div class="order_product__item-total -right -item"><span>' + item.price + '₫</span> </div>';
        }
        else {
            pr += '<div class="order_product__item-total -right -item"><span>' + item.price_sale + '₫</span></div>';
        }

        pr += '</div>';

        $('.order_product_list').prepend(pr);

        admin_order_add_review();
    });

    $(document).on('change', '.order_product__item .order_product__item-quantity input', function (e) {

        item                = $(this).closest('.order_product__item');

        quantity_change     = $(this).val();

        quantity_unchage    = item.find('input.line_item_quantity').val();

        quantity_change = parseInt(quantity_change);

        quantity_unchage = parseInt(quantity_unchage);

        if(quantity_change == 0) {
            
            quantity_change = 1;

            $(this).val(quantity_change);
        }


        if(quantity_change != quantity_unchage) {

            price       = item.find('input.line_item_price').val();

            price_sale  = item.find('input.line_item_price_sale').val();

            if (price_sale != 0) price = price_sale;

            order_provisional = order_provisional - (quantity_unchage * price);

            order_provisional = order_provisional + (quantity_change * price);

            item.find('.order_product__item-total span').html(formatNumber(quantity_change * price, '.', ','));

            item.find('input.line_item_quantity').val(quantity_change);
        }

        admin_order_add_review();
    });

    $(document).on('click', '.order_product__item .order_product__item-delete', function (e) {

        item = $(this).closest('.order_product__item');

        quantity = item.find('input.line_item_quantity').val();

        quantity = parseInt(quantity);

        if (quantity == 0) quantity = 1;

        price = item.find('input.line_item_price').val();

        order_provisional = order_provisional - (quantity * price);

        product_id = item.find('input.line_item_id').val() + '_' + item.find('input.line_item_variation').val();

        if (typeof order_detail[product_id] != 'undefined') {

            delete order_detail[product_id];
        }

        item.remove();

        admin_order_add_review();
    });

    //click customer
    $(document).on('click', '#order_customer_search .popover__ul li.option', function (e) {

        let id = $(this).attr('data-key');

        if(id == 0) {
            customer_info.show();
            return false;
        }

        let data = {
            action: 'Admin_Order_Ajax_Add::loadCustomer',
            id: id
        };

        $.post(base + '/ajax', data, function () { }, 'json').done(function (data) {
            if (data.type === 'success') {
                $('#order_customer_infomation_result').html(data.customer_review);
                customer_info.show();
                $('#order_customer_search').hide();
            }
            else {
                show_message(data.message, data.type);
            }
        });
    });

    //Change shipping price
    $(document).on('keyup', 'input[name="_shipping_price"]', function (e) {
        admin_order_add_review();
    });

    //Save
    $(document).on('submit', '#order_save__form', function (e) {

        let data = $(this).serializeJSON();

        data.action = 'Admin_Order_Ajax_Add::addOrder';

        $.post(ajax, data, function () { }, 'json').done(function (response) {

            show_message(response.message, response.status);

            if (response.status === 'success') {

                window.location = base+'plugins?page=order&view=detail&id=' + response.id;
            }
        });

        return false;

    });

    //Hủy đơn hàng
    $(document).on('click', '.js_order__btn_cancelled', function(e) {

        order_id = $(this).attr('data-id');

        $('#js_order__modal_cancelled').modal('show');

        return false;
    });

    $(document).on('submit', '#js_order__form_cancelled', function (e) {

        let btnTxt = $(this).find('button[type="submit"]');

        btnTxt.html('<i class="fal fa-circle-notch fa-spin"></i> Đang hủy');

        let data = $(this).serializeJSON();

        data.action = 'Admin_Order_Ajax::cancelled';

        data.id = order_id;

        $jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

        $jqxhr.done(function (response) {

            show_message(response.message, response.status);

            if (response.status === 'success') {
                
                location.reload();
            }
        });

        return false;
    });
});

function formatNumber(nStr, decSeperate, groupSeperate) {
    nStr += '';
    x = nStr.split(decSeperate);
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
    }
    return x1 + x2;
}

function admin_order_add_review() {

    let data = $('#order_save__form').serializeJSON();

    data.action = 'Admin_Order_Ajax_Add::loadReview';

    $jqxhr = $.post(base + '/ajax', data, function () { }, 'json');

    $jqxhr.done(function (response) {
        if (response.status === 'success') {
            $('#order_amount').html(response.order_review);
        }

    });
}