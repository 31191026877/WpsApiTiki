$(function () {
    document.addEventListener("keydown", function(event) {
        let keyCode = event.keyCode || event.which;
        console.log(keyCode);
        //F2
        if(keyCode === 113) {
            event.preventDefault();
            window.location = base + 'products/add';
            return false;
        }
        //F3
        if(keyCode === 114) {
            if(event.ctrlKey) {
                let model = $('#product_categories_quick_add');
                if(typeof model.html() != 'undefined') {
                    event.preventDefault();
                    model.modal({backdrop: 'static', keyboard: false});
                    model.modal('show');
                    return false;
                }
            }
            else {
                event.preventDefault();
                window.location = base + 'products/products_categories/add';
                return false;
            }
        }
    });

    let collections = $('.editTable-product-collections');

    if(typeof collections.html() !== 'undefined') {
        let product_collections_source = JSON.parse(collections.first().attr('data-source'));
        collections.each(function (index) {
            let product_collections_value = JSON.parse($(this).attr('data-value'));

            $(this).editable({
                type: 'checklist',
                value: product_collections_value,
                source: product_collections_source,
                params: function (params) {
                    params.action = 'Product_Admin_Ajax::saveCollection';
                    return params;
                },
                url: ajax,
            });
        });
    }

    let prPublic = $('.editTable-product-public');

    if(typeof prPublic.html() !== 'undefined') {
        let product_public_source = JSON.parse(prPublic.first().attr('data-source'));
        prPublic.each(function (index) {
            let product_public_value = JSON.parse($(this).attr('data-value'));

            $(this).editable({
                type: 'select',
                value: product_public_value,
                source: product_public_source,
                params: function (params) {
                    params.action = 'Product_Admin_Ajax::savePublic';
                    return params;
                },
                url: ajax,
                display: function(value, sourceData) {
                    let txt = '';
                    if(value == 1) txt = '<span class="label label-success">Hiển thị</span>';
                    if(value == 0) txt = '<span class="label label-danger">Ẩn hiển thị</span>';
                    $(this).html(txt);
                }
            });
        });
    }

    $('.js_product_btn__undo').bootstrap_confirm_delete({
        heading:'Phục hồi',
        message:'Bạn chắc chắn muốn phục hồi dữ liệu đã chọn!',
        btn_ok_label:'Khôi Phục',
        btn_cancel_label:'Hủy',
        callback:function ( event ) {
            let button = event.data.originalObject;
            let id = [];
            id.push(button.attr('data-id'));
            if(id.length === 0) {
                show_message('Không có dữ liệu nào được phục hồi ?', 'error');
            }
            else {
                let data ={
                    'action' : 'ajax_undo',
                    'data'   : id,
                    'table'  : 'products',
                };
                $.post(ajax+urlType, data, function() {}, 'json').done(function(response) {
                    show_message(response.message, response.status);
                    if(response.status === 'success') {
                        if (typeof response.data != 'undefined') {
                            let count = response.data.length;
                            for (let i = 0; i < count; i++) {
                                $('.tr_'+response.data[i]).hide('fast').remove();
                            }
                        }
                        else {
                            let button = event.data.originalObject;
                            button.closest( 'tr' ).remove();
                        }
                    }
                    if(response.status === 'reload') { location.reload(); }
                    relogin(response);
                });
            }
        },
    });

    $('.js_products_category_quick_btn').click(function () {
        let model = $('#product_categories_quick_add');
        if(typeof model.html() != 'undefined') {
            event.preventDefault();
            model.modal({backdrop: 'static', keyboard: false});
            model.modal('show');
            return false;
        }
    });

    $('.js_products_price__update').editable({
        type 	: 'text',
        url 	: ajax,
        params: function(params) {
            params.action = 'Product_Admin_Ajax::updatePrice';
            return params;
        },
        success: function(response, newValue) {
            if(response.status == 'error') {
                show_message(response.message, response.status);
            }
        }
    });
});