(function ($) {
    "use strict";
    // Shared slider function
    function elementcamp_gallery_slider($scope, $) {
        const $slider = $scope.find('.tcgelements-gallery-slider');
        const sliderSettings = $slider.data('tcgelements-gallery-slider');
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
                },
                resize: function () {
                    setSlideHeight(this);
                    this.update();
                },
            },
            observer: true,
            observeParents: true,
        };

        if (sliderSettings.effect === 'parallax') {
            swiperOptions.parallax = true;
            swiperOptions.breakpoints = oneSlideView(sliderSettings.breakpoints);
        }
        else if (sliderSettings.effect === 'coverflow') {
            swiperOptions.coverflowEffect = {
                rotate: sliderSettings.coverflowRotate !== undefined ? sliderSettings.coverflowRotate : 50,
                stretch: sliderSettings.coverflowStretch !== undefined ? sliderSettings.coverflowStretch : 0,
                depth: sliderSettings.coverflowDepth !== undefined ? sliderSettings.coverflowDepth : 100,
                modifier: sliderSettings.coverflowModifier !== undefined ? sliderSettings.coverflowModifier : 1,
                slideShadows: sliderSettings.coverflowSlideShadows === 'true'
            };
            swiperOptions.breakpoints = sliderSettings.breakpoints;
        }
        else if (sliderSettings.effect === 'material') {
            swiperOptions.materialEffect = {
                slideSplitRatio: 0.65
            };
            swiperOptions.modules = [EffectMaterial];
            swiperOptions.breakpoints = sliderSettings.breakpoints;
        }
        else if (sliderSettings.effect === 'panorama') {
            swiperOptions.modules = [EffectPanorama];
            swiperOptions.panoramaEffect = {
                depth: sliderSettings.panoramaDepth,
                rotate: sliderSettings.panoramaRotate,
            };
            swiperOptions.breakpoints = sliderSettings.breakpoints;

        }
        else if (sliderSettings.effect !== 'slide' && sliderSettings.effect !== 'coverflow' && sliderSettings.effect !== 'cards' && sliderSettings.effect !== 'material' && sliderSettings.effect !== 'panorama') {
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

    function tce_float_cursor($scope, $) {
        const floatCursorContainers = document.querySelectorAll('.tce-float-cursor-container');
        const isRTL = $('body').css('direction') === 'rtl';

        floatCursorContainers.forEach(container => {
            const floatCursor = container.querySelector('.tce-float-cursor');
            let mouseX = 0, mouseY = 0;
            let isMoving = false;

            // When mouse enters the container
            container.addEventListener('mouseenter', () => {
                floatCursor.style.opacity = '1';
                floatCursor.style.transform = 'scale(1)';
            });

            // When mouse moves within the container
            container.addEventListener('mousemove', (e) => {
                const rect = container.getBoundingClientRect();
                mouseX = isRTL ? rect.right - e.clientX - 75 : e.clientX - rect.left - 75;
                mouseY = e.clientY - rect.top - 75;
                isMoving = true;
            });

            // Update cursor position using requestAnimationFrame for smoother performance
            function updateCursor() {
                if (isMoving) {
                    floatCursor.style.left = isRTL ? 'auto' : `${mouseX}px`;
                    floatCursor.style.right = isRTL ? `${mouseX}px` : 'auto';
                    floatCursor.style.top = `${mouseY}px`;
                    isMoving = false;
                }
                requestAnimationFrame(updateCursor);
            }

            updateCursor();  // Start the update loop

            // When mouse leaves the container
            container.addEventListener('mouseleave', () => {
                floatCursor.style.opacity = '0';
                floatCursor.style.transform = 'scale(0)';
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-gallery-slider.default',elementcamp_gallery_slider) ;
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-gallery-slider.default', tce_float_cursor);

    });
})(jQuery);