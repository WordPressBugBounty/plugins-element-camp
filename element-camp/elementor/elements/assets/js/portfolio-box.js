(function ($) {
    "use strict";

    function elementcamp_portfolio_box($scope, $) {
        var $items = $scope.find('.tcg-portfolio-box .item');
        var $container = $scope.find(".tcg-portfolio-box");
        var activationMethod = $container.data('activation-method') || 'click';

        /**
         * Set initial active state
         */
        function setInitialActive() {
            $items.removeClass('active');

            // For click mode, set first item active on desktop
            if (activationMethod === 'click') {
                if (window.matchMedia("(min-width: 992px)").matches) {
                    $items.first().addClass('active');
                }
            }
            // For hover mode, no initial active state needed
        }

        /**
         * Bind events based on activation method
         */
        function bindEvents() {
            // Clear existing events
            $container.off(".portfolio");

            if (activationMethod === 'hover') {
                var supportsHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

                if (supportsHover) {
                    // When entering an item -> make it active, remove from siblings
                    $container.on("mouseenter.portfolio", ".item", function () {
                        $(this).addClass("active").siblings().removeClass("active");
                    });

                    // When mouse leaves the whole container -> remove active from all items
                    $container.on("mouseleave.portfolio", function () {
                        $items.removeClass("active");
                    });

                    // Accessibility: use focusin/focusout for delegation (focus/blur don't bubble)
                    $container.on("focusin.portfolio", ".item", function () {
                        $(this).addClass("active").siblings().removeClass("active");
                    });

                    $container.on("focusout.portfolio", ".item", function () {
                        // small timeout to allow focus to move between items without clearing prematurely
                        setTimeout(function () {
                            if ($container.find(':focus').length === 0) {
                                $items.removeClass("active");
                            }
                        }, 10);
                    });
                }
            } else {
                // Click activation (default)
                $container.on("click.portfolio", ".item", function (e) {
                    e.preventDefault();
                    $(this).addClass("active").siblings().removeClass("active");
                });
            }
        }
        // Initialize
        setInitialActive();
        bindEvents();
    }

    // Initialize when Elementor is ready
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-portfolio-box.default',
            elementcamp_portfolio_box
        );
    });

})(jQuery);