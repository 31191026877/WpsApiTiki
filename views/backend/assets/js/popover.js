let popover_typingTimer;
let popover_input = $('.input-popover-group .input-popover-search');
let popover_search_result = [];
let popover_result = [];
function input_popover_collection_item_multiple(key, name, text) {
    item = '<li class="collection-list__li_' + key + '"><input type="checkbox" name="' + name + '[]" value="' + key + '" checked><div class="collection-list__grid">';
    item += '<div class="collection-list__cell"><a href="">' + text + '</a></div>';
    item += '<div class="collection-list__cell"> <button class="ui-button collection-list-delete" data-key="' + key + '"> <i class="fal fa-times"></i> </button> </div> </div> </li>';
    return item;
}
function input_popover_collection_item(key, name, text) {
    item = '<li class="collection-list__li_' + key + '"><input type="checkbox" name="' + name + '" value="' + key + '" checked><div class="collection-list__grid">';
    item += '<div class="collection-list__cell"><a href="">' + text + '</a></div>';
    item += '<div class="collection-list__cell"> <button class="ui-button collection-list-delete" data-key="' + key + '"> <i class="fal fa-times"></i> </button> </div> </div> </li>';
    return item;
}
function popover_startToSearch(e) {
    let box = e.closest('.input-popover-group');

    let module = box.data('module');

    let key_type  = box.data('key-type');

    let keyword = box.find('.input-popover-search').val();

    keyword = keyword.trim();

    let id_popover_index = box.attr('id') + module + key_type + keyword;

    box.find('.popover__loading').show();

    box.find('.popover__ul').hide();

    let input = [];

    box.find('input[type="checkbox"]').each(function(){
        input[$(this).val()] = $(this).val();
    });

    if (keyword.length === 0) {

        box.find('.popover__loading').hide();
        box.find('.popover__ul').show();
        box.find('.popover__ul').html(popover_result[id_popover_index]);

        if (typeof input != 'undefined' && input.length === 0) {
            box.find('.popover__ul li.option').removeClass('option--is-active');
            popover_result[id_popover_index] = box.find('.popover__ul').html();
        } else {
            box.find('.popover__ul li.option').each(function () {
                let key = $(this).attr('data-key');
                if (typeof input[key] != 'undefined' && input[key] === key) $(this).addClass('option--is-active');
            });
        }

        return false;
    }

    if (typeof popover_search_result[id_popover_index] != 'undefined') {
        box.find('.popover__loading').hide();
        box.find('.popover__ul').show();
        box.find('.popover__ul').html(popover_search_result[id_popover_index]);
    } else {
        let data = {
            'keyword' : keyword,
            'select'  : input,
            'module'  : module,
            'key_type': key_type,
            'action'  : 'ajax_input_popover_search',
        };
        $.post(ajax, data, function () {}, 'json').done(function (data) {
            box.find('.popover__loading').hide();
            box.find('.popover__ul').show();
            box.find('.popover__ul').html(data.data);
            popover_search_result[id_popover_index] = data.data;
        });
    }
}
$(document).on('click', '.input-popover-group .popover__ul li.option', function () {
    let box      = $(this).closest('.input-popover-group');
    let multiple = box.attr('data-multiple');
    let text     = $(this).find('.label-option').text();
    let key      = $(this).attr('data-key');
    box.find('.input-popover-search').val('');
    if (multiple == 'false') {
        if ($(this).hasClass('option--is-active')) {
            box.find('.collection-list__li_' + key).remove();
        }
        else {
            name = box.attr('data-name');
            box.find('.collection-list').html(input_popover_collection_item(key, name, text));
        }
        $('.popover-content').removeClass('popover-content--is-active');
    }
    else {
        if ($(this).hasClass('option--is-active')) {
            box.find('.collection-list__li_' + key).remove();
        }
        else {
            name = box.attr('data-name');
            box.find('.collection-list').prepend(input_popover_collection_item_multiple(key, name, text));
        }
    }
    $(this).toggleClass('option--is-active');
    return false;
});
$(document).on('click', '.input-popover-group .collection-list-delete', function () {

    let box = $(this).closest('.input-popover-group');

    let key = $(this).attr('data-key');

    box.find('.option-' + key).toggleClass('option--is-active');

    box.find('.collection-list__li_' + key).remove();

    return false;
});
$(document).on('click', function (e) {
    $('.input-popover-search').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.input-popover-group').has(e.target).length === 0) {
            $('.popover-content').removeClass('popover-content--is-active');
        }
    });
});
popover_input.on('keyup', function (event) {
    let self = $(this);
    let keyword = self.val();

    $('.popover-content').removeClass('popover-content--is-active');
    self.closest('.input-popover-group').find('.popover-content').addClass('popover-content--is-active');
    self.closest('.input-popover-group').find('.popover__ul li.option').first().addClass('is--select');

    if (event.which === 13) {
        if (keyword === "") {
            return false;
        }
    } else {
        let waitTyping = 1000;
        clearTimeout(popover_typingTimer);
        popover_typingTimer = setTimeout(function () {
            if (keyword !== "") {
                popover_startToSearch(self);
            }
        }, waitTyping);
    }
});
popover_input.each(function(){
    let box = $(this).closest('.input-popover-group');
    let module      = box.data('module');
    let key_type    = box.data('key-type');
    let id_popover_index = box.attr('id') + module + key_type;
    popover_result[id_popover_index] = $(this).closest('.input-popover-group').find('.popover__ul').html();
});
$(document).on('focus', '.input-popover-group .input-popover-search', function () {
    popover_startToSearch($(this));
    $('.popover-content').removeClass('popover-content--is-active');
    $(this).closest('.input-popover-group').find('.popover-content').addClass('popover-content--is-active');
    $(this).closest('.input-popover-group').find('.popover__ul li.option').first().addClass('is--select');
});
$(document).on('mouseover', '.input-popover-group .popover__ul li.option', function () {
    $('.input-popover-group .popover__ul li.option').removeClass('is--select');
});

