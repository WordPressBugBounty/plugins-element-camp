(function ($) {
    "use strict";

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

        // Kill any existing animations
        ScrollTrigger.getAll().forEach(trigger => {
            if (trigger.vars.trigger === $wrapper.find('.section-target-desktop')[0]) {
                trigger.kill();
            }
        });

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
                    trigger: $wrapper.find('.section-target-desktop')[0],
                    start: "top center",
                    end: "top+=200px center",
                    scrub: 1,
                    onUpdate: (self) => {
                        // Expand container width
                        const progress = self.progress;
                        const startWidth = 33.333333;
                        const endWidth = 100;
                        const currentWidth = startWidth + (endWidth - startWidth) * progress;
                        $container.css('width', currentWidth + '%');

                        // Show card info after 30% progress (changed from 50% for earlier reveal)
                        if (progress > 0.3) {
                            gsap.to($cards.find('.card-info'), {
                                opacity: 1,
                                duration: 0.3
                            });
                        } else {
                            gsap.to($cards.find('.card-info'), {
                                opacity: 0,
                                duration: 0.3
                            });
                        }
                    }
                }
            }
        );

        // Refresh ScrollTrigger on window resize
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                ScrollTrigger.refresh();
            }, 250);
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-gallery-cards.default', tcElementsGalleryCards);
    });

})(jQuery);