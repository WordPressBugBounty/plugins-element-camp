(function ($) {
    "use strict";

    function elementcamp_services_tabs($scope, $) {
        $scope.find('.tcgelements-services-tabs.hover').each(function() {
            var $tabs = $(this);
            var $tabLinks = $tabs.find('.nav-link');

            $tabLinks.on('mouseenter', function() {
                var $this = $(this);
                var target = $this.attr('data-bs-target'); // Get the target tab content ID

                // Remove active classes from all tabs and contents
                $tabLinks.removeClass("active");
                $tabs.find('.tab-pane').removeClass("active show");

                // Add active classes to the hovered tab and its content
                $this.addClass("active");
                $(target).addClass("active show");
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-services-tabs.default', elementcamp_services_tabs);
    });

})(jQuery);
