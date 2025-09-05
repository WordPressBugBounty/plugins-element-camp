(function ($) {
    "use strict";

    function waitForElementToExist(selector) {
        return new Promise(resolve => {
            if (document.querySelector(selector)) {
                return resolve(document.querySelector(selector));
            }

            const observer = new MutationObserver(() => {
                if (document.querySelector(selector)) {
                    resolve(document.querySelector(selector));
                    observer.disconnect();
                }
            });

            observer.observe(document.body, {
                subtree: true,
                childList: true,
            });
        });
    }

    function elementcamp_image($scope, $) {
        $(document).ready(function () {
            $('.e-parent .elementor-widget-tcgelements-image').each(function () {
                $scope.parent().on('mouseenter', function () {
                    $scope.find('.selector-type-container').addClass('tcgelements-image-container-active');
                }).on('mouseleave', function () {
                    $scope.find('.selector-type-container').removeClass('tcgelements-image-container-active');
                });
            });
            $('.e-parent .elementor-widget-tcgelements-image').each(function () {
                $scope.parent().parent().on('mouseenter', function () {
                    $scope.find('.selector-type-parent-container').addClass('tcgelements-image-container-active');
                }).on('mouseleave', function () {
                    $scope.find('.selector-type-parent-container').removeClass('tcgelements-image-container-active');
                });
            });
            waitForElementToExist('.swiper-initialized').then((elm) => {
                $('.e-parent > .elementor-widget-tcgelements-image').parent().on('mouseenter', function () {
                    // When the mouse enters an element with class 'e-parent' and has a direct child with class 'elementor-widget-tcgelements-image'
                    $(this).find('.selector-type-container').addClass('tcgelements-image-container-active'); // Add the active class to '.selector-type-container' inside 'e-parent'
                }).on('mouseleave', function () {
                    // When the mouse leaves an element with class 'e-parent' and has a direct child with class 'elementor-widget-tcgelements-image'
                    $(this).find('.selector-type-container').removeClass('tcgelements-image-container-active'); // Remove the active class from '.selector-type-container' inside 'e-parent'
                });
            });
        });
    };
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-image.default', elementcamp_image);
    });

})(jQuery);