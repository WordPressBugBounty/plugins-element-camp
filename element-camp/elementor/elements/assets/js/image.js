(function ($) {
    "use strict";

    /* ----------------------------------------------------
     *  Keep image height equal to width (BEST METHOD)
     * ---------------------------------------------------- */
    function setImageHeightSameAsWidth($scope) {
        const $targets = $scope
            ? $scope.find('.tcgelements-image .img-h-w')
            : $('.img-h-w');

        $targets.each(function () {
            const el = this;

            // Prevent multiple observers on same element
            if (el.__tcgResizeObserver) return;

            const observer = new ResizeObserver(entries => {
                entries.forEach(entry => {
                    const width = entry.contentRect.width;
                    el.style.height = width + 'px';
                });
            });

            observer.observe(el);
            el.__tcgResizeObserver = observer;
        });
    }

    /* ----------------------------------------------------
     *  Elementor Image Hover Logic
     * ---------------------------------------------------- */
    function elementcamp_image($scope, $) {
        const $images = $scope.find('.tcgelements-image');
        if (!$images.length) return;

        $images.each(function (index) {
            const $imageElement = $(this);
            const uniqueNs = `.tcg-${$scope[0].dataset.id}-${index}`;

            const $widget = $imageElement.closest('.elementor-widget');
            const $container = $widget.closest('.e-con');
            const $parentContainer = $container.parent().closest('.e-con');

            // Clear only this widget events
            $container.off(uniqueNs);
            $parentContainer.off(uniqueNs);

            const activate = () =>
                $imageElement.addClass('tcgelements-image-container-active');
            const deactivate = () =>
                $imageElement.removeClass('tcgelements-image-container-active');

            if ($imageElement.hasClass('selector-type-container')) {
                $container
                    .on(`mouseenter${uniqueNs}`, activate)
                    .on(`mouseleave${uniqueNs}`, deactivate);
            }

            if ($imageElement.hasClass('selector-type-parent-container')) {
                $parentContainer
                    .on(`mouseenter${uniqueNs}`, activate)
                    .on(`mouseleave${uniqueNs}`, deactivate);
            }

            if ($imageElement.hasClass('selector-type-parent-parent-container')) {
                const $parentParent = $parentContainer.parent().closest('.e-con');
                $parentParent.off(uniqueNs)
                    .on(`mouseenter${uniqueNs}`, activate)
                    .on(`mouseleave${uniqueNs}`, deactivate);
            }

            if ($imageElement.hasClass('selector-type-parent-n')) {
                const level = parseInt($imageElement.data('parent-level'), 10) || 1;
                let $target = $widget;

                for (let i = 0; i < level; i++) {
                    $target = $target.parent();
                    if (!$target.length) break;
                }

                if ($target.length) {
                    $target.off(uniqueNs)
                        .on(`mouseenter${uniqueNs}`, activate)
                        .on(`mouseleave${uniqueNs}`, deactivate);
                }
            }

            if ($imageElement.hasClass('selector-type-image')) {
                $container
                    .on(`mouseenter${uniqueNs}`, activate)
                    .on(`mouseleave${uniqueNs}`, deactivate);
            }
        });

        // 🔥 Apply height = width logic
        setImageHeightSameAsWidth($scope);
    }

    /* ----------------------------------------------------
     *  Elementor Init
     * ---------------------------------------------------- */
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-image.default',
            elementcamp_image
        );
    });

})(jQuery);
