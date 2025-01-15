
// tabs
$('button').on('click', function(){
    $('button').removeClass('active');
    $(this).addClass('active');
});
document.addEventListener("DOMContentLoaded", () => {
    const tabHeaders = document.querySelectorAll("#tabHeaders .tab-header");
    const headers = document.querySelectorAll(".accordion-header");
    const tabContents = document.querySelectorAll("#tabContents .tab-content");
    const tabHeader = document.getElementById("tabHeaders");
    const tabAccordians = document.getElementById("tab-accordian");
   

    const toggleContent = (id) => {
      tabContents.forEach((content) => {
        content.classList.add("hidden");
      });
      document.getElementById(id).classList.remove("hidden");
      
    };
   

    const handleSmallScreen = () => {
        headers.forEach((header) => {
            header.addEventListener("click", () => {
              const targetId = header.getAttribute("data-target");
              const content = document.getElementById(targetId);
    
              // Toggle the content visibility
              const isHidden = content.classList.contains("hidden");
              document.querySelectorAll(".accordion-content").forEach((el) => el.classList.add("hidden"));
              if (isHidden) content.classList.remove("hidden");
            });
          });
    };

    const handleLargeScreen = () => {
      tabHeaders.forEach((header, index) => {
        header.addEventListener("click", () => {
          const targetId = header.getAttribute("data-target");
          console.log(targetId);
          toggleContent(targetId);
        });

        // Set first tab active by default on large screens
        if (index === 0) {
          const targetId = header.getAttribute("data-target");
          toggleContent(targetId);
        }
      });
    };

    const updateLayout = () => {
      const isSmallScreen = window.innerWidth < 1024;

      if (isSmallScreen) {
        tabHeader.classList.add("hidden");
        tabAccordians.classList.remove("hidden")
        // Accordion behavior
        tabContents.forEach((content) => content.classList.add("hidden"));
        handleSmallScreen();
      } else {
        tabHeader.classList.remove("hidden");
        tabAccordians.classList.add("hidden")
       
        // Tabs behavior
        tabContents.forEach((content) => content.classList.add("hidden"));
        handleLargeScreen();
      }
    };

    // Initialize layout and resize listener
    updateLayout();
    window.addEventListener("resize", updateLayout);
  });

// end tabs
(function ($) {
    "use strict";
    // Page loading
    $(window).on('load', function () {
        $('#preloader-active').delay(450).fadeOut('slow');
        $('body').delay(450).css({
            'overflow': 'visible'
        });
    });
    /*-----------------
        Menu Stick
    -----------------*/
    var header = $('.sticky-bar');
    var win = $(window);
    win.on('scroll', function () {
        var scroll = win.scrollTop();
        if (scroll < 20) {
            header.removeClass('stick');
        } else {
            header.addClass('stick');
        }
    });

    /*Carausel 2 columns*/
    $(".carausel-2-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = '#' + id;
        var appendArrowsClassName = '#' + id + '-arrows'

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: true,
            slidesToShow: 2,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ],
            prevArrow: '<span class="mr-4 text-blue-500 flex"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path></svg></span>',
            nextArrow: '<span class="text-blue-500  flex"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></span>',
            appendArrows: (appendArrowsClassName),
        });
    });

    /*Carausel Fade*/
    $('.carausel-fade').each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = '#' + id;
        var appendArrowsClassName = '#' + id + '-arrows'

        $(sliderID).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            loop: true,
            dots: false,
            arrows: true,
            prevArrow: '<span class="mr-4 text-blue-500 flex"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path></svg></span>',
            nextArrow: '<span class="text-blue-500  flex"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></span>',
            appendArrows: (appendArrowsClassName),
            autoplay: true,
        });
    });

    /*Carausel Fade has Dots*/
    $('.carausel-fade-2').each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = '#' + id;
        var appendArrowsClassName = '#' + id + '-arrows'

        $(sliderID).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            loop: true,
            dots: true,
            arrows: false,
            autoplay: true,
        });
    });



    /*---------------------
        Mobile menu active
    ------------------------ */
    var $offCanvasNav = $('.mobile-menu'),
        $offCanvasNavSubMenu = $offCanvasNav.find('.dropdown');

    /*Add Toggle Button With Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.parent().prepend('<span class="menu-expand">+</span>');

    /*Close Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.slideUp();

    /*Category Sub Menu Toggle*/
    $offCanvasNav.on('click', 'li a, li .menu-expand', function (e) {
        var $this = $(this);
        if (($this.parent().attr('class').match(/\b(menu-item-has-children|has-children|has-sub-menu)\b/)) && ($this.attr('href') === '#' || $this.hasClass('menu-expand'))) {
            e.preventDefault();
            if ($this.siblings('ul:visible').length) {
                $this.parent('li').removeClass('active');
                $this.siblings('ul').slideUp();
            } else {
                $this.parent('li').addClass('active');
                $this.closest('li').siblings('li').removeClass('active').find('li').removeClass('active');
                $this.closest('li').siblings('li').find('ul:visible').slideUp();
                $this.siblings('ul').slideDown();
            }
        }
    });

    /*------ ScrollUp -------- */
    $.scrollUp({
        scrollText: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });

    /*------ Wow Active ----*/
    new WOW().init();

    /*---- CounterUp ----*/
    $('.count').counterUp({
        delay: 10,
        time: 2000
    });
})(jQuery);

var tl = gsap.timeline();
tl.to("#ecd_card", {opacity:0, y: -100, duration: 1, delay:2});
// tl.to("#id", {y: 50, duration: 1});
// tl.to("#id", {opacity: 0, duration: 1});