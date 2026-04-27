(function ($) {
    "use strict";

    function elementcamp_flow_network($scope) {
        const wrapper = $scope.find('.flow-wrapper');

        if (!wrapper.length) return;

        wrapper.each(function() {
            const el = this;
            const pathsGroup = el.querySelector('[id^="paths-"]');
            const satellites = el.querySelectorAll('.node.satellite');
            const centralNode = el.querySelector('.node.central');

            if (!pathsGroup || satellites.length === 0) return;

            // Check if RTL
            const isRTL = $('html').attr('dir') === 'rtl' || $('body').attr('dir') === 'rtl' || document.documentElement.dir === 'rtl';

            // Register GSAP plugins if available
            if (window.gsap && window.MotionPathPlugin) {
                gsap.registerPlugin(MotionPathPlugin);
            }

            let activeTweens = [];
            const nodeListeners = new WeakMap();

            function buildLayout() {
                // Kill existing animations
                activeTweens.forEach(t => t.kill());
                activeTweens = [];
                pathsGroup.innerHTML = '';

                const rect = el.getBoundingClientRect();
                const wrapperW = rect.width;
                const wrapperH = rect.height;

                if (wrapperW === 0 || wrapperH === 0) return;

                const scaleX = 1000 / wrapperW;
                const scaleY = 600 / wrapperH;
                const baseDist = Math.min(wrapperW, wrapperH);

                satellites.forEach((node, i) => {
                    // Get values from data attributes
                    const gx = parseFloat(node.dataset.gx) || 0;
                    const gy = parseFloat(node.dataset.gy) || 0;
                    const distInput = parseFloat(node.dataset.dist) || 280;

                    // Calculate position
                    const dist = baseDist * (distInput / 650);
                    const x = gx * dist;
                    const y = gy * dist;

                    if (isRTL) {
                        // In RTL, use 50% instead of -50% for horizontal positioning
                        node.style.transform = `translate(calc(50% + ${x}px), calc(-50% + ${y}px))`;
                    } else {
                        node.style.transform = `translate(calc(-50% + ${x}px), calc(-50% + ${y}px))`;
                    }

                    // SVG coordinates
                    const x1 = 500 + x * scaleX;
                    const y1 = 300 + y * scaleY;
                    const x2 = 500;
                    const y2 = 300;

                    // Create path data
                    let pathData;
                    if (gx === 0 || gy === 0) {
                        // Straight line for axis-aligned nodes
                        pathData = `M ${x1} ${y1} L ${x2} ${y2}`;
                    } else {
                        // Curved path for diagonal nodes
                        const dx = x2 - x1;
                        const dy = y2 - y1;
                        const len = Math.sqrt(dx * dx + dy * dy);
                        const nx = -dy / len;
                        const ny = dx / len;
                        const offset = len * 0.25;
                        const waveDir = (gx * gy > 0) ? 1 : -1;

                        const c1x = x1 + dx * 0.3 + nx * offset * waveDir;
                        const c1y = y1 + dy * 0.3 + ny * offset * waveDir;
                        const c2x = x1 + dx * 0.7 - nx * offset * waveDir;
                        const c2y = y1 + dy * 0.7 - ny * offset * waveDir;

                        pathData = `M ${x1} ${y1} C ${c1x} ${c1y} ${c2x} ${c2y} ${x2} ${y2}`;
                    }

                    // Create path element
                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('d', pathData);
                    path.setAttribute('class', 'connection-path');
                    path.setAttribute('id', `path-${pathsGroup.id}-${i}`);
                    pathsGroup.appendChild(path);

                    // Create particles - matching sample exactly
                    if (window.gsap && window.MotionPathPlugin) {
                        // Create particle + trail exactly like sample
                        [{ r: 2.5, opacity: 1, offset: 0 }, { r: 1.5, opacity: 0.5, offset: 0.1 }]
                            .forEach(cfg => {
                                const particle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                                particle.setAttribute('r', cfg.r);
                                particle.setAttribute('class', 'light-particle');
                                particle.style.opacity = cfg.opacity;
                                pathsGroup.appendChild(particle);

                                const duration = 2.5 + Math.random() * 2;
                                const delay = Math.random() * 2 + cfg.offset;

                                const tween = gsap.to(particle, {
                                    duration,
                                    repeat: -1,
                                    ease: 'power1.inOut',
                                    delay,
                                    motionPath: {
                                        path: path,
                                        align: path,
                                        autoRotate: true,
                                        alignOrigin: [0.5, 0.5]
                                    }
                                });
                                activeTweens.push(tween);
                            });
                    }

                    // Handle hover events - matching sample exactly
                    if (nodeListeners.has(node)) {
                        const old = nodeListeners.get(node);
                        node.removeEventListener('mouseenter', old.enter);
                        node.removeEventListener('mouseleave', old.leave);
                    }

                    const pathHoverColor = el.dataset.pathHover || '#0055ff';

                    const onEnter = () => {
                        if (window.gsap) {
                            // Scale the node
                            gsap.to(node, {
                                scale: 1.1,
                                duration: 0.4,
                                ease: 'power2.out'
                            });

                            // Highlight the path
                            gsap.to(path, {
                                stroke: 'var(--cr-main)',
                                strokeWidth: 3,
                                opacity: 0.8,
                                duration: 0.4,
                                ease: 'power2.out'
                            });

                            // Change border color - matching sample
                            node.style.borderColor = 'var(--cr-main)';
                        }
                    };

                    const onLeave = () => {
                        if (window.gsap) {
                            // Reset node scale
                            gsap.to(node, {
                                scale: 1,
                                duration: 0.4,
                                ease: 'power2.out'
                            });

                            // Reset path - using sample's color: rgba(87, 75, 87, 0.1)
                            gsap.to(path, {
                                stroke: 'rgba(87, 75, 87, 0.1)',
                                strokeWidth: 1.5,
                                opacity: 1,
                                duration: 0.4,
                                ease: 'power2.out'
                            });

                            // Reset border color - matching sample: rgba(91, 72, 91, 0.15)
                            node.style.borderColor = 'rgba(91, 72, 91, 0.15)';
                        }
                    };

                    node.addEventListener('mouseenter', onEnter);
                    node.addEventListener('mouseleave', onLeave);
                    nodeListeners.set(node, { enter: onEnter, leave: onLeave });
                });
            }

            // Debounced resize handler
            let resizeTimer;
            const handleResize = () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(buildLayout, 120);
            };

            window.addEventListener('resize', handleResize);

            // Initial build after a short delay
            setTimeout(buildLayout, 300);

            // Clean up on element removal
            $(el).on('remove', () => {
                window.removeEventListener('resize', handleResize);
                activeTweens.forEach(t => t.kill());
            });
        });
    }

    // Initialize on Elementor frontend
    $(window).on('elementor/frontend/init', function() {
        if (elementorFrontend) {
            elementorFrontend.hooks.addAction(
                'frontend/element_ready/tcgelements-flow-network.default',
                elementcamp_flow_network
            );
        }
    });

})(jQuery);