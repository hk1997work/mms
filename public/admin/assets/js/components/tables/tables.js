'use strict';
let dataTable;
let offSidebarDataTable;
let canSubmit = true;

function initTable(table) {
    if (table.length > 0) {
        let options = {
            language: {url: '/admin/assets/vendors/js/datatables/zh.json'},
            ajax: {url: "/ajax_" + table.data('menu'), type: "POST", data: {"_token": csrf_token}},
            deferRender: true,
            serverSide: true,
            scrollY: $(window).height() - table.offset().top - 150,
            rowId: 0,
            searchDelay: 500,
            order: [1, 'asc'],
            fixedColumns: {leftColumns: 1},
            select: {style: 'multi', selector: 'td:first-child', headerCheckbox: false},
            scroller: true,
            columnDefs: [{orderable: false, render: DataTable.render.select(), targets: 0}, {type: 'string', orderSequence: ['asc', 'desc'], targets: '_all'}],
        };
        if (table.hasClass('table-tree')) {
            options.ordering = false;
        }
        if (table.hasClass('table-all')) {
            options.serverSide = false;
            options.paging = false;
            delete options.scroller;
        }
        if (table.hasClass('table-data')) {
            options.serverSide = false;
            options.paging = false;
            delete options.ajax;
            delete options.scroller;
        }
        let dt = table.DataTable(options);
        dt.on('xhr.dt', function () {
            let obj = $(this).closest('.table-responsive');
            let count = dt.select.cumulative().rows.length;
            btn_change(obj, count);
            $("#preloader").fadeOut();
        });
        dt.on('select deselect', function (e, dtApi, type, indexes) {
            let obj = $(this).closest('.table-responsive');
            let count = dt.select.cumulative().rows.length + (e.type === 'select' ? indexes.length : -indexes.length);
            btn_change(obj, count);
        });
        return dt;
    }
}

function btn_change(obj, count) {
    if (count === 0) {
        obj.find('.check-single').css('visibility', 'hidden');
        obj.find('.check-multiple').css('visibility', 'hidden');
    } else if (count === 1) {
        obj.find('.check-single').css('visibility', 'visible');
        obj.find('.check-multiple').css('visibility', 'visible');
    } else {
        obj.find('.check-single').css('visibility', 'hidden');
        obj.find('.check-multiple').css('visibility', 'visible');
    }
}

dataTable = initTable($('table'));

function sidebar_ajax(btn, url, menu, callback) {
    if (canSubmit) {
        canSubmit = false;
        let title = btn.text();
        let sidebar = $('.from-' + btn.data('pos'));
        $.ajax({
            url: url, success: function (data) {
                if (data) {
                    sidebar.html(data)
                    sidebar.find('.sidebar-btn').text(title)
                    sidebar.find('.sidebar-url').attr('data-url', menu);
                    $(window).trigger('resize');
                    if (sidebar.find('#off-sidebar-table').length > 0) {
                        offSidebarDataTable = initTable($('#off-sidebar-table')).on('xhr.dt init.dt', function () {
                            sidebar.addClass('is-visible');
                        });
                    } else {
                        void sidebar[0].offsetHeight;
                        sidebar.addClass('is-visible');
                    }
                    callback?.();
                } else {
                    notifications(title + '失败');
                }
            }, error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications(title + '失败');
            },
        });
        setTimeout(() => {
            canSubmit = true;
        }, 800);
    } else {
        notifications('操作太快,请重试.');
    }
}

$('.index,.off-sidebar').on('click', '.btn-add', function () {
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = $(this).data('id') ? $(this).data('id') : table.select.cumulative().rows.join(',');
    let menu = $(this).data('menu');
    let url = '/' + menu + '/create' + (id ? '?id=' + id : '');
    sidebar_ajax($(this), url, menu);
});
$('.index,.off-sidebar').on('click', ' .btn-edit', function () {
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = table.select.cumulative().rows.join(',');
    let menu = $(this).data('menu');
    let url = '/' + menu + '/' + id + '/edit';
    sidebar_ajax($(this), url, menu);
});
$('.index,.off-sidebar').on('click', ' .btn-show', function () {
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = table.select.cumulative().rows.join(',');
    let menu = $(this).data('menu');
    let url = '/' + menu + '/' + id;
    sidebar_ajax($(this), url, menu);
});
$('.index,.off-sidebar').on('click', ' .btn-delete', function () {
    let menu = $(this).data('menu');
    let url = '/delete';
    sidebar_ajax($(this), url, menu);
});

