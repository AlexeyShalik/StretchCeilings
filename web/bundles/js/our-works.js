(function() {
    jQuery(document).ready(function ($) {
        var box = $('.box');
        var numberImage = 1;
        var left = $('.glyphicon-chevron-left');
        var right = $('.glyphicon-chevron-right');
        var path;

        box.on('click', function (event) {
            numberImage = event.target.getAttribute('data-number') - 1;

            $('.black-background').attr('hidden', false);

            path = event.target.style.backgroundImage.split('"')[1];
            createSlider(path);
        });

        $('.black-background').on('click', function () {
            $('.black-background').attr('hidden', 'hidden');
            $('.div-black-background-image').attr('hidden', 'hidden');
        });

        left.on('click', function () {
            numberImage = parseInt(numberImage) - 1;
            if (numberImage < 0) {
                numberImage = box.length - 1;
            }
            path = box[numberImage].style.backgroundImage.split('"')[1];
            createSlider(path);
        });

        right.on('click', function () {
            numberImage = parseInt(numberImage) + 1;
            if (numberImage > box.length - 1) {
                numberImage = 0;
            }
            path = box[numberImage].style.backgroundImage.split('"')[1];
            createSlider(path);
        });

        var createSlider = function (path) {
            var image = $('.black-background-image');
            image.attr('src', path);
            $('.div-black-background-image').attr('hidden', false);
            image.css('left', 'calc((100% - ' + image.css('width') + ')/2)');
            image.css('top', 'calc((100% - ' + image.css('height') + ')/2)');

            left.css('left', image.css('left'));
            right.css('right', image.css('right'));
        }
    });
}());