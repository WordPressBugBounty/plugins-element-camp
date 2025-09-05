(function ($) {
    "use strict";
    function tcElementsHeading($scope, $) {
        $(document).ready(function () {
            // parent
            $('.e-parent .elementor-widget-tcgelements-heading,.e-con .elementor-widget-tcgelements-heading').each(function () {
                $scope.parent().on('mouseenter', function () {
                    $scope.find('.heading-selector-type-container').addClass('tc-heading-container-active');
                }).on('mouseleave', function () {
                    $scope.find('.heading-selector-type-container').removeClass('tc-heading-container-active');
                });
            });
            // parent parent
            $('.e-parent .elementor-widget-tcgelements-heading').each(function () {
                $scope.parent().parent().on('mouseenter', function () {
                    $scope.find('.heading-selector-type-parent-container').addClass('tc-heading-container-active');
                }).on('mouseleave', function () {
                    $scope.find('.heading-selector-type-parent-container').removeClass('tc-heading-container-active');
                });
            });
            // parent n
            $('.e-parent .elementor-widget-tcgelements-heading').each(function () {
                const element =$(this).find('.tcgelements-heading-text')
                if (element.attr('data-parent-level') !== undefined){
                    let parentLevel = parseInt($(this).find('.tcgelements-heading-text').data('parent-level')) || 1; // Default to 1 parent if no data attribute is set
                    let $parentTarget = $(this);
                    for (let i = 0; i < parentLevel; i++) {
                        $parentTarget = $parentTarget.parent();
                    }
                    // Apply mouseenter and mouseleave event handlers for the selected parent
                    $parentTarget.on('mouseenter', function () {
                        $(this).find('.heading-selector-type-parent-n').addClass('tc-heading-container-active');
                    }).on('mouseleave', function () {
                        $(this).find('.heading-selector-type-parent-n').removeClass('tc-heading-container-active');
                    });
                }
            });
        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-heading.default', tcElementsHeading);
    });

})(jQuery);