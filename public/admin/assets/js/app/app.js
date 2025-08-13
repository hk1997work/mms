(function ($) {
    'use strict';
    $(document).on('mouseover', '[data-bs-toggle="popover"]', function () {
        $(this).popover();
        $(this).popover('show');
    });

    $('.off-sidebar').on('click', '.sidebar-close', function () {
        $(this).closest('.off-sidebar').removeClass('is-visible');
    });

    $('.dropdown').on('show.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(200);
    });

    $('.dropdown').on('hide.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
    });
})(jQuery);

function notifications(text) {
    new Noty({
        type: 'notification', layout: 'topRight', text: text, progressBar: true, timeout: 2000, animation: {
            open: 'animated bounceInRight', close: 'animated bounceOutRight',
        }
    }).show()
}

