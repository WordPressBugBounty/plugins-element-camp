(function ($) {
    "use strict";

    function elementcamp_dropdown($scope, $) {
        // Find elements with hover class
        $scope.find('.tcgelements-dropdown.hover').each(function() {
            const $dropdown = $(this);

            // Add hover behavior
            $dropdown.on('mouseenter', function() {
                $(this).addClass('show');  // Add 'show' class on hover
                $(this).find('.dropdown-menu').addClass('show');
            });

            $dropdown.on('mouseleave', function() {
                $(this).removeClass('show');  // Remove 'show' class when not hovering
                $(this).find('.dropdown-menu').removeClass('show');
            });

            // Prevent clicking links in hover mode
            $dropdown.find('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();  // Prevent the click action
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-dropdown.default', elementcamp_dropdown);
    });

})(jQuery);
