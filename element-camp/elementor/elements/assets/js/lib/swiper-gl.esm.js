const defaultVertex = `
attribute vec2 uv;
attribute vec3 position;
uniform mat4 modelViewMatrix;
uniform mat4 projectionMatrix;
varying vec2 vUv;

void main() {
  vUv = uv;
  gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
}
`;

const getRandomShader = (arr, opts) => {
    const { shaderPerSlide, swiper } = opts;
    const index = swiper.params.loop ? swiper.realIndex : swiper.activeIndex;
    let allShaders = shaders;
    if (Array.isArray(arr) && arr.length) {
        if (shaderPerSlide) {
            if (typeof arr[index] === 'undefined') return shaders[arr[0]];
            return shaders[arr[index]];
        }
        allShaders = {};
        Object.keys(shaders).forEach((key) => {
            if (arr.includes(key)) allShaders[key] = shaders[key];
        });
    }
    const keyIndex = Math.floor(Math.random() * Object.keys(allShaders).length);
    return allShaders[Object.keys(allShaders)[keyIndex]];
};

class GL {
    constructor(opts) {
        const shader =
            opts.shader === 'random' || Array.isArray(opts.shader)
                ? getRandomShader(opts.shader, opts)
                : shaders[opts.shader];
        this.shader = shader;
        this.displacement = opts.displacementMap || displacement;
        this.scene = new Transform();
        this.swiper = opts.swiper;
        this.vertex = shader.vertex || defaultVertex;
        this.fragment = shader.fragment;
        this.uniforms = shader.uniforms || {};
        this.renderer = new Renderer({ dpr: 2, webgl: 2, alpha: true });
        this.gl = this.renderer.gl;
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.renderer.setSize(this.width, this.height);
        this.gl.clearColor(1, 1, 1, 0);
        this.gl.clear(this.gl.COLOR_BUFFER_BIT);
        this.opts = opts;

        this.container = this.swiper.el;
        this.displacementTexture = null;

        this.width = this.swiper.width;
        this.height = this.swiper.height;
        if (this.swiper.isElement) {
            this.gl.canvas.setAttribute('slot', 'container-start');
        }
        this.container.prepend(this.gl.canvas);

        this.camera = new Camera(this.gl, { fov: 45 });
        this.camera.perspective({
            aspect: this.gl.canvas.width / this.gl.canvas.height,
        });

        this.camera.position.set(0, 0, 2);
        this.time = 0;
        this.current = 0;

        this.init(() => {
            this.addObjects();
            this.resize();
            this.render();
        });
    }

    animateUniform(uniform, targetValue, cb) {
        const startPosition = uniform.value;
        let startTime = null;
        let time;

        window.cancelAnimationFrame(this.animateUniformFrame);

        const dir = targetValue > uniform.value ? 'next' : 'prev';

        const isOutOfBound = (current, target) =>
            (dir === 'next' && current >= target) ||
            (dir === 'prev' && current <= target);

        const animate = () => {
            if (this.destroyed) return;
            time = new Date().getTime();
            if (startTime === null) {
                startTime = time;
            }

            const progress = Math.max(
                Math.min((time - startTime) / this.swiper.params.speed, 1),
                0,
            );
            const easeProgress = 0.5 - Math.cos(progress * Math.PI) / 2;
            let currentPosition =
                startPosition + easeProgress * (targetValue - startPosition);

            if (isOutOfBound(currentPosition, targetValue)) {
                currentPosition = targetValue;
            }
            uniform.value = currentPosition;
            if (isOutOfBound(currentPosition, targetValue)) {
                cancelAnimationFrame(this.animateUniformFrame);
                if (cb) cb();
                return;
            }
            this.animateUniformFrame = requestAnimationFrame(animate);
        };
        animate();
    }

    loadTextures() {
        const promises = [];
        const that = this;
        this.images = [];
        this.textures = [];

        this.container.querySelectorAll('.swiper-gl-image').forEach((img) => {
            this.images.push(img.src);
        });

        this.images.forEach((url, i) => {
            const promise = new Promise((resolve) => {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                const texture = new Texture(this.gl);
                img.onload = () => {
                    texture.image = img;
                    that.textures[i] = texture;
                    resolve();
                };
                img.src = url;
            });
            promises.push(promise);
        });
        promises.push(
            new Promise((resolve) => {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                const texture = new Texture(this.gl);
                img.onload = () => {
                    texture.image = img;
                    that.displacementTexture = texture;
                    resolve();
                };
                img.src = displacement;
            }),
        );

        return Promise.all(promises);
    }

