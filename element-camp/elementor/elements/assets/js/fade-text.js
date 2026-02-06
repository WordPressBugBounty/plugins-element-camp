(function ($) {
    "use strict";
    function splitTextToWords(element) {
        const html = element.innerHTML.trim();
        const parts = html.split(/(<br\s*\/?>|\s+)/g);

        element.innerHTML = '';

        parts.forEach(part => {
            // Preserve <br>
            if (part.match(/<br\s*\/?>/)) {
                element.insertAdjacentHTML('beforeend', part);
                return;
            }

            // Preserve spaces
            if (part.trim() === '') {
                element.insertAdjacentHTML('beforeend', part);
                return;
            }

            // Word wrapper
            const wordSpan = document.createElement('span');
            wordSpan.className = 'tce-word';
            wordSpan.style.display = 'inline-flex';

            // Split word into characters
            part.split('').forEach(char => {
                const charSpan = document.createElement('span');
                charSpan.className = 'char';
                charSpan.style.display = 'inline-block';
                charSpan.style.opacity = '0';
                charSpan.innerHTML = char;
                wordSpan.appendChild(charSpan);
            });

            element.appendChild(wordSpan);
        });
    }

    function initFadeTextAnimation($scope) {
        const fadeTextElements = $scope.find('.tcgelements-heading.tce-fade-text');
        if (!fadeTextElements.length) return;

        fadeTextElements.each(function () {
            const element = this;
            const autoTrigger = element.dataset.fadeAuto === 'yes';

            // Split text safely
            splitTextToWords(element);

            const chars = element.querySelectorAll('.char');

            if (!autoTrigger) return;

            const hasPreloader = $('.loader-wrap').length > 0;

            $(window).on('load', function () {
                const delay = hasPreloader ? 1000 : 400;

                setTimeout(() => {
                    if (typeof gsap !== 'undefined') {
                        gsap.fromTo(
                            chars,
                            { y: 100, opacity: 0 },
                            {
                                y: 0,
                                opacity: 1,
                                duration: 0.8,
                                stagger: 0.04,
                                ease: "power2.out"
                            }
                        );
                    }
                }, delay);
            });
        });
    }

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/tcgelements-heading.default',
            initFadeTextAnimation
        );
    });

})(jQuery);