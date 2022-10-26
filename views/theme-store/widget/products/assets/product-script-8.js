$(function () {
    let widgetLoad = [], tabId, widgetId, data_item = [];

    if('IntersectionObserver' in window) {
        let productListWidget = document.querySelectorAll('.js_product_style_8_data');
        let observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    let id = entry.target.getAttribute('data-id');
                    if (typeof widgetLoad[id] == 'undefined') {
                        widgetLoad[id] = id;
                        product_style_8_slider_load(entry.target.getAttribute('data-tab'), entry.target.getAttribute('data-id'), JSON.parse(entry.target.getAttribute('data-options')));
                    }
                }
            });
        });
        productListWidget.forEach(widget => {
            observer.observe(widget)
        });
    }
    else {
        $('.widget_product_style_8 .js_product_style_8_data').each(function () {
            let options = $(this).data('options');
            product_style_8_slider_load($(this).data('tab'), $(this).data('id'), options);
        });
    }

    $(document).on('click touch', '.product_style_8_header .product_style_8_category_list li.item a', function() {
        tabId = $(this).attr('data-tab');
        widgetId = $(this).closest('.js_product_style_8_data').attr('data-id');
        $('#product_style_8_header_' + widgetId +' .product_style_8_category_list li.item a').removeClass('active');
        $(this).closest('.js_product_style_8_data').attr('data-tab', tabId);
        $(this).addClass('active');
        product_style_8_slider_load(tabId, widgetId, $(this).closest('.product_style_8_header').data('options'));
        return false;
    });

    function product_style_8_slider(id, options) {
        if(options.display.type === 1) return false;
        let productList     = '#product_style_8_content_' + id + ' .swiper';
        let productBtnNext  = $('#product_style_8_content_' + id + ' .next');
        let productBtnPrev  = $('#product_style_8_content_' + id + ' .prev');
        function shouldBeEnabled(carousel, numberShow) {
            const slidesCount = carousel.find('.swiper-slide').length;
            if (slidesCount < numberShow) {
                return {loop: false, };
            }
            return {loop: true,};
        }
        let config = {
            ...shouldBeEnabled($(productList), parseInt(options.numberShow)),
            autoplay: {
                delay: parseInt(options.display.time)*1000
            },
            speed:500,
            slidesPerView: parseInt(options.numberShow),
            spaceBetween: parseInt(getComputedStyle(document.body).getPropertyValue('--bs-gutter-x')),
            breakpoints : {
                0: {
                    ...shouldBeEnabled($(productList), parseInt(options.numberShowMobile)),
                    slidesPerView: parseInt(options.numberShowMobile)
                },
                768: {
                    ...shouldBeEnabled($(productList), parseInt(options.numberShowTablet)),
                    slidesPerView: parseInt(options.numberShowTablet)
                },
                1200: {
                    ...shouldBeEnabled($(productList), parseInt(options.numberShow)),
                    slidesPerView: parseInt(options.numberShow)
                },
            },
        }
        let swiper = new Swiper(productList, config);
        productBtnNext.click(function () { swiper.slideNext(); });
        productBtnPrev.click(function () { swiper.slidePrev(); });
    }

    function product_style_8_slider_load(tabId, widgetId, options) {

        let productBox = $('.js_widget_product_style_8_' + widgetId);

        let productList = productBox.find('.list-product');

        let productLink = productBox.find('a.more-link');

        let loading     = productBox.find('.wg-loading');

        options.display.type = parseInt(options.display.type);

        productList.html('');

        loading.show();

        if (typeof data_item[widgetId] == 'undefined') data_item[widgetId] = [];

        if (typeof data_item[widgetId][tabId] != 'undefined') {
            productList.html(data_item[widgetId][tabId].item);
            productLink.attr('href', data_item[widgetId][tabId].url);
            loading.hide();
            product_style_8_slider(widgetId, options);
            return false;
        }
        else {
            let data = {
                action  : 'widget_product_style_8::loadProduct',
                widgetId   : widgetId,
                tabId : tabId,
            };
            $.post( ajax , data, function() {}, 'json').done(function(response) {
                loading.hide();
                if(response.status === 'success') {
                    productList.html(response.item);
                    productLink.attr('href', response.slug);
                    data_item[widgetId][tabId] = {
                        item : response.item,
                        url  : response.slug
                    };
                    product_style_8_slider(widgetId, options);
                }
            });
        }
    }
});