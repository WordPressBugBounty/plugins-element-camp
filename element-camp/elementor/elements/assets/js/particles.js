/**
 * Element Camp Particles Animation
 * Simple particle system matching the provided sample
 */

(function ($) {
    "use strict";

    // Particle System Configuration
    const particleConfig = {
        particleCount: 40,
        maxDistance: 150,
        baseRadius: 2,
        maxRadius: 4,
        speed: 1.3,
        mouseRadius: 300
    };

    class Particle {
        constructor(canvas) {
            this.canvas = canvas;
            this.reset();
            this.baseX = this.x;
            this.baseY = this.y;
        }

        reset() {
            this.x = Math.random() * this.canvas.width;
            this.y = Math.random() * this.canvas.height;
            this.vx = (Math.random() - 0.5) * particleConfig.speed;
            this.vy = (Math.random() - 0.5) * particleConfig.speed;
            this.radius = particleConfig.baseRadius + Math.random() * (particleConfig.maxRadius - particleConfig.baseRadius);
        }

        update(mouse) {
            // Move particle
            this.x += this.vx;
            this.y += this.vy;

            // Bounce off edges
            if (this.x < 0 || this.x > this.canvas.width) this.vx *= -1;
            if (this.y < 0 || this.y > this.canvas.height) this.vy *= -1;

            // Mouse interaction
            if (mouse.x !== null && mouse.y !== null) {
                const dx = mouse.x - this.x;
                const dy = mouse.y - this.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < particleConfig.mouseRadius) {
                    const angle = Math.atan2(dy, dx);
                    const force = (particleConfig.mouseRadius - distance) / particleConfig.mouseRadius;
                    this.x -= Math.cos(angle) * force * 3;
                    this.y -= Math.sin(angle) * force * 3;
                }
            }
        }

        draw(ctx) {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(255, 255, 255, 0.1)';
            ctx.fill();
        }
    }

    class ParticleSystem {
        constructor(containerSelector) {
            // Handle both DOM elements and selector strings
            if (typeof containerSelector === 'string') {
                this.container = document.querySelector(containerSelector);
            } else if (containerSelector instanceof HTMLElement) {
                this.container = containerSelector;
            } else {
                this.container = null;
            }

            if (!this.container) return;

            // Prevent multiple initializations on the same container
            if (this.container.classList.contains('particles-initialized')) {
                return;
            }
            this.container.classList.add('particles-initialized');

            this.canvas = document.createElement('canvas');
            this.ctx = this.canvas.getContext('2d');
            this.canvas.style.position = 'absolute';
            this.canvas.style.top = '0';
            this.canvas.style.left = '0';
            this.canvas.style.width = '100%';
            this.canvas.style.height = '100%';
            this.canvas.style.pointerEvents = 'none';
            this.canvas.style.zIndex = '1';

            this.container.appendChild(this.canvas);

            this.particles = [];
            this.mouse = { x: null, y: null };

            this.init();
            this.setupEventListeners();
            this.animate();
        }

        init() {
            this.resize();
            for (let i = 0; i < particleConfig.particleCount; i++) {
                this.particles.push(new Particle(this.canvas));
            }
        }

        resize() {
            // Get parent dimensions since container might have 0 height
            const parent = this.container.parentElement;
            const width = parent ? parent.offsetWidth : this.container.offsetWidth;
            const height = parent ? parent.offsetHeight : this.container.offsetHeight;

            this.canvas.width = width || 800;
            this.canvas.height = height || 600;
        }

        setupEventListeners() {
            window.addEventListener('resize', () => this.resize());

            this.container.addEventListener('mousemove', (e) => {
                const rect = this.canvas.getBoundingClientRect();
                this.mouse.x = e.clientX - rect.left;
                this.mouse.y = e.clientY - rect.top;
            });

            this.container.addEventListener('mouseleave', () => {
                this.mouse.x = null;
                this.mouse.y = null;
            });
        }

        connectParticles() {
            for (let i = 0; i < this.particles.length; i++) {
                for (let j = i + 1; j < this.particles.length; j++) {
                    const dx = this.particles[i].x - this.particles[j].x;
                    const dy = this.particles[i].y - this.particles[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < particleConfig.maxDistance) {
                        const opacity = 1 - (distance / particleConfig.maxDistance);
                        this.ctx.beginPath();
                        this.ctx.strokeStyle = `rgba(100, 150, 255, ${opacity * 0.3})`;
                        this.ctx.lineWidth = 1;
                        this.ctx.moveTo(this.particles[i].x, this.particles[i].y);
                        this.ctx.lineTo(this.particles[j].x, this.particles[j].y);
                        this.ctx.stroke();
                    }
                }
            }
        }

        animate() {
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

            this.particles.forEach(particle => {
                particle.update(this.mouse);
                particle.draw(this.ctx);
            });

            this.connectParticles();

            requestAnimationFrame(() => this.animate());
        }
    }

    // Only initialize on Elementor frontend
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-canvas.default', function($scope) {
            const $particlesContainer = $scope.find('.particles-container');
            if ($particlesContainer.length > 0) {
                new ParticleSystem($particlesContainer[0]);
            }
        });
    });

})(jQuery);