$('[data-live-search="true"]').selectpicker();
$('.single-date').daterangepicker({
    singleDatePicker: true, autoApply: true, parentEl: $('.single-date').parent().parent().parent(), container: $('.single-date'),
});