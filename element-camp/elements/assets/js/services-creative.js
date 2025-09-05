(function ($) {
    "use strict";
    function elementcamp_service_creative($scope) {
        $(".tcgelements-services-creative").each(function() {
            $('.tcgelements-services-creative .service-card').hover(function() {
                let servNumber = $(this).attr('data-serv');
                $('.tcgelements-services-creative .images-column .imgs img[data-serv="' + servNumber + '"]').addClass('show').siblings().removeClass("show");
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-services-creative.default', elementcamp_service_creative);
    });

})(jQuery);
