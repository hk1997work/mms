'use strict';
let dataTable
let offSidebarDataTable
let canSubmit = true;

function initTable(table) {
    if (table.length > 0) {
        let options = {
            language: {
                sProcessing: "",
                sLengthMenu: "显示 _MENU_ 项",
                sZeroRecords: "没有匹配结果",
                sInfo: "显示第 _START_ 至 _END_ 项，共 _TOTAL_ 项",
                sInfoEmpty: "显示第 0 至 0 项，共 0 项",
                sInfoFiltered: "(由 _MAX_ 项过滤)",
                sInfoPostFix: "",
                sSearch: "搜索:",
                sUrl: "",
                sEmptyTable: "表中数据为空",
                sLoadingRecords: "载入中...",
                sInfoThousands: ",",
                oPaginate: {
                    sFirst: "首页",
                    sPrevious: "上页",
                    sNext: "下页",
                    sLast: "末页"
                },
            },
            oAria: {
                sSortAscending: ": 以升序排列此列",
                sSortDescending: ": 以降序排列此列"
            },
            ajax: {
                url: "/ajax_" + table.data('menu'),
                type: "POST",
                data: {"_token": csrf_token}
            },
            scroller:{
                loadingIndicator:  true
            },
            searchDelay: 500,
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollY: $(window).height() - table.offset().top - 210,
            fixedColumns: {
                leftColumns: 1
            },
            order: [1, 'asc'],
            columnDefs: [
                {orderable: false, targets: 0}
            ],
        }
        if (table.attr('id') == 'no-ajax-table') {
            delete options.ajax
            $(".loader").fadeOut();
            $("#preloader").fadeOut();
        }
        if (table.hasClass('nocheck')) {
            delete options.order
            delete options.fixedColumns
            options.columnDefs = [
                {orderable: false, targets: -1}
            ]
        }
        if (table.hasClass('unsorted')) {
            options.ordering = false;
        }
        if (table.hasClass('tl-100')) {
            options.scrollY = $(window).height() - table.offset().top - 310;
        }
        return table.DataTable(options).on('xhr.dt', function () {
            $(".loader").fadeOut();
            $("#preloader").fadeOut();
        });
    }
}

function checkboxChange(obj) {
    let selectedCount = 0;
    let allChecked = true;
    let hide;
    obj.find('.cb').each(function () {
        if (!$(this).prop('checked')) {
            allChecked = false;
        } else {
            selectedCount++;
            if ($(this).data('hide') !== undefined) {
                hide = $(this).data('hide')
            }
        }
    });

    if (selectedCount == 0) {
        obj.find('.check-single').hide()
        obj.find('.check-multiple').hide()
    } else if (selectedCount == 1) {
        obj.find('.check-single').show()
        obj.find('.check-multiple').show()
    } else {
        obj.find('.check-single').hide()
        obj.find('.check-multiple').show()
    }
    if (hide) {
        obj.find('.' + hide).hide()
    }
    return allChecked
}

function get_id(obj, btn) {
    let id = []
    let menus = []
    obj.find('.cb:checked').each(function () {
        id.push($(this).attr('id').replace('cb', ''))
        if ($(this).data('menu') !== undefined) {
            let menuValue = $(this).data('menu')
            if (!menus.includes(menuValue)) {
                menus.push(menuValue);
            }
        }
    });
    if (menus.length == 1) {
        obj.find(btn).data('menu', menus[0]);
        return id.join(',')
    } else if (menus.length == 0) {
        return id.join(',')
    }
    return false
}

//sidebar_ajax
function sidebar_ajax(url, menu, title, pos, callback) {
    if (canSubmit) {
        canSubmit = false;
        if (callback == 'load') {
            $("#preloader")[0].style.display = 'block';
        }
        $.ajax({
            url: url,
            success: function (data) {
                if (data) {
                    $('.from-' + pos).html(data);
                    $('.from-' + pos).find('.sidebar-btn').text(title)
                    $('.from-' + pos).find('.sidebar-url').val(menu)
                    $(window).trigger('resize')
                    if ($('.from-' + pos).find($('#no-ajax-table')).length > 0) {
                        initTable($('#no-ajax-table'))
                    }
                    if ($('.from-' + pos).find($('#off-sidebar-table')).length > 0) {
                        offSidebarDataTable = initTable($('#off-sidebar-table')).on('xhr.dt', function () {
                            $('.from-' + pos).addClass('is-visible');
                        });
                        checkboxChange($('.from-' + pos))
                    } else {
                        $('.from-' + pos).addClass('is-visible');
                    }
                    if (callback) {
                        $('.from-' + pos).find('.submit-add').attr('data-cb', callback)
                    }
                } else {
                    notifications(title + '失败')
                }
                $("#preloader").fadeOut();
            },
            error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications(title + '失败')
                $("#preloader").fadeOut();
            },
        });
        setTimeout(() => {
            canSubmit = true;
        }, 800);
    } else {
        notifications('操作间隔为1秒,请重试.')
    }
}

