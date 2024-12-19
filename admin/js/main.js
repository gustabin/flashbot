$(document).ready(function () {
    "use strict";

    // Ajusta la altura de los elementos con la clase js-fullheight
    var fullHeight = function () {
        $('.js-fullheight').css('height', $(window).height());
        $(window).resize(function () {
            $('.js-fullheight').css('height', $(window).height());
        });
    };
    fullHeight();
});
