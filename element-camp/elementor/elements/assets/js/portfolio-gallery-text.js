(function ($) {
    "use strict";

    function elementcamp_portfolio_gallery_text($scope, $) {
        var $galleryImgContainer = $scope.find('.gallery-img');
        var sliderSettingsAttr = $galleryImgContainer.data('tcgelements-portfolio-gallery-image') || {}; // Get settings from attribute
        // Default settings (fall back to these if not provided in attribute)
        var defaultSliderSettings = {
            spaceBetween: 40,
            centeredSlides: true,
            loop: true,
            loopedSlides: 4,
            speed: 1500,
            navigation: {
                nextEl: '.tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-next',
                prevEl: '.tcgelements-portfolio-gallery-text .swiper-controls .swiper-button-prev',
            },
            pagination: {
                el: '.tcgelements-portfolio-gallery-text .swiper-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '">' +
                        '<svg class="fp-arc-loader" width="16" height="16" viewBox="0 0 16 16">' +
                        '<circle class="path" cx="8" cy="8" r="5.5" fill="none" transform="rotate(-90 8 8)" stroke="#FFF" stroke-opacity="1" stroke-width="1px"></circle>' +
                        '<circle cx="8" cy="8" r="3" fill="#FFF"></circle>' +
                        '</svg></span>';
                }
            },
            keyboard: {
                enabled: true,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                },
                640: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                },
                768: {
                    slidesPerView: 1,
                    spaceBetween: 40,
                },
                1024: {
                    slidesPerView: 2,
                },
            }
        };
        var finalSliderSettings = $.extend(true, {}, defaultSliderSettings, sliderSettingsAttr);

        // text slider
        var galleryTextSettings = {
            spaceBetween: 30,
            slidesPerView: 1,
            direction: 'vertical',
            loop: true,
            loopedSlides: 4,
            touchRatio: 0.2,
            slideToClickedSlide: true,
            speed: 1500,
        };
        if (finalSliderSettings.mousewheel === true || finalSliderSettings.mousewheel === 'true') {
            galleryTextSettings.mousewheel = true;
        }
        var galleryText = new Swiper('.tcgelements-portfolio-gallery-text .gallery-text .swiper-container', galleryTextSettings);
        // image slider
        // Merge attribute settings with default settings
        if (finalSliderSettings.mousewheel === true || finalSliderSettings.mousewheel === 'true') {
            finalSliderSettings.mousewheel = true;
        }
        var galleryImg = new Swiper('.tcgelements-portfolio-gallery-text .gallery-img .swiper-container', finalSliderSettings);
        galleryImg.on("slideChangeTransitionStart", function () {
            galleryText.slideTo(galleryImg.activeIndex);
        });
        galleryText.on("transitionStart", function () {
            galleryImg.slideTo(galleryText.activeIndex);
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-portfolio-gallery-text.default', elementcamp_portfolio_gallery_text);
    });

})(jQuery);
