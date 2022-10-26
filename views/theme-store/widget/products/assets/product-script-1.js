$(function () {
    let widgetLoad = [], categoryId, widgetId, data_item = [];
    if('IntersectionObserver' in window) {
        let productListWidget = document.querySelectorAll('.js_product_style_1_data');
        let observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    let id = entry.target.getAttribute('data-id');
                    if (typeof widgetLoad[id] == 'undefined') {
                        widgetLoad[id] = id;
                        product_style_1_slider_load(entry.target.getAttribute('data-category'), entry.target.getAttribute('data-id'), JSON.parse(entry.target.getAttribute('data-options')));
                    }
                }
            });
        });
        productListWidget.forEach(widget => {
            observer.observe(widget)
        });
    }
    else {
        $('.widget_product_style_1 .js_product_style_1_data').each(function () {
            let options = $(this).data('options');
            product_style_1_slider_load($(this).data('category'), $(this).data('id'), options);
        });
    }
    function product_style_1_slider(id, options) {
        if(options.display.type === 1) return false;
        let productList     = '#product_style_1_content_' + id + ' .swiper';
        let productBtnNext  = $('#product_style_1_content_' + id + ' .next');
        let productBtnPrev  = $('#product_style_1_content_' + id + ' .prev');
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
        productBtnPrev.click(function () { swiper.slidePrev(); })
    }
    function product_style_1_slider_load(categoryId, widgetId, options) {
        let productBox = $('.js_widget_product_style_1_' + widgetId);
        let productList = productBox.find('.list-product');
        let productLink = productBox.find('a.more-link');
        let loading     = productBox.find('.wg-loading');
        options.display.type = parseInt(options.display.type);
        productList.html('');
        loading.show();
        if (typeof data_item[widgetId] == 'undefined') data_item[widgetId] = [];
        if (typeof data_item[widgetId][categoryId] != 'undefined') {
            productList.html(data_item[widgetId][categoryId].item);
            productLink.attr('href', data_item[widgetId][categoryId].url);
            loading.hide();
            product_style_1_slider(widgetId, options);
            return false;
        }
        else {
            let data = {
                action  : 'widget_product_style_1::loadProduct',
                widgetId   : widgetId,
                categoryId : categoryId,
            };
            $.post( ajax , data, function() {}, 'json').done(function(response) {
                loading.hide();
                if(response.status === 'success') {
                    productList.html(response.item);
                    productLink.attr('href', response.slug);
                    data_item[widgetId][categoryId] = {
                        item : response.item,
                        url  : response.slug
                    };
                    product_style_1_slider(widgetId, options);
                }
            });
        }
    }
});
