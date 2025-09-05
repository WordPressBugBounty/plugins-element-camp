(function ($) {
    "use strict";

    function elementcamp_image_parallax($scope, $) {
        var b = document.getElementsByTagName("body")[0];

        // Check if the body element exists
        if (b) {
            b.addEventListener("mousemove", function(event) {
                parallaxed(event);
            });
        } else {
            console.error('Body element not found.');
        }

        function parallaxed(e) {
            var amountMovedX = (e.clientX * -0.3 / 8);
            var amountMovedY = (e.clientY * -0.3 / 8);
            var x = document.getElementsByClassName("tcgelements-image tce-mouse-parallax");
            for (var i = 0; i < x.length; i++) {
                x[i].style.transform = 'translate(' + amountMovedX + 'px,' + amountMovedY + 'px)';
            }
        }
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-image.default', elementcamp_image_parallax);
    });

})(jQuery);
