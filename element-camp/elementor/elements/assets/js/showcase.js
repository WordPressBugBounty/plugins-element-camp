(function ($) {
    "use strict";
    function elementcamp_showcase($scope, $) {
        const $slider = $scope.find('.tcgelements-showcase');
        const sliderSettings = $slider.data('tcgelements-showcase');
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

        // set slidesPerView to 1
        function oneSlideView(breakpoints) {
            Object.keys(breakpoints).forEach(key => {
                breakpoints[key].slidesPerView = 1;
            });
            return breakpoints;
        }

        function updateProgressBar(swiper) {
            if (sliderSettings.customProgress) {
                const totalSlides = swiper.slides.length - (swiper.loop ? 2 : 0);
                const currentIndex = swiper.realIndex + 1;
                const progressPercentage = (currentIndex / totalSlides) * 100;
                const progressBar = document.querySelector('.tcgelements-custom-progress .swiper-progress-bar');
                if (progressBar) {
                    progressBar.style.width = `${progressPercentage}%`;
                }
            }
        }

        function updateCustomPagination(swiper) {
            if (sliderSettings.customFraction) {
                const totalSlides = swiper.slides.length - (swiper.loop ? 2 : 0);
                const currentIndex = swiper.realIndex + 1;
                const customFraction = document.querySelector('.tcgelements-custom-fraction');
                if (customFraction) {
                    const slideMark = sliderSettings.slideMark;
                    customFraction.innerHTML = `<span class="slide-cont">${currentIndex}</span><span class="slide-mark">${slideMark}</span><span class="slide-all">${totalSlides}</span>`;
                }
            }
        }

        const swiperOptions = {
            loop: sliderSettings.loop === 'true',
            effect: sliderSettings.effect,
            speed: sliderSettings.speed,
            direction: sliderSettings.direction,
            oneWayMovement: sliderSettings.oneWayMovement === 'true',
            centeredSlides: sliderSettings.centeredSlides === 'true',
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
                    if (sliderSettings.customProgress) {
                        updateProgressBar(this);
                    }
                    if (sliderSettings.customFraction) {
                        updateCustomPagination(this);
                    }
                },
                resize: function () {
                    setSlideHeight(this);
                    this.update();
                },
                slideChange: function () {
                    if (sliderSettings.customProgress) {
                        updateProgressBar(this);
                    }
                    if (sliderSettings.customFraction) {
                        updateCustomPagination(this);
                    }
                },
            },
            observer: true,
            observeParents: true,
        };

        if (sliderSettings.effect === 'parallax') {
            swiperOptions.parallax = true;
            swiperOptions.breakpoints = sliderSettings.breakpoints;
            if (sliderSettings.parallaxChoose == 'all'){
                swiperOptions.on = {
                    init: function () {
                        var swiper = this;
                        for (var i = 0; i < swiper.slides.length; i++) {
                            $(swiper.slides[i])
                                .find('.item')
                                .attr({
                                    'data-swiper-parallax': 0.75 * swiper.width
                                });
                        }
                    },
                    resize: function () {
                        this.update();
                    }
                };
            }
        }
        if (sliderSettings.effect === 'material') {
            swiperOptions.materialEffect = {
                slideSplitRatio: 0.65
            };
            swiperOptions.modules = [EffectMaterial];
            swiperOptions.breakpoints = sliderSettings.breakpoints;
        }
        else if (sliderSettings.effect !== 'slide' && sliderSettings.effect !== 'coverflow' && sliderSettings.effect !== 'cards' && sliderSettings.effect !== 'material' && sliderSettings.effect !== 'parallax') {
            swiperOptions.breakpoints = oneSlideView(sliderSettings.breakpoints);
        } else {
            swiperOptions.breakpoints = sliderSettings.breakpoints;
        }

        if (sliderSettings.autoplay) {
            swiperOptions.autoplay = {
                delay: sliderSettings.autoplay.delay,
                reverseDirection: sliderSettings.autoplay.reverseDirection === 'true',
                disableOnInteraction: sliderSettings.autoplay.disableOnInteraction === 'true',
            };
        }

        if (sliderSettings.effect === 'cards') {
            swiperOptions.cardsEffect = {
                rotate: true,
                slideShadows: false,
                perSlideOffset: sliderSettings.cardsOffset,
                perSlideRotate: sliderSettings.cardsRotate,
            };
        }

        const swiper = new Swiper($swiperContainer.get(0), swiperOptions);
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-showcase.default', elementcamp_showcase);
    });

})(jQuery);