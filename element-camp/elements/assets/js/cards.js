(function ($) {
    "use strict";
    function elementcamp_cards($scope, $) {
        const scrollAnimationEnabled = $scope.find('.tcgelements-cards.scroll-animation');
        if (scrollAnimationEnabled.length > 0){
            var width = $(window).width();
            if (width > 991) {
                gsap.registerPlugin(ScrollTrigger);
                let sections = gsap.utils.toArray(".tcgelements-cards .panel");

                gsap.to(sections, {
                    xPercent: -100 * (sections.length - 1),
                    ease: "none",
                    scrollTrigger: {
                        trigger: ".tcgelements-cards.scroll-animation",
                        pin: true,
                        scrub: 1,
                        end: () => "+=" + document.querySelector(".tcgelements-cards.scroll-animation").offsetWidth
                    }
                });
            }
        }
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-cards.default', elementcamp_cards);
    });
})(jQuery);
