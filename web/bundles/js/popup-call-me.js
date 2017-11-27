(function() {
    jQuery(document).ready(function ($) {
        var callMe = $('.call-me');
        var close = $('.close');
        var popup = $('.popup');

        callMe.on('click', function () {
            $('#popup-background').attr('hidden', false);
            $('.fluir').css('filter', 'blur(5px)');
            popup.attr('hidden', false);
        });

        close.on('click', function () {
            $('#popup-background').attr('hidden', 'hidden');
            $('.fluir').css('filter', 'unset');
            popup.attr('hidden', 'hidden');
        });

        $('.phone').mask("+375 (99) 999-99-99");
    });
}());
