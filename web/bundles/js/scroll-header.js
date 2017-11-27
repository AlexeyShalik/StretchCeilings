(function() {
    jQuery(document).ready(function ($) {
        var navbar = $('.navbar');
        var navbarBottom = navbar.offset().top;
        window.onscroll = function() {
            if (navbar.hasClass('navbar-fixed-top') && window.pageYOffset < navbarBottom) {
                navbar.addClass('navbar-static-top');
                navbar.removeClass('navbar-fixed-top');
            } else if (window.pageYOffset > navbarBottom) {
                navbar.addClass('navbar-fixed-top');
                navbar.removeClass('navbar-static-top');
            }
        };
    });
}());
