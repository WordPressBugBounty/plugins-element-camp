(function($) {
    "use strict";

    function initHoverReveal($scope) {
        $scope.find('.hover-reveal-item').each(function() {
            var $item = $(this);
            var $img = $item.find('.award-reveal-img');
            if (!$img.length) return;

            $item.on('mousemove', function(e) {
                var rect = this.getBoundingClientRect();
                var translateX = e.clientX - rect.left;
                var translateY = e.clientY - rect.top;
                $img.css({
                    'transform': 'translate(' + translateX + 'px, ' + translateY + 'px)'
                });
            });

            $item.on('mouseleave', function() {
                $img.css('transform', '');
            });
        });
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-awards.default', initHoverReveal);
    });
})(jQuery);