const option = "<option value='' selected disabled>请选择...</option>";
let offSidebar = $('.off-sidebar');
//加载生产厂家
offSidebar.on('change', '[name$="[tool_id]"]', function () {
    let form = $(this).closest('.row');
    let tool_id = form.find('[name$="[tool_id]"]').val();
    if (tool_id != null) {
        let path = `/certificate_factories/${tool_id}`;
        let factory_id = form.find('[name$="[factory_id]"]');
        let factory_add = form.find('.factory_add');
        let factory_pdf = form.find('.factory_pdf');
        let number_id = form.find('[name$="[number_id]"]');
        let number_add = form.find('.number_add');
        factory_add.prop("hidden", false).data('id', `${tool_id}&name=${encodeURIComponent(factory_pdf.text())}`);
        number_add.prop("hidden", true).data('id', '');
        factory_id.empty().append(option);
        number_id.empty().append(option).prop("disabled", true);
        $.ajax({
            url: path, success: function (data) {
                if (data) {
                    if (data.length) {
                        factory_id.prop("disabled", false);
                        for (const key in data) {
                            factory_id.append(`<option value="${data[key]['id']}">${data[key]['name']}</option>`);
                            if (factory_pdf.text().trim() === data[key]['name'] || factory_pdf.text().trim() === data[key]['remark']) {
                                factory_id.val(data[key]['id']).trigger('change');
                            }
                        }
                    } else {
                        notifications('未录入生产厂家');
                    }
                } else {
                    factory_id.prop("disabled", true);
                    notifications('生产厂家加载失败');
                }
            }, error: function (xhr) {
                xhr.status === 401 ? document.location.reload() : notifications('生产厂家加载失败');
                factory_id.prop("disabled", true);
            }
        })
    }
})

//加载出厂编号
offSidebar.on('change', '[name$="[factory_id]"]', function () {
    let form = $(this).closest('.row');
    let factory_id = form.find('[name$="[factory_id]"]').val();
    if (factory_id != null) {
        let path = `/certificate_numbers/${factory_id}?number_id=0`;
        let number_id = form.find('[name$="[number_id]"]');
        let number_add = form.find('.number_add');
        let number_pdf = form.find('.number_pdf');
        number_add.prop("hidden", false).data('id', `${factory_id}&name=${encodeURIComponent(number_pdf.text())}`);
        number_id.empty().append(option);
        $.ajax({
            url: path, success: function (data) {
                if (data) {
                    if (data.length) {
                        number_id.prop("disabled", false);
                        for (const key in data) {
                            number_id.append(`<option value="${data[key]['id']}">${data[key]['name']}</option>`);
                            if (number_pdf.text().trim() === data[key]['name']) {
                                number_id.val(data[key]['id']).trigger('change');
                            }
                        }
                    } else {
                        notifications('未录入出厂编号');
                    }
                } else {
                    number_id.prop("disabled", true);
                    notifications('出厂编号加载失败');
                }
            }, error: function (xhr) {
                xhr.status === 401 ? document.location.reload() : notifications('出厂编号加载失败');
                number_id.prop("disabled", true);
            }
        })
    }
})

offSidebar.on('click', '.group-delete', function () {
    $(this).closest('.row').remove();
})
