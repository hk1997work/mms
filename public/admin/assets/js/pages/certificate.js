//日期格式
Date.prototype.format = function (fmt) {
    let o = {
        "M+": this.getMonth() + 1,                 //月
        "d+": this.getDate(),                    //日
    };
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (let k in o) {
        if (new RegExp("(" + k + ")").test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        }
    }
    return fmt;
}

//加载序号
$('.off-sidebar').on('change', '[name="position_id"]', function () {
    let form = $(this).closest('form')
    $.ajax({
        url: "/certificate_sn/" + form.find('[name="position_id"]').val(),
        success: function (data) {
            if (data) {
                form.find('[name="sn"]').val(data).trigger('change');
            } else {
                notifications('序号加载失败');
            }
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('序号加载失败')
        }
    })
})

//加载量具信息
$('.off-sidebar').on('change', '[name="tool_id"]', function () {
    let form = $(this).closest('form')
    $.ajax({
        url: "/certificate_info/" + form.find('[name="tool_id"]').val(),
        success: function (data) {
            if (data) {
                form.find('[name="model"]').val(data['model']);
                form.find('[name="limit"]').val(data['limit']);
                form.find('[name="accuracy"]').val(data['accuracy']);
                form.find('[name="cycle_id"]').val(data['cycle']).trigger('change');
                form.find('[name="abc_id"]').val(data['abc']);
                form.find('[name="plan_id"]').val(data['plan']);
            } else {
                notifications('器具名称加载失败');
            }
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('器具名称加载失败')
        }
    })
})

//加载生产厂家
$('.off-sidebar').on('change', '[name="tool_id"]', function () {
    let form = $(this).closest('form')
    if (form.find('[name="tool_id"]').val() != null) {
        let path = "/certificate_factories/" + form.find('[name="tool_id"]').val();
        form.find('.factory_add').attr("hidden", false);
        form.find('.factory_add').data('id', form.find('[name="tool_id"]').val())
        form.find('.number_add').attr("hidden", true)
        form.find('.number_add').data('id', '')
        form.find('[name="number_id"]').attr("disabled", true)
        form.find('[name="factory_id"]').empty();
        form.find('[name="factory_id"]').append("<option value='' selected disabled>请选择...</option>");
        form.find('[name="number_id"]').empty();
        form.find('[name="number_id"]').append("<option value='' selected disabled>请选择...</option>");
        $.ajax({
            url: path,
            success: function (data) {
                if (data) {
                    if (data.length) {
                        form.find('[name="factory_id"]').attr("disabled", false);
                        for (const key in data) {
                            form.find('[name="factory_id"]').append("<option value=" + data[key]['id'] + ">" + data[key]['factory'] + "</option>");
                            if (form.find(".ff").val() == data[key]['id']) {
                                form.find('[name="factory_id"]').val(form.find(".ff").val()).trigger('change');
                            }
                        }
                    } else {
                        form.find('[name="factory_id"]').attr("disabled", true)
                        notifications('未录入生产厂家');
                    }
                } else {
                    form.find('[name="factory_id"]').attr("disabled", true)
                    notifications('生产厂家加载失败');
                }
            },
            error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications('生产厂家加载失败')
                form.find('[name="factory_id"]').attr("disabled", true)
            }
        })
    }
})

//加载出厂编号
$('.off-sidebar').on('change', '[name="factory_id"]', function () {
    let form = $(this).closest('form')
    if (form.find('[name="factory_id"]').val() != null) {
        let path = "/certificate_numbers/" + form.find('[name="factory_id"]').val();
        if (form.find('[name="number"]').length > 0) {
            path = path + "?number_id=" + form.find('[name="number"]').val();
        }
        form.find('.number_add').attr("hidden", false);
        form.find('.number_add').data('id', form.find('[name="factory_id"]').val())
        form.find('[name="number_id"]').empty();
        form.find('[name="number_id"]').append("<option value='' selected disabled>请选择...</option>");
        $.ajax({
            url: path,
            success: function (data) {
                if (data) {
                    if (data.length) {
                        form.find('[name="number_id"]').attr("disabled", false);
                        for (const key in data) {
                            form.find('[name="number_id"]').append("<option value=" + data[key]['id'] + ">" + data[key]['number'] + "</option>");
                            if (form.find(".nn").val() == data[key]['id']) {
                                form.find('[name="number_id"]').val(form.find(".nn").val()).trigger('change');
                            }
                        }
                    } else {
                        form.find('[name="number_id"]').attr("disabled", true)
                        notifications('未录入出厂编号');
                    }
                } else {
                    form.find('[name="number_id"]').attr("disabled", true)
                    notifications('出厂编号加载失败');
                }
            },
            error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications('出厂编号加载失败')
                form.find('[name="number_id"]').attr("disabled", true)
            }
        })
    }
})