//submit_ajax
function submit_ajax(url, pos, btn, callback) {
    let form = btn.closest('form')
    if (canSubmit) {
        canSubmit = false;
        if (callback == 'load') {
            $("#preloader")[0].style.display = 'block';
        }
        let title = $('.from-' + pos).find('.sidebar-btn').text()
        $.ajax({
            url: url,
            type: 'POST',
            data: new FormData(btn.closest('form')[0]),
            processData: false,  // 不处理数据
            contentType: false,   // 不设置内容类型
            success: function (result) {
                if (result == true) {
                    notifications(title + '成功');
                    if (callback && typeof callback === 'function') {
                        callback();
                    } else {
                        if ($('#off-sidebar-table').length > 0) {
                            offSidebarDataTable.ajax.reload(null, false)
                        }
                        if (dataTable) {
                            dataTable.ajax.reload(function () {
                                $(window).trigger('resize')
                            }, false);
                        }
                    }
                    $('.from-' + pos).removeClass('is-visible');
                } else if (result == false) {
                    notifications(title + '失败');
                } else {
                    notifications(result);
                }
                $("#preloader").fadeOut();
            }, error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications(title + '失败')
                form.find(".warning-danger").remove();
                form.find('.form-control').removeClass('is-invalid').addClass('is-valid');
                let json = JSON.parse(xhr.responseText);
                $.each(json.errors, function (idx, obj) {
                    if (idx.includes('.')) {
                        let parts = idx.split('.');
                        idx = parts[0] + "[" + parts[1] + "][" + parts[2] + "]";
                    }
                    let str = " <small class='text-danger warning-danger'>" + obj + "</small>";
                    form.find('[class~="div-' + idx + '"]').find('.sidebar-heading,label.form-control-label').append(str);
                    form.find('[class~="div-' + idx + '"]').find('.form-control').removeClass('is-valid').addClass('is-invalid');
                });
                $("#preloader").fadeOut();
            }
        });
        setTimeout(() => {
            canSubmit = true;
        }, 800);
    } else {
        notifications('操作间隔为1秒,请重试.')
    }
}

$('.off-sidebar').on('change', '.is-valid,.is-invalid', function () {
    $(this).closest(('[class~="div-' + $(this).attr('name') + '"]').replace('[]', '')).find(".warning-danger").remove();
    $(this).closest(('[class~="div-' + $(this).attr('name') + '"]').replace('[]', '')).find(".form-control").removeClass('is-valid').removeClass('is-invalid');
})

dataTable = initTable($('table'));

window.onbeforeunload = function (e) {
    localStorage.setItem('scrollpos', $('.dataTables_scrollBody').scrollTop());
};

$('.dataTables_scrollBody').scrollTop(localStorage.getItem('scrollpos'));

$('.table-responsive,.off-sidebar').on('draw.dt', 'table', function () {
    checkboxChange($(this).closest('.ckp'))
});

$('.table-responsive,.off-sidebar').on('change', '.check-all', function () {
    $(this).closest('.ckp').find("input:checkbox").prop('checked', $(this).prop("checked"));
    checkboxChange($(this).closest('.ckp'))
});

$('.table-responsive,.off-sidebar').on('change', '.cb', function () {
    let allChecked = checkboxChange($(this).closest('.ckp'))
    $(this).closest('.ckp').find('.check-all').prop('checked', allChecked);
});

checkboxChange($('.table-responsive'))

//增加框
$('.widget-header,.table-responsive,.off-sidebar').on('click', '.btn-add', function () {
    let id = $(this).data('id') ? $(this).data('id') : get_id($(this).closest('.ckp'))
    let menu = $(this).data('menu')
    let title = $(this).text()
    let url = '/' + menu + '/create' + (id ? '?id=' + id : '')
    let pos = $(this).data('pos')
    let callback = $(this).data('cb')
    sidebar_ajax(url, menu, title, pos, callback)
})

//修改框
$('.table-responsive,.off-sidebar').on('click', '.btn-edit', function () {
    let id = get_id($(this).closest('.ckp'), '.btn-edit')
    let menu = $(this).data('menu')
    let title = $(this).text()
    let url = '/' + menu + '/' + id + '/edit'
    let pos = $(this).data('pos')
    sidebar_ajax(url, menu, title, pos)
})

//删除框
$('.table-responsive,.off-sidebar').on('click', '.btn-delete', function () {
    let id = get_id($(this).closest('.ckp'), '.btn-delete')
    if (id) {
        let menu = $(this).data('menu')
        let title = $(this).text()
        let url = '/delete'
        let pos = $(this).data('pos')
        sidebar_ajax(url, menu, title, pos)
    } else {
        notifications('选择数据类型不同,无法删除.')
    }
})

