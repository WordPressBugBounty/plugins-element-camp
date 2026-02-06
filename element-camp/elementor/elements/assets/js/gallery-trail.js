(function ($) {
    "use strict";

    // ==================== UTILITY FUNCTIONS ====================

    /**
     * Performs linear interpolation between two numbers.
     * @param {number} a - The starting value.
     * @param {number} b - The target value.
     * @param {number} n - Normalization factor, typically between 0 and 1.
     * @returns {number} - Result of the linear interpolation.
     */
    const lerp = (a, b, n) => (1 - n) * a + n * b;

    /**
     * Calculates the Euclidean distance between two points in a 2D space.
     * @param {number} x1 - X-coordinate of the first point.
     * @param {number} y1 - Y-coordinate of the first point.
     * @param {number} x2 - X-coordinate of the second point.
     * @param {number} y2 - Y-coordinate of the second point.
     * @returns {number} - Distance between the two points.
     */
    const distance = (x1, y1, x2, y2) => Math.hypot(x2 - x1, y2 - y1);

    /**
     * Retrieves the current position from a mouse or touch event.
     * @param {Event} ev - The mouse or touch event.
     * @returns {Object} - Object containing the x and y coordinates of the cursor or finger.
     */
    const getPointerPos = ev => {
        let posx = 0;
        let posy = 0;

        if (!ev) ev = window.event;

        if (ev.touches) {
            if (ev.touches.length > 0) {
                posx = ev.touches[0].pageX;
                posy = ev.touches[0].pageY;
            }
        }
        else if (ev.clientX || ev.clientY) {
            let content = document.querySelector('.tcgelements-gallery-trail');
            if (content) {
                const rect = content.getBoundingClientRect();
                posx = ev.clientX - rect.left;
                posy = ev.clientY - rect.top;
            }
        }

        return {x: posx, y: posy};
    };

    /**
     * Computes the distance between current and last recorded mouse positions.
     * @param {Object} mousePos - Current mouse position with x and y coordinates.
     * @param {Object} lastMousePos - Last recorded mouse position with x and y coordinates.
     * @returns {number} - Distance between the two mouse positions.
     */
    const getMouseDistance = (mousePos, lastMousePos) => {
        return distance(mousePos.x, mousePos.y, lastMousePos.x, lastMousePos.y);
    };

    // ==================== IMAGE CLASS ====================

    class Image {
        constructor(DOM_el) {
            this.DOM = {
                el: DOM_el,
                inner: DOM_el.querySelector('.tcgelements-gallery-trail__img-inner')
            };

            this.defaultStyle = {
                scale: 1,
                x: 0,
                y: 0,
                opacity: 0
            };

            this.timeline = null;
            this.rect = null;

            this.getRect();
            this.initEvents();
        }

        initEvents() {
            this.resize = () => {
                if (typeof gsap !== 'undefined') {
                    gsap.set(this.DOM.el, this.defaultStyle);
                }
                this.getRect();
            };

            window.addEventListener('resize', () => this.resize());
        }

        getRect() {
            this.rect = this.DOM.el.getBoundingClientRect();
        }
    }

    // ==================== IMAGE TRAIL CLASS ====================

    class ImageTrail {
        constructor(DOM_el) {
            this.DOM = {el: DOM_el};
            this.images = [];
            this.imagesTotal = 0;
            this.imgPosition = 0;
            this.zIndexVal = 1;
            this.activeImagesCount = 0;
            this.isIdle = true;
            this.threshold = 80;

            // Mouse position variables
            this.mousePos = {x: 0, y: 0};
            this.cacheMousePos = {...this.mousePos};
            this.lastMousePos = {...this.mousePos};

            this.init();
        }

        init() {
            // Create Image objects for each image element
            this.images = [...this.DOM.el.querySelectorAll('.tcgelements-gallery-trail__img')].map(img => new Image(img));
            this.imagesTotal = this.images.length;

            // Set up mouse/touch event handlers
            this.handlePointerMove = (ev) => {
                ev.preventDefault();
                if (ev.touches && ev.touches.length > 0) {
                    this.mousePos = getPointerPos(ev.touches[0]);
                } else {
                    this.mousePos = getPointerPos(ev);
                }
            };

            // Add global mouse/touch listeners
            window.addEventListener('mousemove', this.handlePointerMove);
            window.addEventListener('touchmove', this.handlePointerMove, {passive: false});

            // Initial setup
            const onPointerMoveEv = () => {
                this.cacheMousePos = {...this.mousePos};
                requestAnimationFrame(() => this.render());
                window.removeEventListener('mousemove', onPointerMoveEv);
                window.removeEventListener('touchstart', onPointerMoveEv);
            };

            window.addEventListener('mousemove', onPointerMoveEv);
            window.addEventListener('touchstart', onPointerMoveEv);
        }

        getPointerPos(ev) {
            let posx = 0;
            let posy = 0;

            if (!ev) ev = window.event;

            if (ev.touches) {
                if (ev.touches.length > 0) {
                    posx = ev.touches[0].pageX;
                    posy = ev.touches[0].pageY;
                }
            }
            else if (ev.clientX || ev.clientY) {
                const rect = this.DOM.el.getBoundingClientRect();
                posx = ev.clientX - rect.left;
                posy = ev.clientY - rect.top;
            }

            return {x: posx, y: posy};
        }

        render() {
            let distance = getMouseDistance(this.mousePos, this.lastMousePos);

            this.cacheMousePos.x = lerp(this.cacheMousePos.x || this.mousePos.x, this.mousePos.x, 0.1);
            this.cacheMousePos.y = lerp(this.cacheMousePos.y || this.mousePos.y, this.mousePos.y, 0.1);

            if (distance > this.threshold) {
                this.showNextImage();
                this.lastMousePos = {...this.mousePos};
            }

            if (this.isIdle && this.zIndexVal !== 1) {
                this.zIndexVal = 1;
            }

            requestAnimationFrame(() => this.render());
        }

        showNextImage() {
            if (typeof gsap === 'undefined') {
                console.warn('GSAP not loaded');
                return;
            }

            ++this.zIndexVal;

            this.imgPosition = this.imgPosition < this.imagesTotal - 1 ? this.imgPosition + 1 : 0;

            const img = this.images[this.imgPosition];

            gsap.killTweensOf(img.DOM.el);

            img.timeline = gsap.timeline({
                onStart: () => this.onImageActivated(),
                onComplete: () => this.onImageDeactivated()
            })
                .fromTo(img.DOM.el, {
                    opacity: 1,
                    scale: 1,
                    zIndex: this.zIndexVal,
                    x: this.cacheMousePos.x - img.rect.width / 2,
                    y: this.cacheMousePos.y - img.rect.height / 2
                }, {
                    duration: 0.4,
                    ease: 'power1',
                    x: this.mousePos.x - img.rect.width / 2,
                    y: this.mousePos.y - img.rect.height / 2
                }, 0)
                .to(img.DOM.el, {
                    duration: 0.4,
                    ease: 'power3',
                    opacity: 0,
                    scale: 0.2
                }, 0.4);
        }

        onImageActivated() {
            this.activeImagesCount++;
            this.isIdle = false;
        }

        onImageDeactivated() {
            this.activeImagesCount--;
            if (this.activeImagesCount === 0) {
                this.isIdle = true;
            }
        }

        destroy() {
            window.removeEventListener('mousemove', this.handlePointerMove);
            window.removeEventListener('touchmove', this.handlePointerMove);

            this.images.forEach(img => {
                if (img.timeline) {
                    img.timeline.kill();
                }
            });
        }
    }

    // ==================== MAIN WIDGET FUNCTION ====================

    function tcElementsGalleryTrail($scope, $) {
        const $gallery = $scope.find('.tcgelements-gallery-trail');

        if (!$gallery.length) return;

        // Check for GSAP
        if (typeof gsap === 'undefined') {
            console.warn('GSAP is required for Gallery Trail widget');
            return;
        }

        // Wait a moment for images to be processed, then initialize
        setTimeout(() => {
            // Create new ImageTrail instance
            const imageTrail = new ImageTrail($gallery[0]);

            // Store instance for cleanup if needed
            $gallery.data('imageTrail', imageTrail);

            // Cleanup on widget removal/update
            $scope.on('remove', function() {
                const instance = $gallery.data('imageTrail');
                if (instance && instance.destroy) {
                    instance.destroy();
                }
            });
        }, 100);
    }

    // ==================== ELEMENTOR INITIALIZATION ====================

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-gallery-trail.default', tcElementsGalleryTrail);
    });

})(jQuery);