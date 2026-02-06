(function ($) {
    "use strict";

    function elementcamp_service_creative($scope) {
        $(".tcgelements-services-creative").each(function () {
            const $widget = $(this);
            $widget.find('.image-column').css('pointer-events', 'none');
            const useTabContainer = $widget.attr('data-use-tabs') === 'yes';
            const removeOnLeave = $widget.attr('data-remove-on-leave') === 'yes';

            if (useTabContainer) {
                // NEW TAB CONTAINER LOGIC
                function updateProgressBars($activeTabCard) {
                    $activeTabCard.find('.progress-bar[data-progress]').each(function () {
                        const progressValue = $(this).data('progress');
                        $(this).css('width', progressValue + '%');
                    });
                }

                function switchActiveTab(servNumber) {
                    // Remove active class from all tab cards and service cards
                    $widget.find('.tab-card').removeClass('active');
                    $widget.find('.service-card').removeClass('active-card');

                    // Remove show class from all images
                    $widget.find('.imgs img').removeClass('show');

                    // Add active class to corresponding tab card and service card
                    const $activeTabCard = $widget.find('.tab-card[data-tab-item="' + servNumber + '"]');
                    const $activeServiceCard = $widget.find('.service-card[data-serv="' + servNumber + '"]');

                    $activeTabCard.addClass('active');
                    $activeServiceCard.addClass('active-card');

                    // Add show class to corresponding image
                    $activeTabCard.find('img[data-serv="' + servNumber + '"]').addClass('show');

                    // Update progress bars
                    updateProgressBars($activeTabCard);
                }

                function clearActiveState() {
                    $widget.find('.tab-card').removeClass('active');
                    $widget.find('.service-card').removeClass('active-card');
                    $widget.find('.imgs img').removeClass('show');
                }

                // Handle service card hover
                $widget.find('.service-card').hover(function () {
                    let servNumber = $(this).attr('data-serv');
                    switchActiveTab(servNumber);
                });

                // Handle mouse leave from widget
                if (removeOnLeave) {
                    $widget.on('mouseleave', function () {
                        clearActiveState();
                    });
                }

                // Initialize on page load - check if first service card has active-card class
                if ($widget.find('.service-card').first().hasClass('active-card')) {
                    switchActiveTab('1');
                } else {
                    const $firstTabCard = $widget.find('.tab-card[data-tab-item="1"]');
                    $firstTabCard.addClass('active');
                    $firstTabCard.find('img[data-serv="1"]').addClass('show');
                    updateProgressBars($firstTabCard);
                }
            } else {
                // LEGACY LOGIC
                function clearActiveState() {
                    $widget.find('.service-card').removeClass('active-card');
                    $widget.find('.images-column .imgs img').removeClass('show');
                }

                $widget.find('.service-card').hover(function () {
                    let servNumber = $(this).attr('data-serv');

                    // Remove active class from all service cards when hovering
                    $widget.find('.service-card').removeClass('active-card');
                    // Add active class to hovered card
                    $(this).addClass('active-card');

                    $widget.find('.images-column .imgs img[data-serv="' + servNumber + '"]').addClass('show').siblings().removeClass("show");
                });

                // Handle mouse leave from widget
                if (removeOnLeave) {
                    $widget.on('mouseleave', function () {
                        clearActiveState();
                    });
                }

                // Check if any service card has active-card class on page load
                if ($widget.find('.service-card.active-card').length > 0) {
                    // If there's already an active card, make sure its image is shown
                    let activeServNumber = $widget.find('.service-card.active-card').first().attr('data-serv');
                    $widget.find('.images-column .imgs img[data-serv="' + activeServNumber + '"]').addClass('show');
                }
            }
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-services-creative.default', elementcamp_service_creative);
    });

})(jQuery);