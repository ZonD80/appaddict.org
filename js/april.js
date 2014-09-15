function rotate(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

$(document).ready(function() {
    $('*').each(function() {

        var rot = rotate(-2, 2);
        $(this).css({
            '-webkit-transform': 'rotate(' + rot + 'deg)',
            '-moz-transform': 'rotate(' + rot + 'deg)',
            '-ms-transform': 'rotate(' + rot + 'deg)',
            '-o-transform': 'rotate(' + rot + 'deg)',
            'transform': 'rotate(' + rot + 'deg)'

        });
    });
});