$('.off-sidebar').on('change', '[name="number_id"]', function () {
    let form = $(this).closest('form')
    form.find('[name="start"]').val('');
    form.find('[name="times"]').val('');
    if (form.find('[name="number_id"]').val() != null) {
        let path = "/certificate_number/" + form.find('[name="number_id"]').val();
        $.ajax({
            url: path,
            success: function (data) {
                if (data) {
                    form.find('[name="start"]').val(data['start']);
                    form.find('[name="times"]').val(data['times']);
                } else {
                    notifications('启动时间、检定次数加载失败');
                }
            },
            error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications('启动时间、检定次数加载失败');
            }
        })
    }
})

//加载有效期
$('.off-sidebar').on('change', '[name="verification_date"],[name="cycle_id"]', function () {
    let form = $(this).closest('form')
    let date = new Date(form.find('[name="verification_date"]').val());
    let number = Number(form.find('[name="cycle_id"]').val().match(/\d+/));
    let cycle = form.find('[name="cycle_id"]').val().substr(length - 1, 1);
    if (cycle == '月') {
        date.setMonth(date.getMonth() + number);
        date.setDate(date.getDate() - 1);
        form.find('[name="validity_date"]').val(date.format("yyyy-MM-dd")).trigger('change');
        return
    }
    if (cycle == '天') {
        date.setDate(date.getDate() + number);
        date.setDate(date.getDate() - 1);
        form.find('[name="validity_date"]').val(date.format("yyyy-MM-dd")).trigger('change');
        return
    }
    form.find('[name="validity_date"]').val("").trigger('change');
})

//上传文件
$('.off-sidebar').on('change', '[name="file_certificate"]', function () {
    let form = $(this).closest('form')
    form.find(".t").remove()
    form.find(".m").remove()
    form.find(".f").remove()
    form.find(".n").remove()
    form.find(".ff").remove()
    form.find(".nn").remove()
    if (form.find('[name="file_certificate"]').val()) {
        $("#preloader")[0].style.display = 'block';
        $.ajax({
            url: "/pdf",
            type: "POST",
            data: new FormData(form[0]),
            processData: false,  // 不处理数据
            contentType: false,   // 不设置内容类型
            success: function (result) {
                if (typeof result === 'object') {
                    if (result['standard_id']) {
                        form.find('[name="standard_id[]"] option').prop("selected", '');
                        form.find('[name="standard_id[]"]').val(result['standard_id'].split(',')).trigger('change');
                        form.find('[name="standard_id[]"]').selectpicker('refresh');
                    }
                    if (result['exist']) {
                        form.find(".div-tool_id").find('.sidebar-heading').append(" <small class='text-warning' id='t'>证书已录入</small>");
                        form.find('[name="file_certificate"]').val('');
                    } else {
                        form.find(".div-tool_id .sidebar-heading").append(" <small class='text-info m'>" + result['tool'] + result['model'] + "</small>");
                        form.find(".div-factory_id .sidebar-heading").append(" <small class='text-info f'>" + result['factory'] + "</small>");
                        form.find(".div-number_id .sidebar-heading").append(" <small class='text-info n'>" + result['number'] + "</small>");
                        form.find('[name="category_id"] option:contains(' + result['category'].substring(0, 4) + ")").attr("selected", true).trigger('change');
                        form.find('[name="department_id"] option:contains(' + result['department'] + ")").attr("selected", true).trigger('change');
                        form.find('[name="verification_date"]').val(result['verification_date']).trigger('change');
                        form.find('[name="certificate_no"]').val(result['certificate_no']).trigger('change');
                        form.find('[name="certificate_name"]').val(result['tool']).trigger('change');
                    }
                    if (result['number_id'] && form.find('[name="tool_id"] option').filter(function () {
                        return $(this).val() == result['tool_id']
                    }).length > 0) {
                        form.find('[name="tool_id"]').selectpicker('val', result['tool_id'])
                        form.find(".div-factory_id").append("<input type='hidden' class='ff' value='" + result['factory_id'] + "'>");
                        form.find(".div-number_id").append("<input type='hidden' class='nn' value='" + result['number_id'] + "'>");
                    }
                } else {
                    notifications(result)
                }
                $("#preloader").fadeOut();
            },
            error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications('上传证书失败')
                $("#preloader").fadeOut();
            }
        });
    }
});
