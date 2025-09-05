(function ($) {
    "use strict";
    function elementcamp_map_items() {
        $('.tcgelements-map-items .map-item').each(function() {
            var $this = $(this);
            var x = $this.data('r') + '%';  // Add % after fetching the data
            var y = $this.data('b') + '%';  // Add % after fetching the data
            $this.css({ right: x, bottom: y });
        });

        $(".tcgelements-map-items .map-item .dot").on("mouseenter", function(){
            $(this).parent(".map-item").addClass("show");
        });

        $(".tcgelements-map-items .map-item").on("mouseleave", function(){
            $(this).removeClass("show");
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-map-items.default', elementcamp_map_items);
    });

})(jQuery);
