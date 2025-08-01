(function ($) {

    'use strict';

    // ------------------------------------------------------- //
    // Datepicker
    // ------------------------------------------------------ //
    $(function () {
        $('#verification_date').daterangepicker({
            singleDatePicker: true,
            container:'#verification_date',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
})(jQuery);
