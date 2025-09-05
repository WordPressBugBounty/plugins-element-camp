(function ($) {
    "use strict";

    function initTcgelementsMenuList() {
        // Icons
        $('.tcgelements-menu-list ul > .menu-item-has-children > a').each(function () {
            let iconHtml = $(this).closest('.tcgelements-menu-list').data('dropdown-icon');
            // Avoid appending if an icon already exists
            if ($(this).find('i, svg').length === 0) {
                if ($(this).siblings('.sub-menu').length > 0) {
                    if (iconHtml) {
                        $(this).append(iconHtml);
                    } else {
                        $(this).append('<i class="fas fa-chevron-down"></i>');
                    }
                }
            }
        });

        $('.tcgelements-menu-list ul > .menu-item-has-children > a').off('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let $icon = $(this).find('i, svg');
            let $subMenu = $(this).siblings('.sub-menu');
            let $menuItem = $(this).parent();

            if ($subMenu.hasClass('sub-open')) {
                $subMenu.removeClass('sub-open');
                $menuItem.removeClass('sub-menu-open');
                if ($icon.is('i')) {
                    $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                } else if ($icon.is('svg')) {
                    let iconHtml = $(this).closest('.tcgelements-menu-list').data('dropdown-icon');
                    $icon.replaceWith(iconHtml);
                }
            } else {
                $subMenu.addClass('sub-open');
                $menuItem.addClass('sub-menu-open');
                if ($icon.is('i')) {
                    $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                } else if ($icon.is('svg')) {
                    let activeIconHtml = $(this).closest('.tcgelements-menu-list').data('dropdown-icon-active');
                    $icon.replaceWith(activeIconHtml);
                }
            }
        });

        if ($('.tcgelements-menu-list.hover-animation').length) {
            // Use jQuery to handle setting data-text and adding the fill-text class
            $('.tcgelements-menu-list.hover-animation > div > ul > .menu-item > a').each(function () {
                $(this).attr('data-text', $(this).text().trim());
                $(this).addClass('fill-text');

            });
        }
        $('.tcgelements-menu-list.hover-animation ul > .menu-item').on('mouseenter', function () {
            $(this).removeClass('hoverd').siblings().addClass('hoverd');
        });

        $('.tcgelements-menu-list.hover-animation ul > .menu-item').on('mouseleave', function () {
            $(this).removeClass('hoverd').siblings().removeClass('hoverd');
        });

    }

    // Initialize in frontend
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tcgelements-menu-list.default', initTcgelementsMenuList);

        // Ensure it works in the editor preview
        if (elementorFrontend.isEditMode()) {
            initTcgelementsMenuList();
        }
    });

    // For the off-canvas menu or if it's called directly
    if (typeof elementorFrontend === 'undefined' || !elementorFrontend.isEditMode()) {
        initTcgelementsMenuList();
    }

})(jQuery);
