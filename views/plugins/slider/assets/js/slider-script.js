$(function () {

    let itemId = 0, sliderId = 0, sliderType;

    let SliderHandler = function() {
        $( document )
            .on('submit','#js_slider_form__add', this.add)
            .on('click','.js_slider__delete', this.delete)
            .on('click','.js_slider__options', this.optionsLoad)
            .on('submit','#js_slider_form__options', this.optionsSave)
            .on('click','#js_slider_item__add', this.itemAdd)
            .on('click','.js_slider_item', this.itemInfo)
            .on('click','.js_slider_item__delete', this.itemDelete)
            .on('submit','#js_slider_item__edit', this.itemSave)
            .on('click','.js_slider_item_caption__list li label', this.loadItemCap);

        if(typeof $('#js_slider_item__sort').html() != 'undefined') {
            Sortable.create(js_slider_item__sort, {
                animation: 200,
                // Element dragging ended
                onEnd: function (/**Event*/evt) {
                    let o = 0;
                    $.each($(".js_slider_item"), function (e) {
                        let i = $(this).attr("data-id");
                        $('#js_slider_item_sort_' + i).val(o);
                        o++;
                    });
                    SliderHandler.prototype.itemSort();
                },
            });
            $('.js_slider_item').first().trigger('click');
        }
    };

    SliderHandler.prototype.add = function(e) {

        let name 	= $(this).find('input[name="name"]').val();

        let type 	= $(this).find('input[name="type"]:checked').val();

        if(name.length === 0) {
            show_message('Không được bỏ trống tên slider', 'error');
            return false;
        }

        if(typeof type == 'undefined' || type.length === 0) {
            show_message('Bạn chưa chọn loại slider', 'error');
            return false;
        }

        let data = {
            'action' 		: 'AdminAjaxSlider::add',
            'name' 			: name,
            'type' 			: type,
        };

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {
            show_message(response.message, response.status);
            if(response.status === 'success') window.location.reload();
        });

        return false;
    };

    SliderHandler.prototype.delete = function(e) {

        sliderId 	= $(this).data('id');

        let data = {
            'action' 		: 'AdminAjaxSlider::delete',
            'id' 			: sliderId,
        };

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {
            show_message(response.message, response.status);
            if(response.status === 'success') window.location.reload();
        });

        return false;
    };

    SliderHandler.prototype.optionsLoad = function(e) {

        sliderId 	= $(this).data('id');

        $('#sliderOptionsModal .loading').show();

        $('#sliderOptionsModal').modal('show');

        $('#sliderOptionsModal #sliderOptionsModal_content').html('');

        let data = {
            'action'    : 'AdminAjaxSlider::optionsLoad',
            'id' 	    : sliderId,
        };

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {

            $('#sliderOptionsModal .loading').hide();

            if(response.status === 'success') {
                $('#sliderOptionsModal #sliderOptionsModal_content').html(response.data);
                formBuilderReset();
            }
            else {
                show_message(response.message, response.status);
            }
        });

        return false;
    };

    SliderHandler.prototype.optionsSave = function(e) {

        $('#sliderOptionsModal .loading').show();

        let data = $(this).serializeJSON();

        data.action = 'AdminAjaxSlider::optionsSave';

        data.id = sliderId;

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {
            $('#sliderOptionsModal .loading').hide();
            show_message(response.message, response.status);
            if(response.status === 'success') {
                $('#sliderOptionsModal').modal('hide');
            }
        });

        return false;
    };

    SliderHandler.prototype.itemInfo = function(e) {

        $('.js_slider_item .item').removeClass('active');

        $(this).find('.item').addClass('active');

        $('#js_slider_item_box__edit .loading').show();

        itemId = $(this).attr('data-id');

        sliderId = $(this).attr('data-group');

        sliderType = $(this).attr('data-type');

        let data = {
            'action'    : 'AdminAjaxSlider::itemLoad',
            'id' 	    : itemId,
            'sliderType': sliderType,
        };

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {

            $('#js_slider_item_box__edit .loading').hide();

            if(response.status === 'success') {
                $('#js_slider_item__edit').html(response.data);
                load_image_review();
                $('.item-color input').spectrum({
                    type: "color",
                    showInput: true,
                    showInitial: true,
                    chooseText: "Chọn", cancelText: "Hủy"
                });
                $('.item-color-hexa input').spectrum({
                    type: "component",
                    showInput: true,
                    showInitial: true,
                    chooseText: "Chọn"
                });
            }
            else {
                show_message(response.message, response.status);
            }
        });

        return false;
    };

    SliderHandler.prototype.itemAdd = function(e) {

        sliderId = $(this).attr('data-id');

        sliderType = $(this).attr('data-type');

        let data = {
            'action' 	: 'AdminAjaxSlider::itemAdd',
            'sliderId' 	: sliderId,
        };

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {
            show_message(response.message, response.status);
            if(response.status === 'success') {
                window.location = base + '/plugins?page=slider&view=detail&id=' + sliderId;
            }
            if(response.status === 'success') {
                location.reload();
            }
        });

        return false;
    };

    SliderHandler.prototype.itemSave = function(e) {

        let data = $(this).serializeJSON();

        data.action = 'AdminAjaxSlider::itemSave';

        data.id = itemId;

        data.type = sliderType;

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {
            show_message(response.message, response.status);
        });

        return false;
    };

    SliderHandler.prototype.itemSort = function(e) {

        let data = $(':input', $('#js_slider_item__sort')).serializeJSON();

        data.action = 'AdminAjaxSlider::itemSort';

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {
            show_message(response.message, response.status);
        });

        return false;
    };

    SliderHandler.prototype.itemDelete = function(e) {

        let box = $(this).closest('.js_slider_item');

        let id = box.attr('data-id');

        let data = {
            'action' : 'AdminAjaxSlider::itemDelete',
            'id'	 : id,
        };

        $.post(ajax, data, function(response) {}, 'json').done(function( response ) {
            show_message(response.message, response.status);
            if(response.status === 'success') {
                box.remove();
                if(id == itemId) {
                    itemId = 0;
                    $('#js_slider_item__edit').html('');
                }
            }
        });

        return false;
    };

    /**
     * Init AddToCartHandler.
     */
    if(typeof $('.list-sliders').html() != 'undefined') {
        new SliderHandler();
    }
});