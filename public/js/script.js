jQuery(function () {
    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 80) {
                $('#scrollUp').css('left', '2rem');
            } else {
                $('#scrollUp').removeAttr('style');
            }
        });
    });
});