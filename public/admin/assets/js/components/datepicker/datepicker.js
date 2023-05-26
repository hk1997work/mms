(function ($) {

    'use strict';

    // ------------------------------------------------------- //
    // Datepicker
    // ------------------------------------------------------ //
    $(function () {
        //default date range picker
        $('#daterange').daterangepicker({
            autoApply: true,
            locale: {
                format: 'YYYY-MM-DD'
            },
        });

        //date time picker
        $('#datetime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
                format: 'MM/DD/YYYY h:mm A'
            }
        });

        //single date
        $('#date').daterangepicker({
            singleDatePicker: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        //single date
        $('#verification_date').daterangepicker({
            singleDatePicker: true,
            container:'#verification_date',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });

})(jQuery);
