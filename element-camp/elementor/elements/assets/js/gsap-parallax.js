(function ($) {
    "use strict";

    function elementcamp_image_gsap_parallax($scope, $) {
        const container = $scope.find('.tce-gsap-parallax')[0];
        if (!container) return;

        // Check GSAP + ScrollTrigger are loaded
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            console.warn('GSAP or ScrollTrigger not loaded');
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        const img = container.querySelector('img');
        if (!img) return;

        // Read values set from PHP data attributes
        const yPercent = parseFloat(container.dataset.gsapYPercent ?? -20);
        const scale    = parseFloat(container.dataset.gsapScale ?? 1.4);

        // Overflow hidden is required for the effect to look right
        container.style.overflow = 'hidden';

        gsap.to(img, {
            yPercent: yPercent,
            scale: scale,
            ease: 'none',
            scrollTrigger: {
                trigger: container,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1,
            }
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-image.default',
            elementcamp_image_gsap_parallax
        );
    });

})(jQuery);