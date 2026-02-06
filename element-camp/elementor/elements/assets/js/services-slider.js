(function ($) {
    "use strict";

    // Split Text Animation Manager
    window.TCGSplitTextAnimation = window.TCGSplitTextAnimation || {
        currentSplits: [],
        isAnimating: false,
        animationTimeout: null,

        animateSplitText: function(slide) {
            if (typeof gsap === 'undefined' || typeof SplitText === 'undefined' || !slide) {
                console.warn('GSAP or SplitText not available');
                return;
            }

            if (this.isAnimating) {
                clearTimeout(this.animationTimeout);
            }

            this.isAnimating = true;
            this.cleanup();

            const animationContainers = slide.querySelectorAll('.tcg-split-text-animation');

            if (animationContainers.length === 0) {
                this.isAnimating = false;
                return;
            }

            const textElements = [];
            animationContainers.forEach((container) => {
                const headings = container.querySelectorAll('h1, h2, h3, h4, h5, h6');
                const paragraphs = container.querySelectorAll('p');
                const spans = container.querySelectorAll('span');

                textElements.push(...headings, ...paragraphs, ...spans);
            });

            if (textElements.length === 0) {
                this.isAnimating = false;
                return;
            }

            // Use requestAnimationFrame to ensure DOM is ready
            requestAnimationFrame(() => {
                textElements.forEach((element, index) => {
                    try {
                        const splitInstance = new SplitText(element, {
                            type: "words,chars"
                        });

                        this.currentSplits.push(splitInstance);

                        // Set initial state
                        gsap.set(splitInstance.chars, {
                            opacity: 0,
                            y: 50
                        });

                        // Animate
                        gsap.to(splitInstance.chars, {
                            opacity: 1,
                            y: 0,
                            duration: 0.6,
                            stagger: 0.05,
                            ease: "power2.out",
                            delay: index * 0.1,
                            onComplete: () => {
                                console.log('Animation complete for element', index);
                            }
                        });
                    } catch (error) {
                        console.error('Split text error:', error);
                    }
                });
            });

            setTimeout(() => {
                this.isAnimating = false;
            }, 100);
        },

        cleanup: function() {
            if (this.currentSplits.length > 0) {
                this.currentSplits.forEach((split) => {
                    try {
                        if (split?.chars) {
                            gsap.killTweensOf(split.chars);
                        }
                        if (split?.revert) {
                            split.revert();
                        }
                    } catch (error) {
                        console.error('Cleanup error:', error);
                    }
                });
            }

            this.currentSplits = [];
        },

        initialize: function(swiper) {
            if (!swiper || typeof gsap === 'undefined' || typeof SplitText === 'undefined') {
                console.warn('Cannot initialize: missing swiper, GSAP, or SplitText');
                return;
            }

            this.destroy();

            const activeSlide = swiper.slides[swiper.activeIndex];
            if (activeSlide) {
                console.log('Initializing animation for active slide');
                this.animateSplitText(activeSlide);
            }

            swiper.on('slideChangeTransitionStart', () => {
                const currentSlide = swiper.slides[swiper.activeIndex];
                if (currentSlide) {
                    console.log('Slide changed, animating new slide');
                    this.animateSplitText(currentSlide);
                }
            });
        },

        destroy: function() {
            clearTimeout(this.animationTimeout);
            this.isAnimating = false;
            this.cleanup();
        }
    };

    // Shared slider function
    function elementcamp_services_slider($scope, $) {
        const $slider = $scope.find('.tcgelements-services-slider');
        const sliderSettings = $slider.data('tcgelements-services-slider');
        const $swiperContainer = $slider.find('.swiper-container');
        const $nextArrow = $slider.find('.swiper-button-next');
        const $prevArrow = $slider.find('.swiper-button-prev');
        const $pagination = $slider.find('.swiper-pagination');
        const $scrollbar = $slider.find('.swiper-scrollbar');

        function handleNthSlides(swiper) {
            if (sliderSettings.enableNthSlides !== 'true') return;

            // Remove existing nth classes
            swiper.slides.removeClass('swiper-slide-nth-prev-2 swiper-slide-nth-next-2 swiper-slide-nth-prev-3 swiper-slide-nth-next-3');

            const activeSlide = $(swiper.slides[swiper.activeIndex]);
            const realIndex = swiper.realIndex;

            // Add nth-prev-2, nth-next-2, nth-prev-3, and nth-next-3 classes
            if (swiper.loop) {
                // For loop mode, use realIndex logic
                swiper.slides.each(function(index) {
                    const slideRealIndex = $(this).attr('data-swiper-slide-index');
                    if (slideRealIndex) {
                        const slideIndex = parseInt(slideRealIndex);
                        const totalSlides = swiper.slides.length - 2; // Exclude duplicated slides

                        // Calculate nth-prev-2
                        const prevIndex2 = (realIndex - 2 + totalSlides) % totalSlides;
                        if (slideIndex === prevIndex2) {
                            $(this).addClass('swiper-slide-nth-prev-2');
                        }

                        // Calculate nth-next-2
                        const nextIndex2 = (realIndex + 2) % totalSlides;
                        if (slideIndex === nextIndex2) {
                            $(this).addClass('swiper-slide-nth-next-2');
                        }

                        // Calculate nth-prev-3
                        const prevIndex3 = (realIndex - 3 + totalSlides) % totalSlides;
                        if (slideIndex === prevIndex3) {
                            $(this).addClass('swiper-slide-nth-prev-3');
                        }

                        // Calculate nth-next-3
                        const nextIndex3 = (realIndex + 3) % totalSlides;
                        if (slideIndex === nextIndex3) {
                            $(this).addClass('swiper-slide-nth-next-3');
                        }
                    }
                });
            } else {
                // For non-loop mode, use direct index
                const prevSlide2 = activeSlide.prevAll().eq(1);
                const nextSlide2 = activeSlide.nextAll().eq(1);
                const prevSlide3 = activeSlide.prevAll().eq(2);
                const nextSlide3 = activeSlide.nextAll().eq(2);

                if (prevSlide2.length) {
                    prevSlide2.addClass('swiper-slide-nth-prev-2');
                }
                if (nextSlide2.length) {
                    nextSlide2.addClass('swiper-slide-nth-next-2');
                }
                if (prevSlide3.length) {
                    prevSlide3.addClass('swiper-slide-nth-prev-3');
                }
                if (nextSlide3.length) {
                    nextSlide3.addClass('swiper-slide-nth-next-3');
                }
            }
        }

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
                    handleNthSlides(this);

                    // Initialize split text animation
                    console.log('Swiper initialized, starting animation manager');
                    window.TCGSplitTextAnimation.initialize(this);
                },
                slideChangeTransitionStart: function () {
                    handleNthSlides(this);
                },
                slideChange: function () {
                    handleNthSlides(this);
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

        if (sliderSettings.effect === 'material') {
            swiperOptions.materialEffect = {
                slideSplitRatio: 0.65
            };
            swiperOptions.modules = [EffectMaterial];
            swiperOptions.breakpoints = sliderSettings.breakpoints;
        }

        if (sliderSettings.effect === 'gl') {
            swiperOptions.modules = [window.SwiperGL];
            var shaderOption = $slider.data('shader-option');
            swiperOptions.gl = {
                shader: shaderOption || 'random',
            };
            swiperOptions.breakpoints = oneSlideView(sliderSettings.breakpoints);
        }
        else if (sliderSettings.effect !== 'slide' && sliderSettings.effect !== 'coverflow' && sliderSettings.effect !== 'cards' && sliderSettings.effect !== 'material') {
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
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-services-slider.default', elementcamp_services_slider);
    });
})(jQuery);