    init(cb) {
        this.loadTextures().then(() => {
            this.initialized = true;
            if (this.onInit) this.onInit();
            cb();
        });
    }

    resize() {
        if (!this.initialized || this.destroyed) return;
        const { width, height } = this.swiper;
        this.width = width;
        this.height = height;
        this.renderer.setSize(width, height);
        const dist = this.camera.position.z;

        this.camera.perspective({
            aspect: width / height,
            fov: 2 * (180 / Math.PI) * Math.atan(1 / (2 * dist)),
        });
        if (!this.textures[0].image) return;
        const imageAspect =
            this.textures[0].image.height / this.textures[0].image.width;
        let a1;
        let a2;
        if (height / width > imageAspect) {
            a1 = (width / height) * imageAspect;
            a2 = 1;
        } else {
            a1 = 1;
            a2 = height / width / imageAspect;
        }

        this.material.uniforms.resolution.value.x = width;
        this.material.uniforms.resolution.value.y = height;
        this.material.uniforms.resolution.value.z = a1;
        this.material.uniforms.resolution.value.w = a2;

        if (this.shader.vertex && this.vertexMaterial) {
            this.vertexMaterial.uniforms.resolution.value.x = width;
            this.vertexMaterial.uniforms.resolution.value.y = height;
            this.vertexMaterial.uniforms.resolution.value.z = a1;
            this.vertexMaterial.uniforms.resolution.value.w = a2;
        }

        if (this.shader.vertex) {
            this.nextMesh.scale.set(this.camera.aspect / 2, 1 / 2, 1 / 2);
            this.currentMesh.scale.set(this.camera.aspect / 2, 1 / 2, 1 / 2);
        } else {
            this.plane.scale.x = this.camera.aspect;
            this.plane.scale.y = 1;
        }
    }

    createMaterial() {
        return new Program(this.gl, {
            extensions: {
                derivatives: '#extension GL_OES_standard_derivatives : enable',
            },
            // side: DoubleSide,
            uniforms: {
                time: { type: 'f', value: 0 },
                progress: { type: 'f', value: 0 },
                intensity: { type: 'f', value: 0 },
                width: { type: 'f', value: 0 },
                radius: { type: 'f', value: 0 },
                size: { type: 'f', value: 0 },
                texture1: { type: 'f', value: this.textures[0] },
                texture2: { type: 'f', value: this.textures[1] },

                displacement: {
                    type: 'f',
                    value: this.displacementTexture,
                },
                resolution: { type: 'v4', value: new Vec4() },
            },
            vertex: this.shader.vertex || defaultVertex,
            fragment: this.shader.fragment,
            ...(this.shader.vertex
                ? {
                    transparent: true,
                    depthWrite: false,
                }
                : {}),
        });
    }

    addObjects() {
        this.scene.children.forEach((child) => {
            this.scene.removeChild(child);
        });
        this.scene.children.forEach((child) => {
            this.scene.removeChild(child);
        });
        this.material = this.createMaterial();

        if (this.shader.vertex) {
            const geometry = getGeometry(
                this.gl,
                this.shader.detail,
                this.shader.offsetTop,
            );
            const texture = this.textures[1];
            this.vertexMaterial = this.createMaterial();
            this.vertexMaterial.uniforms.texture1.value = texture;
            this.currentMesh = new Mesh(this.gl, {
                geometry,
                program: this.material,
            });
            this.nextMesh = new Mesh(this.gl, {
                geometry,
                program: this.vertexMaterial,
            });
            this.nextMesh.position.z = -0.0001;
            this.currentMesh.setParent(this.scene);
            this.nextMesh.setParent(this.scene);
        } else {
            const geometry = new Plane(this.gl, {
                width: 1,
                height: 1,
                widthSegments: 2,
                heightSegments: 2,
            });
            this.plane = new Mesh(this.gl, { geometry, program: this.material });
            this.plane.setParent(this.scene);
        }
    }

    replaceShader(newShaderName) {
        let fromTexture;
        let newTexture;
        if (this.shader.vertex) {
            fromTexture = this.material.uniforms.texture1.value;
            newTexture = this.vertexMaterial.uniforms.texture1.value;
        } else {
            fromTexture = this.material.uniforms.texture1.value;
            newTexture = this.material.uniforms.texture2.value;
        }
        const shader =
            newShaderName === 'random' || Array.isArray(newShaderName)
                ? getRandomShader(newShaderName, this.opts)
                : shaders[newShaderName];
        const { fragment, uniforms, vertex } = shader;
        this.shader = shader;
        this.vertex = vertex || defaultVertex;
        this.fragment = fragment || ``;
        this.uniforms = uniforms || {};
        this.addObjects();
        if (this.shader.vertex) {
            this.material.uniforms.texture1.value = newTexture;
            this.vertexMaterial.uniforms.texture1.value = newTexture;
        } else {
            this.material.uniforms.texture1.value = fromTexture;
            this.material.uniforms.texture2.value = newTexture;
            this.material.uniforms.progress.value = 1;
        }

        this.resize();
        this.swiper.params.gl.shader = newShaderName;
    }

