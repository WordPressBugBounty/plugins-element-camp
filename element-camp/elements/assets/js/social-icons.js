(function ($) {
    "use strict";
    function tcElementsSocialIcons($scope, $) {
        $(document).ready(function () {
            // HOVER SELECTORS SCRIPT
            $('.e-parent .elementor-widget-tcgelements-social-icons').parent().on('mouseenter', function () {
                // When the mouse enters an element with class 'e-parent' and has a direct child with class 'elementor-widget-tcg-post-featured-image'
                $(this).find('.social-icons-selector-type-container').addClass('tc-social-icons-container-active'); // Add the active class to '.selector-type-container' inside 'e-parent'
            }).on('mouseleave', function () {
                // When the mouse leaves an element with class 'e-parent' and has a direct child with class 'elementor-widget-tcg-post-featured-image'
                $(this).find('.social-icons-selector-type-container').removeClass('tc-social-icons-container-active'); // Remove the active class from '.selector-type-container' inside 'e-parent'
            });
            $('.e-parent .elementor-widget-tcgelements-social-icons').parent().parent().on('mouseenter', function () {
                // When the mouse enters an element with class 'e-parent' and has a direct child with class 'elementor-widget-tcg-post-featured-image'
                $(this).find('.social-icons-selector-type-parent-container').addClass('tc-social-icons-container-active'); // Add the active class to '.selector-type-container' inside 'e-parent'
            }).on('mouseleave', function () {
                // When the mouse leaves an element with class 'e-parent' and has a direct child with class 'elementor-widget-tcg-post-featured-image'
                $(this).find('.social-icons-selector-type-parent-container').removeClass('tc-social-icons-container-active'); // Remove the active class from '.selector-type-container' inside 'e-parent'
            });
            //     Parallax Script
            const link = document.querySelectorAll('.tcgelements-social-icons .tcgelements-hover-this');
            // const cursor = document.querySelector('.infolio-cursor');
            const animateit = function (e) {
                const hoverAnim = this.querySelector('.tcgelements-social-icons .tcgelements-hover-anim');
                const { offsetX: x, offsetY: y } = e,
                    { offsetWidth: width, offsetHeight: height } = this,
                    move = 25,
                    xMove = x / width * (move * 2) - move,
                    yMove = y / height * (move * 2) - move;
                hoverAnim.style.transform = `translate(${xMove}px, ${yMove}px)`;
                if (e.type === 'mouseleave') hoverAnim.style.transform = '';
            };
            const editCursor = e => {
                const { clientX: x, clientY: y } = e;
            };
            link.forEach(b => b.addEventListener('mousemove', animateit));
            link.forEach(b => b.addEventListener('mouseleave', animateit));
            window.addEventListener('mousemove', editCursor);



        });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-social-icons.default', tcElementsSocialIcons);
    });

})(jQuery);