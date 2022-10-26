<div class="modal fade" id="quickEditProductPriceModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" id="quickEditProductPriceForm">
                <div class="modal-header">
                    <h5 class="modal-title">Cập Nhật Giá</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="m-1">
                        <input type="hidden" value="" class="form-control" name="id">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Phân loại hàng hóa</th>
                                <th>Giá</th>
                                <th>Giá khuyến mãi</th>
                            </tr>
                            </thead>
                            <tbody class="quickEditProductPriceBody"></tbody>
                        </table>
                    </div>
                    <div class="m-1 text-end">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-blue">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    table.table {
        border:1px solid #ccc;
    }
</style>
<script>
    $(function () {
        let quickEditPriceModal = $('#quickEditProductPriceModal'), variations, productId;

        $(document).on('click', '.js_product_variations_more', function () {
            let text = $(this).text().trim();
            let textShow = $(this).data('txt-show');
            let textHide = $(this).data('txt-hide');
            if(text == textShow) {
                $(this).closest('tr').find('.product-variations-model.d-hidden').show();
                $(this).html(textHide + ' <i class=\'fa-thin fa-arrow-up\'></i>');
            }
            if(text == textHide) {
                $(this).closest('tr').find('.product-variations-model.d-hidden').hide();
                $(this).html(textShow + ' <i class=\'fa-thin fa-arrow-down\'></i>');
            }
        });

        $(document).on('click', '.js_product_quick_edit_price', function () {
            variations = $(this).data('variations');
            productId = $(this).data('id');
            quickEditPriceModal.find('input[name="id"]').val(productId);
            quickEditPriceModal.find('.quickEditProductPriceBody').html('');
            for (const [key, items_tmp] of Object.entries(variations)) {
                let items = [items_tmp];
                quickEditPriceModal.find('.quickEditProductPriceBody').append(items.map(function(item) {
                    return $('#quick_edit_product_price_template').html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                }));
            }
            quickEditPriceModal.modal('show');
        });

        $(document).on('submit', '#quickEditProductPriceForm', function () {

            let data = $(this).serializeJSON();

            data.action = 'Admin_Product_Table_Ajax::priceSave';

            $.post(ajax, data, function() {}, 'json').done(function(response) {
                show_message(response.message, response.status);

                if(response.status === 'success') {

                    quickEditPriceModal.modal('hide');

                    $.each(response.data, function (index, value) {
                        $('.product_price_'+index).html(FormatNumber(value.price));
                        $('.product_price_sale_'+index).html(FormatNumber(value.price_sale));
                    });

                    for (const [id, productPrice] of Object.entries(data.productPrice)) {
                        variations[id].price = parseInt(productPrice.price);
                        variations[id].price_sale = parseInt(productPrice.price_sale);
                    }

                    $('#tr_' + productId + ' .column-stock .js_product_quick_edit_price').attr('variations', JSON.stringify(variations));
                }
            });

            return false;
        });
    })
</script>
<script id="quick_edit_product_price_template" type="text/x-custom-template">
    <tr class="">
        <td style="width: 200px;">
            <p style="font-weight: bold; margin-bottom: 2px;">${optionName}</p>
            <p style="color: #999; margin-bottom: 2px;">SKU: ${code}</p>
        </td>
        <td><input type="number" value="${price}" class="form-control" name="productPrice[${id}][price]"></td>
        <td><input type="number" value="${price_sale}" class="form-control" name="productPrice[${id}][price_sale]"></td>
    </tr>
</script>