    replaceRandomShader() {
        const shader = getRandomShader(this.opts.shader, this.opts);
        const { fragment, uniforms, vertex } = shader;
        this.shader = shader;
        this.fragment = fragment || ``;
        this.uniforms = uniforms || {};
        this.vertex = vertex || defaultVertex;
        this.addObjects();
        this.resize();
    }

    setProgress(
        fromIndex,
        toIndex,
        progress,
        needsTransitionDuration,
        needShaderReplace,
    ) {
        if (this.destroyed || this.swiper.glDestroyed) return;
        if (!this.initialized) {
            this.onInit = () => {
                requestAnimationFrame(() => {
                    this.setProgress(
                        fromIndex,
                        toIndex,
                        progress,
                        needsTransitionDuration,
                    );
                });
            };
            return;
        }
        if (
            this.swiper.params.loop &&
            this.swiper.slides[fromIndex] &&
            this.swiper.slides[toIndex]
        ) {
            fromIndex = parseInt(
                this.swiper.slides[fromIndex].getAttribute('data-swiper-slide-index'),
                10,
            );
            toIndex = parseInt(
                this.swiper.slides[toIndex].getAttribute('data-swiper-slide-index'),
                10,
            );
        }
        const newTexture = this.textures[toIndex];
        const fromTexture = this.textures[fromIndex];

        this.material.uniforms.texture1.value = fromTexture;
        if (!this.shader.vertex) {
            this.material.uniforms.texture2.value = newTexture;
        } else {
            this.vertexMaterial.uniforms.texture1.value = newTexture;
        }
        if (needShaderReplace) {
            if (this.preventShaderReplace) {
                this.material.uniforms.progress.value = Math.abs(progress);
                return;
            }
            this.preventShaderReplace = true;
            requestAnimationFrame(() => {
                this.preventShaderReplace = false;
            });

            if (
                this.swiper.params.gl.shader === 'random' ||
                Array.isArray(this.swiper.params.gl.shader)
            ) {
                this.replaceRandomShader();
                this.material.uniforms.texture1.value = fromTexture;
                this.material.uniforms.texture2.value = newTexture;
                this.material.uniforms.progress.value = Math.abs(progress);
            } else {
                this.material.uniforms.progress.value = Math.abs(progress);
            }
        } else if (needsTransitionDuration) {
            if (progress === 0 && this.material.uniforms.progress.value === 0) {
                this.material.uniforms.progress.value = 1;
            }
            if (progress === 1 && this.material.uniforms.progress.value === 1) {
                this.material.uniforms.progress.value = 0;
            }
            this.animateUniform(this.material.uniforms.progress, progress, () => {
                if (
                    this.swiper.params.gl.shader === 'random' ||
                    Array.isArray(this.swiper.params.gl.shader)
                ) {
                    this.replaceRandomShader();
                    this.material.uniforms.texture1.value = fromTexture;
                    this.material.uniforms.texture2.value = newTexture;
                    this.material.uniforms.progress.value = progress;
                }
                if (progress === 1) {
                    this.material.uniforms.texture1.value = newTexture;
                }
                this.material.uniforms.progress.value = 0;
            });
        } else {
            this.material.uniforms.progress.value = Math.abs(progress);
        }
    }

    render() {
        if (this.swiper.destroyed || this.destroyed) return;
        this.time += 0.05;
        this.material.uniforms.time.value = this.time;

        Object.keys(this.uniforms).forEach((item) => {
            this.material.uniforms[item].value = this.uniforms[item].value;
        });

        requestAnimationFrame(this.render.bind(this));
        this.renderer.render({ scene: this.scene, camera: this.camera });
    }

    destroy() {
        this.initialized = false;
        this.destroyed = true;
        if (this.gl && this.gl.canvas) this.container.removeChild(this.gl.canvas);
    }
}


if (typeof window !== 'undefined' && window.SwiperElementRegisterParams) {
    window.SwiperElementRegisterParams(['gl']);
}

