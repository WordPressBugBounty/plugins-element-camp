(function ($) {
    "use strict";

    function tcLettersLightAnimation($scope, $) {
        $scope.find(".tce-letters-light").each(function () {
            const element      = this;
            const dimOpacity   = parseFloat(element.dataset.llDimOpacity   ?? 0.1);
            const maxLit       = parseInt(element.dataset.llMaxLit          ?? 2, 10);
            const fadeDuration = parseFloat(element.dataset.llFadeDuration  ?? 0.3);
            const minInterval  = parseInt(element.dataset.llMinInterval     ?? 400, 10);
            const maxInterval  = parseInt(element.dataset.llMaxInterval     ?? 800, 10);
            const animateBy    = element.dataset.llAnimateBy               ?? 'letter';

            const byWord = animateBy === 'word';

            const splitText = new SplitText(element, {
                type: byWord ? "words" : "words,chars",
                charsClass: "letter",
                wordsClass: "word",
            });

            // Use words or chars depending on the setting
            const items = byWord ? splitText.words : splitText.chars;
            gsap.set(items, { opacity: dimOpacity });

            let litItems  = [];
            let timeoutId = null;

            function animateRandomItem() {
                if (litItems.length >= maxLit) {
                    const itemToTurnOff = litItems.shift();
                    gsap.to(itemToTurnOff, {
                        opacity:  dimOpacity,
                        duration: fadeDuration,
                        ease:     "power1.inOut",
                    });
                }

                let randomItem;
                let attempts = 0;
                do {
                    const idx = Math.floor(Math.random() * items.length);
                    randomItem = items[idx];
                    attempts++;
                } while (litItems.includes(randomItem) && attempts < items.length);

                gsap.to(randomItem, {
                    opacity:  1,
                    duration: fadeDuration,
                    ease:     "power1.inOut",
                });

                litItems.push(randomItem);

                const delay = Math.random() * (maxInterval - minInterval) + minInterval;
                timeoutId = setTimeout(animateRandomItem, delay);
            }

            animateRandomItem();
        });
    }

    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/tcgelements-heading.default",
            tcLettersLightAnimation
        );
    });

})(jQuery);