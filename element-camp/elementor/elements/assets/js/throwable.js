!(function ($) {
    "use strict";
    function elementcamp_throwable_content($scope, $) {
        "use strict";
        const restArguments = function(t, e) {
                return e = null == e ? t.length - 1 : +e,
                    function(i, s) {
                        for (var n = Math.max(arguments.length - e, 0), o = Array(n), a = 0; a < n; a++) o[a] = arguments[a + e];
                        switch (e) {
                            case 0: return t.call(this, o);
                            case 1: return t.call(this, i, o);
                            case 2: return t.call(this, i, s, o)
                        }
                        var l = Array(e + 1);
                        for (a = 0; a < e; a++) l[a] = arguments[a];
                        return l[e] = o, t.apply(this, l)
                    }
            },
            tpDelay = restArguments((function(t, e, i) {
                return setTimeout((function() {
                    return t.apply(null, i)
                }), e)
            }));
        window.tpDebounce = function(t, e, i) {
            var s, n, o = function(e, i) {
                    s = null, i && (n = t.apply(e, i))
                },
                a = restArguments((function(a) {
                    if (s && clearTimeout(s), i) {
                        var l = !s;
                        s = setTimeout(o, e), l && (n = t.apply(this, a))
                    } else s = tpDelay(o, e, this, a);
                    return n
                }));
            return a.cancel = function() {
                clearTimeout(s), s = null
            }, a
        };

        const t = "tpThrowable";
        let e = {
            roundness: "sharp",
            scrollGravity: false
        };

        class i {
            constructor(i, s) {
                this._defaults = e;
                this._name = t;
                this.options = { ...e, ...s };
                this.DOM = {};
                this.DOM.element = i;
                this.DOM.$element = $(i);
                this.DOM.throwables = this.DOM.element.querySelectorAll("[data-tp-throwable-el]");
                this.onWindowResize = tpDebounce(this.onWindowResize.bind(this), 250);
                this.bodies = [];
                // Cache element dimensions to avoid offsetWidth/offsetHeight in tick loop
                this.dims = [];
                this.rainSettled = false;
                this.init();
            }

            init() {
                this.createWorld();
                this.createBoundries();
                this.createBodies();
                this.enableRunner();
                this.makeItRain();
                this.bindResize();
            }

            enableRunner() {
                this.runnerObserver = new IntersectionObserver(([entry]) => {
                    this.runner.enabled = entry.isIntersecting;
                });
                this.runnerObserver.observe(this.DOM.element);
            }

            makeItRain() {
                const rainObserver = new IntersectionObserver(([entry], obs) => {
                    if (entry.isIntersecting) {
                        // ── Use plain CSS opacity instead of GSAP to avoid
                        //    GSAP taking ownership of the element's transform ──
                        this.DOM.throwables.forEach(el => {
                            el.style.transition = "opacity 0.35s ease";
                            el.style.opacity = "1";
                        });
                        this.startRain();
                        obs.disconnect();
                    }
                });
                rainObserver.observe(this.DOM.element);
            }

            bindResize() {
                window.addEventListener("resize", this.onWindowResize, { passive: true });
            }

            createWorld() {
                this.height = this.DOM.element.offsetHeight;
                this.width  = this.DOM.element.offsetWidth;

                this.engine = Matter.Engine.create();
                this.runner = Matter.Runner.create();
                this.mouse  = Matter.Mouse.create(this.DOM.element);

                this.DOM.element.removeEventListener("mousewheel", this.mouse.mousewheel);
                this.DOM.element.addEventListener("mouseleave", this.mouse.mouseup);

                this.mouseConstraint = Matter.MouseConstraint.create(this.engine, {
                    mouse: this.mouse,
                    constraint: { render: { visible: false } }
                });

                this.engine.gravity.y = .8;
                Matter.Composite.add(this.engine.world, [this.mouseConstraint]);
                Matter.Runner.start(this.runner, this.engine);

                Matter.Events.on(this.mouseConstraint, "mousedown", () => {
                    this.DOM.element.style.pointerEvents = "auto";
                });
                Matter.Events.on(this.mouseConstraint, "mouseup", () => {
                    this.DOM.element.style.pointerEvents = "";
                });

                this.runner.enabled = false;

                // ── SINGLE tick listener — no GSAP, no offsetWidth reads ──
                Matter.Events.on(this.runner, "tick", () => {
                    if (!this.runner.enabled) return;

                    const bodies = this.bodies;
                    const dims   = this.dims;
                    const len    = bodies.length;

                    for (let idx = 0; idx < len; idx++) {
                        const g  = bodies[idx];
                        const el = this.DOM.throwables[idx];
                        const w  = dims[idx].w;
                        const h  = dims[idx].h;
                        // Position from top-left (0,0) of container — same as sample script
                        el.style.transform =
                            `translate(${(g.position.x - w / 2).toFixed(1)}px, ${(g.position.y - h / 2).toFixed(1)}px) rotate(${g.angle.toFixed(2)}rad)`;
                    }

                    if (!this.rainSettled && len > 0 &&
                        bodies[len - 1].position.y > this.height / 2) {
                        this.createTopBound();
                        if (this.options.scrollGravity) this.makeScrollGravity();
                        this.rainSettled = true;
                    }
                });
            }

            createBoundries() {
                this.boundStart  = Matter.Bodies.rectangle(-250, this.height / 2, 500, 4 * this.height, { isStatic: true });
                this.boundEnd    = Matter.Bodies.rectangle(this.width + 250, this.height / 2, 500, 4 * this.height, { isStatic: true });
                this.boundBottom = Matter.Bodies.rectangle(this.width / 2, this.height + 250, 2 * this.width, 500, { isStatic: true });
                Matter.Composite.add(this.engine.world, [this.boundBottom, this.boundStart, this.boundEnd]);
            }

            createBodies() {
                // ── Batch ALL dimension reads before any writes ──
                const dims = [];
                this.DOM.throwables.forEach(el => {
                    // Force element to be visible/measurable before reading
                    el.style.opacity   = "0";
                    el.style.position  = "absolute";
                    el.style.top       = "0";
                    el.style.left      = "0";
                    // Clear any lingering GSAP inline transform so getBoundingClientRect is accurate
                    el.style.transform = "none";
                    const r = el.getBoundingClientRect();
                    dims.push({ w: r.width || el.offsetWidth, h: r.height || el.offsetHeight });
                });

                // Cache dims for use in tick loop (avoids reading DOM every frame)
                this.dims = dims;

                this.DOM.throwables.forEach((el, idx) => {
                    const { w, h } = dims[idx];
                    const radius   = this.options.roundness === "sharp" ? 0 : h / 2;
                    const angle    = (Math.random() - 0.5) * 0.4 * Math.PI;
                    const startX   = Math.random() * (this.width  - w) + w / 2;
                    const startY   = -(w + idx * (h + 10));

                    const body = Matter.Bodies.rectangle(startX, startY, w, h, {
                        chamfer:     { radius },
                        angle,
                        isStatic:    true,
                        restitution: .3,
                        friction:    0.1
                    });

                    this.bodies.push(body);
                    Matter.Composite.add(this.engine.world, [body]);
                    // ── No GSAP quickSetters — plain style.transform only ──
                });
            }

            createTopBound() {
                this.boundTop = Matter.Bodies.rectangle(this.width / 2, -250, 2 * this.width, 500, { isStatic: true });
                Matter.Composite.add(this.engine.world, [this.boundTop]);
            }

            makeScrollGravity() {
                let lastScroll = 0;
                Matter.Events.on(this.runner, "tick", () => {
                    const scroll = document.documentElement.scrollTop - document.documentElement.clientTop;
                    const delta  = scroll - lastScroll;
                    this.engine.gravity.y = 0.7 - Math.max(-2, Math.min(4, 0.1 * delta));
                    lastScroll = scroll;
                });
            }

            updateBoundries() {
                const setRect = (body, x, y, w, h) => {
                    Matter.Body.setPosition(body, { x, y });
                    Matter.Body.setVertices(body,
                        Matter.Bodies.rectangle(x, y, w, h, { isStatic: true }).vertices
                    );
                };
                this.boundTop    && setRect(this.boundTop,    this.width / 2,     -250,               2 * this.width, 500);
                this.boundStart  && setRect(this.boundStart,  -250,               this.height / 2,    500, 4 * this.height);
                this.boundEnd    && setRect(this.boundEnd,    this.width + 250,   this.height / 2,    500, 4 * this.height);
                this.boundBottom && setRect(this.boundBottom, this.width / 2,     this.height + 250,  2 * this.width, 500);
            }

            updateBodies() {
                // Re-read dims after resize
                const dims = [];
                this.DOM.throwables.forEach(el => {
                    const r = el.getBoundingClientRect();
                    dims.push({ w: r.width || el.offsetWidth, h: r.height || el.offsetHeight });
                });
                this.dims = dims;

                this.DOM.throwables.forEach((el, idx) => {
                    const body     = this.bodies[idx];
                    const { w, h } = dims[idx];
                    const radius   = this.options.roundness === "sharp" ? 0 : h / 2;
                    const newBody  = Matter.Bodies.rectangle(
                        body.position.x, body.position.y, w, h,
                        { chamfer: { radius }, angle: body.angle }
                    );
                    Matter.Body.setVertices(body, newBody.vertices);

                    if (body.position.y > this.height) {
                        Matter.Body.setPosition(body, { x: body.position.x, y: this.height / 2 });
                    }
                    if (body.position.x > this.width) {
                        Matter.Body.setPosition(body, {
                            x: Math.random() * (this.width - w) + w / 2,
                            y: body.position.y
                        });
                    }
                });
            }

            startRain() {
                this.bodies.forEach((body, idx) => {
                    const timer = setTimeout(() => {
                        Matter.Body.setStatic(body, false);
                        clearTimeout(timer);
                    }, 80 * idx);
                });
            }

            refresh() {
                const newH = this.DOM.element.offsetHeight;
                const newW = this.DOM.element.offsetWidth;
                if (this.height === newH && this.width === newW) return false;
                this.height = newH;
                this.width  = newW;
                const timer = setTimeout(() => {
                    this.updateBoundries();
                    this.updateBodies();
                    clearTimeout(timer);
                });
            }

            onWindowResize() { this.refresh(); }

            destroy() {
                this.runner.enabled = false;
                Matter.Runner.stop(this.runner);
                Matter.Events.off(this.runner, "tick");
                this.runnerObserver && this.runnerObserver.disconnect();
                window.removeEventListener("resize", this.onWindowResize);
            }
        }

        $.fn[t] = function(e) {
            return this.each(function() {
                const s = { ...$(this).data("throwable-options"), ...e };
                $.data(this, "plugin_" + t) || $.data(this, "plugin_" + t, new i(this, s));
            });
        };

        $("[data-tp-throwable-scene]").tpThrowable();
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-throwable-content.default',
            elementcamp_throwable_content
        );
    });
})(jQuery);