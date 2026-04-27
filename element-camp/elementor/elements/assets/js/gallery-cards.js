(function ($) {
    "use strict";

    // Debounce utility for scroll/resize events
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

    function tcElementsGalleryCards($scope, $) {
        const $wrapper = $scope.find('.tcgelements-gallery-cards');

        if ($wrapper.length === 0) {
            return;
        }

        // Check if GSAP and ScrollTrigger are available
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            console.warn('GSAP or ScrollTrigger not loaded');
            return;
        }

        // Register ScrollTrigger plugin
        gsap.registerPlugin(ScrollTrigger);

        // Get animation settings from data attributes
        const initialWidth = $wrapper.data('initial-width') || 80;
        const initialHeight = $wrapper.data('initial-height') || 350;
        const finalWidth = $wrapper.data('final-width') || 320;
        const finalHeight = $wrapper.data('final-height') || 450;
        const moveY = $wrapper.data('move-y') || 300;
        const stagger = $wrapper.data('stagger') || 0.1;

        const $cards = $wrapper.find('.animated-image-card');
        const $container = $wrapper.find('.animated-images-container');

        if ($cards.length === 0) {
            return;
        }

        // Cache DOM elements for better performance
        const containerElement = $container[0];
        const cardInfos = $cards.find('.card-info').toArray();
        const sectionTarget = $wrapper.find('.section-target-desktop')[0];

        // Pre-calculate values
        const startWidth = 33.333333;
        const endWidth = 100;
        const widthRange = endWidth - startWidth;

        // Kill any existing animations on this trigger
        ScrollTrigger.getAll().forEach(trigger => {
            if (trigger.vars.trigger === sectionTarget) {
                trigger.kill();
            }
        });

        // Use gsap.quickSetter for better performance
        const containerSetWidth = gsap.quickSetter(containerElement, 'width', '%');
        
        // Cache card info elements for quick access
        const cardInfoSetters = cardInfos.map(cardInfo => ({
            setOpacity: gsap.quickSetter(cardInfo, 'opacity', ''),
            element: cardInfo
        }));

        // Track previous state to avoid redundant updates
        let lastProgress = -1;
        let lastOpacityState = null;

        gsap.fromTo($cards,
            {
                width: initialWidth + "px",
                height: initialHeight + "px",
                y: 0
            },
            {
                width: finalWidth + "px",
                height: finalHeight + "px",
                y: moveY,
                borderRadius: "15px",
                stagger: stagger,
                ease: "none",
                scrollTrigger: {
                    trigger: sectionTarget,
                    start: "top center",
                    end: "top+=200px center",
                    scrub: 1,
                    onUpdate: (self) => {
                        const progress = self.progress;

                        // Skip if progress hasn't changed significantly (prevent tiny updates)
                        if (Math.abs(progress - lastProgress) < 0.001) {
                            return;
                        }
                        lastProgress = progress;

                        // Update container width using quickSetter
                        const currentWidth = startWidth + widthRange * progress;
                        containerSetWidth(currentWidth);

                        // Determine opacity state
                        const showCardInfo = progress > 0.3;

                        // Only update if state has changed
                        if (showCardInfo !== lastOpacityState) {
                            lastOpacityState = showCardInfo;

                            // Batch update all card info elements
                            cardInfoSetters.forEach(({ setOpacity }) => {
                                setOpacity(showCardInfo ? 1 : 0);
                            });
                        }
                    }
                }
            }
        );

        // Refresh ScrollTrigger on window resize with debouncing
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                ScrollTrigger.refresh();
            }, 250);
        });

        // Cleanup on element destroy
        $scope.data('galleryCardsCleanup', function() {
            ScrollTrigger.getAll().forEach(trigger => {
                if (trigger.vars.trigger === sectionTarget) {
                    trigger.kill();
                }
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-gallery-cards.default', tcElementsGalleryCards);
    });

})(jQuery);
