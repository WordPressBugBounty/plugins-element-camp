(function ($) {
    "use strict";
    function elementcamp_accordion($scope, $) {
        $scope.find('.tcgelements-accordion').each(function() {
            var $accordion = $(this);

            $accordion.find('.accordion-item').on('click', function() {
                $accordion.find('.accordion-item').removeClass("active");
                $(this).addClass("active");
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-accordion.default', elementcamp_accordion);
    });
})(jQuery);
