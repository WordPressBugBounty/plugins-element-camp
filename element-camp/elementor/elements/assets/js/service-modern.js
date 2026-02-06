(function ($) {
    "use strict";
    function elementcamp_service_modern($scope) {
        $(".tcgelements-service-modern").each(function() {
            var container = $(this);
            container.mousemove(function(e) {
                var parentOffset = container.offset();
                var relX = e.pageX - parentOffset.left;
                var relY = e.pageY - parentOffset.top;
                container.find(".float-img").css({"left": relX, "top": relY });
                container.find(".float-img").addClass("show");
            });
            container.mouseleave(function(e) {
                container.find(".float-img").removeClass("show");
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-service-modern.default', elementcamp_service_modern);
    });

})(jQuery);
