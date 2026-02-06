(function($) {
    "use strict";

    const galleryInstances = new Map();

    const initGalleryStack = ($scope) => {
        const $galleryWrapper = $scope.find('.tcgelements-gallery-stack');

        if (!$galleryWrapper.length) {
            console.warn('Gallery Stack: Wrapper not found');
            return;
        }

        const galleryElement = $galleryWrapper.find('.gallery--stack')[0];

        if (!galleryElement) {
            console.warn('Gallery Stack: Gallery element not found');
            return;
        }

        const widgetId = galleryElement.getAttribute('data-widget-id');
        const pinSpacing = $galleryWrapper.data('pin-spacing') || 200;

        // Clean up existing instance
        if (galleryInstances.has(widgetId)) {
            const instance = galleryInstances.get(widgetId);
            if (instance.scrollTrigger) {
                instance.scrollTrigger.kill();
            }
            if (instance.timeline) {
                instance.timeline.kill();
            }
            galleryInstances.delete(widgetId);
        }

        // Check dependencies
        if (typeof gsap === 'undefined') {
            console.error('Gallery Stack: GSAP is not loaded');
            return;
        }

        if (typeof ScrollTrigger === 'undefined') {
            console.error('Gallery Stack: ScrollTrigger is not loaded');
            return;
        }

        if (typeof Flip === 'undefined') {
            console.error('Gallery Stack: Flip plugin is not loaded');
            return;
        }

        // Register plugins
        gsap.registerPlugin(ScrollTrigger, Flip);

        const triggerFlipAnimation = () => {
            const galleryCaption = galleryElement.querySelector('.caption');
            const galleryItems = galleryElement.querySelectorAll('.gallery__item');

            if (!galleryItems.length) {
                console.warn('Gallery Stack: No gallery items found');
                return;
            }

            const galleryItemsInner = [...galleryItems].map(item =>
                item.children.length > 0 ? [...item.children] : []
            ).flat();

            // Capture the state with the switch class
            galleryElement.classList.add('gallery--switch');
            const flipstate = Flip.getState([galleryItems, galleryCaption], {
                props: 'filter, opacity'
            });
            galleryElement.classList.remove('gallery--switch');

            // Create the main flip animation
            const tl = Flip.to(flipstate, {
                ease: 'none',
                absoluteOnLeave: false,
                absolute: false,
                scale: true,
                simple: true,
                scrollTrigger: {
                    trigger: $galleryWrapper[0],
                    start: 'top top',
                    end: '+=' + pinSpacing + '%',
                    pin: true,
                    scrub: 1,
                    anticipatePin: 1,
                    invalidateOnRefresh: true,
                    markers: false,
                },
                stagger: 0
            });

            // Animate inner elements if they exist
            if (galleryItemsInner.length) {
                tl.fromTo(galleryItemsInner,
                    {
                        scale: 2
                    },
                    {
                        scale: 1,
                        ease: 'none',
                        scrollTrigger: {
                            trigger: $galleryWrapper[0],
                            start: 'top top',
                            end: '+=' + pinSpacing + '%',
                            scrub: 1,
                        },
                    },
                    0
                );
            }

            // Store instance
            galleryInstances.set(widgetId, {
                scrollTrigger: tl.scrollTrigger,
                timeline: tl
            });
        };

        // Small delay to ensure DOM is ready
        setTimeout(() => {
            triggerFlipAnimation();
            ScrollTrigger.refresh();
        }, 100);
    };

    // Elementor Frontend Init
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend === 'undefined') {
            console.error('Gallery Stack: elementorFrontend is not available');
            return;
        }

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-gallery-stack.default',
            initGalleryStack
        );
    });

    // Refresh on window load
    $(window).on('load', function() {
        if (typeof ScrollTrigger !== 'undefined') {
            setTimeout(() => {
                ScrollTrigger.refresh();
            }, 500);
        }
    });

})(jQuery);