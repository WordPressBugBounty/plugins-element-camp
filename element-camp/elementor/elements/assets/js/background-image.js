(function ($) {
    "use strict";

    function elementcamp_background_image($scope, $) {
        // background image
        var pageSection = $(".bg-img");
        pageSection.each(function (indx) {

            if ($(this).attr("data-background")) {
                $(this).css("background-image", "url(" + $(this).data("background") + ")");
            }
        });
    }

    // Initialize the script on both frontend and editor
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/global', elementcamp_background_image);
    });

})(jQuery);
