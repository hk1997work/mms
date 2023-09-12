function load_factories(id) {
    var tool_id = $("#tool_id" + id).val();
    $.ajax({
        url: "/certificate_factories/" + tool_id,
        success: function (data) {
            if (data) {
                $("#factory_id" + id).empty();
                $("#factory_id" + id).parent().parent().addClass('has-danger');
                $("#factory_id" + id).append("<option value='' selected disabled>请选择...</option>");
                $("#number_id" + id).empty();
                $("#number_id" + id).parent().parent().addClass('has-danger');
                $("#number_id" + id).append("<option value='' selected disabled>请选择...</option>");
                if (data.length) {
                    for (var key in data) {
                        $("#factory_id" + id).append("<option value=" + data[key]['id'] + ">" + data[key]['factory'] + "</option>");
                    }
                    if ($("#ff" + id).val()) {
                        $("#factory_id" + id).val($("#ff" + id).val())
                        $("#ff" + id).remove()
                        load_numbers(id)
                    }
                } else {
                    notifications('未录入生产厂家');
                    factory_id_click(id);
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

function load_numbers(id) {
    var factory_id = $("#factory_id" + id).val();
    $.ajax({
        url: "/nj/number/" + factory_id,
        success: function (data) {
            if (data) {
                $("#number_id" + id).empty();
                $("#number_id" + id).parent().parent().addClass('has-danger');
                $("#number_id" + id).append("<option value='' selected disabled>请选择...</option>");
                if (data.length) {
                    for (var key in data) {
                        $("#number_id" + id).append("<option value=" + data[key]['id'] + ">" + data[key]['number'] + "</option>");
                    }
                    if ($("#nn" + id).val()) {
                        $("#number_id" + id).val($("#nn" + id).val())
                        $("#nn" + id).remove()
                    }
                } else {
                    notifications('未录入出厂编号');
                    number_id_click(id)
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

function factory_id_click(id) {
    if ($("#tool_id" + id).val() != null) {
        if ($("#factory_id_btn" + id).text() == $("#number_id_btn" + id).text()) {
            number_id_click(id);
        }
        if ($("#factory_id_btn" + id).text() == '返回') {
            $("#factory_id" + id).replaceWith("<select name='factory_id[" + id + "]' id='factory_id" + id + "' class='custom-select form-control' onchange='load_numbers(" + id + ")'><option value='' selected>请选择...</option></select>");
            $("#factory_id_btn" + id).replaceWith("<span class='input-group-addon addon-primary' id='factory_id_btn" + id + "' onclick='factory_id_click(" + id + ")'>增加</span>");
            load_factories(id);
        } else {
            $("#factory_id" + id).replaceWith("<input type='text' name='factory_id[" + id + "]' id='factory_id" + id + "' class='form-control'>");
            $("#factory_id_btn" + id).replaceWith("<span class='input-group-addon addon-orange' id='factory_id_btn" + id + "' onclick='factory_id_click(" + id + ")'>返回</span>");
        }
    }
}

function number_id_click(id) {
    if ($("#tool_id" + id).val() != null) {
        if ($("#number_id_btn" + id).text() == '返回' && $("#factory_id_btn" + id).text() != '返回') {
            $("#number_id" + id).replaceWith("<select name='number_id[" + id + "]' id='number_id" + id + "' class='custom-select form-control'><option value='' selected>请选择...</option></select>");
            $("#number_id_btn" + id).replaceWith("<span class='input-group-addon addon-primary' id='number_id_btn" + id + "' onclick='number_id_click(" + id + ")'>增加</span>");
            load_numbers(id);
        } else {
            $("#number_id" + id).replaceWith("<input type='text' name='number_id[" + id + "]' id='number_id" + id + "' class='form-control'>");
            $("#number_id_btn" + id).replaceWith("<span class='input-group-addon addon-orange' id='number_id_btn" + id + "' onclick='number_id_click(" + id + ")'>返回</span>");
        }
    }
}
