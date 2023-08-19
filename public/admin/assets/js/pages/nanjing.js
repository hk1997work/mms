function load_info(order) {
    var tool_id = $("#tool_id" + order).val();
    $.ajax({
        url: "/certificate_info/" + tool_id,
        success: function (data) {
            if (data) {
                $("#factory_id" + order).empty();
                $("#factory_id" + order).append("<option value='' selected disabled>请选择...</option>");
                $("#number_id" + order).empty();
                $("#number_id" + order).append("<option value='' selected disabled>请选择...</option>");
                load_standard(order);
                if (data['factories'].length) {
                    for (var key in data['factories']) {
                        $("#factory_id" + order).append("<option value=" + data['factories'][key]['id'] + ">" + data['factories'][key]['factory'] + "</option>");
                    }
                    if ($("#ff" + order).val()) {
                        $("#factory_id" + order).val($("#ff" + order).val())
                        $("#ff" + order).remove()
                        load_numbers(order)
                    }
                } else {
                    notifications('未录入生产厂家');
                    factory_id_click(order);
                }
            } else {
                notifications('器具名称加载失败');
            }
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('器具名称加载失败')
        }
    })
}

function load_numbers(order) {
    var factory_id = $("#factory_id" + order).val();
    $.ajax({
        url: "/nj/number/" + factory_id,
        success: function (data) {
            if (data) {
                $("#number_id" + order).empty();
                $("#number_id" + order).append("<option value='' selected disabled>请选择...</option>");
                if (data.length) {
                    for (var key in data) {
                        $("#number_id" + order).append("<option value=" + data[key]['id'] + ">" + data[key]['number'] + "</option>");
                    }
                    if ($("#nn" + order).val()) {
                        $("#number_id" + order).val($("#nn" + order).val())
                        $("#nn" + order).remove()
                    }
                } else {
                    notifications('未录入出厂编号');
                }
            } else {
                notifications('数据加载失败');
            }
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('出厂编号加载失败')
        }
    })
}

function factory_id_click(order) {
    if ($("#tool_id" + order).val() != null) {
        if ($("#factory_id_btn" + order).text() == $("#number_id_btn" + order).text()) {
            number_id_click(order);
        }
        if ($("#factory_id_btn" + order).text() == '返回') {
            $("#factory_id" + order).replaceWith("<select name='factory_id[" + order + "]' id='factory_id" + order + "' class='custom-select form-control' onchange='load_numbers(" + order + ")'><option value='' selected>请选择...</option></select>");
            $("#factory_id_btn" + order).replaceWith("<span class='input-group-addon addon-primary' id='factory_id_btn" + order + "' onclick='factory_id_click(" + order + ")'>增加</span>");
            load_info(order);
        } else {
            $("#factory_id" + order).replaceWith("<input type='text' name='factory_id[" + order + "]' id='factory_id" + order + "' class='form-control'>");
            $("#factory_id_btn" + order).replaceWith("<span class='input-group-addon addon-orange' id='factory_id_btn" + order + "' onclick='factory_id_click(" + order + ")'>返回</span>");
        }
    }
}

function number_id_click(order) {
    if ($("#tool_id" + order).val() != null) {
        if ($("#number_id_btn" + order).text() == '返回' && $("#factory_id_btn" + order).text() != '返回') {
            $("#number_id" + order).replaceWith("<select name='number_id[" + order + "]' id='number_id" + order + "' class='custom-select form-control'><option value='' selected>请选择...</option></select>");
            $("#number_id_btn" + order).replaceWith("<span class='input-group-addon addon-primary' id='number_id_btn" + order + "' onclick='number_id_click(" + order + ")'>增加</span>");
            load_numbers(order);
        } else {
            $("#number_id" + order).replaceWith("<input type='text' name='number_id[" + order + "]' id='number_id" + order + "' class='form-control'>");
            $("#number_id_btn" + order).replaceWith("<span class='input-group-addon addon-orange' id='number_id_btn" + order + "' onclick='number_id_click(" + order + ")'>返回</span>");
        }
    }
}

function load_standard(order) {
    $.ajax({
        url: "/certificate_standard/" + $("#tool_id" + order).val(),
        success: function (data) {
            $('#standard_id' + order + ' option').prop("selected", '');
            if (data) {
                $('#standard_id' + order).val(data.split(','));
                $('#standard_id' + order).closest('.col-sm-12').find(".warning-danger").remove()
            }
            $('#standard_id' + order).selectpicker('refresh');
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('检定标准加载失败')
        }
    })
}
