$(function(){
    $('.widget_feedback_style_1 .swiper').each(function () {
        let id = $(this).data('id');
        let timeout = parseInt($(this).data('time'))*1000;
        let sliderList = "#feedback_list_"+id;
        let sliderBtnNext = $('#feedback_btn_' + id + ' .next');
        let sliderBtnPrev = $('#feedback_btn_' + id + ' .prev');
        let config = {
            loop: true,
            autoplay: { delay: timeout },
            speed:500,
            slidesPerView: 1,
        }
        let swiper = new Swiper(sliderList, config);
        sliderBtnNext.click(function () { swiper.slideNext(); });
        sliderBtnPrev.click(function () { swiper.slidePrev(); })
    });
});