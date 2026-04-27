(function ($) {
    "use strict";

    // Debounce utility
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Throttle utility
    function throttle(func, limit) {
        let inThrottle;
        return function executedFunction(...args) {
            if (!inThrottle) {
                func(...args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    function tcElementsButton($scope, $) {

        function setImageHeightSameAsWidth() {
            $(".tcgelements-button.img-h-w").each(function() {
                var imgWidth = $(this).width();
                $(this).css("height", imgWidth);
            });
        }

        // Initialize on load
        setImageHeightSameAsWidth();

        // Handle on window resize with debouncing
        let resizeTimer;
        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                setImageHeightSameAsWidth();
            }, 100);
        });

        // Existing button hover functionality
        $('.e-parent .elementor-widget-tcgelements-button').parent().on('mouseenter', function () {
            $(this).find('.btn-text-selector-type-container').addClass('tc-button-text-container-active');
        }).on('mouseleave', function () {
            $(this).find('.btn-text-selector-type-container').removeClass('tc-button-text-container-active');
        });

        $scope.parent().on('mouseenter', function () {
            $scope.find('.btn-selector-type-container').addClass('tc-button-container-active');
        }).on('mouseleave', function () {
            $scope.find('.btn-selector-type-container').removeClass('tc-button-container-active');
        });

        $scope.parent().parent().on('mouseenter', function () {
            $scope.find('.btn-selector-type-parent-container').addClass('tc-button-container-active');
        }).on('mouseleave', function () {
            $scope.find('.btn-selector-type-parent-container').removeClass('tc-button-container-active');
        });

        // Parent n level hover
        const element = $scope.find('.tcgelements-button');
        if (element.attr('data-parent-level') !== undefined) {
            let parentLevel = parseInt(element.data('parent-level')) || 1;
            let $parentTarget = $scope;
            for (let i = 0; i < parentLevel; i++) {
                $parentTarget = $parentTarget.parent();
            }
            $parentTarget.on('mouseenter', function () {
                $scope.find('.btn-selector-type-parent-n').addClass('tc-button-container-active');
            }).on('mouseleave', function () {
                $scope.find('.btn-selector-type-parent-n').removeClass('tc-button-container-active');
            });
        }

        // NEW: Before button hover selector functionality
        function initBeforeButtonHover() {
            $scope.find('.tcgelements-button').each(function() {
                const $button = $(this);
                const beforeSelector = $button.attr('data-before-selector');
                const beforeParentLevel = parseInt($button.attr('data-before-parent-level')) || 1;

                // Skip if no selector or default button selector
                if (!beforeSelector || beforeSelector === 'button') {
                    return;
                }

                let $hoverTarget;

                // Determine hover target based on selector type
                if (beforeSelector === 'container') {
                    $hoverTarget = $scope.parent();
                } else if (beforeSelector === 'parent-container') {
                    $hoverTarget = $scope.parent().parent();
                } else if (beforeSelector === 'parent-n') {
                    $hoverTarget = $scope;
                    for (let i = 0; i < beforeParentLevel; i++) {
                        $hoverTarget = $hoverTarget.parent();
                    }
                }

                // Apply hover functionality
                if ($hoverTarget && $hoverTarget.length) {
                    $hoverTarget.on('mouseenter.beforeButton', function() {
                        $button.addClass('tc-before-container-active');
                    });

                    $hoverTarget.on('mouseleave.beforeButton', function() {
                        $button.removeClass('tc-before-container-active');
                    });
                }
            });
        }

        // Initialize before button hover
        initBeforeButtonHover();

        // Scroll to top functionality
        $scope.find('.tcgelements-scroll-top').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if ($('body').hasClass('elementor-editor-active') || $('body').hasClass('elementor-editor-preview')) {
                $('.elementor-editor-active, .elementor-editor-preview').animate({ scrollTop: 0 }, 800);
                return false;
            }

            scrollToTop();
            return false;
        });

        function scrollToTop() {
            if (window.pageYOffset <= 10) return;

            if (window.ScrollSmoother && window.ScrollSmoother.get()) {
                window.ScrollSmoother.get().scrollTo(0, true, "power2.out");
                return;
            }

            if (window.gsap && window.ScrollToPlugin) {
                gsap.registerPlugin(ScrollToPlugin);
                gsap.killTweensOf(window, "scrollTo");
                gsap.to(window, {
                    duration: 1.2,
                    scrollTo: { y: 0 },
                    ease: "power2.inOut",
                    onComplete: () => window.ScrollTrigger && ScrollTrigger.refresh()
                });
                return;
            }

            if (window.gsap) {
                gsap.killTweensOf(document.documentElement);
                gsap.to(document.documentElement, {
                    duration: 1.2,
                    scrollTop: 0,
                    ease: "power2.inOut",
                    onComplete: () => window.ScrollTrigger && ScrollTrigger.refresh()
                });
                return;
            }

            const containers = ['#smooth-content', '#smooth-wrapper', '.blank-builder', 'html, body'];
            for (let container of containers) {
                const $container = $(container);
                if ($container.length > 0) {
                    $container.animate({ scrollTop: 0 }, 1200);
                    return;
                }
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Enhanced mouse effects handler with optimizations
        function initMouseEffects() {
            const parallaxStrength = 40;
            let parallaxTargets = [];
            let glowTargets = [];
            let isAnimating = false;
            let rafId = null;

            function initButtons() {
                // Initialize parallax buttons
                $('.tce-btn-mouse-parallax').each(function () {
                    const $btn = $(this);
                    if (!$btn.data('parallaxInit')) {
                        $btn.data('parallaxInit', true);
                        
                        // Pre-compute center position
                        const rect = $btn[0].getBoundingClientRect();
                        
                        parallaxTargets.push({
                            $el: $btn,
                            targetX: 0,
                            targetY: 0,
                            currentX: 0,
                            currentY: 0,
                            centerX: rect.left + rect.width / 2,
                            centerY: rect.top + rect.height / 2,
                            width: rect.width,
                            height: rect.height
                        });

                        $btn.css({
                            willChange: 'transform'
                        });
                    }
                });

                // Initialize glow buttons
                $('.tce-btn-glow-effect').each(function () {
                    const $btn = $(this);
                    const $glow = $btn.find('.glow');

                    if ($glow.length && !$btn.data('glowInit')) {
                        $btn.data('glowInit', true);

                        const speed = parseFloat($glow.data('speed')) || 0.3;
                        const glowData = {
                            $btn: $btn,
                            $glow: $glow,
                            speed: speed,
                            leaveSpeed: speed + 0.2,
                            // Use quickSetter for better performance
                            xSetter: gsap.quickSetter($glow[0], 'x', 'px'),
                            ySetter: gsap.quickSetter($glow[0], 'y', 'px')
                        };

                        glowTargets.push(glowData);
                        initGlowEffect(glowData);
                    }
                });
            }

            function initGlowEffect(glowData) {
                const { $btn, $glow, speed, leaveSpeed, xSetter, ySetter } = glowData;

                // Set initial position using quickSetter
                gsap.set($glow[0], { x: 0, y: 0 });

                $btn.on('mousemove.glowEffect', function (e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;

                    if (window.gsap && xSetter && ySetter) {
                        xSetter(x);
                        ySetter(y);
                    } else {
                        $glow.css({
                            transform: `translate(calc(-50% + ${x}px), calc(-50% + ${y}px))`,
                            transition: `transform ${speed}s cubic-bezier(0.215, 0.61, 0.355, 1)`
                        });
                    }
                });

                $btn.on('mouseleave.glowEffect', function () {
                    if (window.gsap && xSetter && ySetter) {
                        xSetter(0);
                        ySetter(0);
                    } else {
                        $glow.css({
                            transform: 'translate(-50%, -50%)',
                            transition: `transform ${leaveSpeed}s cubic-bezier(0.215, 0.61, 0.355, 1)`
                        });
                    }
                });
            }

            // Throttled mouse move handler
            const throttledMouseMove = throttle(function(e) {
                const mouseX = e.clientX;
                const mouseY = e.clientY;

                parallaxTargets.forEach(t => {
                    const distanceX = mouseX - t.centerX;
                    const distanceY = mouseY - t.centerY;
                    const distance = Math.sqrt(distanceX * distanceX + distanceY * distanceY);

                    if (distance < 200) {
                        t.targetX = (distanceX / t.width) * parallaxStrength;
                        t.targetY = (distanceY / t.height) * parallaxStrength;
                    } else {
                        t.targetX = 0;
                        t.targetY = 0;
                    }
                });
            }, 16);

            // Animation loop for parallax - only runs when there are targets
            function animateParallax() {
                if (parallaxTargets.length === 0) {
                    isAnimating = false;
                    return;
                }

                if (!isAnimating) {
                    isAnimating = true;
                }

                parallaxTargets.forEach(t => {
                    t.currentX += (t.targetX - t.currentX) * 0.1;
                    t.currentY += (t.targetY - t.currentY) * 0.1;
                    t.$el.css('transform', `translate(${t.currentX}px, ${t.currentY}px) scale(1.05)`);
                });

                rafId = requestAnimationFrame(animateParallax);
            }

            // Initialize and start only if there are parallax targets
            function startParallax() {
                initButtons();
                if (parallaxTargets.length > 0 && !isAnimating) {
                    isAnimating = true;
                    $(document).on('mousemove.parallax', throttledMouseMove);
                    animateParallax();
                }
            }

            // Use MutationObserver to detect when new buttons are added
            if ('MutationObserver' in window) {
                const observer = new MutationObserver(debounce(function() {
                    initButtons();
                }, 500));

                observer.observe(document.body, { childList: true, subtree: true });
                
                // Start after a short delay
                setTimeout(startParallax, 100);
            } else {
                // Fallback for older browsers
                setInterval(initButtons, 1000);
                setTimeout(startParallax, 100);
            }

            // Cleanup function
            return function cleanup() {
                if (rafId) {
                    cancelAnimationFrame(rafId);
                }
                $(document).off('mousemove.parallax', throttledMouseMove);
                glowTargets.forEach(glowData => {
                    glowData.$btn.off('mousemove.glowEffect');
                    glowData.$btn.off('mouseleave.glowEffect');
                });
            };
        }

        // Initialize mouse effects
        const cleanupMouseEffects = initMouseEffects();

        // Store cleanup for Elementor
        $scope.data('buttonCleanup', cleanupMouseEffects);
    }

    // Initialize
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-button.default', tcElementsButton);
    });

    // Fallback initialization
    $(document).ready(function () {
        function setImageHeightSameAsWidth() {
            $(".img-h-w").each(function() {
                var imgWidth = $(this).width();
                $(this).css("height", imgWidth);
            });
        }

        setImageHeightSameAsWidth();

        // Handle resize for fallback elements
        let fallbackResizeTimer;
        $(window).on('resize', function() {
            clearTimeout(fallbackResizeTimer);
            fallbackResizeTimer = setTimeout(function() {
                setImageHeightSameAsWidth();
            }, 100);
        });
        setTimeout(function () {
            // NEW: Fallback for before button hover selector
            $('.tcgelements-button[data-before-selector]:not([data-before-bound])').each(function() {
                const $button = $(this);
                const beforeSelector = $button.attr('data-before-selector');
                const beforeParentLevel = parseInt($button.attr('data-before-parent-level')) || 1;

                // Skip if default button selector
                if (!beforeSelector || beforeSelector === 'button') {
                    return;
                }

                $button.attr('data-before-bound', 'true');

                let $hoverTarget;
                const $scope = $button.closest('.elementor-widget-tcgelements-button');

                if (beforeSelector === 'container') {
                    $hoverTarget = $scope.parent();
                } else if (beforeSelector === 'parent-container') {
                    $hoverTarget = $scope.parent().parent();
                } else if (beforeSelector === 'parent-n') {
                    $hoverTarget = $scope;
                    for (let i = 0; i < beforeParentLevel; i++) {
                        $hoverTarget = $hoverTarget.parent();
                    }
                }

                if ($hoverTarget && $hoverTarget.length) {
                    $hoverTarget.on('mouseenter.beforeButtonFallback', function() {
                        $button.addClass('tc-before-container-active');
                    });

                    $hoverTarget.on('mouseleave.beforeButtonFallback', function() {
                        $button.removeClass('tc-before-container-active');
                    });
                }
            });

            // Scroll to top fallback
            $('.tcgelements-scroll-top:not([data-tcg-bound])').each(function () {
                $(this).attr('data-tcg-bound', 'true').on('click', function (e) {
                    e.preventDefault();

                    if (window.ScrollSmoother && window.ScrollSmoother.get()) {
                        window.ScrollSmoother.get().scrollTo(0, true, "power2.out");
                    } else if (window.gsap && window.ScrollToPlugin) {
                        gsap.registerPlugin(ScrollToPlugin);
                        gsap.to(window, { duration: 1.2, scrollTo: { y: 0 }, ease: "power2.inOut" });
                    } else {
                        $('html, body').animate({ scrollTop: 0 }, 1200);
                    }

                    return false;
                });
            });

            // Initialize glow effects for buttons loaded outside Elementor
            $('.tce-btn-glow-effect:not([data-glow-init])').each(function () {
                const $btn = $(this);
                const $glow = $btn.find('.glow');

                if ($glow.length) {
                    $btn.attr('data-glow-init', 'true');

                    const speed = parseFloat($glow.data('speed')) || 0.3;

                    $btn.on('mousemove', function (e) {
                        const rect = this.getBoundingClientRect();
                        const x = e.clientX - rect.left - rect.width / 2;
                        const y = e.clientY - rect.top - rect.height / 2;

                        if (window.gsap) {
                            gsap.to($glow[0], {
                                x: x,
                                y: y,
                                duration: speed,
                                ease: "power3.out"
                            });
                        }
                    });

                    $btn.on('mouseleave', function () {
                        if (window.gsap) {
                            gsap.to($glow[0], {
                                x: 0,
                                y: 0,
                                duration: speed + 0.2,
                                ease: "power3.out"
                            });
                        }
                    });
                }
            });
        }, 1000);
    });

})(jQuery);
