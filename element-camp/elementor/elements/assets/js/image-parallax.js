(function ($) {
    "use strict";

    function elementcamp_image_parallax($scope, $) {
        var body = document.body;

        if (!body) {
            console.error('Mouse Parallax: Body element not found.');
            return;
        }

        // Find parallax elements within the scope
        var parallaxElements = $scope.find('.tcgelements-image.tce-mouse-parallax');

        if (parallaxElements.length === 0) {
            return;
        }

        // Initialize both parallax modes
        initializeGlobalParallax();
        initializeContainerParallax();
    }

    // Mode 1: Global body mousemove (original behavior)
    function initializeGlobalParallax() {
        var body = document.body;

        // Remove any existing global handler to prevent duplicates
        if (body.parallaxHandler) {
            body.removeEventListener("mousemove", body.parallaxHandler);
        }

        // Create the parallax handler function
        body.parallaxHandler = function(event) {
            var allParallaxElements = document.querySelectorAll(".tcgelements-image.tce-mouse-parallax:not(.parallax-container .tce-mouse-parallax)");

            if (allParallaxElements.length === 0) {
                return;
            }

            var windowWidth = window.innerWidth;
            var windowHeight = window.innerHeight;

            for (var i = 0; i < allParallaxElements.length; i++) {
                var element = allParallaxElements[i];

                // Skip if element is inside a parallax-container
                if (element.closest('.parallax-container')) {
                    continue;
                }

                // Skip if element is not visible
                if (element.offsetParent === null) {
                    continue;
                }

                // Get speed and direction from data attributes
                var speedAttr = element.getAttribute("data-parallax-speed");
                var speed = speedAttr ? parseFloat(speedAttr) : 0.3;

                var direction = element.getAttribute("data-parallax-direction") || "default";

                // Validate speed value
                if (isNaN(speed) || speed === 0) {
                    speed = 0.3;
                }

                var centerX = windowWidth / 2;
                var centerY = windowHeight / 2;

                var amountMovedX, amountMovedY;

                // Apply direction logic
                switch(direction) {
                    case "inverse":
                        amountMovedX = ((centerX - event.clientX) * speed) / 8;
                        amountMovedY = ((centerY - event.clientY) * speed) / 8;
                        break;
                    case "horizontal-only":
                        amountMovedX = ((event.clientX - centerX) * speed) / 8;
                        amountMovedY = 0;
                        break;
                    case "vertical-only":
                        amountMovedX = 0;
                        amountMovedY = ((event.clientY - centerY) * speed) / 8;
                        break;
                    default: // "default"
                        amountMovedX = ((event.clientX - centerX) * speed) / 8;
                        amountMovedY = ((event.clientY - centerY) * speed) / 8;
                        break;
                }

                element.style.transform = 'translate3d(' + amountMovedX + 'px, ' + amountMovedY + 'px, 0)';
                element.style.willChange = 'transform';
            }
        };

        body.addEventListener("mousemove", body.parallaxHandler, { passive: true });
    }

    // Mode 2: Container-based parallax (new behavior from sample)
    function initializeContainerParallax() {
        var parallaxContainers = document.getElementsByClassName("parallax-container");

        for (var j = 0; j < parallaxContainers.length; j++) {
            var container = parallaxContainers[j];

            // Prevent duplicate listeners
            if (container.parallaxInitialized) {
                continue;
            }

            container.parallaxInitialized = true;

            container.addEventListener("mouseenter", function (event) {
                var self = this;
                this.parallaxMoveHandler = function(e) {
                    parallaxedContainer.call(self, e);
                };
                this.addEventListener("mousemove", this.parallaxMoveHandler);
            });

            container.addEventListener("mouseleave", function (event) {
                if (this.parallaxMoveHandler) {
                    this.removeEventListener("mousemove", this.parallaxMoveHandler);
                }

                // Reset transforms when mouse leaves
                var parallaxElements = this.querySelectorAll(".parallaxed, .tce-mouse-parallax");
                for (var i = 0; i < parallaxElements.length; i++) {
                    parallaxElements[i].style.transform = "translate(0px, 0px) scale(1)";
                }
            });
        }

        function parallaxedContainer(e) {
            var container = this;
            var containerRect = container.getBoundingClientRect();
            var mouseX = e.clientX - containerRect.left;
            var mouseY = e.clientY - containerRect.top;
            var containerWidth = containerRect.width;
            var containerHeight = containerRect.height;

            // Calculate normalized mouse position (-1 to 1)
            var normalizedX = (mouseX / containerWidth) * 2 - 1;
            var normalizedY = (mouseY / containerHeight) * 2 - 1;

            var parallaxElements = container.querySelectorAll(".parallaxed, .tce-mouse-parallax");

            for (var i = 0; i < parallaxElements.length; i++) {
                var element = parallaxElements[i];
                var elementRect = element.getBoundingClientRect();
                var elementCenterX = elementRect.left + elementRect.width / 2 - containerRect.left;
                var elementCenterY = elementRect.top + elementRect.height / 2 - containerRect.top;

                // Calculate distance from mouse to element center
                var distanceX = (mouseX - elementCenterX) / containerWidth;
                var distanceY = (mouseY - elementCenterY) / containerHeight;

                // Get custom intensity or use default
                var customIntensity = parseFloat(element.getAttribute("data-parallax-intensity"));
                var baseIntensity = !isNaN(customIntensity) ? customIntensity : 0.4;
                var intensity = baseIntensity + i * 0.15;

                // Get custom scale or use default
                var useScale = element.getAttribute("data-parallax-scale") !== "false";
                var customScaleFactor = parseFloat(element.getAttribute("data-parallax-scale-factor"));
                var scaleFactor = !isNaN(customScaleFactor) ? customScaleFactor : 0.15;

                // Movement calculations
                var moveRange = parseFloat(element.getAttribute("data-parallax-range")) || 50;
                var amountMovedX = distanceX * intensity * moveRange;
                var amountMovedY = distanceY * intensity * moveRange;

                // Enhanced scale based on mouse proximity
                var scale = 1;
                if (useScale) {
                    scale = 1 + (Math.abs(distanceX) + Math.abs(distanceY)) * scaleFactor;
                }

                // Combine transformations
                element.style.transform = 'translate(' + amountMovedX + 'px, ' + amountMovedY + 'px) scale(' + scale + ')';
                element.style.transition = 'transform 0.1s ease-out';
            }
        }
    }

    // Initialize on Elementor frontend
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-image.default',
            elementcamp_image_parallax
        );
    });

    // Cleanup function
    function cleanup() {
        var body = document.body;
        if (body && body.parallaxHandler) {
            body.removeEventListener("mousemove", body.parallaxHandler);
            delete body.parallaxHandler;
        }

        // Clean up container listeners
        var parallaxContainers = document.getElementsByClassName("parallax-container");
        for (var i = 0; i < parallaxContainers.length; i++) {
            var container = parallaxContainers[i];
            if (container.parallaxMoveHandler) {
                container.removeEventListener("mousemove", container.parallaxMoveHandler);
                delete container.parallaxMoveHandler;
            }
            delete container.parallaxInitialized;
        }
    }

    // Cleanup on page unload
    $(window).on('beforeunload', cleanup);

    // Manual initialization for testing
    window.initMouseParallax = function() {
        $('.tcgelements-image.tce-mouse-parallax').each(function() {
            elementcamp_image_parallax($(this), $);
        });
        initializeContainerParallax();
    };

})(jQuery);