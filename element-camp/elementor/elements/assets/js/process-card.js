(function ($) {
    "use strict";

    function elementcamp_services_card($scope, $) {
        $scope.find('.tcgelements-process-card').each(function() {
            const processCard = this; // Native DOM element
            const floatTxt = processCard.querySelector('.float-txt');

            if (floatTxt) {
                processCard.addEventListener('mouseenter', () => {
                    floatTxt.style.transform = 'scale(1)';
                    floatTxt.style.opacity = '1';
                });

                processCard.addEventListener('mouseleave', () => {
                    floatTxt.style.transform = 'scale(0)';
                    floatTxt.style.opacity = '0';
                });

                processCard.addEventListener('mousemove', (e) => {
                    const rect = processCard.getBoundingClientRect();
                    const x = e.clientX - rect.left + 50;
                    const y = e.clientY - rect.top + 30;
                    floatTxt.style.left = `${x}px`;
                    floatTxt.style.top = `${y}px`;
                    floatTxt.style.transform = 'scale(1) translate(-50%, -50%)';
                });
            }
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-process-card.default', elementcamp_services_card);
    });

})(jQuery);