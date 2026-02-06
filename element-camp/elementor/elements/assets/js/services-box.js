(function ($) {
  "use strict";
  function elementcamp_services_box($scope) {
    // Add active class to first item on page load
    $(".tcgelements-services-box .item:first").addClass("active");
    
    // Click functionality to switch active class
    $(".tcgelements-services-box").on("click", ".item", function () {
      $(this).addClass("active").siblings().removeClass("active");
    });
  }
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/tcgelements-services-box.default",
      elementcamp_services_box
    );
  });
})(jQuery);
