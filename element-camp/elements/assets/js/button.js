(function ($) {
    "use strict";


    function tcElementsButton($scope, $) {
        $(document).ready(function () {
            $('.e-parent .elementor-widget-tcgelements-button').parent().on('mouseenter', function () {
                // When the mouse enters an element with class 'e-parent' and has a direct child with class 'elementor-widget-tcg-post-featured-image'
                $(this).find('.btn-text-selector-type-container').addClass('tc-button-text-container-active'); // Add the active class to '.selector-type-container' inside 'e-parent'
            }).on('mouseleave', function () {
                // When the mouse leaves an element with class 'e-parent' and has a direct child with class 'elementor-widget-tcg-post-featured-image'
                $(this).find('.btn-text-selector-type-container').removeClass('tc-button-text-container-active'); // Remove the active class from '.selector-type-container' inside 'e-parent'
            });
            $('.e-parent .elementor-widget-tcgelements-button').each(function () {
                $scope.parent().on('mouseenter', function () {
                    $scope.find('.btn-selector-type-container').addClass('tc-button-container-active');
                }).on('mouseleave', function () {
                    $scope.find('.btn-selector-type-container').removeClass('tc-button-container-active');
                });
            });

            // parent parent
            $('.e-parent .elementor-widget-tcgelements-button').each(function () {
                $scope.parent().parent().on('mouseenter', function () {
                    $scope.find('.btn-selector-type-parent-container').addClass('tc-button-container-active');
                }).on('mouseleave', function () {
                    $scope.find('.btn-selector-type-parent-container').removeClass('tc-button-container-active');
                });
            });

            // parent n
            $('.e-parent .elementor-widget-tcgelements-button').each(function () {
                const element =$(this).find('.tcgelements-button')
                if (element.attr('data-parent-level') !== undefined){
                    let parentLevel = parseInt($(this).find('.tcgelements-button').data('parent-level')) || 1; // Default to 1 parent if no data attribute is set
                    let $parentTarget = $(this);
                    for (let i = 0; i < parentLevel; i++) {
                        $parentTarget = $parentTarget.parent();
                    }
                    // Apply mouseenter and mouseleave event handlers for the selected parent
                    $parentTarget.on('mouseenter', function () {
                        $(this).find('.btn-selector-type-parent-n').addClass('tc-button-container-active');
                    }).on('mouseleave', function () {
                        $(this).find('.btn-selector-type-parent-n').removeClass('tc-button-container-active');
                    });
                }
            });

        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-button.default', tcElementsButton);
    });

})(jQuery);