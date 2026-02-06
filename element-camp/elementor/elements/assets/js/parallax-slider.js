(function ($) {
    "use strict";

    function elementcamp_parallax_slider($scope, $) {
        if (document.querySelectorAll('.tcg-parallax-slider').length) {

            let images = [...document.querySelectorAll('.tcg-post-image img')];
            let slider = document.querySelector('.parallax-sliders');
            let sliderWidth;
            let imageWidth;
            let current = 0;
            let target = 0;
            let ease = 0.05;

            window.addEventListener('resize', init);

            // Dynamically set background images from data-src
            images.forEach((img) => {
                let imgSrc = img.getAttribute('src');
                if (imgSrc) {
                    img.src = imgSrc;
                }
            });

            function lerp(start, end, t) {
                return start * (1 - t) + end * t;
            }

            function setTransform(el, transform) {
                el.style.transform = transform;
            }

            function init() {
                sliderWidth = slider.getBoundingClientRect().width;
                imageWidth = sliderWidth / images.length;
            }

            function animate() {
                current = parseFloat(lerp(current, target, ease)).toFixed(2);
                target = window.scrollY;
                setTransform(slider, `translateX(-${current}px)`);
                animateImages();
                requestAnimationFrame(animate);
            }

            function animateImages() {
                let ratio = current / imageWidth;
                let intersectionRatioValue;

                images.forEach((image, idx) => {
                    intersectionRatioValue = ratio - (idx * 0.7);
                    setTransform(image, `translateX(${intersectionRatioValue * 100}px)`);
                });
            }

            init();
            animate();
        }
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcg-parallax-slider.default', elementcamp_parallax_slider);
    });

})(jQuery);