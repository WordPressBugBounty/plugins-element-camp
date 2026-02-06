(function ($) {
    'use strict';

    /**
     * Interactive Hero Box Handler
     */
    var InteractiveHeroBoxHandler = function ($scope, $) {
        var $heroBox = $scope.find('.tcgelements-interactive-hero-box');

        if (!$heroBox.length) {
            return;
        }

        var transitionDuration = parseFloat($heroBox.data('transition')) || 1;
        var zoomDuration = parseFloat($heroBox.data('zoom')) || 30;
        var blurAmount = parseInt($heroBox.data('blur')) || 10;
        var hoverEffect = $heroBox.data('effect') || 'zoom';

        // Apply dynamic styles
        var styleId = 'tcgelements-hero-box-style-' + $heroBox.attr('id');
        var existingStyle = $('#' + styleId);

        if (existingStyle.length) {
            existingStyle.remove();
        }

        var dynamicStyles = `
            <style id="${styleId}">
                #${$heroBox.attr('id')} .boxes-bg .bg {
                    opacity: 0;
                    filter: blur(${blurAmount}px);
                    transition: opacity ${transitionDuration}s ease, filter ${transitionDuration}s ease, transform ${transitionDuration}s ease;
                }
                
                #${$heroBox.attr('id')} .boxes-bg .bg.show {
                    opacity: 1;
                    filter: blur(0);
                    ${hoverEffect === 'zoom' ? `
                        transform: scale(1.5);
                        transition: opacity ${transitionDuration}s ease, filter ${transitionDuration}s ease, transform ${zoomDuration}s ease;
                    ` : `
                        transform: scale(1);
                    `}
                }
            </style>
        `;

        $('head').append(dynamicStyles);

        // Hover effect for background images
        $heroBox.find('.box-item').on('mouseenter', function () {
            var bgClass = $(this).data('bg');
            $heroBox.find('.boxes-bg .bg').removeClass('show');
            $heroBox.find('.boxes-bg .' + bgClass).addClass('show');

            // Update active state
            $heroBox.find('.box-item').removeClass('active');
            $(this).addClass('active');
        });

        // Optional: Reset to default on mouseleave from container
        $heroBox.on('mouseleave', function() {
            // Find the default active box if you want to revert
            var $defaultActive = $heroBox.find('.box-item[data-default="yes"]');
            if ($defaultActive.length) {
                var bgClass = $defaultActive.data('bg');
                $heroBox.find('.boxes-bg .bg').removeClass('show');
                $heroBox.find('.boxes-bg .' + bgClass).addClass('show');

                $heroBox.find('.box-item').removeClass('active');
                $defaultActive.addClass('active');
            }
        });

        // Handle responsive behavior
        var handleResponsive = function() {
            var windowWidth = $(window).width();

            // You can add specific responsive behaviors here if needed
            if (windowWidth < 768) {
                // Mobile specific adjustments
                $heroBox.find('.boxes-bg .bg').css('transform', 'scale(1)');
            }
        };

        handleResponsive();
        $(window).on('resize', _.debounce(handleResponsive, 250));
    };

    // Make sure to run the handler when Elementor widgets are initialized
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-interactive-hero-box.default', InteractiveHeroBoxHandler);
    });

    // Also run on document ready for non-Elementor pages
    $(document).ready(function() {
        if (typeof elementorFrontend === 'undefined') {
            $('.tcgelements-interactive-hero-box').each(function() {
                InteractiveHeroBoxHandler($(this), $);
            });
        }
    });

})(jQuery);