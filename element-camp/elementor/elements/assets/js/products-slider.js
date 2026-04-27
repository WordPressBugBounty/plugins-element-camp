(function ($) {
    "use strict";

    // Debounce utility for performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Throttle utility for smoother animations
    function throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // RequestAnimationFrame wrapper for smooth animations
    function smoothAnimation(callback) {
        let rafId = null;
        return function(...args) {
            if (rafId) cancelAnimationFrame(rafId);
            rafId = requestAnimationFrame(() => {
                callback.apply(this, args);
                rafId = null;
            });
        };
    }

    function elementcamp_products_slider($scope, $) {
        const $slider = $scope.find('.tcgelements-products-slider');
        const sliderSettings = $slider.data('tcgelements-products-slider');
        const $swiperContainer = $slider.find('.swiper-container');
        const $nextArrow = $slider.find('.swiper-button-next');
        const $prevArrow = $slider.find('.swiper-button-prev');
        const $pagination = $slider.find('.swiper-pagination');
        const $scrollbar = $slider.find('.swiper-scrollbar');

        // Check if parallax is enabled
        const isParallaxEnabled = $slider.data('parallax') === true;

        // Only apply performance styles if parallax is enabled

        // Cache DOM queries for parallax
        let parallaxElementsCache = new Map();

        // fix swiper vertical slider height bug
        function setSlideHeight(swiper) {
            if (sliderSettings.autoHeight === 'true' && sliderSettings.direction !== 'vertical') {
                return;
            }

            const currentSlide = swiper.slides[swiper.activeIndex];
            if (!currentSlide) return;

            const newHeight = currentSlide.offsetHeight;

            // Batch CSS updates using class toggle instead of inline styles when possible
            $swiperContainer.find('.swiper-wrapper, .swiper-slide').css({ height: newHeight });
            swiper.update();
        }

        // Debounced version for resize events
        const debouncedSetSlideHeight = debounce(function(swiper) {
            setSlideHeight(swiper);
        }, 100);

        // set slidesPerView to 1
        function oneSlideView(breakpoints) {
            const newBreakpoints = {};
            Object.keys(breakpoints).forEach(key => {
                newBreakpoints[key] = { ...breakpoints[key], slidesPerView: 1 };
            });
            return newBreakpoints;
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
                sensitivity: 1,
                releaseOnEdges: true,
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
                    if (window.TCGSplitTextAnimation) {
                        window.TCGSplitTextAnimation.initialize(this);
                    }
                },
                resize: function () {
                    debouncedSetSlideHeight(this);
                    this.update();
                },
                // Pause parallax during slide transition for better performance
                transitionStart: function() {
                    if (isParallaxEnabled) {
                        $slider.addClass('swiper-transitioning');
                    }
                },
                transitionEnd: function() {
                    if (isParallaxEnabled) {
                        $slider.removeClass('swiper-transitioning');
                    }
                }
            },
            observer: true,
            observeParents: true,
            // Improve touch handling
            touchEventsTarget: 'container',
            touchRatio: 1,
            touchAngle: 45,
            simulateTouch: true,
            shortSwipes: true,
            longSwipes: true,
            threshold: 5,
        };

        if (sliderSettings.effect === 'parallax') {
            swiperOptions.parallax = true;
            swiperOptions.breakpoints = oneSlideView(sliderSettings.breakpoints);
        }

        if (sliderSettings.effect === 'material') {
            swiperOptions.materialEffect = {
                slideSplitRatio: 0.65
            };
            if (typeof EffectMaterial !== 'undefined') {
                swiperOptions.modules = [EffectMaterial];
            }
            swiperOptions.breakpoints = sliderSettings.breakpoints;
        }

        if (sliderSettings.effect === 'gl') {
            if (typeof window.SwiperGL !== 'undefined') {
                swiperOptions.modules = [window.SwiperGL];
            }
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
                pauseOnMouseEnter: true,
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

        // Initialize swiper
        const swiper = new Swiper($swiperContainer.get(0), swiperOptions);

        // Cleanup on widget destroy
        $scope.data('productsSliderCleanup', function() {
            if (window.TCGSplitTextAnimation) {
                window.TCGSplitTextAnimation.destroy();
            }
            if (swiper && !swiper.destroyed) {
                swiper.destroy(true, true);
            }

        });

        // -------- Optimized Parallax img mouse move (only if enabled) --------
        if (isParallaxEnabled) {
            // Use passive event listeners for better scroll performance
            const activeListener = { passive: false };

            // Pre-calculate styles for reuse
            const defaultTransform = 'translate(0px, 0px) scale(1)';

            // Cache all parallax containers
            const parallaxContainers = Array.from($slider[0].getElementsByClassName('parallax-container'));

            // Pre-cache elements for each container
            parallaxContainers.forEach(container => {
                const parallaxedElements = Array.from(container.getElementsByClassName('parallaxed'));
                parallaxElementsCache.set(container, parallaxedElements);
            });

            // Optimized parallax handler with transform caching
            function createParallaxHandler(container, elements) {
                // Cache container rect to avoid multiple reads
                let cachedRect = null;
                let lastMouseX = 0, lastMouseY = 0;
                let rafId = null;

                // Function to get container rect with caching (invalidates on scroll/resize)
                function getContainerRect() {
                    if (!cachedRect) {
                        cachedRect = container.getBoundingClientRect();
                        // Invalidate cache after a short delay (for scroll/resize)
                        setTimeout(() => { cachedRect = null; }, 100);
                    }
                    return cachedRect;
                }

                // Function to calculate transforms
                function calculateTransforms(mouseX, mouseY, rect) {
                    const containerWidth = rect.width;
                    const containerHeight = rect.height;
                    const results = [];

                    for (let i = 0; i < elements.length; i++) {
                        const element = elements[i];
                        const elementRect = element.getBoundingClientRect();
                        const elementCenterX = elementRect.left + elementRect.width / 2 - rect.left;
                        const elementCenterY = elementRect.top + elementRect.height / 2 - rect.top;

                        let distanceX = (mouseX - elementCenterX) / containerWidth;
                        let distanceY = (mouseY - elementCenterY) / containerHeight;

                        // Clamp values to prevent extreme transforms
                        distanceX = Math.max(-0.8, Math.min(0.8, distanceX));
                        distanceY = Math.max(-0.8, Math.min(0.8, distanceY));

                        const intensity = 0.4 + i * 0.15;
                        const amountMovedX = distanceX * intensity * 50;
                        const amountMovedY = distanceY * intensity * 50;
                        const scale = 1 + (Math.abs(distanceX) + Math.abs(distanceY)) * 0.15;

                        results.push({
                            element,
                            transform: `translate(${amountMovedX}px, ${amountMovedY}px) scale(${scale})`
                        });
                    }

                    return results;
                }

                // Apply transforms using RAF
                function applyTransforms(results) {
                    for (const result of results) {
                        result.element.style.transform = result.transform;
                    }
                }

                // Main parallax handler with RAF
                const parallaxHandler = (e) => {
                    // Skip if swiper is transitioning
                    if ($slider.hasClass('swiper-transitioning')) {
                        return;
                    }

                    // Store mouse position
                    lastMouseX = e.clientX;
                    lastMouseY = e.clientY;

                    if (rafId) return;

                    rafId = requestAnimationFrame(() => {
                        const rect = getContainerRect();
                        if (!rect) {
                            rafId = null;
                            return;
                        }

                        const mouseX = lastMouseX - rect.left;
                        const mouseY = lastMouseY - rect.top;

                        // Skip if mouse is outside container
                        if (mouseX < 0 || mouseX > rect.width || mouseY < 0 || mouseY > rect.height) {
                            rafId = null;
                            return;
                        }

                        const transforms = calculateTransforms(mouseX, mouseY, rect);
                        applyTransforms(transforms);

                        rafId = null;
                    });
                };

                // Reset handler
                const resetHandler = () => {
                    if (rafId) {
                        cancelAnimationFrame(rafId);
                        rafId = null;
                    }
                    // Batch reset all transforms
                    for (const element of elements) {
                        element.style.transform = defaultTransform;
                    }
                    cachedRect = null;
                };

                // Mouse enter handler - setup
                const enterHandler = () => {
                    resetHandler();
                    container.addEventListener('mousemove', parallaxHandler, activeListener);
                };

                // Mouse leave handler - cleanup
                const leaveHandler = () => {
                    container.removeEventListener('mousemove', parallaxHandler);
                    resetHandler();
                };

                return { enterHandler, leaveHandler };
            }

            // Initialize parallax for each container with optimized handlers
            for (let j = 0; j < parallaxContainers.length; j++) {
                const container = parallaxContainers[j];
                const elements = parallaxElementsCache.get(container) || [];

                if (elements.length === 0) continue;

                const { enterHandler, leaveHandler } = createParallaxHandler(container, elements);

                container.addEventListener('mouseenter', enterHandler);
                container.addEventListener('mouseleave', leaveHandler);

                // Store cleanup function
                container._parallaxCleanup = () => {
                    container.removeEventListener('mouseenter', enterHandler);
                    container.removeEventListener('mouseleave', leaveHandler);
                };
            }
        }
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-products-slider.default', elementcamp_products_slider);
    });
})(jQuery);