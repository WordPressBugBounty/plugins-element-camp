(function ($) {
    "use strict";

    function tcgCreativeCards($scope, $) {
        $scope.find('.tcgelements-creative-cards .links-card .links a').on('mouseenter', function () {
            var demoClass = $(this).data('demo');
            $scope.find('.tcgelements-creative-cards .demos-imgs .img').removeClass('active');
            $scope.find('.tcgelements-creative-cards .demos-imgs .img.' + demoClass).addClass('active');
        });

        // Menu functionality
        $(".tcg-offcanvas").each(function () {

            // ------------ offcanvas style1 -----------
            const tl = gsap.timeline({ paused: true });

            // Set initial state - menu hidden with advanced styling
            gsap.set('.tcgelements-creative-cards', {
                x: '0',
                y: "10%",
                scaleX: 0.95,
                scaleY: 0.95,
                opacity: 0,
                visibility: 'hidden',
                rotationX: -15,
                transformOrigin: "center top",
                filter: "blur(10px)"
            });

            // Set initial state for links-card
            gsap.set('.links-card', {
                y: 30,
                opacity: 0,
                scale: 0.9,
                rotationX: 20
            });

            // Set initial state for links inside cards
            gsap.set('.links-card a, .links-card .link-item', {
                y: 20,
                opacity: 0,
                scale: 0.95,
                rotationY: 10
            });

            // Set initial state for links-card
            gsap.set('.demos-imgs', {
                y: 30,
                opacity: 0,
                scale: 0.9,
                rotationX: 20
            });
            // Create advanced animation timeline
            tl.to('.tcgelements-creative-cards', {
                x: '0%',
                y: "0%",
                scaleX: 1,
                scaleY: 1,
                opacity: 1,
                visibility: 'visible',
                rotationX: 0,
                filter: "blur(0px)",
                duration: 0.8,
                ease: 'power3.out'
            })
                .to('.links-card', {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    rotationX: 0,
                    duration: 0.6,
                    ease: 'back.out(1.2)',
                    stagger: 0.1
                }, '-=0.3')
                .to('.links-card a, .links-card .link-item', {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    rotationY: 0,
                    duration: 0.4,
                    ease: 'power2.out',
                    stagger: {
                        amount: 0.6,
                        from: "start",
                        ease: "power1.out"
                    }
                }, '-=0.2')
                .to('.demos-imgs', {
                    y: 0,
                    opacity: 1,
                    scale: 1,
                    rotationX: 0,
                    duration: 0.6,
                    ease: 'back.out(1.2)',
                    stagger: 0.1
                }, '-=0.3')

            let isMenuOpen = $(this).find('.tcg-offcanvas-wrapper').hasClass('show-offcanvas') ? true : false;
            const menuOpenBtn = $(this).find('button.offcanvas-toggle');
            const closeBtn = $(this).find('.cls-btn');

            // Open menu with open button
            if (menuOpenBtn) {
                menuOpenBtn.on('click', function () {
                    if (!isMenuOpen) {
                        // Reset all elements to initial state before opening
                        gsap.set('.tcgelements-creative-cards', {
                            x: '0',
                            y: "10%",
                            scaleX: 0.95,
                            scaleY: 0.95,
                            opacity: 0,
                            visibility: 'hidden',
                            rotationX: -15,
                            transformOrigin: "center top",
                            filter: "blur(10px)"
                        });

                        gsap.set('.links-card', {
                            y: 30,
                            opacity: 0,
                            scale: 0.9,
                            rotationX: 20
                        });

                        gsap.set('.links-card a, .links-card .link-item', {
                            y: 20,
                            opacity: 0,
                            scale: 0.95,
                            rotationY: 10
                        });

                        // Open menu
                        tl.restart();
                        isMenuOpen = true;
                    }
                });
            }

            // Close menu with close button
            if (closeBtn) {
                closeBtn.on('click', function () {
                    if (isMenuOpen) {
                        // Advanced close animation
                        gsap.to('.links-card a, .links-card .link-item', {
                            y: -15,
                            opacity: 0,
                            scale: 0.9,
                            rotationY: -10,
                            duration: 0.2,
                            ease: 'power2.in',
                            stagger: {
                                amount: 0.3,
                                from: "end"
                            }
                        });

                        gsap.to('.links-card', {
                            y: -20,
                            opacity: 0,
                            scale: 0.85,
                            rotationX: -15,
                            duration: 0.3,
                            ease: 'power2.in',
                            delay: 0.2
                        });

                        gsap.to('.tcgelements-creative-cards', {
                            y: "10%",
                            scaleX: 0.95,
                            scaleY: 0.95,
                            opacity: 0,
                            rotationX: 15,
                            filter: "blur(5px)",
                            duration: 0.5,
                            ease: 'power2.in',
                            delay: 0.4,
                            onComplete: function () {
                                gsap.set('.tcgelements-creative-cards', { visibility: 'hidden' });
                            }
                        });

                        isMenuOpen = false;
                    }
                });
            }
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-creative-cards.default', tcgCreativeCards);
    });

})(jQuery);