//显示框
$('.widget-header,.table-responsive,.off-sidebar').on('click', '.btn-show', function () {
    let id = $(this).data('id') ? $(this).data('id') : get_id($(this).closest('.ckp'), '.btn-show')
    id = id ? id : 0
    let menu = $(this).data('menu')
    let title = $(this).text()
    let url = '/' + menu + '/' + id
    let pos = $(this).data('pos')
    sidebar_ajax(url, menu, title, pos)
})

//增加操作
$('.table-responsive,.off-sidebar').on('click', '.submit-add', function () {
    let pos = $(this).closest('.off-sidebar').data('pos')
    let url = "/" + $('.from-' + pos).find('.sidebar-url').val()
    let callback
    if ($(this).data('cb') == 'load') {
        callback = 'load'
    } else if ($(this).data('cb')) {
        callback = function () {
            $('[name="' + $('.from-' + pos).find('.submit-add').data('cb') + '"]').trigger('change');
        }
    }
    submit_ajax(url, pos, $(this), callback)
})

//修改操作
$('.table-responsive,.off-sidebar').on('click', '.submit-edit', function () {
    let id
    if ($(this).data('id')) {
        id = $(this).data('id')
    } else if ($('#off-sidebar-table').length > 0) {
        id = get_id($('.off-sidebar'))
    } else {
        id = get_id($('.table-responsive'))
    }
    let pos = $(this).closest('.off-sidebar').data('pos')
    let url = "/" + $('.from-' + pos).find('.sidebar-url').val() + '/' + id
    submit_ajax(url, pos, $(this))
})

//删除操作
$('.table-responsive,.off-sidebar').on('click', '.submit-delete', function () {
    let id
    if ($(this).data('id')) {
        id = $(this).data('id')
    } else if ($('#off-sidebar-table').length > 0) {
        id = get_id($('.off-sidebar'))
    } else {
        id = get_id($('.table-responsive'))
    }
    let pos = $(this).closest('.off-sidebar').data('pos')
    let url = '/' + $('.from-' + pos).find('.sidebar-url').val() + "/" + id
    submit_ajax(url, pos, $(this))
})

// 移动操作
$('.table-responsive,.off-sidebar').on('click', '.btn-move', function () {
    let menu = $(this).data('menu')
    let id = get_id($(this).closest('.ckp'))
    let type = $(this).data('type')
    $.ajax({
        url: '/move/' + menu + '/' + id + '/' + type,
        type: 'POST',
        data: {'_token': csrf_token},
        success: function (result) {
            if (result == true) {
                notifications('移动成功')
                if ($('#off-sidebar-table').length > 0) {
                    offSidebarDataTable.ajax.reload(null, false)
                }
                dataTable.ajax.reload(function () {
                    $(window).trigger('resize')
                }, false);
            } else {
                notifications(result);
            }
        }, error: function (xhr) {
            xhr.status == 401 ? document.location.reload() : notifications('移动失败')
        }
    });
})

//下载操作
$('.table-responsive,.off-sidebar').on('click', '.btn-download', function () {
    let menu = $(this).data('menu')
    let id = get_id($(this).closest('.ckp'))
    $.each(id.split(','), function (index, value) {
        window.open('/download_' + menu + '/' + value);
    })
})

//打开操作
$('.table-responsive,.off-sidebar').on('click', '.btn-open', function () {
    let id = get_id($(this).closest('.ckp'))
    $.each(id.split(','), function (index, value) {
        window.open($('#' + value).data('url'));
    })
})

//提交操作
$('.table-responsive,.off-sidebar').on('click', '.btn-submit', function () {
    let id
    if ($(this).data('id')) {
        id = $(this).data('id')
    } else if ($('#off-sidebar-table').length > 0) {
        id = get_id($('.off-sidebar'))
    } else {
        id = get_id($('.table-responsive'))
    }
    let pos = $(this).closest('.off-sidebar').data('pos')
    let url = "/" + $('.from-' + pos).find('.sidebar-url').val() + '/' + id
    $('.from-' + pos).find('form').attr('action', url)
    $('.from-' + pos).removeClass('is-visible');
    $('.from-' + pos).find('form').submit()
})

//导入操作
$('.submit-import').on('click', function () {
    $('[name="import-file"]').click()
})

$('[name="import-file"]').on('change', function () {
    let menu = $(this).data('menu')
    let url = 'import/' + menu
    if ($(this).val()) {
        let formData = new FormData($('#form-import')[0]);
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (result) {
                if (result == true) {
                    notifications('操作成功')
                } else {
                    notifications(result);
                }
            },
            error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications('操作失败')
            }
        });
    }
    $('[name="import-file"]').val('')
});

$(document).ready(function () {
    $('.off-sidebar').on('transitionend', function (event) {
        if (event.originalEvent && event.originalEvent.propertyName && event.originalEvent.propertyName === 'transform') {
            if ($(this).hasClass('is-visible') == false) {
                $(this).html('')
            }
        }
    });
});

