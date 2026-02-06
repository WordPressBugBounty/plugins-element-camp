(function ($) {
    "use strict";

    function initTcgelementsMenuList() {
        // Add menu item icons (before dropdown icons)
        $('.tcgelements-menu-list ul .menu-item a').each(function () {
            let $link = $(this);
            let $menuList = $link.closest('.tcgelements-menu-list');
            let menuItemIconHtml = $menuList.data('menu-item-icon');

            // Only add if icon doesn't already exist and we have an icon to add
            if (menuItemIconHtml && $link.find('.menu-item-icon').length === 0) {
                // Wrap the icon in a span for better styling control
                $link.prepend('<span class="menu-item-icon">' + menuItemIconHtml + '</span>');
            }
        });

        // Existing dropdown icons code
        $('.tcgelements-menu-list ul > .menu-item-has-children > a').each(function () {
            let iconHtml = $(this).closest('.tcgelements-menu-list').data('dropdown-icon');
            // Avoid appending if a dropdown icon already exists
            if ($(this).find('.dropdown-icon').length === 0) {
                if ($(this).siblings('.sub-menu').length > 0) {
                    if (iconHtml) {
                        $(this).append('<span class="dropdown-icon">' + iconHtml + '</span>');
                    } else {
                        $(this).append('<span class="dropdown-icon"><i class="fas fa-chevron-down"></i></span>');
                    }
                }
            }
        });

        // Update click handler to target the dropdown icon specifically
        $('.tcgelements-menu-list ul > .menu-item-has-children > a').off('click').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            let $dropdownIcon = $(this).find('.dropdown-icon i, .dropdown-icon svg');
            let $subMenu = $(this).siblings('.sub-menu');
            let $menuItem = $(this).parent();

            if ($subMenu.hasClass('sub-open')) {
                $subMenu.removeClass('sub-open');
                $menuItem.removeClass('sub-menu-open');
                if ($dropdownIcon.is('i')) {
                    $dropdownIcon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                } else if ($dropdownIcon.is('svg')) {
                    let iconHtml = $(this).closest('.tcgelements-menu-list').data('dropdown-icon');
                    $dropdownIcon.replaceWith(iconHtml);
                }
            } else {
                $subMenu.addClass('sub-open');
                $menuItem.addClass('sub-menu-open');
                if ($dropdownIcon.is('i')) {
                    $dropdownIcon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                } else if ($dropdownIcon.is('svg')) {
                    let activeIconHtml = $(this).closest('.tcgelements-menu-list').data('dropdown-icon-active');
                    $dropdownIcon.replaceWith(activeIconHtml);
                }
            }
        });

        // Existing hover animation code...
        if ($('.tcgelements-menu-list.hover-animation').length) {
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

        if (elementorFrontend.isEditMode()) {
            initTcgelementsMenuList();
        }
    });

    if (typeof elementorFrontend === 'undefined' || !elementorFrontend.isEditMode()) {
        initTcgelementsMenuList();
    }

})(jQuery);