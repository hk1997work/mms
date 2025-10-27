const option = "<option value='' selected disabled>请选择...</option>";
let offSidebar = $('.off-sidebar');
//加载序号
offSidebar.on('change', '[name="position_id"]', function () {
    let form = $(this).closest('form');
    $.ajax({
        url: `/certificate_sn/${form.find('[name="position_id"]').val()}`, success: function (data) {
            if (data) {
                form.find('[name="sn"]').val(data).trigger('change');
            } else {
                notifications('序号加载失败');
            }
        }, error: function (xhr) {
            xhr.status === 401 ? document.location.reload() : notifications('序号加载失败');
        }
    })
})

//加载量具信息
offSidebar.on('change', '[name="tool_id"]', function () {
    let form = $(this).closest('form');
    $.ajax({
        url: `/certificate_info/${form.find('[name="tool_id"]').val()}`, success: function (data) {
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
        }, error: function (xhr) {
            xhr.status === 401 ? document.location.reload() : notifications('器具名称加载失败');
        }
    })
})

//加载生产厂家
offSidebar.on('change', '[name="tool_id"]', function () {
    let form = $(this).closest('form');
    let tool_id = form.find('[name="tool_id"]').val();
    if (tool_id != null) {
        let path = `/certificate_factories/${tool_id}`;
        let factory_id = form.find('[name="factory_id"]');
        let factory_add = form.find('.factory_add');
        let factory_pdf = form.find('.factory_pdf');
        let factory_id_pdf = form.find('.factory_id_pdf').val();
        let number_id = form.find('[name="number_id"]');
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
                            if (factory_id_pdf == data[key]['id']) {
                                factory_id.val(factory_id_pdf).trigger('change');
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
offSidebar.on('change', '[name="factory_id"]', function () {
    let form = $(this).closest('form');
    let factory_id = form.find('[name="factory_id"]').val();
    if (factory_id != null) {
        let path = `/certificate_numbers/${factory_id}`;
        let number = form.find('[name="number"]');
        let number_id = form.find('[name="number_id"]');
        let number_add = form.find('.number_add');
        let number_pdf = form.find('.number_pdf');
        let number_id_pdf = form.find('.number_id_pdf').val();
        if (number.length > 0) {
            path = `${path}?number_id=${number.val()}`;
        }
        number_add.prop("hidden", false).data('id', `${factory_id}&name=${encodeURIComponent(number_pdf.text())}`);
        number_id.empty().append(option);
        $.ajax({
            url: path, success: function (data) {
                if (data) {
                    if (data.length) {
                        number_id.prop("disabled", false);
                        for (const key in data) {
                            number_id.append(`<option value="${data[key]['id']}">${data[key]['name']}</option>`);
                            if (number_id_pdf == data[key]['id']) {
                                number_id.val(number_id_pdf).trigger('change');
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

offSidebar.on('change', '[name="number_id"]', function () {
    let form = $(this).closest('form');
    let start = form.find('[name="start"]');
    let times = form.find('[name="times"]');
    let number_id = form.find('[name="number_id"]').val();
    start.val('');
    times.val('');
    if (number_id != null) {
        let path = `/certificate_number/${number_id}`;
        $.ajax({
            url: path, success: function (data) {
                if (data) {
                    start.val(data['start']).trigger('change');
                    times.val(data['times']).trigger('change');
                } else {
                    notifications('启动时间、检定次数加载失败');
                }
            }, error: function (xhr) {
                xhr.status === 401 ? document.location.reload() : notifications('启动时间、检定次数加载失败');
            }
        })
    }
})

//加载有效期
offSidebar.on('change', '[name="verification_date"],[name="cycle_id"]', function () {
    let form = $(this).closest('form');
    let date = new Date(form.find('[name="verification_date"]').val());
    let cycle_id = form.find('[name="cycle_id"]').val();
    let validity_date = form.find('[name="validity_date"]');
    let number = Number(cycle_id.match(/\d+/));
    let cycle = cycle_id.at(-1);
    if (cycle === '月') {
        date.setMonth(date.getMonth() + number);
        date.setDate(date.getDate() - 1);
        validity_date.val(date.toLocaleDateString('en-CA')).trigger('change');
        return;
    }
    if (cycle === '天') {
        date.setDate(date.getDate() + number);
        date.setDate(date.getDate() - 1);
        validity_date.val(date.toLocaleDateString('en-CA')).trigger('change');
        return;
    }
    validity_date.val("").trigger('change');
})

//上传文件
offSidebar.on('change', '[name="file_certificate"]', function () {
    let form = $(this).closest('form');
    form.find(".tool_pdf").remove();
    form.find(".model_pdf").remove();
    form.find(".factory_pdf").remove();
    form.find(".number_pdf").remove();
    form.find(".factory_id_pdf").remove();
    form.find(".number_id_pdf").remove();
    if (form.find('[name="file_certificate"]').val()) {
        $("#preloader").show();
        $.ajax({
            url: "/pdf", type: "POST", data: new FormData(form[0]), processData: false, contentType: false, success: function (result) {
                if (typeof result === 'object') {
                    if (result['exist']) {
                        form.find(".div-tool_id .form-label").append(" <small class='text-warning tool_pdf'>证书已录入</small>");
                        form.find('[name="file_certificate"]').val('');
                        $("#preloader").fadeOut();
                        return;
                    } else {
                        form.find(".div-tool_id .form-label").append(` <small class='text-info model_pdf'>${result['tool']}${result['model']}</small>`);
                        form.find(".div-factory_id .form-label").append(` <small class='text-info factory_pdf'>${result['factory']}</small>`);
                        form.find(".div-number_id .form-label").append(` <small class='text-info number_pdf'>${result['number']}</small>`);
                        form.find(`[name="category_id"] option:contains(${result['category']})`).prop("selected", true).trigger('change');
                        form.find(`[name="department_id"] option:contains(${result['department']})`).prop("selected", true).trigger('change');
                        form.find('[name="verification_date"]').val(result['verification_date']).trigger('change');
                        form.find('[name="certificate_no"]').val(result['certificate_no']).trigger('change');
                        form.find('[name="certificate_name"]').val(result['tool']).trigger('change');
                    }
                    if (result['number_id'] && form.find('[name="tool_id"] option').filter(function () {
                        return $(this).val() == result['tool_id'];
                    }).length > 0) {
                        form.find(".div-factory_id").append(`<input type='hidden' class='factory_id_pdf' value='${result['factory_id']}'>`);
                        form.find(".div-number_id").append(`<input type='hidden' class='number_id_pdf' value='${result['number_id']}'>`);
                        form.find('[name="tool_id"]').val(result['tool_id']).trigger('change');
                        form.find('[name="tool_id"]').selectpicker('refresh');
                    }
                    if (result['standard_id']) {
                        form.find('[name="standard_id[]"] option').prop("selected", '');
                        form.find('[name="standard_id[]"]').val(result['standard_id'].split(',')).trigger('change');
                        form.find('[name="standard_id[]"]').selectpicker('refresh');
                    }
                } else {
                    notifications(result);
                }
                $("#preloader").fadeOut();
            }, error: function (xhr) {
                xhr.status === 401 ? document.location.reload() : notifications('上传证书失败');
                $("#preloader").fadeOut();
            }
        });
    }
});
