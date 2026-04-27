(function ($) {
    "use strict";

    // Cache for frequently accessed values
    const cache = new Map();
    const activeAnimations = new Set();
    const animationVisibilityState = new Map(); // Track visibility for each animation

    // Visibility API optimization - pause animations when not visible
    function setupVisibilityOptimization(animationId, $element, animationFn) {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    animationVisibilityState.set(animationId, entry.isIntersecting);
                });
            }, { threshold: 0 });
            observer.observe($element[0]);
            return observer;
        }
        return null;
    }

    // Check if animation should run based on visibility
    function shouldRunAnimation(animationId) {
        const state = animationVisibilityState.get(animationId);
        return state === undefined || state === true;
    }

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

    // Throttle utility for scroll/resize events
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

    // Optimized updateCanvasSquareAspect with single pass
    function updateCanvasSquareAspect() {
        const $wrappers = $('.canvas-h-w-yes');
        if ($wrappers.length === 0) return;

        // Cache wrapper data to avoid repeated DOM queries
        $wrappers.each(function () {
            const $wrapper = $(this);
            const canvasWidth = $wrapper.outerWidth();

            // Set wrapper height once
            $wrapper.css('height', canvasWidth + 'px');

            // Cache internal elements
            const $canvasElements = $wrapper.find('.tcgelements-canvas');
            const $videoContainers = $wrapper.find('.video-canvas-container');
            const $canvases = $wrapper.find('canvas');

            // Batch CSS updates
            $canvasElements.css({ 'width': '100%', 'height': '100%' });
            $videoContainers.css({ 'width': '100%', 'height': '100%' });

            // Update canvas dimensions in batch
            $canvases.each(function () {
                this.style.width = '100%';
                this.style.height = '100%';
                if (this.tagName === 'CANVAS') {
                    const rect = this.getBoundingClientRect();
                    this.width = rect.width;
                    this.height = rect.height;
                }
            });
        });
    }

    function setupSacredGeometryEffect($container) {
        const container = $container[0];
        if (!container) return;

        if (typeof THREE === 'undefined') {
            console.warn('Three.js library is not loaded.');
            return;
        }

        const canvasElement = $container.closest('.tcgelements-canvas')[0];
        const color1 = canvasElement.getAttribute('data-sg-color1') || '#00ccff';
        const color2 = canvasElement.getAttribute('data-sg-color2') || '#8000ff';

        // Get animation settings
        const enableAnimation = canvasElement.getAttribute('data-enable-animation') === 'true';
        const triggerElement = canvasElement.getAttribute('data-trigger') || '.tc-demos-preview';
        const endTriggerElement = canvasElement.getAttribute('data-end-trigger') || '.tc-inner-preview';

        try {
            const $canvasWrapper = $container.closest('.tcgelements-canvas');
            const wrapperElement = $canvasWrapper[0];
            const computedStyle = window.getComputedStyle(wrapperElement);
            let containerWidth = parseInt(computedStyle.width) || $canvasWrapper.width() || 500;
            let containerHeight = parseInt(computedStyle.height) || $canvasWrapper.height() || 500;

            if (containerHeight === 0) {
                containerHeight = 600;
                $canvasWrapper.css('min-height', containerHeight + 'px');
            }

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(75, containerWidth / containerHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });

            renderer.setSize(containerWidth, containerHeight);
            renderer.setClearColor(0x000000, 0);
            container.appendChild(renderer.domElement);

            // Create dodecahedron and extract unique vertices once
            const geometry = new THREE.DodecahedronGeometry(2, 0);
            const edgesGeometry = new THREE.EdgesGeometry(geometry, 30);
            const positions = edgesGeometry.attributes.position.array;

            const vertexMap = new Map();
            const vertices = [];

            // Single pass to extract unique vertices
            for (let i = 0; i < positions.length; i += 3) {
                const key = `${positions[i].toFixed(6)},${positions[i + 1].toFixed(6)},${positions[i + 2].toFixed(6)}`;
                if (!vertexMap.has(key)) {
                    vertexMap.set(key, true);
                    vertices.push(new THREE.Vector3(positions[i], positions[i + 1], positions[i + 2]));
                }
            }

            // Create sphere geometry and material once
            const sphereGeometry = new THREE.SphereGeometry(0.032, 8, 6);
            const sphereMaterial = new THREE.ShaderMaterial({
                uniforms: {
                    color1: { value: new THREE.Color(color1) },
                    color2: { value: new THREE.Color(color2) }
                },
                vertexShader: `
                    varying vec3 vPosition;
                    void main() {
                        vPosition = position;
                        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                    }
                `,
                fragmentShader: `
                    uniform vec3 color1;
                    uniform vec3 color2;
                    varying vec3 vPosition;
                    void main() {
                        float intensity = (vPosition.y + 1.0) * 0.5;
                        vec3 finalColor = mix(color2, color1, intensity);
                        gl_FragColor = vec4(finalColor, 0.9);
                    }
                `,
                transparent: true
            });

            // Create instanced mesh for spheres
            const sphereMesh = new THREE.InstancedMesh(sphereGeometry, sphereMaterial, vertices.length);
            const dummy = new THREE.Object3D();
            vertices.forEach((vertex, i) => {
                dummy.position.copy(vertex);
                dummy.updateMatrix();
                sphereMesh.setMatrixAt(i, dummy.matrix);
            });
            sphereMesh.instanceMatrix.needsUpdate = true;
            scene.add(sphereMesh);

            // Create tube material once (reused for all tubes)
            const tubeMaterial = new THREE.ShaderMaterial({
                uniforms: {
                    color1: { value: new THREE.Color(color1) },
                    color2: { value: new THREE.Color(color2) }
                },
                vertexShader: `
                    varying vec3 vPosition;
                    void main() {
                        vPosition = position;
                        gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                    }
                `,
                fragmentShader: `
                    uniform vec3 color1;
                    uniform vec3 color2;
                    varying vec3 vPosition;
                    void main() {
                        float intensity = (vPosition.y + 1.0) * 0.5;
                        vec3 finalColor = mix(color2, color1, intensity);
                        gl_FragColor = vec4(finalColor, 0.9);
                    }
                `,
                transparent: true
            });

            // Create edge tubes (keep as separate meshes like original for correct geometry)
            const tubeGroup = new THREE.Group();
            for (let i = 0; i < positions.length; i += 6) {
                const start = new THREE.Vector3(positions[i], positions[i + 1], positions[i + 2]);
                const end = new THREE.Vector3(positions[i + 3], positions[i + 4], positions[i + 5]);

                const curve = new THREE.LineCurve3(start, end);
                const tubeGeometry = new THREE.TubeGeometry(curve, 8, 0.03, 8, false);

                const tubeMesh = new THREE.Mesh(tubeGeometry, tubeMaterial);
                tubeGroup.add(tubeMesh);
            }
            scene.add(tubeGroup);

            // Add lights
            const ambientLight = new THREE.AmbientLight(0x404040, 0.6);
            scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(1, 1, 1);
            scene.add(directionalLight);

            const pointLight = new THREE.PointLight(0x00aaff, 1, 100);
            pointLight.position.set(0, 0, 10);
            scene.add(pointLight);

            camera.position.z = 5;

            // Interaction variables
            let mouseX = 0, mouseY = 0;
            let targetRotationX = 0, targetRotationY = 0;
            let currentRotationX = 0, currentRotationY = 0;
            let isMouseDown = false;
            let scrollRotationX = 0, scrollRotationY = 0, scrollRotationZ = 0;

            // Bounded event handlers
            const onMouseMoveGlobal = throttle(function(event) {
                if (!isMouseDown) {
                    const rect = container.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;
                    const mouseXRelative = (event.clientX - centerX) / (rect.width / 2);
                    const mouseYRelative = (event.clientY - centerY) / (rect.height / 2);
                    targetRotationY = mouseXRelative * 0.5;
                    targetRotationX = -mouseYRelative * 0.5;
                }
            }, 16);

            const onMouseDown = function(event) {
                isMouseDown = true;
                mouseX = event.clientX;
                mouseY = event.clientY;
            };

            const onMouseMove = function(event) {
                if (!isMouseDown) return;
                const deltaX = event.clientX - mouseX;
                const deltaY = event.clientY - mouseY;
                targetRotationY += deltaX * 0.01;
                targetRotationX += deltaY * 0.01;
                mouseX = event.clientX;
                mouseY = event.clientY;
            };

            const onMouseUp = function() {
                isMouseDown = false;
            };

            const onScroll = throttle(function(event) {
                const scrollY = window.scrollY;
                const maxScroll = document.body.scrollHeight - window.innerHeight;
                const scrollProgress = maxScroll > 0 ? scrollY / maxScroll : 0;
                scrollRotationX = scrollProgress * Math.PI * 4;
                scrollRotationY = scrollProgress * Math.PI * 6;
                scrollRotationZ = scrollProgress * Math.PI * 3;
            }, 16);

            const onWindowResize = debounce(function() {
                const newWidth = $container.width();
                const newHeight = $container.height();
                camera.aspect = newWidth / newHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(newWidth, newHeight);
            }, 150);

            document.addEventListener('mousemove', onMouseMoveGlobal, false);
            renderer.domElement.addEventListener('mousedown', onMouseDown, false);
            renderer.domElement.addEventListener('mousemove', onMouseMove, false);
            renderer.domElement.addEventListener('mouseup', onMouseUp, false);
            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onWindowResize, { passive: true });

            // GSAP ScrollTrigger animation - only if enabled and GSAP is available
            let scrollTriggerInstance = null;
            if (enableAnimation && typeof gsap !== 'undefined' && gsap.registerPlugin) {
                // Register ScrollTrigger plugin
                if (typeof ScrollTrigger !== 'undefined') {
                    gsap.registerPlugin(ScrollTrigger);

                    // Check if trigger elements exist
                    if (document.querySelector(triggerElement) && document.querySelector(endTriggerElement)) {
                        const tl = gsap.timeline({
                            scrollTrigger: {
                                trigger: triggerElement,
                                start: "top center",
                                endTrigger: endTriggerElement,
                                end: "bottom center",
                                scrub: true,
                                onUpdate: function(self) {
                                    // Store the ScrollTrigger instance for cleanup
                                    scrollTriggerInstance = self;
                                }
                            }
                        });

                        // Add class to container for targeting
                        $container.addClass('sacard');

                        tl.to($container[0], {
                            x: "-90vw",
                            scale: 3,
                            ease: "none"
                        })
                            .to($container[0], {
                                x: "-25vw",
                                scale: 1,
                                ease: "none"
                            });
                    }
                }
            }

            let animationId;
            const animationKey = 'sacred_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            const animate = function() {
                animationId = requestAnimationFrame(animate);

                // Always update rotation targets for smooth transition when becomes visible
                currentRotationX += (targetRotationX - currentRotationX) * 0.05;
                currentRotationY += (targetRotationY - currentRotationY) * 0.05;

                // Only render if visible
                if (shouldRunAnimation(animationKey)) {
                    sphereMesh.rotation.x = currentRotationX + scrollRotationX;
                    sphereMesh.rotation.y = currentRotationY + scrollRotationY;
                    tubeGroup.rotation.x = currentRotationX + scrollRotationX;
                    tubeGroup.rotation.y = currentRotationY + scrollRotationY;
                    if (!isMouseDown) {
                        sphereMesh.rotation.y += 0.005;
                    }
                    renderer.render(scene, camera);
                }
            };

            activeAnimations.add(animationId);

            // Set up visibility optimization
            const visibilityObserver = setupVisibilityOptimization(animationKey, $container, animate);

            animate();

            $container.data('sacredGeometryCleanup', function() {
                cancelAnimationFrame(animationId);
                activeAnimations.delete(animationId);
                if (visibilityObserver) visibilityObserver.disconnect();
                animationVisibilityState.delete(animationKey);

                // Cleanup GSAP ScrollTrigger if it exists
                if (scrollTriggerInstance) {
                    scrollTriggerInstance.kill();
                }
                if (typeof ScrollTrigger !== 'undefined') {
                    ScrollTrigger.getAll().forEach(trigger => {
                        if (trigger.trigger === triggerElement || trigger.trigger === endTriggerElement) {
                            trigger.kill();
                        }
                    });
                }

                document.removeEventListener('mousemove', onMouseMoveGlobal);
                renderer.domElement.removeEventListener('mousedown', onMouseDown);
                renderer.domElement.removeEventListener('mousemove', onMouseMove);
                renderer.domElement.removeEventListener('mouseup', onMouseUp);
                window.removeEventListener('scroll', onScroll);
                window.removeEventListener('resize', onWindowResize);
                if (renderer.domElement.parentNode) {
                    renderer.domElement.parentNode.removeChild(renderer.domElement);
                }
                if (renderer.dispose) renderer.dispose();
                sphereGeometry.dispose();
                sphereMaterial.dispose();
                tubeMaterial.dispose();
                tubeGroup.traverse((child) => {
                    if (child.isMesh && child.geometry) {
                        child.geometry.dispose();
                    }
                });

                // Remove the sacard class
                $container.removeClass('sacard');
            });

        } catch (error) {
            console.error('Error initializing Sacred Geometry effect:', error);
        }
    }

    function setupCircleAnimation($container) {
        const container = $container[0];
        if (!container) return;

        const canvasElement = $container.closest('.tcgelements-canvas')[0];
        const speedMultiplier = parseFloat(canvasElement.getAttribute('data-speed-multiplier') || '1');
        const boundaryMultiplier = parseFloat(canvasElement.getAttribute('data-boundary-multiplier') || '2');
        const baseSpeed = parseFloat(canvasElement.getAttribute('data-base-speed') || '4');
        const circlesCount = parseInt(canvasElement.getAttribute('data-circles-count') || '4');
        const animationDirection = canvasElement.getAttribute('data-animation-direction') || 'both';
        const keepOriginalY = canvasElement.getAttribute('data-keep-original-y') === 'true';

        // Cache circle selectors
        const circleSelectors = [];
        for (let i = 1; i <= circlesCount; i++) {
            circleSelectors.push(`.circle${i}`);
        }
        const circleSelectorsString = circleSelectors.join(', ');

        const circles = $container.find(circleSelectorsString);
        const containerWidth = container.clientWidth;
        const containerHeight = container.clientHeight;
        const circlesData = [];

        circles.each(function (index) {
            const circle = this;
            const $circle = $(circle);
            const size = circle.offsetWidth;

            circlesData.push({
                element: circle,
                $element: $circle,
                x: Math.random() * (containerWidth - size),
                y: keepOriginalY && animationDirection === 'horizontal' ? (circle.offsetTop || 0) : Math.random() * (containerHeight - size),
                vx: (Math.random() - 0.5) * baseSpeed,
                vy: animationDirection === 'horizontal' ? 0 : (Math.random() - 0.5) * baseSpeed,
                radius: size / 2,
                maxX: containerWidth - size,
                maxY: containerHeight - size
            });
        });

        let animationId;
        const animationKey = 'circles_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        const animateCircles = function() {
            animationId = requestAnimationFrame(animateCircles);

            // Only update positions and DOM when visible
            if (!shouldRunAnimation(animationKey)) return;

            // Batch transform updates using single CSS operation
            let transformString = '';

            circlesData.forEach(circle => {
                circle.x += circle.vx * speedMultiplier;
                if (animationDirection === 'both') {
                    circle.y += circle.vy * speedMultiplier;
                }

                // Boundary collision
                if (circle.x <= 0 || circle.x >= circle.maxX) {
                    circle.vx *= -1;
                    circle.x = Math.max(0, Math.min(circle.x, circle.maxX));
                }

                if (animationDirection === 'both') {
                    if (circle.y <= 0 || circle.y >= circle.maxY) {
                        circle.vy *= -1;
                        circle.y = Math.max(0, Math.min(circle.y, circle.maxY));
                    }
                }

                transformString += `translate(${circle.x}px, ${circle.y}px) `;
            });

            // Apply all transforms at once
            circles.css('transform', transformString.trim());
        };

        activeAnimations.add(animationId);

        // Set up visibility optimization
        const visibilityObserver = setupVisibilityOptimization(animationKey, $container, animateCircles);

        animateCircles();

        return function () {
            if (animationId) {
                cancelAnimationFrame(animationId);
                activeAnimations.delete(animationId);
            }
            if (visibilityObserver) visibilityObserver.disconnect();
            animationVisibilityState.delete(animationKey);
            circlesData.length = 0;
        };
    }

    function setupImageHoverEffect($container, imageUrl) {
        if (!$container.length || !imageUrl) return;

        const containerElement = $container[0];
        const imgSize = [1250, 833];

        if (typeof ogl === 'undefined') {
            console.warn('OGL library is not loaded.');
            return;
        }

        try {
            const renderer = new ogl.Renderer({ dpr: 2 });
            const gl = renderer.gl;

            gl.canvas.style.position = 'absolute';
            gl.canvas.style.top = '0';
            gl.canvas.style.left = '0';
            gl.canvas.style.width = '100%';
            gl.canvas.style.height = '100%';
            gl.canvas.style.zIndex = '-1';
            gl.canvas.style.pointerEvents = 'none';
            containerElement.appendChild(gl.canvas);

            let aspect = 1;
            const mouse = new ogl.Vec2(-1);
            const velocity = new ogl.Vec2();

            // Cache container dimensions
            let containerWidth = $container.width();
            let containerHeight = $container.height();

            function resize() {
                containerWidth = $container.width();
                containerHeight = $container.height();
                const imageAspect = imgSize[1] / imgSize[0];
                let a1, a2;

                if (containerHeight / containerWidth < imageAspect) {
                    a1 = 1;
                    a2 = containerHeight / containerWidth / imageAspect;
                } else {
                    a1 = (containerWidth / containerHeight) * imageAspect;
                    a2 = 1;
                }

                if (mesh && mesh.program) {
                    mesh.program.uniforms.res.value = new ogl.Vec4(containerWidth, containerHeight, a1, a2);
                }

                renderer.setSize(containerWidth, containerHeight);
                aspect = containerWidth / containerHeight;
            }

            const flowmap = new ogl.Flowmap(gl);

            const geometry = new ogl.Geometry(gl, {
                position: { size: 2, data: new Float32Array([-1, -1, 3, -1, -1, 3]) },
                uv: { size: 2, data: new Float32Array([0, 0, 2, 0, 0, 2]) },
            });

            const texture = new ogl.Texture(gl, { minFilter: gl.LINEAR, magFilter: gl.LINEAR });

            const img = new Image();
            img.onload = () => {
                texture.image = img;
                requestAnimationFrame(update);
            };
            img.crossOrigin = "Anonymous";
            img.src = imageUrl;

            const program = new ogl.Program(gl, {
                vertex: `
                    attribute vec2 uv;
                    attribute vec2 position;
                    varying vec2 vUv;
                    void main() {
                        vUv = uv;
                        gl_Position = vec4(position, 0, 1);
                    }
                `,
                fragment: `
                    precision highp float;
                    uniform sampler2D tWater;
                    uniform sampler2D tFlow;
                    uniform float uTime;
                    varying vec2 vUv;
                    uniform vec4 res;
                    void main() {
                        vec3 flow = texture2D(tFlow, vUv).rgb;
                        vec2 uv = .5 * gl_FragCoord.xy / res.xy;
                        vec2 myUV = (uv - vec2(0.5)) * res.zw + vec2(0.5);
                        myUV -= flow.xy * (0.15 * 0.7);
                        vec3 tex = texture2D(tWater, myUV).rgb;
                        gl_FragColor = vec4(tex.r, tex.g, tex.b, 1.0);
                    }
                `,
                uniforms: {
                    uTime: { value: 0 },
                    tWater: { value: texture },
                    res: { value: new ogl.Vec4(containerWidth, containerHeight, 1, 1) },
                    tFlow: flowmap.uniform,
                },
            });

            const mesh = new ogl.Mesh(gl, { geometry, program });

            const resizeHandler = debounce(resize, 150);
            $(window).on('resize.imageHover' + Date.now(), resizeHandler);
            resize();

            const containerOffset = { left: 0, top: 0 };
            const updateMouse = throttle(function(e) {
                e.preventDefault();

                let x = e.x, y = e.y;
                if (e.changedTouches && e.changedTouches.length) {
                    x = e.changedTouches[0].pageX;
                    y = e.changedTouches[0].pageY;
                }
                if (x === undefined) {
                    x = e.pageX;
                    y = e.pageY;
                }

                containerOffset.left = $container.offset().left;
                containerOffset.top = $container.offset().top;
                const relativeX = x - containerOffset.left;
                const relativeY = y - containerOffset.top;

                if (relativeX >= 0 && relativeX <= containerWidth &&
                    relativeY >= 0 && relativeY <= containerHeight) {
                    mouse.set(relativeX / containerWidth, 1.0 - relativeY / containerHeight);
                    velocity.x = (relativeX - (lastMouse.x || relativeX)) / 10;
                    velocity.y = (relativeY - (lastMouse.y || relativeY)) / 10;
                    velocity.needsUpdate = true;
                } else {
                    mouse.set(-1);
                    velocity.set(0);
                    velocity.needsUpdate = false;
                }
                lastMouse.x = relativeX;
                lastMouse.y = relativeY;
            }, 16);

            const lastMouse = { x: 0, y: 0 };
            const isTouchCapable = "ontouchstart" in window;
            const $eventTarget = $container.closest('.elementor-element');

            if (isTouchCapable) {
                $eventTarget.on("touchstart.imageHover", updateMouse);
                $eventTarget.on("touchmove.imageHover", updateMouse);
            } else {
                $eventTarget.on("mousemove.imageHover", updateMouse);
            }

            let animationId;
            const animationKey = 'imageHover_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            function update(t) {
                animationId = requestAnimationFrame(update);

                // Only render when visible
                if (!shouldRunAnimation(animationKey)) return;

                if (!velocity.needsUpdate) {
                    mouse.set(-1);
                    velocity.set(0);
                }
                velocity.needsUpdate = false;
                flowmap.aspect = aspect;
                flowmap.mouse.copy(mouse);
                flowmap.velocity.lerp(velocity, velocity.len ? 0.15 : 0.1);
                flowmap.update();
                program.uniforms.uTime.value = t * 0.01;
                renderer.render({ scene: mesh });
            }

            activeAnimations.add(animationId);

            // Set up visibility optimization
            const visibilityObserver = setupVisibilityOptimization(animationKey, $container, update);

            update();

            $container.data('imageHoverCleanup', function() {
                cancelAnimationFrame(animationId);
                activeAnimations.delete(animationId);
                if (visibilityObserver) visibilityObserver.disconnect();
                animationVisibilityState.delete(animationKey);
                if (isTouchCapable) {
                    $eventTarget.off("touchstart.imageHover");
                    $eventTarget.off("touchmove.imageHover");
                } else {
                    $eventTarget.off("mousemove.imageHover");
                }
                $(window).off('resize.imageHover' + Date.now());
                if (renderer.gl) {
                    const ext = renderer.gl.getExtension('WEBGL_lose_context');
                    if (ext) ext.loseContext();
                }
                program.destroy();
                geometry.destroy();
                texture.destroy();
                flowmap.destroy();
            });

        } catch (error) {
            console.error('Error initializing image hover effect:', error);
        }
    }

    function setupGlobeDotsEffect($container) {
        const container = $container[0];
        if (!container) return;

        if (typeof THREE === 'undefined') {
            console.warn('Three.js library is not loaded.');
            return;
        }

        // ── Read color ──────────────────────────────────────────────────────────
        const dotsColorData = container.getAttribute('data-dots-color') || '42,81,230';
        const dotsRGB = dotsColorData.split(',').map(v => parseInt(v));

        // ── Read new configurable settings (with safe defaults = old widget behavior) ──
        const PARTICLE_COUNT = parseInt(container.getAttribute('data-particle-count') || '400');
        const SPHERE_RADIUS  = 8;

        // Particle size: base size + animated variation
        const particleSizeBase      = parseFloat(container.getAttribute('data-particle-size-base')      || '0.2');
        const particleSizeVariation = parseFloat(container.getAttribute('data-particle-size-variation') || '0.04');

        // Texture shape: 'circle' (old default) or 'capsule' (sample style)
        const textureShape = container.getAttribute('data-texture-shape') || 'circle';

        // vertexColors: 'false' = old default (color from map only), 'true' = use buffer color attribute
        const useVertexColors = container.getAttribute('data-vertex-colors') === 'true';

        try {
            const containerWidth  = container.offsetWidth  || container.clientWidth;
            const containerHeight = container.offsetHeight || container.clientHeight;

            const scene    = new THREE.Scene();
            const camera   = new THREE.PerspectiveCamera(75, containerWidth / containerHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            renderer.setSize(containerWidth, containerHeight);
            renderer.setClearColor(0x000000, 0);
            renderer.domElement.style.position    = 'absolute';
            renderer.domElement.style.top         = '0';
            renderer.domElement.style.left        = '0';
            renderer.domElement.style.width       = '100%';
            renderer.domElement.style.height      = '100%';
            renderer.domElement.style.zIndex      = '-1';
            renderer.domElement.style.pointerEvents = 'none';
            container.appendChild(renderer.domElement);

            const raycaster         = new THREE.Raycaster();
            const mouse             = new THREE.Vector2();
            const positions         = new Float32Array(PARTICLE_COUNT * 3);
            const colors            = new Float32Array(PARTICLE_COUNT * 3);
            const originalPositions = new Float32Array(PARTICLE_COUNT * 3);
            const targetPositions   = new Float32Array(PARTICLE_COUNT * 3);

            // Generate sphere points
            for (let i = 0; i < PARTICLE_COUNT; i++) {
                const y      = 1 - (i / (PARTICLE_COUNT - 1)) * 2;
                const radius = Math.sqrt(1 - y * y);
                const theta  = 2.399963229728653 * i;

                const x    = Math.cos(theta) * radius * SPHERE_RADIUS;
                const z    = Math.sin(theta) * radius * SPHERE_RADIUS;
                const yPos = y * SPHERE_RADIUS;

                positions[i * 3]     = x;
                positions[i * 3 + 1] = yPos;
                positions[i * 3 + 2] = z;
                originalPositions[i * 3]     = x;
                originalPositions[i * 3 + 1] = yPos;
                originalPositions[i * 3 + 2] = z;
                targetPositions[i * 3]     = x;
                targetPositions[i * 3 + 1] = yPos;
                targetPositions[i * 3 + 2] = z;

                colors[i * 3]     = dotsRGB[0] / 255;
                colors[i * 3 + 1] = dotsRGB[1] / 255;
                colors[i * 3 + 2] = dotsRGB[2] / 255;
            }

            // ── Build texture based on chosen shape ──────────────────────────────
            const texCanvas = document.createElement('canvas');
            texCanvas.width  = 64;
            texCanvas.height = 64;
            const ctx = texCanvas.getContext('2d');

            if (textureShape === 'capsule') {
                // Elongated capsule / dash shape (matches standalone globe-dots.js sample)
                ctx.clearRect(0, 0, 64, 64);
                const gradient = ctx.createLinearGradient(16, 32, 48, 32);
                gradient.addColorStop(0,   `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 0)`);
                gradient.addColorStop(0.2, `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 1)`);
                gradient.addColorStop(0.5, `rgba(${Math.min(dotsRGB[0] + 20, 255)}, ${Math.min(dotsRGB[1] + 20, 255)}, ${Math.min(dotsRGB[2] + 20, 255)}, 1)`);
                gradient.addColorStop(0.8, `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 1)`);
                gradient.addColorStop(1,   `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 0)`);
                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.roundRect(16, 28, 32, 8, 4);
                ctx.fill();
            } else {
                // Default: circular dot with radial gradient (original widget behavior)
                const gradient = ctx.createRadialGradient(20, 20, 0, 32, 32, 32);
                gradient.addColorStop(0,   'rgba(255, 255, 255, 1)');
                gradient.addColorStop(0.3, `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 1)`);
                gradient.addColorStop(0.8, `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 1)`);
                gradient.addColorStop(1,   `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 0.9)`);
                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.arc(32, 32, 30, 0, Math.PI * 2);
                ctx.fill();
            }

            const texture = new THREE.CanvasTexture(texCanvas);

            const geometry = new THREE.BufferGeometry();
            geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            geometry.setAttribute('color',    new THREE.BufferAttribute(colors, 3));

            const material = new THREE.PointsMaterial({
                size:           particleSizeBase,
                map:            texture,
                vertexColors:   useVertexColors,
                transparent:    true,
                opacity:        1.0,
                sizeAttenuation: true,
                blending:       THREE.NormalBlending,
                depthTest:      true
            });

            const particleSystem = new THREE.Points(geometry, material);
            scene.add(particleSystem);
            camera.position.z = 20;

            let mouseX = 0, mouseY = 0;
            let time   = 0;
            const mouseWorldPos = new THREE.Vector3();
            const vector        = new THREE.Vector3();
            const dir           = new THREE.Vector3();

            const onMouseMove = throttle(function(event) {
                const containerRect = container.getBoundingClientRect();
                const relativeX = event.clientX - containerRect.left;
                const relativeY = event.clientY - containerRect.top;

                if (relativeX >= 0 && relativeX <= containerWidth &&
                    relativeY >= 0 && relativeY <= containerHeight) {
                    mouseX  = (relativeX - containerWidth  / 2) / 200;
                    mouseY  = (relativeY - containerHeight / 2) / 200;
                    mouse.x = (relativeX / containerWidth)  * 2 - 1;
                    mouse.y = -(relativeY / containerHeight) * 2 + 1;
                } else {
                    mouse.x = -1;
                    mouse.y = -1;
                    mouseX  = 0;
                    mouseY  = 0;
                }
            }, 16);

            const onWindowResize = debounce(function() {
                if (!camera || !renderer || !container) return;
                const newWidth  = container.offsetWidth  || container.clientWidth;
                const newHeight = container.offsetHeight || container.clientHeight;
                camera.aspect = newWidth / newHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(newWidth, newHeight);
            }, 150);

            document.addEventListener('mousemove', onMouseMove,    { passive: true });
            window.addEventListener('resize',      onWindowResize, { passive: true });

            let animationId;
            const animationKey = 'globeDots_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            const animate = function() {
                animationId = requestAnimationFrame(animate);

                if (!shouldRunAnimation(animationKey)) return;

                time += 0.01;

                const positions     = particleSystem.geometry.attributes.position.array;
                const mouseInfluence  = 2.5;
                const globalInfluence = 0.8;
                const maxDistance     = SPHERE_RADIUS * 3;

                vector.set(mouse.x, mouse.y, 0.5);
                vector.unproject(camera);
                dir.copy(vector).sub(camera.position).normalize();
                mouseWorldPos.copy(camera.position).add(dir.multiplyScalar(20));

                for (let i = 0; i < PARTICLE_COUNT; i++) {
                    const i3 = i * 3;
                    const originalX = originalPositions[i3];
                    const originalY = originalPositions[i3 + 1];
                    const originalZ = originalPositions[i3 + 2];

                    const dx = mouseWorldPos.x - originalX;
                    const dy = mouseWorldPos.y - originalY;
                    const dz = mouseWorldPos.z - originalZ;
                    const distanceToMouse = Math.sqrt(dx * dx + dy * dy + dz * dz);

                    const normalizedDistance = Math.min(distanceToMouse / maxDistance, 1);
                    const influence  = Math.pow(1 - normalizedDistance, 2) * globalInfluence;
                    const moveAmount = influence * mouseInfluence;

                    targetPositions[i3]     = originalX + dx / distanceToMouse * moveAmount + mouseX * influence * 0.3;
                    targetPositions[i3 + 1] = originalY + dy / distanceToMouse * moveAmount + mouseY * influence * 0.3;
                    targetPositions[i3 + 2] = originalZ + dz / distanceToMouse * moveAmount;
                }

                for (let i = 0; i < PARTICLE_COUNT * 3; i++) {
                    positions[i] += (targetPositions[i] - positions[i]) * 0.25;
                }

                particleSystem.geometry.attributes.position.needsUpdate = true;
                particleSystem.rotation.x += 0.001 + mouseY * 0.001;
                particleSystem.rotation.y += 0.002 + mouseX * 0.001;

                // Animate size using configured base + variation
                material.size = particleSizeBase + Math.sin(time * 3) * particleSizeVariation;

                camera.position.x = Math.sin(time * 0.3) * 2;
                camera.position.y = Math.cos(time * 0.2) * 1.5;
                camera.lookAt(0, 0, 0);

                renderer.render(scene, camera);
            };

            activeAnimations.add(animationId);

            const visibilityObserver = setupVisibilityOptimization(animationKey, $container, animate);

            animate();

            window.globeDotsCleanup = function() {
                cancelAnimationFrame(animationId);
                activeAnimations.delete(animationId);
                if (visibilityObserver) visibilityObserver.disconnect();
                animationVisibilityState.delete(animationKey);
                document.removeEventListener('mousemove', onMouseMove);
                window.removeEventListener('resize', onWindowResize);
                if (renderer.domElement.parentNode) {
                    renderer.domElement.parentNode.removeChild(renderer.domElement);
                }
                if (renderer.dispose) renderer.dispose();
                geometry.dispose();
                material.dispose();
                texture.dispose();
            };

        } catch (error) {
            console.error('Error initializing globe dots effect:', error);
        }
    }

    function setupBrushEffect($container) {
        const sectionContainer = $container.closest('.elementor-element').parent()[0];
        if (!sectionContainer) return;

        const BRUSH_RADIUS = 80, BRUSH_FEATHER = 1, LERP = 0.22, DPR = window.devicePixelRatio || 1;
        const canvas = $container.find('.maskCanvas')[0];
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        let target = { x: -1000, y: -1000 }, last = { x: -1000, y: -1000 }, pointerActive = false;
        let overlayReady = false;
        const overlayImg = new Image();
        overlayImg.src = canvas.dataset.overlay || "";

        overlayImg.onload = () => {
            overlayReady = true;
            resize();
        };

        const w = Math.max(1, sectionContainer.clientWidth);
        const h = Math.max(1, sectionContainer.clientHeight);

        function resize() {
            const newW = Math.max(1, sectionContainer.clientWidth);
            const newH = Math.max(1, sectionContainer.clientHeight);
            canvas.style.width  = newW + 'px';
            canvas.style.height = newH + 'px';
            canvas.width  = Math.floor(newW * DPR);
            canvas.height = Math.floor(newH * DPR);
            ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
            if (overlayReady) {
                ctx.globalCompositeOperation = 'source-over';
                ctx.drawImage(overlayImg, 0, 0, newW, newH);
            }
        }

        resize();
        const resizeHandler = debounce(resize, 150);
        window.addEventListener('resize', resizeHandler, { passive: true });

        function getLocalPos(x, y) {
            const r = sectionContainer.getBoundingClientRect();
            return {
                x: Math.max(0, Math.min(r.width,  x - r.left)),
                y: Math.max(0, Math.min(r.height, y - r.top))
            };
        }

        function drawBrush(x, y, radius = BRUSH_RADIUS) {
            ctx.save();
            ctx.globalCompositeOperation = 'destination-out';
            const g = ctx.createRadialGradient(x, y, 0, x, y, radius);
            g.addColorStop(0, 'rgba(0,0,0,1)');
            g.addColorStop(Math.max(0.2, 0.6 / BRUSH_FEATHER), 'rgba(0,0,0,0.7)');
            g.addColorStop(1, 'rgba(0,0,0,0)');
            ctx.filter    = 'blur(2px)';
            ctx.fillStyle = g;
            ctx.beginPath();
            ctx.arc(x, y, radius, 0, Math.PI * 2);
            ctx.fill();
            ctx.filter = 'none';
            ctx.restore();
        }

        let animationId;
        const animationKey = 'brush_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        const animate = function() {
            animationId = requestAnimationFrame(animate);

            if (!shouldRunAnimation(animationKey) || !pointerActive) return;

            last.x += (target.x - last.x) * LERP;
            last.y += (target.y - last.y) * LERP;
            const dx   = target.x - last.x;
            const dy   = target.y - last.y;
            const dist = Math.hypot(dx, dy);
            if (dist > 0.5) {
                const step = Math.max(1, Math.floor(dist / (BRUSH_RADIUS * 0.30)));
                for (let i = 0; i < step; i++) {
                    const t  = (i + 1) / step;
                    const ix = last.x + dx * t;
                    const iy = last.y + dy * t;
                    drawBrush(ix, iy, BRUSH_RADIUS);
                    drawBrush(ix + (Math.random() - 0.5) * 6, iy + (Math.random() - 0.5) * 6, BRUSH_RADIUS * 0.5);
                }
                last.x = target.x;
                last.y = target.y;
            } else {
                drawBrush(last.x, last.y, BRUSH_RADIUS);
            }
        };

        activeAnimations.add(animationId);

        const visibilityObserver = setupVisibilityOptimization(animationKey, $container, animate);

        animate();

        function start(x, y) {
            const p = getLocalPos(x, y);
            target = { ...p };
            last   = { ...p };
            pointerActive = true;
        }
        function move(x, y) {
            const p = getLocalPos(x, y);
            target = { ...p };
            pointerActive = true;
        }
        function stop() { pointerActive = false; }

        sectionContainer.addEventListener('mousemove',  e => move(e.clientX, e.clientY));
        sectionContainer.addEventListener('mouseenter', e => start(e.clientX, e.clientY));
        sectionContainer.addEventListener('mouseleave', stop);
        sectionContainer.addEventListener('touchstart', e => start(e.touches[0].clientX, e.touches[0].clientY), { passive: true });
        sectionContainer.addEventListener('touchmove',  e => move(e.touches[0].clientX, e.touches[0].clientY),  { passive: false });
        sectionContainer.addEventListener('touchend', stop);

        $container.data('brushCleanup', function() {
            cancelAnimationFrame(animationId);
            activeAnimations.delete(animationId);
            if (visibilityObserver) visibilityObserver.disconnect();
            animationVisibilityState.delete(animationKey);
            window.removeEventListener('resize', resizeHandler);
        });
    }

    function setupVideoCanvasEffect($container) {
        const container = $container[0];
        if (!container) return;

        const canvas           = container.querySelector('.video-canvas');
        const loadingIndicator = container.querySelector('.video-loading');

        if (!canvas) {
            console.warn('Video canvas element not found');
            return;
        }

        const ctx             = canvas.getContext('2d', { alpha: true });
        const canvasElement   = container;
        const frameCount      = parseInt(canvasElement.getAttribute('data-frame-count')      || '100');
        const framePrefix     = canvasElement.getAttribute('data-frame-prefix')              || 'frame_';
        const frameExtension  = canvasElement.getAttribute('data-frame-extension')           || 'jpg';
        const framesFolder    = canvasElement.getAttribute('data-frames-folder')             || '';
        const backgroundColor = canvasElement.getAttribute('data-bg-color')                 || '#F0F2EF';
        const scrollSpeed     = parseFloat(canvasElement.getAttribute('data-scroll-speed')  || '1000');
        const animationEase   = parseFloat(canvasElement.getAttribute('data-animation-ease')|| '0.3');
        const imageScale      = parseFloat(canvasElement.getAttribute('data-image-scale')   || '1.02');
        const canvasWidth     = parseInt(canvasElement.getAttribute('data-canvas-width')    || '630');
        const canvasHeight    = parseInt(canvasElement.getAttribute('data-canvas-height')   || '730');
        const showLoading     = canvasElement.getAttribute('data-show-loading') === 'true';

        if (!framesFolder) {
            console.error('Video Canvas: Frames folder URL is required');
            if (loadingIndicator) loadingIndicator.textContent = 'Error: No frames folder specified';
            return;
        }

        let displayWidth  = canvasWidth;
        let displayHeight = canvasHeight;

        function resizeCanvas() {
            const dpr = Math.min(window.devicePixelRatio || 1, 2);
            canvas.width        = Math.floor(displayWidth  * dpr);
            canvas.height       = Math.floor(displayHeight * dpr);
            canvas.style.width  = displayWidth  + "px";
            canvas.style.height = displayHeight + "px";
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }
        resizeCanvas();

        const imgPath = i => {
            const cleanFolderUrl  = framesFolder.replace(/\/$/, '');
            const paddedNumber    = String(i).padStart(4, '0');
            return `${cleanFolderUrl}/${framePrefix}${paddedNumber}.${frameExtension}`;
        };

        const images      = new Array(frameCount + 1);
        let loadedImages  = 0;
        let isLoading     = true;

        function updateLoadingIndicator() {
            if (loadingIndicator && showLoading) {
                const progress = Math.round((loadedImages / frameCount) * 100);
                loadingIndicator.textContent = `Loading frames... ${progress}%`;
                if (loadedImages >= frameCount) {
                    loadingIndicator.style.display = 'none';
                    isLoading = false;
                }
            }
        }

        function preloadImages() {
            const batchSize  = 10;
            let currentIndex = 1;

            function loadBatch() {
                const endIndex = Math.min(currentIndex + batchSize, frameCount + 1);
                for (let i = currentIndex; i < endIndex; i++) {
                    const img  = new Image();
                    img.onload = () => { loadedImages++; updateLoadingIndicator(); };
                    img.onerror = () => { console.error(`Failed to load video frame: ${imgPath(i)}`); loadedImages++; updateLoadingIndicator(); };
                    img.src    = imgPath(i);
                    images[i]  = img;
                }
                currentIndex = endIndex;
                if (currentIndex <= frameCount) requestAnimationFrame(loadBatch);
            }
            loadBatch();
        }

        preloadImages();

        let targetFrame  = 1;
        let currentFrame = 1;

        const updateFrameFromScroll = throttle(function() {
            const pixelsPerFrame    = scrollSpeed / Math.max(1, frameCount - 1);
            const scrollBasedFrame  = Math.floor(window.scrollY / pixelsPerFrame) + 1;
            targetFrame             = Math.max(1, Math.min(frameCount, scrollBasedFrame));
        }, 16);

        const handleScroll = function() {
            if (!isLoading) updateFrameFromScroll();
        };

        window.addEventListener('scroll', handleScroll, { passive: true });

        let lastDrawnFrame = 0;

        function drawFrame(index) {
            if (index === lastDrawnFrame) return;
            if (index < 1 || index > frameCount) return;

            const img = images[index];
            if (!img || !img.complete) return;

            try {
                ctx.clearRect(0, 0, displayWidth, displayHeight);
                ctx.fillStyle = backgroundColor;
                ctx.fillRect(0, 0, displayWidth, displayHeight);

                const scale      = imageScale;
                const imgAspect  = img.naturalWidth / img.naturalHeight;
                const canvasAspect = displayWidth / displayHeight;

                let drawW, drawH;
                if (imgAspect > canvasAspect) {
                    drawH = displayHeight * scale;
                    drawW = drawH * imgAspect;
                } else {
                    drawW = displayWidth * scale;
                    drawH = drawW / imgAspect;
                }

                const drawX = (displayWidth  - drawW) / 2;
                const drawY = (displayHeight - drawH) / 2;

                ctx.drawImage(img, drawX, drawY, drawW, drawH);
                lastDrawnFrame = index;
            } catch (error) {
                console.error(`Error drawing video frame ${index}:`, error);
            }
        }

        let animationId;
        function animate() {
            animationId = requestAnimationFrame(animate);
            if (!isLoading) {
                currentFrame += (targetFrame - currentFrame) * animationEase;
                drawFrame(Math.round(currentFrame));
            }
        }

        function startAnimation() {
            if (images[1] && images[1].complete && loadedImages >= Math.min(10, frameCount)) {
                updateFrameFromScroll();
                animate();
            } else {
                requestAnimationFrame(startAnimation);
            }
        }

        const handleResize = debounce(function() {
            const containerWidth  = $container.width();
            const containerHeight = $container.height();
            if (containerWidth > 0 && containerHeight > 0) {
                displayWidth  = containerWidth;
                displayHeight = containerHeight;
                resizeCanvas();
                updateFrameFromScroll();
            }
        }, 150);

        window.addEventListener('resize', handleResize, { passive: true });

        startAnimation();

        $container.data('videoCanvasCleanup', function() {
            cancelAnimationFrame(animationId);
            activeAnimations.delete(animationId);
            window.removeEventListener('scroll', handleScroll);
            window.removeEventListener('resize', handleResize);
            for (let i = 1; i <= frameCount; i++) {
                if (images[i]) { images[i].src = ''; images[i] = null; }
            }
        });
    }

    function elementcamp_canvas($scope, $) {
        $('.tcgelements-canvas', $scope).each(function (canvasIndex) {
            const $currentCanvas = $(this);

            const $sacredGeometryContainer = $currentCanvas.find('.sacred-geometry-container');
            if ($sacredGeometryContainer.length > 0) {
                setupSacredGeometryEffect($sacredGeometryContainer);
            }

            const $circlesContainer = $currentCanvas.find('.circles');
            if ($circlesContainer.length > 0) {
                const circleCleanup = setupCircleAnimation($circlesContainer);
                $circlesContainer.data('circleCleanup', circleCleanup);
            }

            const $imageHoverContainer = $currentCanvas.find('.image-hover-container');
            if ($imageHoverContainer.length > 0) {
                const imageUrl = $imageHoverContainer.data('image');
                if (imageUrl) setupImageHoverEffect($imageHoverContainer, imageUrl);
            }

            const $globeDotsContainer = $currentCanvas.find('.globe-dots');
            if ($globeDotsContainer.length > 0) {
                setupGlobeDotsEffect($globeDotsContainer);
            }

            const $crystalContainer = $currentCanvas.find('.crystal-container');
            if ($crystalContainer.length > 0) {
                $crystalContainer.each(function (crystalIndex) {
                    const $container = $(this);
                    const instanceId = 'crystal_instance_' + canvasIndex + '_' + crystalIndex;

                    const scene    = new THREE.Scene();
                    const camera   = new THREE.PerspectiveCamera(75, $container.width() / $container.height(), 0.1, 1000);
                    camera.position.z = 5;

                    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
                    renderer.setSize($container.width(), $container.height());
                    renderer.setClearColor(0x000000, 0);
                    renderer.physicallyCorrectLights    = true;
                    renderer.toneMapping                = THREE.ACESFilmicToneMapping;
                    renderer.toneMappingExposure        = 1.5;
                    renderer.shadowMap.enabled          = true;
                    renderer.shadowMap.type             = THREE.PCFSoftShadowMap;
                    $container.append(renderer.domElement);

                    const ambientLight = new THREE.AmbientLight(0x404040, 1.2);
                    scene.add(ambientLight);

                    const directionalLight1 = new THREE.DirectionalLight(0xffffff, 2);
                    directionalLight1.position.set(5, 5, 5);
                    directionalLight1.castShadow = true;
                    directionalLight1.shadow.mapSize.width  = 1024;
                    directionalLight1.shadow.mapSize.height = 1024;
                    scene.add(directionalLight1);

                    const directionalLight2 = new THREE.DirectionalLight(0x6666ff, 1.2);
                    directionalLight2.position.set(-5, 5, -5);
                    scene.add(directionalLight2);

                    const rimLight = new THREE.DirectionalLight(0xffffff, 1);
                    rimLight.position.set(0, 0, -10);
                    scene.add(rimLight);

                    const pointLight = new THREE.PointLight(0xffffcc, 1, 10);
                    pointLight.position.set(0, 3, 2);
                    scene.add(pointLight);

                    const group = new THREE.Group();
                    scene.add(group);

                    function createGradientTexture() {
                        const canvas   = document.createElement('canvas');
                        canvas.width   = 512;
                        canvas.height  = 512;
                        const context  = canvas.getContext('2d');
                        const gradient = context.createLinearGradient(0, 0, 512, 0);
                        gradient.addColorStop(0,   '#0044FF');
                        gradient.addColorStop(0.4, '#ffffff');
                        gradient.addColorStop(0.6, '#ffffff');
                        gradient.addColorStop(1,   '#0044FF');
                        context.fillStyle = gradient;
                        context.fillRect(0, 0, 512, 512);
                        const texture = new THREE.CanvasTexture(canvas);
                        texture.needsUpdate = true;
                        return texture;
                    }

                    function createGemGeometry(size) {
                        const geometry = new THREE.BufferGeometry();
                        const vertices = new Float32Array([
                            0, size * 1.5, 0,
                            size * 0.7, size * 0.3, size * 0.7,
                            size * 0.7, size * 0.3, -size * 0.7,
                            -size * 0.7, size * 0.3, -size * 0.7,
                            -size * 0.7, size * 0.3, size * 0.7,
                            0, size * 0.3, size,
                            0, size * 0.3, -size,
                            size * 0.5, -size * 0.3, size * 0.5,
                            size * 0.5, -size * 0.3, -size * 0.5,
                            -size * 0.5, -size * 0.3, -size * 0.5,
                            -size * 0.5, -size * 0.3, size * 0.5,
                            0, -size * 0.3, size * 0.7,
                            0, -size * 0.3, -size * 0.7,
                            0, -size, 0
                        ]);
                        geometry.setAttribute('position', new THREE.BufferAttribute(vertices, 3));
                        const indices = [
                            0, 1, 2, 0, 2, 3, 0, 3, 4, 0, 4, 1, 0, 5, 1, 0, 2, 6, 0, 6, 3, 0, 4, 5,
                            1, 5, 7, 5, 11, 7, 5, 4, 11, 4, 10, 11, 4, 3, 10, 3, 9, 10, 3, 6, 9, 6, 8, 9, 6, 2, 8, 2, 1, 8, 1, 7, 8,
                            13, 7, 8, 13, 8, 9, 13, 9, 10, 13, 10, 11, 13, 11, 12, 13, 12, 7
                        ];
                        geometry.setIndex(indices);
                        geometry.computeVertexNormals();
                        return geometry;
                    }

                    const crystalMaterial = new THREE.MeshPhysicalMaterial({
                        color: 0x6e9ee9, metalness: 0.1, roughness: 0.05,
                        clearcoat: 1.0, clearcoatRoughness: 0.1, reflectivity: 1.0,
                        transparent: true, opacity: 0.8, transmission: 0.5,
                        side: THREE.DoubleSide
                    });

                    const crystalGeometry = createGemGeometry(2);
                    const crystal         = new THREE.Mesh(crystalGeometry, crystalMaterial);
                    crystal.castShadow    = true;
                    crystal.receiveShadow = true;

                    const edges       = new THREE.EdgesGeometry(crystalGeometry);
                    const edgeMaterial = new THREE.LineBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.2 });
                    const wireframe   = new THREE.LineSegments(edges, edgeMaterial);
                    crystal.add(wireframe);

                    group.add(crystal);

                    let lastScrollTop = 0;
                    let rotationSpeed = { x: 0, y: 0 };

                    const scrollHandler = function (event) {
                        const scrollTop  = window.pageYOffset || document.documentElement.scrollTop;
                        const scrollDelta = scrollTop - lastScrollTop;
                        rotationSpeed.y  += scrollDelta * 0.0004;
                        lastScrollTop     = scrollTop;
                    };
                    $(window).on('scroll.' + instanceId, scrollHandler);

                    const wheelHandler = function (event) {
                        if (event.shiftKey) {
                            event.preventDefault();
                            camera.position.z += event.originalEvent.deltaY * 0.01;
                            camera.position.z  = Math.max(2, Math.min(10, camera.position.z));
                        }
                    };
                    $(window).on('wheel.' + instanceId, wheelHandler);

                    let animationId;
                    function animate() {
                        animationId = requestAnimationFrame(animate);
                        group.rotation.y += rotationSpeed.y;
                        rotationSpeed.y  *= 0.98;
                        const time = Date.now() * 0.001;
                        pointLight.position.x = Math.sin(time * 0.2) * 2;
                        pointLight.position.z = Math.cos(time * 0.2) * 2;
                        renderer.render(scene, camera);
                    }

                    const resizeHandler = function () {
                        camera.aspect = $container.width() / $container.height();
                        camera.updateProjectionMatrix();
                        renderer.setSize($container.width(), $container.height());
                    };
                    $(window).on('resize.' + instanceId, resizeHandler);

                    const autoRotationInterval = setInterval(() => {
                        if (Math.abs(rotationSpeed.y) < 0.001) group.rotation.y += 0.0003;
                    }, 30);

                    group.rotation.x = Math.PI / 6;
                    group.rotation.y = Math.PI / 8;

                    activeAnimations.add(animationId);
                    animate();

                    $container.data('crystalCleanup', function() {
                        cancelAnimationFrame(animationId);
                        activeAnimations.delete(animationId);
                        $(window).off('scroll.' + instanceId);
                        $(window).off('wheel.'  + instanceId);
                        $(window).off('resize.' + instanceId);
                        clearInterval(autoRotationInterval);
                        if (scene.dispose)    scene.dispose();
                        if (renderer.dispose) renderer.dispose();
                        crystalGeometry.dispose();
                        crystalMaterial.dispose();
                        edgeMaterial.dispose();
                    });
                });
            }

            const $brushContainer = $currentCanvas.find('.brush-container');
            if ($brushContainer.length > 0) setupBrushEffect($brushContainer);

            const $videoContainer = $currentCanvas.find('.video-canvas-container');
            if ($videoContainer.length > 0) setupVideoCanvasEffect($videoContainer);
        });
    }

    $(document).ready(function() {
        updateCanvasSquareAspect();
    });

    let resizeTimeout;
    $(window).on('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(updateCanvasSquareAspect, 150);
    });

    $(window).on('elementor/frontend/init', function () {
        updateCanvasSquareAspect();
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-canvas.default', elementcamp_canvas);
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-canvas.default', debounce(function() {
            updateCanvasSquareAspect();
        }, 200));
    });

})(jQuery);