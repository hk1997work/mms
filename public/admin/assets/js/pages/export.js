$('#daterange').daterangepicker({
    autoApply: true,
});
$('#check_daterange').change(function () {
    $('#daterange').prop('disabled', !this.checked);
});