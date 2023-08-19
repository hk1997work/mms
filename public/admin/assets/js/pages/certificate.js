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

$(".modal-body input").change(function () {
    $(this).closest('.col-sm-12').find(".warning-danger").remove()
})
$(".modal-body select").change(function () {
    $(this).closest('.col-sm-12').find(".warning-danger").remove()
})

//加载有效期
function load_validity_date() {
    let date = new Date($("#verification_date").val());
    let number = Number($("#cycle_id").val().match(/\d+/));
    let cycle = $("#cycle_id").val().substr(length - 1, 1);
    if (cycle == '月') {
        date.setMonth(date.getMonth() + number);
        date.setDate(date.getDate() - 1);
        $("#validity_date").val(date.format("yyyy-MM-dd"));
        return
    }
    if (cycle == '天') {
        date.setDate(date.getDate() + number);
        date.setDate(date.getDate() - 1);
        $("#validity_date").val(date.format("yyyy-MM-dd"));
        return
    }
    $("#validity_date").val("");
}

//加载序号
function load_sn() {
    $.ajax({
        url: "/certificate_sn/" + $("#position_id").val(),
        success: function (data) {
            if (data) {
                $("#sn").val(data);
                $("#sn").closest('.col-sm-12').find(".warning-danger").remove()
            } else {
                notifications('序号加载失败');
            }
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('序号加载失败')
        }
    })
}

//加载检定标准
function load_standard() {
    $.ajax({
        url: "/certificate_standard/" + $("#tool_id").val(),
        success: function (data) {
            $('#standard_id option').prop("selected", '');
            if (data) {
                $('#standard_id').val(data.split(','));
                $('#standard_id').closest('.col-sm-12').find(".warning-danger").remove()
            }
            $('#standard_id').selectpicker('refresh');
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('检定标准加载失败')
        }
    })
}

