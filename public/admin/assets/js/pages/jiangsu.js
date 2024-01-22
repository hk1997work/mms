//加载生产厂家
$('.off-sidebar').on('change', '[name^="group["][name$="][tool_id]"]', function () {
    let form_group = $(this).closest('.form-group')
    if (form_group.find('[name^="group["][name$="][tool_id]"]').val() != null) {
        let path = "/certificate_factories/" + form_group.find('[name^="group["][name$="][tool_id]"]').val();
        form_group.find('.factory_add').attr("hidden", false);
        form_group.find('.factory_add').data('id', form_group.find('[name^="group["][name$="][tool_id]"]').val())
        form_group.find('.number_add').attr("hidden", true)
        form_group.find('.number_add').data('id', '')
        form_group.find('[name^="group["][name$="][factory_id]"]').empty();
        form_group.find('[name^="group["][name$="][factory_id]"]').append("<option value='' selected disabled>请选择...</option>");
        form_group.find('[name^="group["][name$="][number_id]"]').empty();
        form_group.find('[name^="group["][name$="][number_id]"]').append("<option value='' selected disabled>请选择...</option>");
        $.ajax({
            url: path,
            success: function (data) {
                if (data) {
                    if (data.length) {
                        for (const key in data) {
                            form_group.find('[name^="group["][name$="][factory_id]"]').append("<option value=" + data[key]['id'] + ">" + data[key]['factory'] + "</option>");
                            if (form_group.find(".ff").text().trim() == data[key]['factory'] || form_group.find(".ff").text().trim() == data[key]['fullname']) {
                                form_group.find('[name^="group["][name$="][factory_id]"]').val(data[key]['id']).trigger('change');
                            }
                        }
                    } else {
                        notifications('未录入生产厂家');
                    }
                } else {
                    notifications('生产厂家加载失败');
                }
            },
            error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications('生产厂家加载失败')
            }
        })
    }
})

//加载出厂编号
$('.off-sidebar').on('change', '[name^="group["][name$="][factory_id]"]', function () {
    let form_group = $(this).closest('.form-group')
    if (form_group.find('[name^="group["][name$="][factory_id]"]').val() != null) {
        let path = "/certificate_numbers/" + form_group.find('[name^="group["][name$="][factory_id]"]').val() + '?number_id=0';
        form_group.find('.number_add').attr("hidden", false);
        form_group.find('.number_add').data('id', form_group.find('[name^="group["][name$="][factory_id]"]').val())
        form_group.find('[name^="group["][name$="][number_id]"]').empty();
        form_group.find('[name^="group["][name$="][number_id]"]').append("<option value='' selected disabled>请选择...</option>");
        $.ajax({
            url: path,
            success: function (data) {
                if (data) {
                    if (data.length) {
                        for (const key in data) {
                            form_group.find('[name^="group["][name$="][number_id]"]').append("<option value=" + data[key]['id'] + ">" + data[key]['number'] + "</option>");
                            if (form_group.find(".nn").text().trim() == data[key]['number']) {
                                form_group.find('[name^="group["][name$="][number_id]"]').val(data[key]['id']).trigger('change');
                            }
                        }
                    } else {
                        notifications('未录入出厂编号');
                    }
                } else {
                    notifications('出厂编号加载失败');
                }
            },
            error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications('出厂编号加载失败')
            }
        })
    }
})

$('.off-sidebar').on('click', '.group-delete', function () {
    $(this).closest('.form-group').remove();
})
