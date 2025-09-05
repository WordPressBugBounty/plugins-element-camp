(function ($) {
    "use strict";
    function elementcamp_tip_showcase($scope, $) {
        if ($scope.find('.tcgelements-tip-showcase').hasClass('slider')){
            // slider script
            const $slider = $scope.find('.tcgelements-tip-showcase');
            const sliderSettings = $slider.data('tcgelements-tip-showcase');
            const $swiperContainer = $slider.find('.swiper-container');
            const $nextArrow = $slider.find('.swiper-button-next');
            const $prevArrow = $slider.find('.swiper-button-prev');
            const $pagination = $slider.find('.swiper-pagination');
            const $scrollbar = $slider.find('.swiper-scrollbar');

            // fix swiper vertical slider height bug
            function setSlideHeight(swiper) {
                if (sliderSettings.autoHeight === 'true' && sliderSettings.direction !== 'vertical') {
                    return;
                }

                const currentSlide = swiper.slides[swiper.activeIndex];
                const newHeight = $(currentSlide).height();

                $swiperContainer.find('.swiper-wrapper, .swiper-slide').css({ height: newHeight });
                swiper.update();
            }
            const swiperOptions = {
                loop: sliderSettings.loop === 'true',
                effect: 'slide',
                direction: 'horizontal',
                speed: sliderSettings.speed,
                oneWayMovement: sliderSettings.oneWayMovement === 'true',
                centeredSlides: sliderSettings.centeredSlides === 'true',
                breakpoints : sliderSettings.breakpoints,
                autoHeight: sliderSettings.autoHeight === 'true',
                mousewheel: {
                    enabled: sliderSettings.mousewheel === 'true',
                },
                keyboard: {
                    enabled: sliderSettings.keyboard === 'true',
                },
                navigation: {
                    nextEl: $nextArrow.get(0),
                    prevEl: $prevArrow.get(0),
                },
                pagination: {
                    el: $pagination.get(0),
                    type: sliderSettings.paginationType,
                    clickable: true
                },
                scrollbar: {
                    el: $scrollbar.get(0),
                    draggable: true,
                    snapOnRelease: true,
                },
                on: {
                    init: function () {
                        setSlideHeight(this);
                    },
                    resize: function () {
                        setSlideHeight(this);
                        this.update();
                    },
                },
                observer: true,
                observeParents: true,
            };


            if (sliderSettings.autoplay) {
                swiperOptions.autoplay = {
                    delay: sliderSettings.autoplay.delay,
                    reverseDirection: sliderSettings.autoplay.reverseDirection === 'true',
                    disableOnInteraction: sliderSettings.autoplay.disableOnInteraction === 'true',
                };
            }

            const swiper = new Swiper($swiperContainer.get(0), swiperOptions);
        }

        //  tool tip script
        $('[data-tooltip-tit]').hover(function () {
            $('<div class="div-tooltip-tit"></div>').text($(this).attr('data-tooltip-tit')).appendTo('.tcgelements-tip-showcase').fadeIn('slow');
        }, function () {
            $('.div-tooltip-tit').remove();
        }).mousemove(function (e) {
            $('.div-tooltip-tit').css({top: e.clientY + 10, left: e.clientX + 20})
        });
        $('[data-tooltip-sub]').hover(function () {
            $('<div class="div-tooltip-sub"></div>').text($(this).attr('data-tooltip-sub')).appendTo('.tcgelements-tip-showcase').fadeIn('slow');
        }, function () {
            $('.div-tooltip-sub').remove();
        }).mousemove(function (e) {
            $('.div-tooltip-sub').css({top: e.clientY + 60, left: e.clientX + 20})
        });

    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-tip-showcase.default', elementcamp_tip_showcase);
    });
})(jQuery)
