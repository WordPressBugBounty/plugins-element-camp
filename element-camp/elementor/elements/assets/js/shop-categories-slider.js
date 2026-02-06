(function ($) {
    "use strict";

    function elementcamp_shop_categories_slider($scope, $) {
        const $slider = $scope.find('.tcgelements-shop-categories-slider');
        const sliderSettings = $slider.data('slider-settings');
        const $swiperContainer = $slider.find('.swiper-container');
        const $nextArrow = $slider.find('.swiper-button-next');
        const $prevArrow = $slider.find('.swiper-button-prev');
        const $pagination = $slider.find('.swiper-pagination');

        const swiperOptions = {
            loop: sliderSettings.loop === 'true',
            effect: sliderSettings.effect,
            speed: sliderSettings.speed,
            direction: sliderSettings.direction,
            centeredSlides: sliderSettings.centeredSlides === 'true',
            navigation: {
                nextEl: $nextArrow.get(0),
                prevEl: $prevArrow.get(0),
            },
            pagination: {
                el: $pagination.get(0),
                type: sliderSettings.paginationType,
                clickable: true
            },
            breakpoints: sliderSettings.breakpoints,
        };

        if (sliderSettings.autoplay) {
            swiperOptions.autoplay = {
                delay: sliderSettings.autoplay.delay,
            };
        }

        const swiper = new Swiper($swiperContainer.get(0), swiperOptions);
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-shop-categories.default', elementcamp_shop_categories_slider);
    });
})(jQuery);