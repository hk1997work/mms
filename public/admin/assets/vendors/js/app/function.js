//菜单选中
if (menu != '') {
    $("#" + menu).addClass('active');
    $("#" + menu).parent().parent().collapse('show');
    $(".page-title").text($("#" + menu).text())
}

//提示框
function notifications(text) {
    new Noty({
        type: 'notification',
        layout: 'bottomRight',
        text: text,
        progressBar: true,
        timeout: 2500,
        animation: {
            open: 'animated bounceInRight',
            close: 'animated bounceOutRight',
        }
    }).show()
}

function get_modal() {
    let modal;
    if ($("#modal1").is(":visible")) {
        modal = 2
    } else if ($("#modal2").is(":visible")) {
        modal = 3
    } else {
        modal = 1
    }
    (modal > 1) ? $("#modal" + (modal - 1)).modal('hide') : '';
    return modal
}

//增加模态框
function m_add(p, id) {
    let modal = get_modal()
    $.ajax({
        url: "/" + p + "/create" + (!!id ? '?id=' + id : ''),
        success: function (data) {
            $("#modal" + modal).html(data);
            $("#modal" + modal).find(".btn-primary").attr('onclick', 'b_add("' + p + '")')
            $('#modal' + modal).modal('show')
        },
        error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                notifications('增加失败')
            }
        }
    })
}

//修改模态框
function m_edit(p, id) {
    let modal = get_modal()
    $.ajax({
        url: "/" + p + "/" + id + "/edit",
        success: function (data) {
            $("#modal" + modal).html(data);
            $("#modal" + modal).find(".btn-primary").attr('onclick', 'b_edit("' + p + '",' + id + ')')
            $('#modal' + modal).modal('show')
        },
        error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                notifications('修改失败')
            }
        }
    })
}

//显示模态框
function m_show(p, id, loader) {
    if (loader) {
        $("#preloader")[0].style.display = 'block';
    }
    let modal = get_modal()
    $.ajax({
        url: "/" + p + "/" + id,
        success: function (data) {
            $("#modal" + modal).html(data);
            $('#modal' + modal).modal('show')
        },
        error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                notifications('查看失败')
            }
        },
        complete: function () {
            if ($("#preloader").is(':visible') && p != 'certificate') {
                $("#preloader").fadeOut();
            }
        }
    })
}

//删除模态框
function m_delete(p, id) {
    let modal = get_modal()
    let str = `
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                 <div class="modal-header">
                    <h4 class="modal-title">确认删除?</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">close</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <div id="btn_ok"></div>
                    <button class="btn btn-primary ripple" data-dismiss="modal">确 定</button>
                    <button type="button" class="btn btn-secondary ripple" data-dismiss="modal">取 消</button>
                </div>
            </div>
        </div>
        `;
    $("#modal" + modal).html(str);
    $("#modal" + modal).find(".btn-primary").attr('onclick', 'b_delete("' + p + '",' + id + ')')
    if (modal == 1) {
        $('#modal' + modal).modal('show')
    } else {
        setTimeout(function () {
            $('#modal' + modal).modal('show')
        }, 350)
    }
}

//增加操作
function b_add(p) {
    $.ajax({
        url: "/" + p,
        type: "POST",
        data: new FormData($("#form")[0]),
        processData: false,  // 不处理数据
        contentType: false,   // 不设置内容类型
        success: function (result) {
            if (result) {
                document.location.reload();
            } else {
                notifications('增加失败');
            }
        }, error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                $(".warning-danger").remove();
                let json = JSON.parse(xhr.responseText);
                $.each(json.errors, function (idx, obj) {
                    let str = "<div class='text-danger warning-danger'>" + obj + "</div>";
                    $("#" + idx.replace(".", "")).closest('.col-sm-12').append(str);
                });
            }
        }
    });
}

//修改操作
function b_edit(p, id) {
    $.ajax({
        url: "/" + p + "/" + id,
        type: "POST",
        data: new FormData($("#form")[0]),
        processData: false,  // 不处理数据
        contentType: false,   // 不设置内容类型
        success: function (result) {
            if (result == true) {
                document.location.reload();
            } else if (result == false) {
                notifications('修改失败');
            } else {
                notifications(result);
            }
        }, error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                $(".warning-danger").remove();
                let json = JSON.parse(xhr.responseText);
                $.each(json.errors, function (idx, obj) {
                    let str = "<div class='text-danger warning-danger'>" + obj + "</div>";
                    $("#" + idx).closest('.col-sm-12').append(str);
                });
            }
        }
    });
}

//删除操作
function b_delete(p, id) {
    $.ajax({
        url: "/" + p + "/" + id,
        type: "DELETE",
        data: {"_token": csrf_token},
        success: function (result) {
            if (result == true) {
                document.location.reload();
            } else if (result == false) {
                notifications('删除失败');
            } else {
                notifications(result);
            }
        },
        error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                notifications('删除失败')
            }
        }
    });
}

//模态框切换
$("#modal2").on('hidden.bs.modal', function () {
    $('#modal1').modal('show')
})
$("#modal3").on('hidden.bs.modal', function () {
    $('#modal2').modal('show')
})

// 上移操作
function up(obj, id) {
    $.ajax({
        url: '/move/' + menu + '/' + id + '/1',
        type: "POST",
        data: {"_token": csrf_token},
        success: function (result) {
            if (result == true) {
                document.location.reload();
            } else {
                notifications('已经是最顶层,无法上移');
            }
        }, error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                notifications('上移失败')
            }
        }
    });
}

// 下移操作
function down(obj, id) {
    $.ajax({
        url: '/move/' + menu + '/' + id + '/0',
        type: "POST",
        data: {"_token": csrf_token},
        success: function (result) {
            if (result == true) {
                document.location.reload();
            } else {
                notifications('已经是最底层,无法下移');
            }
        }, error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                notifications('下移失败')
            }
        }
    });
}

// 标记操作
function sign(id) {
    $.ajax({
        url: '/sign/' + menu + '/' + id,
        type: "POST",
        data: {"_token": csrf_token},
        success: function (result) {
            if (result == true) {
                document.location.reload();
            } else {
                notifications('状态更改失败');
            }
        }, error: function (xhr) {
            if (xhr.status == 401) {
                document.location.reload();
            } else {
                notifications('状态更改失败')
            }
        }
    });
}
