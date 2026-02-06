(function ($) {
    "use strict";

    function elementcamp_gallery_text_sliders($scope, $) {
        var galleryText = new Swiper('.tcgelements-gallery-text-sliders .gallery-text .swiper-container', {
            spaceBetween: 30,
            slidesPerView: 1,
            direction: 'vertical',
            loop: true,
            loopedSlides: 4,
            touchRatio: 0.2,
            slideToClickedSlide: true,
            speed: 1500,
        });

        var galleryImg = new Swiper('.tcgelements-gallery-text-sliders .gallery-img .swiper-container', {
            spaceBetween: 60,
            centeredSlides: true,
            loop: true,
            loopedSlides: 4,
            speed: 1500,
            navigation: {
                nextEl: '.tcgelements-gallery-text-sliders .swiper-controls .swiper-button-next',
                prevEl: '.tcgelements-gallery-text-sliders .swiper-controls .swiper-button-prev',
            },
            pagination: {
                el: '.tcgelements-gallery-text-sliders .swiper-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' + '<svg class="fp-arc-loader" width="16" height="16" viewBox="0 0 16 16">' +
                        '<circle class="path" cx="8" cy="8" r="5.5" fill="none" transform="rotate(-90 8 8)" stroke="#FFF"' +
                        'stroke-opacity="1" stroke-width="1px"></circle>' +
                        '<circle cx="8" cy="8" r="3" fill="#FFF"></circle>' +
                        '</svg></span>';
                },
            },
            keyboard: {
                enabled: true,
            },

            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });

        galleryImg.on("slideChangeTransitionStart", function () {
            galleryText.slideTo(galleryImg.activeIndex);
        });
        galleryText.on("transitionStart", function () {
            galleryImg.slideTo(galleryText.activeIndex);
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-gallery-text-sliders.default', elementcamp_gallery_text_sliders);
    });

})(jQuery);
