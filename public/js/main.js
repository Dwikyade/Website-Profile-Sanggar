$(document).ready(function () {
  $(".fa-bars").click(function () {
    $(this).toggleClass("fa-times");
    $(".navbar").toggleClass("nav-toggle");
  });

  $(window).on("load scroll", function () {
    $(".fa-bars").removeClass("fa-times");
    $(".navbar").removeClass("nav-toggle");

    if ($(window).scrollTop() > 35) {
      $(".header").css({
        background: "#F5C000",
        "box-shadow": "0 .2rem .5rem rgba(0,0,0,.4)",
      });
    } else {
      $(".header").css({ background: "none", "box-shadow": "none" });
    }
  });

  const counters = document.querySelectorAll(".counter");
  const speed = 200;

  counters.forEach((counter) => {
    const updateCount = () => {
      const target = +counter.getAttribute("data-target");
      const start = +counter.innerText || 0; // fallback ke 0 kalau kosong
      let count = start;
      const inc = Math.max((target - start) / speed, 1); // minimal increment 1

      const animate = () => {
        if (count < target) {
          count += inc;
          counter.innerText = Math.floor(count); // tampilkan angka bulat ke bawah
          setTimeout(animate, 50);
        } else {
          counter.innerText = target;
        }
      };

      animate();
    };

    updateCount();
  });

  (function ($) {
    "use strict";

    $(".clients-carousel").owlCarousel({
      autoplay: true,
      dots: true,
      loop: true,
      responsive: { 0: { items: 2 }, 768: { items: 4 }, 900: { items: 6 } },
    });

    $(".testimonials-carousel").owlCarousel({
      autoplay: true,
      dots: true,
      loop: true,
      responsive: {
        0: { items: 1 },
        576: { items: 2 },
        768: { items: 3 },
        992: { items: 4 },
      },
    });
    $(".rias-carousel").owlCarousel({
      autoplay: true,
      dots: true,
      loop: true,
      responsive: {
        0: { items: 1 },
        576: { items: 2 },
        768: { items: 3 },
        992: { items: 4 },
      },
    });
  })(jQuery);

  $(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
      $(".back-to-top").fadeIn("slow");
    } else {
      $(".back-to-top").fadeOut("slow");
    }
  });
  $(".back-to-top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 1500, "easeInOutExpo");
    return false;
  });

  $(".accordion-header").click(function () {
    $(".accordion .accordion-body").slideUp(500);
    $(this).next(".accordion-body").slideDown(500);
    $(".accordion .accordion-header span").text("+");
    $(this).children("span").text("-");
  });
});
