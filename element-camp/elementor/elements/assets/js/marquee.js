(function ($) {
    "use strict";

    function initMarqueeLettersAnimation($scope) {
        const marqueeElements = $scope.find('.tcg-marquee.tce-letters-line');

        if (!marqueeElements.length) return;

        // Process text nodes to wrap words and letters
        function processTextNodes(element) {
            const walker = document.createTreeWalker(
                element,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );

            const textNodes = [];
            let node;
            while (node = walker.nextNode()) {
                if (node.textContent.trim() !== '') {
                    textNodes.push(node);
                }
            }

            textNodes.forEach(textNode => {
                const text = textNode.textContent;
                const parent = textNode.parentNode;

                // Skip if parent already has letter spans
                if (parent.classList.contains('word') || parent.classList.contains('letter')) {
                    return;
                }

                // Split by spaces but keep the spaces
                const words = text.split(/(\s+)/);

                const fragment = document.createDocumentFragment();

                words.forEach(word => {
                    if (word.trim() === "") {
                        // Keep whitespace as text node
                        const space = document.createTextNode(word);
                        fragment.appendChild(space);
                    } else {
                        // Create word span
                        const wordSpan = document.createElement('span');
                        wordSpan.className = 'word';
                        wordSpan.style.display = 'inline-block';
                        wordSpan.style.whiteSpace = 'nowrap';

                        // Split word into letters
                        word.split("").forEach(char => {
                            const span = document.createElement('span');
                            span.className = 'letter';
                            span.style.display = 'inline-block';
                            span.textContent = char;
                            wordSpan.appendChild(span);
                        });

                        fragment.appendChild(wordSpan);
                    }
                });

                parent.replaceChild(fragment, textNode);
            });
        }

        marqueeElements.each(function (index) {
            const marquee = this;

            // Skip if already processed
            if (marquee.dataset.lettersProcessed === "true") return;
            marquee.dataset.lettersProcessed = "true";

            // Find all text elements within marquee items
            // Target only the first .box to avoid duplicates
            const textElements = marquee.querySelectorAll('.slide-har > .box:first-child .item h4 .text');

            if (!textElements.length) return;

            // Process text nodes in each text element
            textElements.forEach(textElement => {
                if (!textElement.hasChildNodes()) return;
                processTextNodes(textElement);
            });

            // Get animation settings from data attributes
            const duration = parseFloat($(marquee).data('letters-duration')) || 0.6;
            const stagger = parseFloat($(marquee).data('letters-stagger')) || 0.03;
            const yOffset = parseFloat($(marquee).data('letters-y-offset')) || 50;
            const trigger = $(marquee).data('letters-trigger') || marquee;
            const startPosition = $(marquee).data('letters-start') || "top 80%";

            // Get blur settings
            const enableBlur = $(marquee).data('letters-blur') === 'yes';
            const blurAmount = parseFloat($(marquee).data('letters-blur-amount')) || 100;
            const initialScale = parseFloat($(marquee).data('letters-initial-scale')) || 2;

            // Apply GSAP animation with ScrollTrigger
            if (typeof gsap !== 'undefined' && gsap.registerPlugin) {
                setTimeout(() => {
                    // Select letters only from the first box to animate
                    const letters = marquee.querySelectorAll('.slide-har > .box:first-child .item h4 .text .letter');

                    if (!letters.length) return;

                    // Build FROM properties
                    const fromProps = {
                        opacity: 0,
                        y: yOffset
                    };

                    // Build TO properties
                    const toProps = {
                        opacity: 1,
                        y: 0,
                        duration: duration,
                        stagger: stagger,
                        ease: "power4.out"
                    };

                    // Add blur effect only if enabled
                    if (enableBlur) {
                        fromProps.scale = initialScale;
                        fromProps.filter = `blur(${blurAmount}px)`;
                        toProps.scale = 1;
                        toProps.filter = "blur(0px)";

                        // Add will-change for better performance with blur
                        letters.forEach(letter => {
                            letter.style.willChange = "transform, filter, opacity";
                        });
                    }

                    // Create the animation with ScrollTrigger
                    gsap.fromTo(letters, fromProps, {
                        ...toProps,
                        scrollTrigger: {
                            id: `marquee-letters-${Date.now()}-${index}`,
                            trigger: trigger,
                            start: startPosition,
                            toggleActions: "play none none reverse"
                        }
                    });

                    // Refresh ScrollTrigger after animation setup
                    if (typeof ScrollTrigger !== 'undefined') {
                        ScrollTrigger.refresh();
                    }
                }, 100);
            }
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcg-marquee.default',
            initMarqueeLettersAnimation
        );
    });

})(jQuery);