(function ($) {
    "use strict";

    function elementcamp_progress_bar($scope, $) {
        var $progressBars = $scope.find('.tcgelements-progress-wrapper');

        // Initialize progress bars
        function initProgressBars() {
            $progressBars.each(function() {
                var $wrapper = $(this);
                var $bar = $wrapper.find('.tcgelements-progress-bar');
                var width = $bar.attr('data-max');

                // Reset initial state
                $wrapper.removeClass('animated');
                $bar.css('width', '0%');
                // Check if element is in viewport
                checkAndAnimate();
            });
        }

        // Function to check if element is in viewport and animate accordingly
        function checkAndAnimate() {
            $progressBars.each(function() {
                var $wrapper = $(this);
                var $bar = $wrapper.find('.tcgelements-progress-bar');
                var width = $bar.attr('data-max');

                // Check if element is visible
                if (isInViewport(this) && !$wrapper.hasClass('animated')) {
                    $bar.css('width', width);
                    $wrapper.addClass('animated');
                }
            });
        }

        // Helper function to check if element is in viewport
        function isInViewport(element) {
            var rect = element.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.bottom >= 0
            );
        }

        // Initialize progress bars
        initProgressBars();

        // Handle scroll events
        $(window).on('scroll', checkAndAnimate);

        // Handle resize events (for responsive layouts)
        $(window).on('resize', checkAndAnimate);
    }

    // Initialize when Elementor is ready
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-progress-bar.default', elementcamp_progress_bar);
    });

})(jQuery);