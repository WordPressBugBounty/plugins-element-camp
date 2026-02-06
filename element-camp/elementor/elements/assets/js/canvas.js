(function ($) {
    "use strict";

    function updateCanvasSquareAspect() {
        $('.canvas-h-w-yes').each(function() {
            var $wrapper = $(this);
            var canvasWidth = $wrapper.outerWidth();

            // Set wrapper height to match width
            $wrapper.css('height', canvasWidth + 'px');

            // Also update the internal canvas elements to fill the wrapper
            $wrapper.find('.tcgelements-canvas').css({
                'width': '100%',
                'height': '100%'
            });

            // Update video canvas container specifically
            $wrapper.find('.video-canvas-container').css({
                'width': '100%',
                'height': '100%'
            });

            // Update the actual canvas element
            $wrapper.find('.video-canvas').each(function() {
                this.style.width = '100%';
                this.style.height = '100%';

                // If it's a canvas element, also update its internal dimensions
                if (this.tagName === 'CANVAS') {
                    var rect = this.getBoundingClientRect();
                    this.width = rect.width;
                    this.height = rect.height;
                }
            });

            // Update other canvas types
            $wrapper.find('canvas').each(function() {
                this.style.width = '100%';
                this.style.height = '100%';
                var rect = this.getBoundingClientRect();
                this.width = rect.width;
                this.height = rect.height;
            });
        });
    }

    function setupSacredGeometryEffect($container) {
        const container = $container[0];
        if (!container) return;

        // Check if Three.js is available
        if (typeof THREE === 'undefined') {
            console.warn('Three.js library is not loaded. Sacred Geometry effect requires Three.js.');
            return;
        }

        // Get color settings from data attributes ONLY
        const canvasElement = $container.closest('.tcgelements-canvas')[0];
        const color1 = canvasElement.getAttribute('data-sg-color1') || '#00ccff';
        const color2 = canvasElement.getAttribute('data-sg-color2') || '#8000ff';

        try {
            // Get container dimensions from the canvas wrapper (where CSS is applied)
            const $canvasWrapper = $container.closest('.tcgelements-canvas');
            const wrapperElement = $canvasWrapper[0];

            // Get computed styles to read actual CSS values
            const computedStyle = window.getComputedStyle(wrapperElement);
            let containerWidth = parseInt(computedStyle.width) || $canvasWrapper.width() || 500;
            let containerHeight = parseInt(computedStyle.height) || $canvasWrapper.height() || 500;

            // If height is still 0, force minimum height and set it on the wrapper
            if (containerHeight === 0) {
                containerHeight = 600; // Use a reasonable default
                $canvasWrapper.css('min-height', containerHeight + 'px');
            }

            // Scene setup
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(75, containerWidth / containerHeight, 0.1, 1000);
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });

            renderer.setSize(containerWidth, containerHeight);
            renderer.setClearColor(0x000000, 0);

            container.appendChild(renderer.domElement);

            // Create the sacred geometry (dodecahedron) - using fixed size of 2
            const geometry = new THREE.DodecahedronGeometry(2, 0);
            const mesh = new THREE.Group();

            // Get edges and create vertices
            const edgesGeometry = new THREE.EdgesGeometry(geometry, 30);
            const positions = edgesGeometry.attributes.position.array;

            const vertices = [];
            const vertexMap = new Map();

            for (let i = 0; i < positions.length; i += 3) {
                const vertex = new THREE.Vector3(positions[i], positions[i + 1], positions[i + 2]);
                const key = `${vertex.x.toFixed(6)},${vertex.y.toFixed(6)},${vertex.z.toFixed(6)}`;
                if (!vertexMap.has(key)) {
                    vertexMap.set(key, vertex);
                    vertices.push(vertex);
                }
            }

            // Create vertex spheres with gradient shader using dynamic colors
            vertices.forEach(vertex => {
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

                const sphereMesh = new THREE.Mesh(sphereGeometry, sphereMaterial);
                sphereMesh.position.copy(vertex);
                mesh.add(sphereMesh);
            });

            // Create edge tubes with gradient shader using dynamic colors
            for (let i = 0; i < positions.length; i += 6) {
                const start = new THREE.Vector3(positions[i], positions[i + 1], positions[i + 2]);
                const end = new THREE.Vector3(positions[i + 3], positions[i + 4], positions[i + 5]);

                const curve = new THREE.LineCurve3(start, end);
                const tubeGeometry = new THREE.TubeGeometry(curve, 8, 0.03, 8, false);

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

                const tubeMesh = new THREE.Mesh(tubeGeometry, tubeMaterial);
                mesh.add(tubeMesh);
            }

            scene.add(mesh);

            // Add lights
            const ambientLight = new THREE.AmbientLight(0x404040, 0.6);
            scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(1, 1, 1);
            scene.add(directionalLight);

            const pointLight = new THREE.PointLight(0x00aaff, 1, 100);
            pointLight.position.set(0, 0, 10);
            scene.add(pointLight);

            // Position camera
            camera.position.z = 5;

            // Interaction variables (same as original code)
            let mouseX = 0;
            let mouseY = 0;
            let targetRotationX = 0;
            let targetRotationY = 0;
            let currentRotationX = 0;
            let currentRotationY = 0;
            let isMouseDown = false;
            let scrollRotationX = 0;
            let scrollRotationY = 0;
            let scrollRotationZ = 0;

            // Mouse interaction handlers (exactly like original code)
            function onMouseMoveGlobal(event) {
                if (!isMouseDown) {
                    const rect = container.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;

                    const mouseXRelative = (event.clientX - centerX) / (rect.width / 2);
                    const mouseYRelative = (event.clientY - centerY) / (rect.height / 2);

                    targetRotationY = mouseXRelative * 0.5;
                    targetRotationX = -mouseYRelative * 0.5;
                }
            }

            function onMouseDown(event) {
                isMouseDown = true;
                mouseX = event.clientX;
                mouseY = event.clientY;
            }

            function onMouseMove(event) {
                if (!isMouseDown) return;

                const deltaX = event.clientX - mouseX;
                const deltaY = event.clientY - mouseY;

                targetRotationY += deltaX * 0.01;
                targetRotationX += deltaY * 0.01;

                mouseX = event.clientX;
                mouseY = event.clientY;
            }

            function onMouseUp() {
                isMouseDown = false;
            }

            function onScroll(event) {
                const scrollY = window.scrollY;
                const maxScroll = document.body.scrollHeight - window.innerHeight;
                const scrollProgress = scrollY / maxScroll;

                scrollRotationX = scrollProgress * Math.PI * 4;
                scrollRotationY = scrollProgress * Math.PI * 6;
                scrollRotationZ = scrollProgress * Math.PI * 3;
            }

            function onWindowResize() {
                const newWidth = $container.width();
                const newHeight = $container.height();

                camera.aspect = newWidth / newHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(newWidth, newHeight);
            }

            // Add event listeners (same as original)
            document.addEventListener('mousemove', onMouseMoveGlobal, false);
            renderer.domElement.addEventListener('mousedown', onMouseDown, false);
            renderer.domElement.addEventListener('mousemove', onMouseMove, false);
            renderer.domElement.addEventListener('mouseup', onMouseUp, false);
            window.addEventListener('scroll', onScroll, false);
            window.addEventListener('resize', onWindowResize, false);

            // Animation loop (same as original)
            function animate() {
                requestAnimationFrame(animate);

                // Smooth rotation interpolation
                currentRotationX += (targetRotationX - currentRotationX) * 0.05;
                currentRotationY += (targetRotationY - currentRotationY) * 0.05;

                // Apply rotations
                mesh.rotation.x = currentRotationX + scrollRotationX;
                mesh.rotation.y = currentRotationY + scrollRotationY;
                mesh.rotation.z = scrollRotationZ;

                // Auto rotation when not interacting
                if (!isMouseDown) {
                    mesh.rotation.y += 0.005;
                }

                renderer.render(scene, camera);
            }

            // Start animation
            animate();

            // Store cleanup function
            $container.data('sacredGeometryCleanup', function() {
                // Remove event listeners
                document.removeEventListener('mousemove', onMouseMoveGlobal);
                renderer.domElement.removeEventListener('mousedown', onMouseDown);
                renderer.domElement.removeEventListener('mousemove', onMouseMove);
                renderer.domElement.removeEventListener('mouseup', onMouseUp);
                window.removeEventListener('scroll', onScroll);
                window.removeEventListener('resize', onWindowResize);

                // Dispose of WebGL resources
                if (renderer && renderer.domElement && renderer.domElement.parentNode) {
                    renderer.domElement.parentNode.removeChild(renderer.domElement);
                }
                if (renderer.dispose) {
                    renderer.dispose();
                }
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

        // Build dynamic selector based on circles count
        let circleSelectors = [];
        for (let i = 1; i <= circlesCount; i++) {
            circleSelectors.push(`.circle${i}`);
        }
        const circleSelectorsString = circleSelectors.join(', ');

        const circles = $container.find(circleSelectorsString);
        const circlesData = [];

        circles.each(function (index) {
            const circle = this;
            const $circle = $(circle);
            const size = circle.offsetWidth;
            const maxX = container.clientWidth - size;
            const maxY = container.clientHeight - size;

            let initialY;
            if (keepOriginalY && animationDirection === 'horizontal') {
                // Keep original Y position from CSS
                initialY = circle.offsetTop || 0;
            } else {
                // Random Y position
                initialY = Math.random() * maxY;
            }

            circlesData.push({
                element: circle,
                $element: $circle,
                x: Math.random() * maxX,
                y: initialY,
                vx: (Math.random() - 0.5) * baseSpeed,
                vy: animationDirection === 'horizontal' ? 0 : (Math.random() - 0.5) * baseSpeed,
                radius: size / 2
            });

            // Set initial position
            const circleData = circlesData[circlesData.length - 1];
            $circle.css('transform', `translate(${circleData.x}px, ${circleData.y}px)`);
        });

        function animateCircles() {
            circlesData.forEach(circle => {
                // Update position based on direction
                circle.x += circle.vx * speedMultiplier;
                if (animationDirection === 'both') {
                    circle.y += circle.vy * speedMultiplier;
                }

                // Boundary collision
                if (circle.x <= 0 || circle.x >= container.clientWidth - circle.radius * boundaryMultiplier) {
                    circle.vx *= -1;
                    circle.x = Math.max(0, Math.min(circle.x, container.clientWidth - circle.radius * boundaryMultiplier));
                }

                if (animationDirection === 'both') {
                    if (circle.y <= 0 || circle.y >= container.clientHeight - circle.radius * boundaryMultiplier) {
                        circle.vy *= -1;
                        circle.y = Math.max(0, Math.min(circle.y, container.clientHeight - circle.radius * boundaryMultiplier));
                    }
                }

                circle.$element.css('transform', `translate(${circle.x}px, ${circle.y}px)`);
            });

            requestAnimationFrame(animateCircles);
        }

        if (circlesData.length > 0) {
            animateCircles();
        }

        return function () {
            circlesData.length = 0;
        };
    }

    function setupImageHoverEffect($container, imageUrl) {
        // If container doesn't exist or no image URL, skip this animation
        if (!$container.length || !imageUrl) return;

        const containerElement = $container[0];

        // Image size configuration - you may want to make this dynamic based on actual image
        const imgSize = [1250, 833];

        // Vertex shader for the image hover effect
        const vertex = `
        attribute vec2 uv;
        attribute vec2 position;
        varying vec2 vUv;
        void main() {
            vUv = uv;
            gl_Position = vec4(position, 0, 1);
        }
    `;

        // Fragment shader for the image hover effect
        const fragment = `
        precision highp float;
        precision highp int;
        uniform sampler2D tWater;
        uniform sampler2D tFlow;
        uniform float uTime;
        varying vec2 vUv;
        uniform vec4 res;

        void main() {
            // R and G values are velocity in the x and y direction
            // B value is the velocity length
            vec3 flow = texture2D(tFlow, vUv).rgb;

            vec2 uv = .5 * gl_FragCoord.xy / res.xy ;
            vec2 myUV = (uv - vec2(0.5))*res.zw + vec2(0.5);
            myUV -= flow.xy * (0.15 * 0.7);

            vec3 tex = texture2D(tWater, myUV).rgb;

            gl_FragColor = vec4(tex.r, tex.g, tex.b, 1.0);
        }
    `;

        // Check if OGL is available
        if (typeof ogl === 'undefined') {
            console.warn('OGL library is not loaded. Image hover effect requires OGL.');
            return;
        }

        try {
            const renderer = new ogl.Renderer({ dpr: 2 });
            const gl = renderer.gl;

            // Style the canvas to be positioned absolutely and behind content
            gl.canvas.style.position = 'absolute';
            gl.canvas.style.top = '0';
            gl.canvas.style.left = '0';
            gl.canvas.style.width = '100%';
            gl.canvas.style.height = '100%';
            gl.canvas.style.zIndex = '-1';
            gl.canvas.style.pointerEvents = 'none';

            // Append canvas to the container
            containerElement.appendChild(gl.canvas);

            // Variable inputs to control flowmap
            let aspect = 1;
            const mouse = new ogl.Vec2(-1);
            const velocity = new ogl.Vec2();

            function resize() {
                let a1, a2;
                var imageAspect = imgSize[1] / imgSize[0];
                const containerWidth = $container.width();
                const containerHeight = $container.height();

                if (containerHeight / containerWidth < imageAspect) {
                    a1 = 1;
                    a2 = containerHeight / containerWidth / imageAspect;
                } else {
                    a1 = (containerWidth / containerHeight) * imageAspect;
                    a2 = 1;
                }

                if (mesh && mesh.program) {
                    mesh.program.uniforms.res.value = new ogl.Vec4(
                        containerWidth,
                        containerHeight,
                        a1,
                        a2
                    );
                }

                renderer.setSize(containerWidth, containerHeight);
                aspect = containerWidth / containerHeight;
            }

            const flowmap = new ogl.Flowmap(gl);

            // Triangle that includes -1 to 1 range for 'position', and 0 to 1 range for 'uv'
            const geometry = new ogl.Geometry(gl, {
                position: {
                    size: 2,
                    data: new Float32Array([-1, -1, 3, -1, -1, 3]),
                },
                uv: { size: 2, data: new Float32Array([0, 0, 2, 0, 0, 2]) },
            });

            const texture = new ogl.Texture(gl, {
                minFilter: gl.LINEAR,
                magFilter: gl.LINEAR,
            });

            const img = new Image();
            img.onload = () => {
                texture.image = img;
                // Start animation after image loads
                requestAnimationFrame(update);
            };
            img.crossOrigin = "Anonymous";
            img.src = imageUrl;

            let a1, a2;
            var imageAspect = imgSize[1] / imgSize[0];
            const containerWidth = $container.width();
            const containerHeight = $container.height();

            if (containerHeight / containerWidth < imageAspect) {
                a1 = 1;
                a2 = containerHeight / containerWidth / imageAspect;
            } else {
                a1 = (containerWidth / containerHeight) * imageAspect;
                a2 = 1;
            }

            const program = new ogl.Program(gl, {
                vertex,
                fragment,
                uniforms: {
                    uTime: { value: 0 },
                    tWater: { value: texture },
                    res: {
                        value: new ogl.Vec4(containerWidth, containerHeight, a1, a2),
                    },
                    img: { value: new ogl.Vec2(imgSize[0], imgSize[1]) },
                    tFlow: flowmap.uniform,
                },
            });

            const mesh = new ogl.Mesh(gl, { geometry, program });

            // Resize handler
            const resizeHandler = function () {
                resize();
            };
            $(window).on('resize.imageHover' + Date.now(), resizeHandler);

            // Initial resize
            resize();

            // Mouse/touch handlers - IMPORTANT: Listen on the entire container, not just canvas
            const isTouchCapable = "ontouchstart" in window;
            let lastTime;
            const lastMouse = new ogl.Vec2();

            function updateMouse(e) {
                e.preventDefault();

                if (e.changedTouches && e.changedTouches.length) {
                    e.x = e.changedTouches[0].pageX;
                    e.y = e.changedTouches[0].pageY;
                }
                if (e.x === undefined) {
                    e.x = e.pageX;
                    e.y = e.pageY;
                }

                // Get container offset for relative positioning
                const containerOffset = $container.offset();
                const relativeX = e.x - containerOffset.left;
                const relativeY = e.y - containerOffset.top;

                // Check if mouse is within container bounds
                const containerWidth = $container.width();
                const containerHeight = $container.height();

                if (relativeX >= 0 && relativeX <= containerWidth &&
                    relativeY >= 0 && relativeY <= containerHeight) {

                    // Get mouse value in 0 to 1 range, with y flipped, relative to container
                    mouse.set(
                        relativeX / containerWidth,
                        1.0 - relativeY / containerHeight
                    );

                    // Calculate velocity
                    if (!lastTime) {
                        lastTime = performance.now();
                        lastMouse.set(relativeX, relativeY);
                    }

                    const deltaX = relativeX - lastMouse.x;
                    const deltaY = relativeY - lastMouse.y;

                    lastMouse.set(relativeX, relativeY);

                    let time = performance.now();
                    let delta = Math.max(10.4, time - lastTime);
                    lastTime = time;

                    velocity.x = deltaX / delta;
                    velocity.y = deltaY / delta;
                    velocity.needsUpdate = true;
                } else {
                    // Mouse is outside container, reset
                    mouse.set(-1);
                    velocity.set(0);
                    velocity.needsUpdate = false;
                }
            }

            // KEY FIX: Listen to mouse events on the container AND its parent elements
            // This ensures the effect works even when other elements are on top
            const $eventTarget = $container.closest('.elementor-element');

            if (isTouchCapable) {
                $eventTarget.on("touchstart.imageHover", updateMouse);
                $eventTarget.on("touchmove.imageHover", updateMouse);
                // Also listen on document for better coverage
                $(document).on("touchmove.imageHover", function (e) {
                    const containerOffset = $container.offset();
                    const containerWidth = $container.width();
                    const containerHeight = $container.height();

                    if (e.changedTouches && e.changedTouches.length) {
                        const touch = e.changedTouches[0];
                        const relativeX = touch.pageX - containerOffset.left;
                        const relativeY = touch.pageY - containerOffset.top;

                        if (relativeX >= 0 && relativeX <= containerWidth &&
                            relativeY >= 0 && relativeY <= containerHeight) {
                            updateMouse(e);
                        }
                    }
                });
            } else {
                $eventTarget.on("mousemove.imageHover", updateMouse);
                // Also listen on document for better coverage
                $(document).on("mousemove.imageHover", function (e) {
                    const containerOffset = $container.offset();
                    const containerWidth = $container.width();
                    const containerHeight = $container.height();

                    const relativeX = e.pageX - containerOffset.left;
                    const relativeY = e.pageY - containerOffset.top;

                    if (relativeX >= 0 && relativeX <= containerWidth &&
                        relativeY >= 0 && relativeY <= containerHeight) {
                        updateMouse(e);
                    }
                });
            }

            // Animation loop
            function update(t) {
                requestAnimationFrame(update);

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

            // Store cleanup function
            $container.data('imageHoverCleanup', function () {
                // Remove event listeners
                if (isTouchCapable) {
                    $eventTarget.off("touchstart.imageHover");
                    $eventTarget.off("touchmove.imageHover");
                    $(document).off("touchmove.imageHover");
                } else {
                    $eventTarget.off("mousemove.imageHover");
                    $(document).off("mousemove.imageHover");
                }

                $(window).off('resize.imageHover' + Date.now());

                // Dispose of WebGL resources
                if (renderer && renderer.gl) {
                    renderer.gl.getExtension('WEBGL_lose_context').loseContext();
                }
            });

        } catch (error) {
            console.error('Error initializing image hover effect:', error);
        }
    }

    function setupGlobeDotsEffect($container) {
        let scene, camera, renderer, particleSystem, raycaster, mouse;
        let mouseX = 0, mouseY = 0;
        let time = 0;
        let originalPositions = [];
        let targetPositions = [];

        const PARTICLE_COUNT = 400;
        const SPHERE_RADIUS = 8;

        function init() {
            // Check if Three.js is available
            if (typeof THREE === 'undefined') {
                console.warn('Three.js library is not loaded. Globe dots effect requires Three.js.');
                return;
            }

            const container = document.querySelector('.globe-dots');
            if (!container) {
                console.error('Globe dots container not found');
                return;
            }

            // Get color from data attribute
            const dotsColorData = container.getAttribute('data-dots-color') || '42,81,230';
            const dotsRGB = dotsColorData.split(',').map(v => parseInt(v));

            try {
                // Get container dimensions
                const containerWidth = container.offsetWidth || container.clientWidth;
                const containerHeight = container.offsetHeight || container.clientHeight;

                // Scene setup
                scene = new THREE.Scene();
                camera = new THREE.PerspectiveCamera(75, containerWidth / containerHeight, 0.1, 1000);
                renderer = new THREE.WebGLRenderer({
                    antialias: true,
                    alpha: true // Enable alpha channel for transparency
                });
                renderer.setSize(containerWidth, containerHeight);
                renderer.setClearColor(0x000000, 0); // Set clear color with 0 alpha (transparent)

                // Style the canvas to be positioned absolutely and behind content
                renderer.domElement.style.position = 'absolute';
                renderer.domElement.style.top = '0';
                renderer.domElement.style.left = '0';
                renderer.domElement.style.width = '100%';
                renderer.domElement.style.height = '100%';
                renderer.domElement.style.zIndex = '-1';
                renderer.domElement.style.pointerEvents = 'none';

                // Append canvas to the container
                container.appendChild(renderer.domElement);

                // Raycaster for mouse interaction
                raycaster = new THREE.Raycaster();
                mouse = new THREE.Vector2();

                // Create particle geometry
                const geometry = new THREE.BufferGeometry();
                const positions = [];
                const colors = [];

                // Generate sphere points using Fibonacci spiral - HOLLOW SPHERE SURFACE
                for (let i = 0; i < PARTICLE_COUNT; i++) {
                    // Fibonacci spiral distribution on sphere surface only
                    const y = 1 - (i / (PARTICLE_COUNT - 1)) * 2; // y goes from 1 to -1
                    const radius = Math.sqrt(1 - y * y);
                    const theta = 2.399963229728653 * i; // golden angle

                    const x = Math.cos(theta) * radius * SPHERE_RADIUS;
                    const z = Math.sin(theta) * radius * SPHERE_RADIUS;

                    positions.push(x);
                    positions.push(y * SPHERE_RADIUS);
                    positions.push(z);

                    // Store original positions for mouse interaction
                    originalPositions.push(x, y * SPHERE_RADIUS, z);
                    targetPositions.push(x, y * SPHERE_RADIUS, z);

                    // Use custom color for dots
                    colors.push(dotsRGB[0] / 255, dotsRGB[1] / 255, dotsRGB[2] / 255);
                }

                geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
                geometry.computeBoundingSphere();
                geometry.setAttribute('color', new THREE.Float32BufferAttribute(colors, 3));

                // Create shiny circular dot texture with custom color
                const canvas = document.createElement('canvas');
                canvas.width = 64;
                canvas.height = 64;
                const ctx = canvas.getContext('2d');

                // Clear canvas
                ctx.clearRect(0, 0, 64, 64);

                // Draw solid circle with shine effect using custom color
                const gradient = ctx.createRadialGradient(20, 20, 0, 32, 32, 32);
                gradient.addColorStop(0, 'rgba(255, 255, 255, 1)'); // White shine
                gradient.addColorStop(0.3, `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 1)`); // Custom color core
                gradient.addColorStop(0.8, `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 1)`); // Custom color edge
                gradient.addColorStop(1, `rgba(${dotsRGB[0]}, ${dotsRGB[1]}, ${dotsRGB[2]}, 0.9)`); // Custom color with slight fade at very edge

                ctx.fillStyle = gradient;
                ctx.beginPath();
                ctx.arc(32, 32, 30, 0, Math.PI * 2);
                ctx.fill();

                const texture = new THREE.CanvasTexture(canvas);

                // Shiny point material with circular dots
                const material = new THREE.PointsMaterial({
                    size: 0.2, // Reduced by 20% from 0.25
                    map: texture,
                    vertexColors: false,
                    transparent: true,
                    opacity: 1.0,
                    sizeAttenuation: true,
                    blending: THREE.NormalBlending,
                    depthTest: true
                });

                particleSystem = new THREE.Points(geometry, material);
                scene.add(particleSystem);

                // Position camera
                camera.position.z = 20;

                // Create unique event identifier
                const instanceId = 'globe_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

                // Mouse interaction handler with container bounds checking
                function onMouseMove(event) {
                    // Get container offset for relative positioning
                    const containerRect = container.getBoundingClientRect();
                    const relativeX = event.clientX - containerRect.left;
                    const relativeY = event.clientY - containerRect.top;

                    // Check if mouse is within container bounds
                    if (relativeX >= 0 && relativeX <= containerWidth &&
                        relativeY >= 0 && relativeY <= containerHeight) {

                        mouseX = (relativeX - containerWidth / 2) / 200;
                        mouseY = (relativeY - containerHeight / 2) / 200;

                        // Update mouse coordinates for raycasting
                        mouse.x = (relativeX / containerWidth) * 2 - 1;
                        mouse.y = -(relativeY / containerHeight) * 2 + 1;
                    } else {
                        // Mouse is outside container, reset
                        mouse.x = -1;
                        mouse.y = -1;
                        mouseX = 0;
                        mouseY = 0;
                    }
                }

                // Window resize handler
                function onWindowResize() {
                    if (!camera || !renderer || !container) return;

                    const newWidth = container.offsetWidth || container.clientWidth;
                    const newHeight = container.offsetHeight || container.clientHeight;

                    camera.aspect = newWidth / newHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(newWidth, newHeight);
                }

                // Add event listeners
                document.addEventListener('mousemove', onMouseMove, false);
                window.addEventListener('resize', onWindowResize, false);

                // Store cleanup function for potential future use
                window.globeDotsCleanup = function() {
                    document.removeEventListener('mousemove', onMouseMove);
                    window.removeEventListener('resize', onWindowResize);

                    // Dispose of WebGL resources
                    if (renderer && renderer.domElement && renderer.domElement.parentNode) {
                        renderer.domElement.parentNode.removeChild(renderer.domElement);
                    }
                    if (renderer.dispose) {
                        renderer.dispose();
                    }
                };

            } catch (error) {
                console.error('Error initializing globe dots effect:', error);
            }
        }

        function animate() {
            if (!particleSystem || !renderer || !scene || !camera) return;

            requestAnimationFrame(animate);

            time += 0.01;

            try {
                // Global mouse movement interaction across entire globe
                const mouseInfluence = 2.5;
                const globalInfluence = 0.8;

                // Convert mouse position to 3D world coordinates
                const vector = new THREE.Vector3(mouse.x, mouse.y, 0.5);
                vector.unproject(camera);
                const dir = vector.sub(camera.position).normalize();
                const sphereDistance = 20;
                const mouseWorldPos = camera.position.clone().add(dir.multiplyScalar(sphereDistance));

                // Get current positions
                const positions = particleSystem.geometry.attributes.position.array;

                // Apply global mouse influence to all particles
                for (let i = 0; i < PARTICLE_COUNT; i++) {
                    const i3 = i * 3;

                    // Original particle position in world space
                    const originalWorldPos = new THREE.Vector3(
                        originalPositions[i3],
                        originalPositions[i3 + 1],
                        originalPositions[i3 + 2]
                    );
                    originalWorldPos.applyMatrix4(particleSystem.matrixWorld);

                    // Calculate distance from mouse to particle
                    const distanceToMouse = originalWorldPos.distanceTo(mouseWorldPos);

                    // Create a falloff effect based on distance
                    const maxDistance = SPHERE_RADIUS * 3;
                    const normalizedDistance = Math.min(distanceToMouse / maxDistance, 1);
                    const influence = Math.pow(1 - normalizedDistance, 2) * globalInfluence;

                    // Direction from original position toward mouse
                    const moveDir = new THREE.Vector3();
                    moveDir.subVectors(mouseWorldPos, originalWorldPos).normalize();

                    // Transform direction to local space
                    const localDir = moveDir.transformDirection(particleSystem.matrixWorld.clone().invert());

                    // Apply movement with falloff
                    const moveAmount = influence * mouseInfluence;
                    targetPositions[i3] = originalPositions[i3] + localDir.x * moveAmount;
                    targetPositions[i3 + 1] = originalPositions[i3 + 1] + localDir.y * moveAmount;
                    targetPositions[i3 + 2] = originalPositions[i3 + 2] + localDir.z * moveAmount;

                    // Add some additional movement based on mouse velocity for more dynamic effect
                    const velocityEffect = 0.3;
                    targetPositions[i3] += mouseX * velocityEffect * influence;
                    targetPositions[i3 + 1] += mouseY * velocityEffect * influence;
                }

                // Very responsive interpolation for immediate mouse following
                for (let i = 0; i < PARTICLE_COUNT * 3; i++) {
                    positions[i] += (targetPositions[i] - positions[i]) * 0.25;
                }

                particleSystem.geometry.attributes.position.needsUpdate = true;

                // Slower rotation so mouse interaction is more visible
                particleSystem.rotation.x += 0.001;
                particleSystem.rotation.y += 0.002;

                // Mouse interaction
                particleSystem.rotation.x += mouseY * 0.001;
                particleSystem.rotation.y += mouseX * 0.001;

                // Enhanced shine effect with size variation
                const baseSize = 0.16; // Reduced by 20% from 0.2
                const shineVariation = 0.04 + Math.sin(time * 3) * 0.024; // Reduced by 20%
                particleSystem.material.size = baseSize + shineVariation;

                // Camera orbit
                camera.position.x = Math.sin(time * 0.3) * 2;
                camera.position.y = Math.cos(time * 0.2) * 1.5;
                camera.lookAt(0, 0, 0);

                renderer.render(scene, camera);

            } catch (error) {
                console.error('Globe animation error:', error);
            }
        }

        // Wait for Three.js to load, then initialize
        function checkThreeJS() {
            if (typeof THREE !== 'undefined') {
                init();
                animate();
            } else {
                setTimeout(checkThreeJS, 100);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', checkThreeJS);
        } else {
            checkThreeJS();
        }
    }
    function setupBrushEffect($container) {
        // Target the section container that holds all content (like .content-with-mask in the sample)
        const sectionContainer = $container.closest('.elementor-element').parent()[0];

        if (!sectionContainer) {
            return;
        }

        // Fixed constants as per your requirements - not dynamic
        const BRUSH_RADIUS = 80, BRUSH_FEATHER = 1, LERP = 0.22, DPR = window.devicePixelRatio || 1;

        const canvas = $container.find('.maskCanvas')[0];
        if (!canvas) {
            return;
        }

        const ctx = canvas.getContext('2d');

        let target = { x: -1000, y: -1000 }, last = { x: -1000, y: -1000 }, pointerActive = false;
        let overlayReady = false;
        const overlayImg = new Image();
        overlayImg.src = canvas.dataset.overlay || "";

        overlayImg.onload = () => {
            overlayReady = true;
            resize();
        };

        function resize() {
            // Use the section container dimensions (like .content-with-mask in sample)
            const w = Math.max(1, sectionContainer.clientWidth);
            const h = Math.max(1, sectionContainer.clientHeight);

            canvas.style.width = w + 'px';
            canvas.style.height = h + 'px';
            canvas.width = Math.floor(w * DPR);
            canvas.height = Math.floor(h * DPR);
            ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
            if (overlayReady) {
                ctx.globalCompositeOperation = 'source-over';
                ctx.drawImage(overlayImg, 0, 0, w, h);
            }
        }

        resize();
        window.addEventListener('resize', resize);

        function getLocalPos(x, y) {
            // Use section container for positioning calculations (like sample)
            const r = sectionContainer.getBoundingClientRect();
            return { x: Math.max(0, Math.min(r.width, x - r.left)), y: Math.max(0, Math.min(r.height, y - r.top)) };
        }

        function drawBrush(x, y, radius = BRUSH_RADIUS) {
            ctx.save();
            ctx.globalCompositeOperation = 'destination-out';
            const g = ctx.createRadialGradient(x, y, 0, x, y, radius);
            g.addColorStop(0, 'rgba(0,0,0,1)');
            g.addColorStop(Math.max(0.2, 0.6 / BRUSH_FEATHER), 'rgba(0,0,0,0.7)');
            g.addColorStop(1, 'rgba(0,0,0,0)');
            ctx.filter = 'blur(2px)';
            ctx.fillStyle = g;
            ctx.beginPath();
            ctx.arc(x, y, radius, 0, Math.PI * 2);
            ctx.fill();
            ctx.filter = 'none';
            ctx.restore();
        }

        (function animate() {
            requestAnimationFrame(animate);
            if (pointerActive) {
                last.x += (target.x - last.x) * LERP;
                last.y += (target.y - last.y) * LERP;
                const dx = target.x - last.x, dy = target.y - last.y, dist = Math.hypot(dx, dy);
                if (dist > 0.5) {
                    const step = Math.max(1, Math.floor(dist / (BRUSH_RADIUS * 0.30)));
                    for (let i = 0; i < step; i++) {
                        const t = (i + 1) / step, ix = last.x + dx * t, iy = last.y + dy * t;
                        drawBrush(ix, iy, BRUSH_RADIUS);
                        drawBrush(ix + (Math.random() - 0.5) * 6, iy + (Math.random() - 0.5) * 6, BRUSH_RADIUS * 0.5);
                    }
                    last.x = target.x; last.y = target.y;
                } else drawBrush(last.x, last.y, BRUSH_RADIUS);
            }
        })();

        // Mouse + Touch - Listen on the entire section container (like sample does)
        function start(x, y) {
            const p = getLocalPos(x, y);
            target = { ...p };
            last = { ...p };
            pointerActive = true;
        }

        function move(x, y) {
            const p = getLocalPos(x, y);
            target = { ...p };
            pointerActive = true;
        }

        function stop() {
            pointerActive = false;
        }

        // Listen on the section container (equivalent to .content-with-mask in sample)
        sectionContainer.addEventListener('mousemove', e => move(e.clientX, e.clientY));
        sectionContainer.addEventListener('mouseenter', e => start(e.clientX, e.clientY));
        sectionContainer.addEventListener('mouseleave', stop);

        // Touch events
        sectionContainer.addEventListener('touchstart', e => start(e.touches[0].clientX, e.touches[0].clientY), { passive: true });
        sectionContainer.addEventListener('touchmove', e => move(e.touches[0].clientX, e.touches[0].clientY), { passive: false });
        sectionContainer.addEventListener('touchend', stop);
    }

    function setupVideoCanvasEffect($container) {
        const container = $container[0];
        if (!container) return;

        const canvas = container.querySelector('.video-canvas');
        const loadingIndicator = container.querySelector('.video-loading');

        if (!canvas) {
            console.warn('Video canvas element not found');
            return;
        }

        const ctx = canvas.getContext('2d', { alpha: true });

        // FIXED: Get settings from the video canvas container, not the parent
        const canvasElement = container; // Use the container itself
        const frameCount = parseInt(canvasElement.getAttribute('data-frame-count') || '100');
        const framePrefix = canvasElement.getAttribute('data-frame-prefix') || 'frame_';
        const frameExtension = canvasElement.getAttribute('data-frame-extension') || 'jpg';
        const framesFolder = canvasElement.getAttribute('data-frames-folder') || '';
        const backgroundColor = canvasElement.getAttribute('data-bg-color') || '#F0F2EF';
        const scrollSpeed = parseFloat(canvasElement.getAttribute('data-scroll-speed') || '1000');
        const animationEase = parseFloat(canvasElement.getAttribute('data-animation-ease') || '0.3');
        const imageScale = parseFloat(canvasElement.getAttribute('data-image-scale') || '1.02');
        const canvasWidth = parseInt(canvasElement.getAttribute('data-canvas-width') || '630');
        const canvasHeight = parseInt(canvasElement.getAttribute('data-canvas-height') || '730');
        const showLoading = canvasElement.getAttribute('data-show-loading') === 'true';

        // Validate required settings
        if (!framesFolder) {
            console.error('Video Canvas: Frames folder URL is required');
            if (loadingIndicator) loadingIndicator.textContent = 'Error: No frames folder specified';
            return;
        }

        let displayWidth = canvasWidth;
        let displayHeight = canvasHeight;

        // Canvas resize function
        function resizeCanvas() {
            const dpr = Math.min(window.devicePixelRatio || 1, 2);

            canvas.width = Math.floor(displayWidth * dpr);
            canvas.height = Math.floor(displayHeight * dpr);

            canvas.style.width = displayWidth + "px";
            canvas.style.height = displayHeight + "px";

            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        }
        resizeCanvas();

        // Image path generator
        const imgPath = i => {
            // Clean up folder URL (remove trailing slash if present)
            const cleanFolderUrl = framesFolder.replace(/\/$/, '');
            const paddedNumber = String(i).padStart(4, '0');
            return `${cleanFolderUrl}/${framePrefix}${paddedNumber}.${frameExtension}`;
        };

        // Image loading
        const images = new Array(frameCount + 1);
        let loadedImages = 0;
        let isLoading = true;

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
            for (let i = 1; i <= frameCount; i++) {
                const img = new Image();

                img.onload = () => {
                    loadedImages++;
                    updateLoadingIndicator();
                };

                img.onerror = () => {
                    console.error(`Failed to load video frame: ${imgPath(i)}`);
                    loadedImages++; // Still count as loaded to prevent infinite loading
                    updateLoadingIndicator();
                };

                img.src = imgPath(i);
                images[i] = img;
            }
        }

        // Start preloading
        preloadImages();

        // Frame rendering function
        function drawFrame(index) {
            if (index < 1 || index > frameCount) return;

            const img = images[index];
            if (!img || !img.complete) return;

            try {
                // Clear canvas
                ctx.clearRect(0, 0, displayWidth, displayHeight);

                // Fill background
                ctx.fillStyle = backgroundColor;
                ctx.fillRect(0, 0, displayWidth, displayHeight);

                // Calculate image scaling to maintain aspect ratio
                const scale = imageScale;
                const imgAspect = img.naturalWidth / img.naturalHeight;
                const canvasAspect = displayWidth / displayHeight;

                let drawW, drawH;
                if (imgAspect > canvasAspect) {
                    drawH = displayHeight * scale;
                    drawW = drawH * imgAspect;
                } else {
                    drawW = displayWidth * scale;
                    drawH = drawW / imgAspect;
                }

                // Center the image
                const drawX = (displayWidth - drawW) / 2;
                const drawY = (displayHeight - drawH) / 2;

                ctx.drawImage(img, drawX, drawY, drawW, drawH);
            } catch (error) {
                console.error(`Error drawing video frame ${index}:`, error);
            }
        }

        // Scroll synchronization
        let targetFrame = 1;
        let currentFrame = 1;

        function updateFrameFromScroll() {
            const pixelsPerFrame = scrollSpeed / (frameCount - 1);
            const scrollBasedFrame = Math.floor(window.scrollY / pixelsPerFrame) + 1;
            targetFrame = Math.max(1, Math.min(frameCount, scrollBasedFrame));
        }

        // Create unique event identifier for this instance
        const instanceId = 'video_canvas_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

        // Scroll event handler
        function handleScroll() {
            if (!isLoading) {
                updateFrameFromScroll();
            }
        }

        // Add scroll event listener
        window.addEventListener('scroll', handleScroll, { passive: true });

        // Animation loop
        function animate() {
            if (!isLoading) {
                // Smooth frame interpolation
                currentFrame += (targetFrame - currentFrame) * animationEase;
                const frameToShow = Math.round(currentFrame);
                drawFrame(frameToShow);
            }
            requestAnimationFrame(animate);
        }

        // Start animation when ready
        function startAnimation() {
            if (images[1] && images[1].complete && loadedImages >= Math.min(10, frameCount)) {
                updateFrameFromScroll();
                animate();
            } else {
                setTimeout(startAnimation, 100);
            }
        }

        // Resize handler
        function handleResize() {
            // Update canvas dimensions from container
            const containerWidth = $container.width();
            const containerHeight = $container.height();

            if (containerWidth > 0 && containerHeight > 0) {
                displayWidth = containerWidth;
                displayHeight = containerHeight;
                resizeCanvas();
                updateFrameFromScroll();
            }
        }

        window.addEventListener('resize', handleResize);

        // Initialize
        startAnimation();

        // Error handler
        window.addEventListener('error', e => {
            console.error('Video Canvas error:', e.message);
        });

        // Store cleanup function
        $container.data('videoCanvasCleanup', function() {
            // Remove event listeners
            window.removeEventListener('scroll', handleScroll);
            window.removeEventListener('resize', handleResize);

            // Clear images array
            for (let i = 1; i <= frameCount; i++) {
                if (images[i]) {
                    images[i].src = '';
                    images[i] = null;
                }
            }
        });
    }
    function elementcamp_canvas($scope, $) {

        // Process each canvas widget separately
        $('.tcgelements-canvas', $scope).each(function (canvasIndex) {
            const $currentCanvas = $(this);

            // Handle Sacred Geometry effect for this specific canvas
            const $sacredGeometryContainer = $currentCanvas.find('.sacred-geometry-container');
            if ($sacredGeometryContainer.length > 0) {
                setupSacredGeometryEffect($sacredGeometryContainer);
            }

            // Handle circles animation for this specific canvas
            const $circlesContainer = $currentCanvas.find('.circles');
            if ($circlesContainer.length > 0) {
                // Setup circle animation for this specific container
                const circleCleanup = setupCircleAnimation($circlesContainer);

                // Store cleanup function for potential future use
                $circlesContainer.data('circleCleanup', circleCleanup);
            }

            // Handle image hover effect for this specific canvas
            const $imageHoverContainer = $currentCanvas.find('.image-hover-container');
            if ($imageHoverContainer.length > 0) {
                const imageUrl = $imageHoverContainer.data('image');
                if (imageUrl) {
                    setupImageHoverEffect($imageHoverContainer, imageUrl);
                }
            }

            // Handle globe dots effect for this specific canvas
            const $globeDotsContainer = $currentCanvas.find('.globe-dots');
            if ($globeDotsContainer.length > 0) {
                setupGlobeDotsEffect($globeDotsContainer);
            }

            // Handle crystal container for this specific canvas
            const $crystalContainer = $currentCanvas.find('.crystal-container');
            if ($crystalContainer.length > 0) {

                $crystalContainer.each(function (crystalIndex) {
                    const $container = $(this);
                    const containerElement = this;

                    // Create unique identifier for this instance
                    const instanceId = 'crystal_instance_' + canvasIndex + '_' + crystalIndex;

                    // Set up scene for this instance
                    const scene = new THREE.Scene();

                    // Set up camera
                    const camera = new THREE.PerspectiveCamera(
                        75,
                        $container.width() / $container.height(),
                        0.1,
                        1000
                    );
                    camera.position.z = 5;

                    // Set up renderer with physically correct lighting
                    const renderer = new THREE.WebGLRenderer({
                        antialias: true,
                        alpha: true
                    });
                    renderer.setSize($container.width(), $container.height());
                    renderer.setClearColor(0x000000, 0); // Set renderer background to transparent
                    renderer.physicallyCorrectLights = true;
                    renderer.toneMapping = THREE.ACESFilmicToneMapping;
                    renderer.toneMappingExposure = 1.5;
                    renderer.shadowMap.enabled = true;
                    renderer.shadowMap.type = THREE.PCFSoftShadowMap;

                    // Append renderer to this specific container
                    $container.append(renderer.domElement);

                    // Add lighting
                    const ambientLight = new THREE.AmbientLight(0x404040, 1.2);
                    scene.add(ambientLight);

                    // Add directional lights for highlights and edge definition
                    const directionalLight1 = new THREE.DirectionalLight(0xffffff, 2);
                    directionalLight1.position.set(5, 5, 5);
                    directionalLight1.castShadow = true;
                    directionalLight1.shadow.mapSize.width = 1024;
                    directionalLight1.shadow.mapSize.height = 1024;
                    scene.add(directionalLight1);

                    const directionalLight2 = new THREE.DirectionalLight(0x6666ff, 1.2);
                    directionalLight2.position.set(-5, 5, -5);
                    scene.add(directionalLight2);

                    // Add rim light to enhance edges
                    const rimLight = new THREE.DirectionalLight(0xffffff, 1);
                    rimLight.position.set(0, 0, -10);
                    scene.add(rimLight);

                    // Add point light for extra highlights
                    const pointLight = new THREE.PointLight(0xffffcc, 1, 10);
                    pointLight.position.set(0, 3, 2);
                    scene.add(pointLight);

                    // Create a group to hold our objects
                    const group = new THREE.Group();
                    scene.add(group);

                    // Create gradient texture for cube faces
                    function createGradientTexture() {
                        const canvas = document.createElement('canvas');
                        canvas.width = 512;
                        canvas.height = 512;
                        const context = canvas.getContext('2d');

                        // Create gradient with defined color transitions
                        const gradient = context.createLinearGradient(0, 0, 512, 0);
                        gradient.addColorStop(0, '#0044FF');   // Deep blue
                        gradient.addColorStop(0.4, '#ffffff');  // White center
                        gradient.addColorStop(0.6, '#ffffff');  // White center extended
                        gradient.addColorStop(1, '#0044FF');   // Deep blue

                        context.fillStyle = gradient;
                        context.fillRect(0, 0, 512, 512);

                        const texture = new THREE.CanvasTexture(canvas);
                        texture.needsUpdate = true;
                        return texture;
                    }

                    // Create crystal
                    function createCrystal(x, y, z, size) {
                        // Create crystal geometry - a custom diamond/gemstone shape
                        function createGemGeometry(size) {
                            // Custom geometry for our crystal
                            const geometry = new THREE.BufferGeometry();

                            // Define vertices for a diamond-like shape
                            const vertices = new Float32Array([
                                // Top point
                                0, size * 1.5, 0,

                                // Middle points (hexagonal)
                                size * 0.7, size * 0.3, size * 0.7,
                                size * 0.7, size * 0.3, -size * 0.7,
                                -size * 0.7, size * 0.3, -size * 0.7,
                                -size * 0.7, size * 0.3, size * 0.7,
                                0, size * 0.3, size,
                                0, size * 0.3, -size,

                                // Bottom middle points (hexagonal but smaller)
                                size * 0.5, -size * 0.3, size * 0.5,
                                size * 0.5, -size * 0.3, -size * 0.5,
                                -size * 0.5, -size * 0.3, -size * 0.5,
                                -size * 0.5, -size * 0.3, size * 0.5,
                                0, -size * 0.3, size * 0.7,
                                0, -size * 0.3, -size * 0.7,

                                // Bottom point
                                0, -size, 0
                            ]);

                            geometry.setAttribute('position', new THREE.BufferAttribute(vertices, 3));

                            // Define faces (triangles)
                            const indices = [];

                            // Top faces (connect top point to middle ring)
                            indices.push(0, 1, 2);
                            indices.push(0, 2, 3);
                            indices.push(0, 3, 4);
                            indices.push(0, 4, 1);
                            indices.push(0, 5, 1);
                            indices.push(0, 2, 6);
                            indices.push(0, 6, 3);
                            indices.push(0, 4, 5);

                            // Middle to bottom-middle
                            indices.push(1, 5, 7);
                            indices.push(5, 11, 7);
                            indices.push(5, 4, 11);
                            indices.push(4, 10, 11);
                            indices.push(4, 3, 10);
                            indices.push(3, 9, 10);
                            indices.push(3, 6, 9);
                            indices.push(6, 8, 9);
                            indices.push(6, 2, 8);
                            indices.push(2, 1, 8);
                            indices.push(1, 7, 8);

                            // Bottom faces
                            indices.push(13, 7, 8);
                            indices.push(13, 8, 9);
                            indices.push(13, 9, 10);
                            indices.push(13, 10, 11);
                            indices.push(13, 11, 12);
                            indices.push(13, 12, 7);

                            geometry.setIndex(indices);

                            // Compute vertex normals for proper lighting
                            geometry.computeVertexNormals();

                            return geometry;
                        }

                        // Create crystal material - more translucent and reflective than the cube
                        const crystalMaterial = new THREE.MeshPhysicalMaterial({
                            color: 0x6e9ee9,      // Blue-ish crystal color
                            metalness: 0.1,
                            roughness: 0.05,
                            clearcoat: 1.0,
                            clearcoatRoughness: 0.1,
                            reflectivity: 1.0,
                            transparent: true,
                            opacity: 0.8,
                            transmission: 0.5,    // Makes it more glass-like
                            side: THREE.DoubleSide // Render both sides for transparency
                        });

                        // Use a single material for the entire crystal
                        const crystal = new THREE.Mesh(createGemGeometry(size), crystalMaterial);
                        crystal.position.set(x, y, z);
                        crystal.castShadow = true;
                        crystal.receiveShadow = true;

                        // Add a subtle edge highlight
                        const edges = new THREE.EdgesGeometry(createGemGeometry(size));
                        const edgeMaterial = new THREE.LineBasicMaterial({
                            color: 0xffffff,
                            transparent: true,
                            opacity: 0.2
                        });
                        const wireframe = new THREE.LineSegments(edges, edgeMaterial);
                        crystal.add(wireframe);

                        return crystal;
                    }

                    // Create a single crystal (larger size for better visibility)
                    const cube = createCrystal(0, 0, 0, 2);

                    // Add cube to group
                    group.add(cube);

                    // Scroll/rotation tracking variables for this instance
                    let lastScrollTop = 0;
                    let rotationSpeed = { x: 0, y: 0 };

                    // Create scroll handler for this specific instance
                    const scrollHandler = function (event) {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        const scrollDelta = scrollTop - lastScrollTop;

                        // Convert scroll movement to rotation - significantly reduced for much slower rotation
                        rotationSpeed.y += scrollDelta * 0.0004; // Further reduced from 0.001 to 0.0004

                        // Store current scroll position for next event
                        lastScrollTop = scrollTop;
                    };

                    // Add scroll event listener
                    $(window).on('scroll.' + instanceId, scrollHandler);

                    // Create wheel handler for this specific instance
                    const wheelHandler = function (event) {
                        // Only use wheel for zooming with shift key pressed
                        if (event.shiftKey) {
                            event.preventDefault();
                            // Zoom with shift + mouse wheel
                            camera.position.z += event.originalEvent.deltaY * 0.01;

                            // Limit zoom range
                            camera.position.z = Math.max(2, Math.min(10, camera.position.z));
                        }
                    };

                    // Add wheel event listener
                    $(window).on('wheel.' + instanceId, wheelHandler);

                    // Animation loop - only for applying momentum and rendering
                    function animate() {
                        requestAnimationFrame(animate);

                        // Apply momentum-based rotation from scroll
                        group.rotation.y += rotationSpeed.y;

                        // Dampen rotation speed over time - slower damping for smoother motion
                        rotationSpeed.y *= 0.98; // Changed from 0.95 for smoother slowing

                        // Slightly rotate point light to create dynamic reflections
                        const time = Date.now() * 0.001;
                        pointLight.position.x = Math.sin(time * 0.2) * 2; // Slower light movement
                        pointLight.position.z = Math.cos(time * 0.2) * 2; // Slower light movement

                        renderer.render(scene, camera);
                    }

                    // Handle window resize for this instance
                    const resizeHandler = function () {
                        camera.aspect = $container.width() / $container.height();
                        camera.updateProjectionMatrix();
                        renderer.setSize($container.width(), $container.height());
                    };

                    $(window).on('resize.' + instanceId, resizeHandler);

                    // Slow auto-rotation when no scrolling happens
                    const autoRotationInterval = setInterval(() => {
                        if (Math.abs(rotationSpeed.y) < 0.001) {
                            group.rotation.y += 0.0003; // Further reduced from 0.001 to 0.0003
                        }
                    }, 30);

                    // Initialize scene with a good starting angle
                    group.rotation.x = Math.PI / 6;
                    group.rotation.y = Math.PI / 8;

                    // Start animation loop
                    animate();

                    // Store cleanup function for potential future use
                    $container.data('crystalCleanup', function () {
                        $(window).off('scroll.' + instanceId);
                        $(window).off('wheel.' + instanceId);
                        $(window).off('resize.' + instanceId);
                        clearInterval(autoRotationInterval);
                        // Dispose of Three.js resources
                        if (scene.dispose) scene.dispose();
                        if (renderer.dispose) renderer.dispose();
                    });
                });
            }

            const $brushContainer = $currentCanvas.find('.brush-container');
            if ($brushContainer.length > 0) {
                setupBrushEffect($brushContainer);
            }
            const $videoContainer = $currentCanvas.find('.video-canvas-container');
            if ($videoContainer.length > 0) {
                setupVideoCanvasEffect($videoContainer);
            }
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
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-canvas.default', function() {
            setTimeout(updateCanvasSquareAspect, 200);
        });
    });

})(jQuery);