(function (window) {
    function SwiperGL({ swiper, on, extendParams }) {
        swiper.gl = null;
        let noWebGLSupport = false;

        function supportsWebGL() {
            try {
                const canvas = document.createElement('canvas');
                return (
                    !!window.WebGLRenderingContext &&
                    (canvas.getContext('webgl') || canvas.getContext('experimental-webgl'))
                );
            } catch (e) {
                return false;
            }
        }

        extendParams({
            gl: {
                shader: 'random',
                shaderPerSlide: false,
                displacementMap: undefined,
            },
        });

        const glInit = () => {
            if (typeof GL === 'undefined') {
                console.error('GL is not defined');
                return;
            }

            swiper.gl = new GL({
                swiper,
                shader: swiper.params.gl.shader,
                shaderPerSlide: swiper.params.gl.shaderPerSlide,
            });
        };

        let needsTransitionDuration;
        let previousProgress;

        on('beforeInit', () => {
            if (swiper.params.effect !== 'gl') return;
            if (!supportsWebGL()) {
                noWebGLSupport = true;
                return;
            }

            swiper.classNames.push(`${swiper.params.containerModifierClass}gl`);
            const overwriteParams = {
                watchSlidesProgress: true,
            };
            Object.assign(swiper.params, overwriteParams);
            Object.assign(swiper.originalParams, overwriteParams);
        });
        on('init', () => {
            if (swiper.params.effect !== 'gl' || noWebGLSupport || swiper.glDestroyed)
                return;
            if (!swiper.gl) {
                glInit();
            }
        });
        on('resize', () => {
            if (swiper.params.effect !== 'gl' || noWebGLSupport || swiper.glDestroyed)
                return;

            if (!swiper.gl) {
                glInit();
            }

            if (swiper.gl && typeof swiper.gl.resize === 'function') {
                swiper.gl.resize();
            } else {
                console.error('GL instance or resize method is not defined');
            }
        });

        on('setTranslate', () => {
            if (swiper.params.effect !== 'gl' || noWebGLSupport || swiper.glDestroyed)
                return;
            if (!swiper.gl) {
                glInit();
            }
            let from;
            let to;
            let transitionProgress;
            let needShaderReplace = false;
            let targetIndex;
            swiper.slides.forEach((slideEl, slideIndex) => {
                const progress = slideEl.progress;
                if (swiper.params.cssMode && Math.round(progress * 100) === 0) {
                    needShaderReplace = true;
                }
                if (
                    (progress > 0 && progress < 1) ||
                    (progress === 0 && swiper.progress < previousProgress)
                ) {
                    from = slideIndex;
                    to = slideIndex + 1;
                    transitionProgress = progress;
                    targetIndex = from;
                }
                if (
                    (progress < 0 && progress > -1) ||
                    (progress === 0 && swiper.progress > previousProgress)
                ) {
                    from = slideIndex - 1;
                    to = slideIndex;
                    transitionProgress = 1 + progress;
                    targetIndex = to;
                }
            });

            previousProgress = swiper.progress || 0;
            if (typeof from === 'undefined' && typeof to === 'undefined') {
                return;
            }
            needShaderReplace =
                needShaderReplace &&
                Math.round(transitionProgress) === transitionProgress;
            const newFrom = Math.min(targetIndex, swiper.activeIndex);
            const newTo = Math.max(targetIndex, swiper.activeIndex);
            if (!swiper.params.loop && newTo !== newFrom) {
                from = newFrom;
                to = newTo;
            }

            if (swiper.gl && typeof swiper.gl.setProgress === 'function') {
                swiper.gl.setProgress(
                    from,
                    to,
                    transitionProgress,
                    needsTransitionDuration,
                    needShaderReplace,
                );
            }
        });
        on('setTransition', (_s, duration) => {
            if (swiper.params.effect !== 'gl' || noWebGLSupport || swiper.glDestroyed)
                return;
            needsTransitionDuration = duration > 0 && !swiper.params.cssMode;
        });
        on('slidesGridLengthChange', () => {
            if (
                swiper.params.effect !== 'gl' ||
                noWebGLSupport ||
                !swiper.initialized ||
                swiper.glDestroyed
            )
                return;
            if (swiper.gl && typeof swiper.gl.loadTextures === 'function') {
                swiper.gl.loadTextures();
            }
        });
        on('beforeDestroy', () => {
            if (swiper.params.effect !== 'gl' || noWebGLSupport) return;
            if (swiper.gl) {
                swiper.glDestroyed = true;
                swiper.gl.destroy();
                swiper.gl = null;
            }
        });
    }

    // Attach SwiperGL to the global window object
    window.SwiperGL = SwiperGL;
})(window);