let popover_advance_timer;
let popover_advance_search_result = [];
$(function () { popover_load(); });
$(document).on('focus', '.popover_advance .popover_advance__search', function () {
    $('.popover_advance__box .panel-default').removeClass('active');
    $(this).closest('.popover_advance__box').find('.panel-default').addClass('active');
    popover_advance_search($(this))
});
$(document).on('click', function (e) {
    $('.popover_advance__box').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover_advance__box').has(e.target).length === 0) {
            $('.popover_advance__box .panel-default').removeClass('active');
        }
    });
});
$(document).on('keyup', '.popover_advance__box .popover_advance__search', function (event) {
    let self = $(this);
    let keyword = self.val();

    $('.popover_advance__box .panel-default').removeClass('active');
    self.closest('.popover_advance__box').find('.panel-default').addClass('active');

    if (event.which === 13) {
        if (keyword === "") { return false; }
    } else {
        let waitTyping = 500;
        clearTimeout(popover_advance_timer);
        popover_advance_timer = setTimeout(function () {
            if (keyword !== "") {
                self.closest('.popover_advance__box').find('.pagination').attr('data-page-current', 1);
                popover_advance_search(self);
            }
        }, waitTyping);
    }
});
$(document).on('click', '.popover_advance a.pagination__link', function () {
    let navigation = $(this).closest('.pagination');
    let page = parseInt(navigation.attr('data-page-current'));
    let type = $(this).data('type');
    if(page === 1 && type === 'prev') return false;
    page += (type === 'next') ? 1 : -1;
    navigation.attr('data-page-current', page);
    popover_advance_search($(this).closest('.popover_advance').find('.popover_advance__search'), type);
    return false;
});
$(document).on('click', '.popover_advance .popover_advance__item', function () {
    let box         = $(this).closest('.popover_advance');
    let template    = box.attr('data-template-load');
    let item        = $(this).data('item');
    let id          = $(this).attr('data-id');
    let multiple    = box.attr('data-multiple');
    let name        = box.attr('data-name');
    if (multiple === 'false') {
        box.find('.popover_advance__list').html(popover_advance_collection_item(template, item, name, false));
    }
    else {
        if (typeof box.find('.popover_advance__list .popover_advance__item_result_' + id).html() == 'undefined') {
            box.find('.popover_advance__list').prepend(popover_advance_collection_item(template, item, name, true));
        }
    }
    box.find('.popover_advance__box .panel-default').removeClass('active');
    return false;
});
$(document).on('click', '.popover_advance .popover_advance__list .item .item__btn_delete', function () {
    $(this).closest('.popover_advance__item_result').remove();
    return false;
});
function popover_load() {
    $('.popover_advance').each(function () {
        let popoverDataLoad = $(this).data('load');
        let template    = $(this).attr('data-template-load');
        let multiple = $(this).data('multiple');
        multiple = (multiple == 'true' || multiple == 1) ? true : false;
        let popoverField = $(this).data('name');
        let itemsHtml = '';
        for (const [key, items_tmp] of Object.entries(popoverDataLoad)) {
            let items = [items_tmp];
            items.map(function(item) {
                itemsHtml += popover_advance_collection_item(template, item, popoverField, multiple);
            });
        }
        $(this).find('.popover_advance__list').html(itemsHtml);
    });
}
function popover_advance_search(e, paginationAction = '') {

    let limit = 15;

    let action   = decodeURIComponent(e.data('action'));

    let taxonomy = e.data('taxonomy');

    let box = e.closest('.popover_advance__box');

    let keyword = e.val(); keyword = keyword.trim();

    let template = box.closest('.popover_advance').attr('data-template');

    let pagination = box.find('.pagination');

    let page = pagination.attr('data-page-current');

    let id_popover_index = box.attr('id') + action + taxonomy + keyword + page;

    let loading = box.find('.loading');

    let input = [];

    box.find('input[type="checkbox"]').each(function(){
        input[$(this).val()] = $(this).val();
    });

    loading.show();

    let paginationHtml = '';

    if (typeof popover_advance_search_result[id_popover_index] != 'undefined') {
        loading.hide();
        box.find('.popover_advance__search__data').html(popover_advance_search_result[id_popover_index].html);
        if(page == 1) {
            if(popover_advance_search_result[id_popover_index].total >= limit) {
                paginationHtml = '<li class="pagination__item disabled"><span class="pagination__link" data-type="prev">« Trước</span></li><li class="pagination__item"><a class="pagination__link" href="#" data-type="next">Sau »</a></li>';
            }
        }
        else if((paginationAction == 'next' && popover_advance_search_result[id_popover_index].total < limit) || popover_advance_search_result[id_popover_index].total == 0) {
            paginationHtml = '<li class="pagination__item"><a class="pagination__link" href="#" data-type="prev">« Trước</a></li><li class="pagination__item disabled"><span class="pagination__link" data-type="next">Sau »</span></li>';
        }
        else {
            paginationHtml = '<li class="pagination__item"><a class="pagination__link" href="#" data-type="prev">« Trước</a></li><li class="pagination__item"><a class="pagination__link" href="#" data-type="next">Sau »</a></li>'
        }

        pagination.html(paginationHtml);
    } else {
        let data = {'keyword' : keyword, 'select': input, 'taxonomy' : taxonomy,  'action':action, 'page' : page, 'limit' : limit};
        $.post(ajax, data, function () {}, 'json').done(function (response) {
            loading.hide();
            let itemsData = response.items;
            let itemsHtml = '';
            for (const [key, items_tmp] of Object.entries(itemsData)) {
                let items = [items_tmp];
                items.map(function(item) {
                    itemsHtml += $('#' + template).html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
                });
            }
            box.find('.popover_advance__search__data').html(itemsHtml);
            popover_advance_search_result[id_popover_index] = {
                total : response.total,
                html : itemsHtml
            };
            if(page == 1) {
                if(response.total >= limit) {
                    paginationHtml = '<li class="pagination__item disabled"><span class="pagination__link" data-type="prev">« Trước</span></li><li class="pagination__item"><a class="pagination__link" href="#" data-type="next">Sau »</a></li>';
                }
            }
            else if((paginationAction == 'next' && response.total < limit) || response.total == 0) {
                paginationHtml = '<li class="pagination__item"><a class="pagination__link" href="#" data-type="prev">« Trước</a></li><li class="pagination__item disabled"><span class="pagination__link" data-type="next">Sau »</span></li>';
            }
            else {
                paginationHtml = '<li class="pagination__item"><a class="pagination__link" href="#" data-type="prev">« Trước</a></li><li class="pagination__item"><a class="pagination__link" href="#" data-type="next">Sau »</a></li>'
            }

            pagination.html(paginationHtml);
        });
    }
}
function popover_advance_collection_item(template, item, field, multiple = true) {
    // console.log(template);
    // console.log($('#'+template).html());
    item.field = field + ((multiple === true) ? '[]' : '');
    return $('#'+template).html().split(/\$\{(.+?)\}/g).map(render(item)).join('');
}