(function ($) {
    "use strict";
    function elementcamp_featured_accordion($scope, $) {
        $scope.find('.tcgelements-featured-accordion').each(function() {
            var $accordion = $(this);

            $accordion.find('.accordion-item').on('click', function() {
                $accordion.find('.accordion-item').removeClass("active");
                $(this).addClass("active");
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-featured-accordion.default', elementcamp_featured_accordion);
    });
})(jQuery);