//加载量具信息、出厂编号
function load_info() {
    if ($('#factory_id').attr("disabled")) {
        $('#factory_id').attr("disabled", false);
    }
    $.ajax({
        url: "/certificate_info/" + $("#tool_id").val(),
        success: function (data) {
            if (data) {
                $("#model").val(data['tool']['model']);
                $("#limit").val(data['tool']['limit']);
                $("#accuracy").val(data['tool']['accuracy']);
                $("#cycle_id").val(data['tool']['cycle']);
                $("#abc_id").val(data['tool']['abc']);
                $("#plan_id").val(data['tool']['plan']);

                $("#factory_id").empty();
                $("#factory_id").append("<option value='' selected disabled>请选择...</option>");
                $("#number_id").empty();
                $("#number_id").append("<option value='' selected disabled>请选择...</option>");

                load_validity_date();
                load_standard();

                if (data['factories'].length) {
                    for (const key in data['factories']) {
                        $("#factory_id").append("<option value=" + data['factories'][key]['id'] + ">" + data['factories'][key]['factory'] + "</option>");
                    }
                    if ($("#ff").val()) {
                        $("#factory_id").val($("#ff").val())
                        $("#ff").remove()
                    }
                    $("#number").length > 0 ? load_numbers($("#number").val()) : load_numbers()
                } else {
                    notifications('未录入生产厂家');
                    factory_id_click();
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

//加载出厂编号
function load_numbers(number_id) {
    if ($("#factory_id").val() != null) {
        $('#number_id').attr("disabled", false);
        let path = "/certificate_numbers/" + $("#factory_id").val();
        if (number_id) {
            path = path + "?number_id=" + number_id;
        }
        $.ajax({
            url: path,
            success: function (data) {
                if (data) {
                    $("#number_id").empty();
                    $("#number_id").append("<option value='' selected disabled>请选择...</option>");
                    if (data.length) {
                        for (const key in data) {
                            $("#number_id").append("<option value=" + data[key]['id'] + ">" + data[key]['number'] + "</option>");
                        }
                        if ($("#nn").val()) {
                            $("#number_id").val($("#nn").val())
                            $("#nn").remove()
                        }
                    } else {
                        notifications('未录入出厂编号');
                        number_id_click();
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
}

//生产厂家按键
function factory_id_click(number_id) {
    if ($("#tool_id").val() != null) {
        if ($("#factory_id_btn").text() == $("#number_id_btn").text()) {
            number_id_click(number_id);
        }
        if ($("#factory_id_btn").text() == '返回') {
            $("#factory_id").replaceWith("<select name='factory_id' id='factory_id' class='custom-select form-control' onchange='load_numbers(" + number_id + ")'><option value='' selected disabled>请选择...</option></select>");
            $("#factory_id_btn").replaceWith("<span class='input-group-addon addon-primary' id='factory_id_btn' onclick='factory_id_click(" + number_id + ")'>增加</span>");
            load_info();
        } else {
            $("#factory_id").replaceWith("<input type='text' name='factory_id' id='factory_id' class='form-control'>");
            $("#factory_id_btn").replaceWith("<span class='input-group-addon addon-orange' id='factory_id_btn' onclick='factory_id_click(" + number_id + ")'>返回</span>");
        }
    }
}

//出厂编号按键
function number_id_click(number_id) {
    if ($("#tool_id").val() != null) {
        if ($("#number_id_btn").text() == '返回' && $("#factory_id_btn").text() != '返回') {
            $("#number_id").replaceWith("<select name='number_id' id='number_id' class='custom-select form-control' onchange='load_validity_date()'><option value='' selected disabled>请选择...</option></select>");
            $("#number_id_btn").replaceWith("<span class='input-group-addon addon-primary' id='number_id_btn' onclick='number_id_click(" + number_id + ")'>增加</span>");
            load_numbers(number_id);
        } else {
            $("#number_id").replaceWith("<input type='text' name='number_id' id='number_id' class='form-control'>");
            $("#number_id_btn").replaceWith("<span class='input-group-addon addon-orange' id='number_id_btn' onclick='number_id_click(" + number_id + ")'>返回</span>");
        }
    }
}

//上传文件
function file() {
    $('#btn_certificate').click(function () {
        $('#file_certificate').click();
    });
    $("#file_certificate").on("change", function (e) {
        $("#t").remove()
        $("#m").remove()
        $("#f").remove()
        $("#n").remove()
        const file = e.target.files[0];
        if ($("#file_certificate").val() == '') {
            $("#text_certificate").text('');
        } else {
            $("#text_certificate").text(file.name);
            $("#preloader")[0].style.display = 'block';
            $.ajax({
                url: "/pdf",
                type: "POST",
                data: new FormData($("#form")[0]),
                processData: false,  // 不处理数据
                contentType: false,   // 不设置内容类型
                success: function (result) {
                    if ($.isArray(result)) {
                        if (result[0] == 'nj') {
                            nanjing_post($.base64.encode(JSON.stringify({"id": result[1]}), "utf-8"))
                        } else if (result[0] == 'js_old') {
                            load_pdf_info(result[1])
                        } else if (result[0] == 'js_new') {
                            jiangsu_new_post(result[1])
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
}

function nanjing_post(parseData) {
    $.ajax({
        type: "POST",
        url: "https://lims.njsjly.com/cmiims/f/sys/webQuery/inquiryByIdInfo",
        headers: {'Content-Type': 'application/json'},
        data: parseData,
        dataType: "text",
        success: function (data) {
            const jsonData = JSON.parse($.base64.decode(data, "utf-8"));
            const arr = [];
            if (jsonData.success) {
                arr['category'] = jsonData.certificateInfo.zslx
                arr['tool'] = jsonData.certificateInfo.qj
                arr['model'] = jsonData.certificateInfo.xhgg
                arr['factory'] = jsonData.certificateInfo.zzcs
                arr['number'] = ((jsonData.certificateInfo.ccbh == null || jsonData.certificateInfo.ccbh == '/') ? "" : jsonData.certificateInfo.ccbh) + ((jsonData.certificateInfo.sbbh == null || jsonData.certificateInfo.sbbh == '/') ? '' : jsonData.certificateInfo.sbbh)
                arr['certificate_no'] = jsonData.certificateInfo.zs_bh
                arr['department'] = "市计量院"
                arr['verification_date'] = jsonData.certificateInfo.jd_rq
                load_pdf_info(arr)
            }
        },
        error: function () {
            notifications('南京市计量院网络连接失败')
        }
    })
}

function jiangsu_new_post(parseData) {
    $.ajax({
        type: "GET",
        url: "https://serv.jsmi.com.cn/admin/zs/getByZshEwm/" + parseData,
        success: function (data) {
            const arr = [];
            if (data.data) {
                arr['category'] = data.data.zsZslx
                arr['tool'] = data.data.zsQjmc
                arr['model'] = data.data.zsXhgg
                arr['factory'] = data.data.zsZzc
                arr['number'] = ((data.data.zsCcbh == null || data.data.zsCcbh == '/') ? "" : data.data.zsCcbh) + ((data.data.zsSbbh == null || data.data.zsSbbh == '/') ? "" : data.data.zsSbbh)
                arr['certificate_no'] = data.data.zsZsh
                arr['department'] = "省计量院"
                arr['verification_date'] = data.data.zsJdrq
                load_pdf_info(arr)
            }
        },
        error: function () {
            notifications('江苏省计量院网络连接失败')
        }
    })
}

function load_pdf_info(data) {
    $.ajax({
        url: "/certificate_no/" + data.certificate_no,
        success: function (i) {
            if (i) {
                if (data.certificate_no == $("#certificate_no").val()) {
                    $("#tool_id").parents('.col-xl-4').append("<div class='text-warning' id='t'>证书补录</div>");
                } else {
                    $("#tool_id").parents('.col-xl-4').append("<div class='text-warning' id='t'>证书已录入</div>");
                    $("#file_certificate").val('');
                    $("#text_certificate").text('');
                }
                $("#tool_id").parents('.col-xl-4').append("<div class='text-info' id='m'>" + data.tool + data.model + "</div>");
                $("#factory_id").parents('.col-xl-4').append("<div class='text-info' id='f'>" + data.factory + "</div>");
                $("#number_id").parents('.col-xl-4').append("<div class='text-info' id='n'>" + data.number + "</div>");
            } else {
                $("#category_id").find("option:contains(" + data.category.substring(0, 4) + ")").attr("selected", true);
                $("#tool_id").parents('.col-xl-4').append("<div class='text-info' id='t'>" + data.tool + "</div>");
                $("#tool_id").parents('.col-xl-4').append("<div class='text-info' id='m'>" + data.model + "</div>");
                $("#factory_id").parents('.col-xl-4').append("<div class='text-info' id='f'>" + data.factory + "</div>");
                $("#number_id").parents('.col-xl-4').append("<div class='text-info' id='n'>" + data.number + "</div>");
                $("#certificate_no").val(data.certificate_no);
                $("#department_id").find("option:contains(" + data.department + ")").attr("selected", true);
                $("#verification_date").val(data.verification_date);
                load_validity_date();
                load_number()
            }
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('证书识别异常')
        }
    })
}

//加载出厂编号
function load_number() {
    $.ajax({
        url: "/certificate_number?t=" + encodeURIComponent($("#t").text()) + "&m=" + encodeURIComponent($("#m").text()) + "&f=" + encodeURIComponent($("#f").text()) + "&n=" + encodeURIComponent($("#n").text()),
        success: function (d) {
            if (d) {
                $("#factory_id").parents('.col-xl-4').append("<input type='hidden' id='ff' value='" + d['factory_id'] + "'>");
                $("#number_id").parents('.col-xl-4').append("<input type='hidden' id='nn' value='" + d['number_id'] + "'>");
                $('#tool_id').selectpicker('val', d['tool_id'])
                load_validity_date();
            } else {
                notifications('出厂编号加载失败');
            }
        },
        error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('出厂编号加载失败')
        }
    })
}
