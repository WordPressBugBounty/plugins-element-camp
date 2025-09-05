(function ($) {
    $(document).ready(function () {
        $(".tcgelements-search-form").on("click", ".tcgelements-search-icon", function () {
            $(".tcgelements-search-form").toggleClass("open");

            if ($(".tcgelements-search-form").hasClass('open')) {
                $(".tcgelements-search-form .close-search").slideDown();
            } else {
                $(".tcgelements-search-form .close-search").slideUp();
            }
        });

        // Prevent the click event propagation for the input element
        $(".tcgelements-search-form").on("click", "input", function (event) {
            event.stopPropagation();
        });
    });
})(jQuery);
