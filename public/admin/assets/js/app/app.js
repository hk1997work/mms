(function ($) {
    'use strict';
    $(window).on("load", function () {
        if (document.querySelectorAll('table').length == 0 && document.getElementsByClassName('swiper').length == 0) {
            $("#preloader").fadeOut();
        }
    });

    $(document).on('mouseover', '[data-bs-toggle="popover"]', function () {
        $(this).popover();
        $(this).popover('show');
    });

    $('.off-sidebar').on('click', '.sidebar-close', function () {
        $(this).closest('.off-sidebar').removeClass('is-visible');
    });
})(jQuery);

function notifications(text) {
    new Noty({
        type: 'notification', layout: 'topRight', text: text, progressBar: true, timeout: 2000, animation: {
            open: 'animated bounceInRight', close: 'animated bounceOutRight',
        }
    }).show()
}

