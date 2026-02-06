// tcgelements-image-scroll.js
(function($) {
    'use strict';

    function initAnimation($scope) {
        const $intro = $scope.find('.tcgelements-image-scroll-animation');
        if (!$intro.length) return;

        const rectangles = gsap.utils.toArray(".rectangle", $intro[0]);
        const amount = rectangles.length;

        if (amount === 0) return;

        // Kill old timeline if exists
        if ($intro[0]._timeline) {
            $intro[0]._timeline.kill();
        }

        // Calculate appropriate scroll distance
        const scrollDistance = amount * 40;

        // Create timeline
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: $intro[0],
                start: "center center", // This keeps it centered and natural
                end: "+=" + scrollDistance + "%",
                scrub: 1,
                pin: true,
                pinSpacing: true, // Maintains natural spacing
                anticipatePin: 1,
                invalidateOnRefresh: true
            }
        });

        // Animate
        rectangles.forEach((el, i) => {
            if (i) {
                tl.to(rectangles[i - 1], { width: "33.33333%", duration: 1 }, "+=0.3")
                    .to(el, { width: "50%", duration: 1 }, "<");
            } else {
                tl.to(el, { width: "50%", duration: 1 });
            }
        });

        tl.to(rectangles[amount - 1], { width: "33.33333%", duration: 1 }, "+=0.3");

        // Store timeline
        $intro[0]._timeline = tl;
    }

    $(window).on('elementor/frontend/init', function() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
            console.error('GSAP not loaded');
            return;
        }

        gsap.registerPlugin(ScrollTrigger);

        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-image-scroll-animation.default',
            initAnimation
        );

        // Refresh ScrollTrigger on Elementor preview updates
        if (window.elementorFrontend && window.elementorFrontend.isEditMode()) {
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', function() {
                ScrollTrigger.refresh();
            });
        }
    });

})(jQuery);