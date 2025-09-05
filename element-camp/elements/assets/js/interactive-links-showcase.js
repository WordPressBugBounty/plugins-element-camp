(function ($) {
    "use strict";

    function elementcamp_interactive_links_showcase($scope, $) {

        $('.tcgelements-interactive-links-showcase .links-text li').on('mouseenter', function () {
            var tab_id = $(this).attr('data-tab');
            $('.tcgelements-interactive-links-showcase .links-text li').removeClass('current');
            $(this).addClass('current');

            $('.tcgelements-interactive-links-showcase .links-img .img').removeClass('current');
            $(".tcgelements-interactive-links-showcase  #" + tab_id).addClass('current');

            if ($(this).hasClass('current')) {
                return false;
            }
        });

        $('.tcgelements-interactive-links-showcase .links-text li').on('mouseleave', function () {
            $('.links-text li').removeClass('current');
            $('.links-img .img').removeClass('current');
        });


        $('.tcgelements-interactive-links-showcase .links-text li').on('mouseenter', function () {
            $(this).removeClass('no-active').siblings().addClass('no-active');
        });

        $('.tcgelements-interactive-links-showcase .links-text li').on('mouseleave', function () {
            $('.tcgelements-interactive-links-showcase .links-text li').removeClass('no-active');
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-interactive-links-showcase.default', elementcamp_interactive_links_showcase);
    });

})(jQuery);