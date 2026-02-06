(function ($) {
    "use strict";

    function elementcamp_services_card($scope, $) {
        $scope.find('.tcgelements-service-card.has-float-name').each(function() {
            const serviceCard = this; // Native DOM element
            const floatName = serviceCard.querySelector('.float-name');

            if (floatName) {
                serviceCard.addEventListener('mouseenter', () => {
                    floatName.style.transform = 'scale(1)';
                    floatName.style.opacity = '1';
                });

                serviceCard.addEventListener('mouseleave', () => {
                    floatName.style.transform = 'scale(0)';
                    floatName.style.opacity = '0';
                });

                serviceCard.addEventListener('mousemove', (e) => {
                    const rect = serviceCard.getBoundingClientRect();
                    const x = e.clientX - rect.left + 50;
                    const y = e.clientY - rect.top + 30;
                    floatName.style.left = `${x}px`;
                    floatName.style.top = `${y}px`;
                    floatName.style.transform = 'scale(1) translate(-50%, -50%)';
                });
            }
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-service-card.default', elementcamp_services_card);
    });

})(jQuery);