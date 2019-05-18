(function($) {
  $(document).ready(function() {
    $(".tweet-slider").slick({
      arrows: true,
      dots: true,
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 3,
      centerMode: true,
      centerPadding: "100px"
    });
  });

  /* One scrolling option
  $(document).on("scroll", function () {

    if ($(document).scrollTop() > 200) {
        $("nav").removeClass("large").addClass("small");
    } else {
        $("nav").removeClass("small").addClass("large");
    }
  });
  */

  /* Another scrolling option
  $(function () {
    $(window).scroll(function () {
        if ($(document).scrollTop() > 200) {
            $('.sticky-footer').addClass("show");
        }
        else {
            $('.sticky-footer').removeClass("show");
        }
    });
  });
  */
})(jQuery);
