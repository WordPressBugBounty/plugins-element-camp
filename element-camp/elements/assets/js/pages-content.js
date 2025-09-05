!(function ($) {
    "use strict";
    function elementcamp_pages_content($scope, $) {
        const items = document.querySelectorAll(".tcgelements-pages-content .c-item");
        // Loop through each item and create a timeline for it
        items.forEach((item, index) => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: item,
                    start: "top bottom",
                    end: "800 top",
                    scrub: true // Smooth scrolling effect
                }
            });

            tl.from(item, {
                x: 0,
                y: 0,
                scale: 0.7,
                opacity: 0.5,
                // filter: "blur(10px)",
                duration: 150,
                ease: "none",
                delay: 1,
            });

            tl.to(item, {
                x: 0,
                y: 0,
                scale: 1,
                opacity: 1,
                // filter: "blur(0)",
                duration: 150,
                ease: "none",
                delay: 1,
            });
        });


        const rItems = document.querySelectorAll(".tcgelements-pages-content .r-item");
        // Loop through each item and create a timeline for it
        rItems.forEach((item, index) => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: item,
                    start: "top bottom",
                    end: "800 top",
                    scrub: true // Smooth scrolling effect
                }
            });

            tl.from(item, {
                x: 200,
                y: 0,
                scaleX: 1.5,
                // rotationZ: 10,
                opacity: 0.5,
                filter: "blur(10px)",
                duration: 150,
                ease: "none",
                delay: 1,
            });

            tl.to(item, {
                x: 0,
                y: 0,
                scaleX: 1,
                // rotationZ: 0,
                opacity: 1,
                filter: "blur(0)",
                duration: 150,
                ease: "none",
                delay: 1,
            });
        });


        const lItems = document.querySelectorAll(".tcgelements-pages-content .l-item");
        // Loop through each item and create a timeline for it
        lItems.forEach((item, index) => {
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: item,
                    start: "top bottom",
                    end: "800 top",
                    scrub: true // Smooth scrolling effect
                }
            });

            tl.from(item, {
                x: -200,
                y: 0,
                scaleX: 1.5,
                // rotationZ: -10,
                opacity: 0.5,
                filter: "blur(10px)",
                duration: 150,
                ease: "none",
                delay: 1,
            });

            tl.to(item, {
                x: 0,
                y: 0,
                scaleX: 1,
                // rotationZ: 0,
                opacity: 1,
                filter: "blur(0)",
                duration: 150,
                ease: "none",
                delay: 1,
            });
        });
    }
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-pages-content.default', elementcamp_pages_content);
    });
})(jQuery);