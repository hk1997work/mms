$('.off-sidebar').on('change', '[name="position_id"]', function () {
    let form = $(this).closest('form');
    $.ajax({
        url: `/certificate_sn/${form.find('[name="position_id"]').val()}`, success: function (data) {
            data ? form.find('[name="sn"]').val(data).trigger('change') : notifications('序号加载失败');
        }, error: function (xhr) {
            xhr.status === 401 ? document.location.reload() : notifications('序号加载失败');
        }
    })
})