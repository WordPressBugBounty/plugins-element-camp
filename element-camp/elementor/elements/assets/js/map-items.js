(function ($) {
    "use strict";

    function elementcamp_map_items() {
        // Handle Default Style (backward compatible)
        $('.tcgelements-map-items.default .map-item').each(function() {
            var $this = $(this);
            var x = $this.data('r') + '%';
            var y = $this.data('b') + '%';
            $this.css({ right: x, bottom: y });
        });

        // Handle hover effects for default style
        $(".tcgelements-map-items.default .map-item .dot").on("mouseenter", function(){
            $(this).parent(".map-item").addClass("show");
        });

        $(".tcgelements-map-items.default .map-item").on("mouseleave", function(){
            $(this).removeClass("show");
        });

        // Handle Cards Style positioning
        $('.tcgelements-map-items.cards .place-item').each(function() {
            var $this = $(this);
            var left = $this.data('left');
            var top = $this.data('top');

            // Set position
            $this.css({
                'position': 'absolute',
                'left': left,
                'top': top
            });
        });

        // Handle responsive positioning for cards
        handleResponsiveCardPositions();
    }

    function handleResponsiveCardPositions() {
        var $window = $(window);
        var windowWidth = $window.width();

        $('.tcgelements-map-items.cards .place-item').each(function() {
            var $this = $(this);
            var left, top;

            // Determine which breakpoint to use
            if (windowWidth >= 1025) {
                // Desktop
                left = $this.data('left');
                top = $this.data('top');
            } else if (windowWidth >= 768) {
                // Tablet - fallback to desktop if tablet values not set
                left = $this.data('left-tablet') || $this.data('left');
                top = $this.data('top-tablet') || $this.data('top');
            } else {
                // Mobile - fallback to tablet/desktop if mobile values not set
                left = $this.data('left-mobile') || $this.data('left-tablet') || $this.data('left');
                top = $this.data('top-mobile') || $this.data('top-tablet') || $this.data('top');
            }

            $this.css({
                'left': left,
                'top': top
            });
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        elementcamp_map_items();
    });

    // Handle window resize for responsive positioning
    $(window).on('resize', function() {
        // Debounce the resize handler
        clearTimeout(window.mapResizeTimeout);
        window.mapResizeTimeout = setTimeout(function() {
            handleResponsiveCardPositions();
        }, 250);
    });

    // Elementor frontend hooks
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-map-items.default', elementcamp_map_items);
    });

})(jQuery);