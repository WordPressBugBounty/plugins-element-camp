!(function ($) {
    "use strict";
    function elementcamp_library($scope, $) {
        const libraryColumn = document.querySelectorAll(".tcgelements-library .library-column:not(.center)");
        libraryColumn.forEach((colmn, index) => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: colmn,
                    start: "-2500  bottom",
                    // end: "800 top",
                    scrub: true // Smooth scrolling effect
                }
            });

            tl.from(colmn, {
                x: 0,
                y: 0,
                scaleX: 1,
                // rotationZ: -10,
                duration: 150,
                ease: "none",
                delay: 1,
            });

            tl.to(colmn, {
                x: 0,
                y: "-75%",
                scaleX: 1,
                // rotationZ: 0,
                duration: 150,
                ease: "none",
                delay: 1,
            });
        });
        const libraryColumn1 = document.querySelectorAll(".tcgelements-library .library-column.center");
        libraryColumn1.forEach((colmn, index) => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: colmn,
                    start: "-2500  bottom",
                    // end: "800 top",
                    scrub: true // Smooth scrolling effect
                }
            });

            tl.from(colmn, {
                x: 0,
                y: 0,
                scaleX: 1,
                // rotationZ: -10,
                duration: 150,
                ease: "none",
                delay: 1,
            });

            tl.to(colmn, {
                x: 0,
                y: "50%",
                scaleX: 1,
                // rotationZ: 0,
                duration: 150,
                ease: "none",
                delay: 1,
            });
        });
    }
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-library.default', elementcamp_library);
    });
})(jQuery);