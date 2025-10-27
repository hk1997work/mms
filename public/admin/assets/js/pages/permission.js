$('.tab-pane .btn-check').change(function () {
    let id = $(this).attr('id');
    let pid = $(this).data('pid');
    let ppid = $(this).data('ppid');
    let level = $(this).data('level');
    $(`[data-pid="${id}"]`).prop('checked', this.checked);
    $(`[data-ppid="${id}"]`).prop('checked', this.checked);
    if (this.checked) {
        $(`#${pid}`).prop('checked', this.checked);
        $(`#${ppid}`).prop('checked', this.checked);
    }
    if (level === 2 && $(`[data-pid="${pid}"]:checked`).length === 0) {
        $(`#${pid}`).prop('checked', false);
    }
})