//提交表单
function submit_ajax(btn, url, show = true, callback) {
    if (canSubmit) {
        canSubmit = false;
        let sidebar = $('.from-' + btn.closest('.off-sidebar').data('pos'));
        let title = btn.hasClass('btn-move') ? btn.text() : sidebar.find('.sidebar-btn').text();
        let form = btn.closest('form');
        let data = new FormData(form[0]);
        if (!form[0]) data.append('_token', csrf_token);
        $.ajax({
            url: url, type: 'POST', data: data, processData: false, contentType: false, success: function (result) {
                if (result == true) {
                    callback?.();
                    notifications(title + '成功');
                    reloadTableIfAjax(offSidebarDataTable);
                    reloadTableIfAjax(dataTable);
                    show && sidebar.removeClass('is-visible');
                } else if (result == false) {
                    notifications(title + '失败');
                } else {
                    notifications(result);
                    show && sidebar.removeClass('is-visible');
                }
            }, error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications(title + '失败');
                form.find(".warning-danger").remove();
                form.find('.form-control:not([readonly], [disabled])').removeClass('is-invalid').addClass('is-valid');
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
            }
        });
        setTimeout(() => {
            canSubmit = true;
        }, 800);
    } else {
        notifications('操作太快,请重试.');
    }
}

$('.index,.off-sidebar').on('click', '.submit-add', function () {
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = table.select.cumulative().rows.join(',');
    let url = "/" + $(this).data('url') + (id ? '?id=' + id : '');
    submit_ajax($(this), url);
})
$('.index,.off-sidebar').on('click', '.submit-edit', function () {
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = $(this).data('id') ? $(this).data('id') : table.select.cumulative().rows.join(',');
    let url = "/" + $(this).data('url') + '/' + id;
    submit_ajax($(this), url);
})
$('.index,.off-sidebar').on('click', '.submit-delete', function () {
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = table.select.cumulative().rows.join(',');
    let url = '/' + $(this).data('url') + "/" + id;
    submit_ajax($(this), url);
})
$('.index,.off-sidebar').on('click', '.btn-move', function () {
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = table.select.cumulative().rows.join(',');
    let url = '/move/' + $(this).data('menu') + '/' + id + '/' + $(this).data('type');
    submit_ajax($(this), url, false);
})
$('.index,.off-sidebar').on('click', '.btn-download , .btn-open', function () {
    let menu = $(this).data('menu')
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = table.select.cumulative().rows.join(',');
    let type = $(this).hasClass('btn-open') ? '?type=show' : '';
    $.each(id.split(','), function (index, value) {
        window.open('/download_' + menu + '/' + value + type);
    })
})
$('.index,.off-sidebar').on('click', '.btn-submit', function () {
    let table = offSidebarDataTable ? offSidebarDataTable : dataTable;
    let id = table.select.cumulative().rows.join(',');
    let pos = $(this).closest('.off-sidebar').data('pos')
    let url = "/" + $(this).data('url') + '/' + id;
    $('.from-' + pos).find('form').attr('action', url)
    $('.from-' + pos).removeClass('is-visible');
    $('.from-' + pos).find('form').submit()
})

// 修改输入时,去掉错误提示
$('.off-sidebar').on('change', '.is-valid,.is-invalid', function () {
    $(this).closest(('[class~="div-' + $(this).attr('name') + '"]').replace('[]', '')).find(".warning-danger").remove();
    $(this).closest(('[class~="div-' + $(this).attr('name') + '"]').replace('[]', '')).find(".form-control").removeClass('is-valid').removeClass('is-invalid');
})
// 关闭窗口时,清空内容
$(document).ready(function () {
    $('.off-sidebar').on('transitionend', function (event) {
        if (event.originalEvent && event.originalEvent.propertyName && event.originalEvent.propertyName === 'transform') {
            if ($(this).hasClass('is-visible') == false) {
                if ($(this).find('#off-sidebar-table').length) {
                    offSidebarDataTable = null;
                }
                $(this).empty();
            }
        }
    });
});
// 设置表格高度
$(window).resize(function () {
    $('.auto-scroll').height($(window).height() - 90);
});

function reloadTableIfAjax(table) {
    if (table?.settings()?.[0]?.oInit?.ajax) {
        table.context[0]._select_set = [];
        table.ajax.reload(null, false);
    }
}