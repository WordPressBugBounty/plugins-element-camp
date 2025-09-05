(function ($) {
    "use strict";

    function elementcamp_services_list($scope, $) {
        $('.tcgelements-services-list .item').on('mouseenter', function () {
            $('.tcgelements-services-list .item').removeClass('active');
            $(this).addClass('active');
            if ($(this).hasClass('active')) {
                return false;
            }
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-services-list.default', elementcamp_services_list);
    });

})(jQuery);
