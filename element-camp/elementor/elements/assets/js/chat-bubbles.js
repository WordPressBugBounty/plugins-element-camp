(function ($) {
    "use strict";

    function elementcamp_chat_bubbles($scope, $) {
        const $container = $scope.find('.tcgelements-chat-bubbles');

        if ($container.length === 0) return;

        // Get settings from data attributes
        const typingSpeed = parseInt($container.data('typing-speed')) || 30;
        const pauseAfterMessage = parseInt($container.data('pause-after')) || 2000;
        const pauseBetweenTurns = parseInt($container.data('pause-between')) || 1000;
        const conversationData = $container.data('conversation') || [];
        const displayMode = $container.data('display-mode') || 'all'; // 'all' or 'two'

        if (conversationData.length === 0) return;

        const $bubbles = $container.find('.chat-bubble');
        let currentStep = 0;
        let isAnimating = false;

        /**
         * Type effect function
         */
        async function typeEffect($element, text) {
            $element.text('');

            for (let i = 0; i < text.length; i++) {
                $element.text($element.text() + text[i]);
                await new Promise((resolve) => setTimeout(resolve, typingSpeed));
            }
        }

        /**
         * Reset all bubbles to initial state
         */
        function resetBubbles() {
            gsap.set($bubbles, {
                opacity: 0,
                y: 20,
                clearProps: "all"
            });

            $bubbles.find('.text').text('');
        }

        /**
         * Animate single chat message (for 'all' mode)
         */
        async function animateMessage(index) {
            const $bubble = $bubbles.eq(index);
            const $textElement = $bubble.find('.text');
            const fullText = $textElement.data('full-text');

            // Show bubble with animation
            await gsap.to($bubble, {
                opacity: 1,
                y: 0,
                duration: 0.5,
                ease: 'power2.out',
                clearProps: "transform"
            });

            // Type the text
            await typeEffect($textElement, fullText);

            // Wait after typing
            await new Promise((resolve) => setTimeout(resolve, pauseAfterMessage));
        }

        /**
         * Animate message with content change (for 'two' mode)
         */
        async function animateMessageWithContentChange(bubbleIndex, messageIndex) {
            const $bubble = $bubbles.eq(bubbleIndex);
            const $textElement = $bubble.find('.text');
            const message = conversationData[messageIndex];
            const fullText = message.text || message;

            const isVisible = parseFloat($bubble.css('opacity')) > 0.5;
            const hasContent = $textElement.text().trim().length > 0;

            // If bubble has content, fade out text first
            if (isVisible && hasContent) {
                await gsap.to($textElement, {
                    opacity: 0,
                    duration: 0.3,
                    ease: 'power2.in'
                });

                // Clear text
                $textElement.text('');
            } else if (!isVisible) {
                // Show bubble if it's hidden (first time)
                await gsap.to($bubble, {
                    opacity: 1,
                    y: 0,
                    duration: 0.5,
                    ease: 'power2.out',
                    clearProps: "transform"
                });
            }

            // Ensure text is visible
            gsap.set($textElement, { opacity: 1 });

            // Type the new text
            await typeEffect($textElement, fullText);

            // Wait after typing
            await new Promise((resolve) => setTimeout(resolve, pauseAfterMessage));
        }

        /**
         * Main chat animation loop - ALL messages mode
         */
        async function startChatLoopAll() {
            isAnimating = true;

            while (isAnimating) {
                // If starting over, reset everything
                if (currentStep === 0) {
                    await gsap.to($bubbles, {
                        opacity: 0,
                        y: 20,
                        duration: 0.5,
                        ease: 'power2.in'
                    });

                    await new Promise((resolve) => setTimeout(resolve, 600));
                    resetBubbles();
                }

                // Animate current message
                await animateMessage(currentStep);

                // Move to next step
                currentStep = (currentStep + 1) % conversationData.length;

                // If we completed a full cycle, add extra pause
                if (currentStep % 2 === 0 && currentStep !== 0) {
                    await new Promise((resolve) => setTimeout(resolve, pauseBetweenTurns));
                }
            }
        }

        /**
         * Main chat animation loop - TWO messages mode
         */
        async function startChatLoopTwo() {
            isAnimating = true;

            while (isAnimating) {
                // Reset on first iteration
                if (currentStep === 0) {
                    resetBubbles();
                    await new Promise((resolve) => setTimeout(resolve, 300));
                }

                // Determine which bubble to use (0 for even index, 1 for odd index)
                const bubbleIndex = currentStep % 2;

                // Animate the message with content change
                await animateMessageWithContentChange(bubbleIndex, currentStep);

                // Move to next step
                currentStep = (currentStep + 1) % conversationData.length;

                // Add pause between conversation turns (after every 2 messages)
                if (currentStep % 2 === 0 && currentStep !== 0) {
                    await new Promise((resolve) => setTimeout(resolve, pauseBetweenTurns));
                }

                // Add longer pause when restarting the conversation
                if (currentStep === 0) {
                    await new Promise((resolve) => setTimeout(resolve, pauseBetweenTurns + 1000));
                }
            }
        }

        /**
         * Stop animation
         */
        function stopChatLoop() {
            isAnimating = false;
        }

        /**
         * Start the appropriate animation loop based on display mode
         */
        function startChatLoop() {
            if (displayMode === 'two') {
                startChatLoopTwo();
            } else {
                startChatLoopAll();
            }
        }

        /**
         * Intersection Observer for auto-start/stop
         */
        if (typeof IntersectionObserver !== 'undefined') {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        if (!isAnimating) {
                            startChatLoop();
                        }
                    } else {
                        stopChatLoop();
                    }
                });
            }, {
                threshold: 0.1
            });

            observer.observe($container[0]);
        } else {
            // Fallback: start immediately if IntersectionObserver not supported
            startChatLoop();
        }

        // Cleanup on element remove
        $scope.on('remove', function() {
            stopChatLoop();
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-chat-bubbles.default',
            elementcamp_chat_bubbles
        );
    });

})(jQuery);