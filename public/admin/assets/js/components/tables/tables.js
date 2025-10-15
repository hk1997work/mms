'use strict';
let dataTable = [];
let canSubmit = true;
let main = $('main');

function initTable(table) {
    if (table.length > 0) {
        //服务器模式配置
        let options = {
            language: {url: '/admin/assets/vendors/js/datatables/zh.json'},
            ajax: {url: "/ajax_" + table.data('menu'), type: "POST", data: {"_token": csrf_token}},
            deferRender: true,
            serverSide: true,
            scrollY: $(window).height() - table.offset().top - ($('#timeline').length > 0 ? 250 : 150),
            rowId: 0,
            searchDelay: 500,
            order: [1, 'asc'],
            fixedColumns: {leftColumns: 1},
            select: {style: 'multi', selector: 'td:first-child', headerCheckbox: false},
            scroller: true,
            columnDefs: [{orderable: false, render: DataTable.render.select(), targets: 0}, {type: 'string', orderSequence: ['asc', 'desc'], targets: '_all'}],
        };
        // 禁用排序
        if (table.hasClass('table-tree')) {
            options.ordering = false;
        }
        // 禁用服务器模式
        if (table.hasClass('table-all')) {
            options.serverSide = false;
            options.paging = false;
            delete options.scroller;
        }
        //禁用ajax
        if (table.hasClass('table-data')) {
            options.serverSide = false;
            options.paging = false;
            delete options.ajax;
            delete options.scroller;
        }
        //禁用选中行
        if (table.hasClass('table-unselect')) {
            options.columnDefs = [{type: 'string', orderSequence: ['asc', 'desc'], targets: '_all'}];
            delete options.order;
            delete options.select;
        }
        let dt = table.DataTable(options);
        dt.on('init.dt xhr.dt', function () {
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

dataTable['index'] = initTable($('table'));

function sidebar_ajax(btn, url, menu) {
    if (!canSubmit) {
        notifications('操作太快,请重试.');
        return;
    }
    canSubmit = false;
    let title = btn.text();
    let sidebar = $('.from-' + btn.data('pos'));
    $.ajax({
        url: url, success: function (data) {
            if (data) {
                sidebar.html(data);
                sidebar.find('.sidebar-btn').text(title);
                sidebar.find('.sidebar-url').attr('data-url', menu);
                $(window).trigger('resize');
                if (sidebar.find('#off-sidebar-table').length > 0) {
                    dataTable['sidebar'] = initTable($('#off-sidebar-table')).on('xhr.dt init.dt', function () {
                        sidebar.addClass('is-visible');
                    });
                } else {
                    void sidebar[0].offsetHeight;
                    sidebar.addClass('is-visible');
                }
                if (btn.attr('data-reload')) {
                    sidebar.find('.sidebar-url').attr('data-reload', btn.attr('data-reload'));
                }
            } else {
                notifications(title + '失败');
            }
        }, error: function (xhr) {
            xhr.status === 401 ? document.location.reload() : notifications(title + '失败');
        },
    });
    setTimeout(() => {
        canSubmit = true;
    }, 800);
}

$(main).on('click', '.btn-add', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = $(this).data('id') ?? table.select.cumulative().rows.join(',');
    let menu = $(this).data('menu');
    let url = '/' + menu + '/create' + (id ? '?id=' + id : '');
    sidebar_ajax($(this), url, menu);
});
$(main).on('click', '.btn-edit', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = $(this).data('id') ?? table.select.cumulative().rows.join(',');
    let menu = $(this).data('menu');
    let url = '/' + menu + '/' + id + '/edit';
    sidebar_ajax($(this), url, menu);
});
$(main).on('click', '.btn-show', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = $(this).data('id') ?? table.select.cumulative().rows.join(',');
    let menu = $(this).data('menu');
    let url = '/' + menu + '/' + id;
    sidebar_ajax($(this), url, menu);
});
$(main).on('click', '.btn-delete', function () {
    let menu = $(this).data('menu');
    let url = '/delete';
    sidebar_ajax($(this), url, menu);
});

//提交表单
function submit_ajax(btn, url, show = true) {
    if (!canSubmit) {
        notifications('操作太快,请重试.');
        return;
    }
    canSubmit = false;
    let sidebar = $('.from-' + btn.closest('.off-sidebar').data('pos'));
    let title = (btn.hasClass('btn-move') || btn.hasClass('submit-delete')) ? btn.text().replaceAll(' ', '') : sidebar.find('.sidebar-btn').text();
    let form = btn.closest('form');
    let data = new FormData(form[0]);
    if (!form[0]) data.append('_token', csrf_token);
    $.ajax({
        url: url, type: 'POST', data: data, processData: false, contentType: false, success: function (result) {
            if (result == true) {
                notifications(title + '成功');
                if (dataTable['sidebar']) {
                    reloadTable(dataTable['sidebar'], true);
                    reloadTable(dataTable['index'], false);
                } else {
                    reloadTable(dataTable['index'], true);
                }
                show && sidebar.removeClass('is-visible');
                if (btn.attr('data-reload')) {
                    $('[name="' + btn.attr('data-reload') + '"]').trigger('change');
                }
            } else if (result == false) {
                notifications(title + '失败');
            } else {
                notifications(result);
                show && sidebar.removeClass('is-visible');
            }
        }, error: function (xhr) {
            xhr.status === 401 ? document.location.reload() : notifications(title + '失败');
            form.find(".invalid-text").remove();
            form.find('.form-control:not([readonly], [disabled])').removeClass('is-invalid').addClass('is-valid');
            $.each(JSON.parse(xhr.responseText).errors, function (idx, obj) {
                if (idx.includes('.')) {
                    let parts = idx.split('.');
                    idx = parts[0] + "[" + parts[1] + "][" + parts[2] + "]";
                }
                let str = " <small class='text-danger invalid-text'>" + obj + "</small>";
                form.find('[class~="div-' + idx + '"]').find('.form-label').append(str);
                form.find('[class~="div-' + idx + '"]').find('.form-control').removeClass('is-valid').addClass('is-invalid');
            });
        }
    });
    setTimeout(() => {
        canSubmit = true;
    }, 800);
}

// 修改输入时,去掉错误提示
$(main).on('change', '.is-valid,.is-invalid', function () {
    $(this).closest(('[class~="div-' + $(this).attr('name') + '"]').replace('[]', '')).find(".invalid-text").remove();
    $(this).closest(('[class~="div-' + $(this).attr('name') + '"]').replace('[]', '')).find(".form-control").removeClass('is-valid').removeClass('is-invalid');
})

$(main).on('click', '.submit-add', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = (table?.select?.cumulative?.().rows.join(',') || 'true');
    let url = "/" + $(this).data('url') + (id ? '?id=' + id : '');
    submit_ajax($(this), url);
})
$(main).on('click', '.submit-edit', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = (table?.select?.cumulative?.().rows.join(',') || 'true');
    let url = "/" + $(this).data('url') + '/' + id;
    submit_ajax($(this), url);
})
$(main).on('click', '.submit-delete', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = (table?.select?.cumulative?.().rows.join(',') || 'true');
    let url = '/' + $(this).data('url') + "/" + id;
    submit_ajax($(this), url);
})
$(main).on('click', '.btn-move', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = (table?.select?.cumulative?.().rows.join(',') || 'true');
    let url = '/move/' + $(this).data('menu') + '/' + id + '/' + $(this).data('type');
    submit_ajax($(this), url, false);
})
$(main).on('click', '.btn-download,.btn-open', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = (table?.select?.cumulative?.().rows.join(',') || 'true');
    let menu = $(this).data('menu');
    let type = $(this).hasClass('btn-open') ? '?type=show' : '';
    $.each(id.split(','), function (index, value) {
        window.open('/download_' + menu + '/' + value + type);
    })
})
$(main).on('click', '.btn-submit', function () {
    let table = dataTable['sidebar'] ?? dataTable['index'];
    let id = (table?.select?.cumulative?.().rows.join(',') || 'true');
    let pos = $(this).closest('.off-sidebar').data('pos');
    let url = "/" + $(this).data('url') + '/' + id;
    let sidebar = $('.from-' + pos);
    sidebar.find('form').attr('action', url);
    sidebar.removeClass('is-visible');
    sidebar.find('form').submit();
})

// 关闭窗口时,清空内容
$(document).ready(function () {
    $('.off-sidebar').on('transitionend', function (event) {
        if (event.originalEvent && event.originalEvent.propertyName && event.originalEvent.propertyName === 'transform') {
            if (!$(this).hasClass('is-visible')) {
                if ($(this).find('#off-sidebar-table').length) {
                    dataTable['sidebar'] = null;
                }
                $(this).empty();
            }
        }
    });
});

function reloadTable(table, clear) {
    if (table?.settings()?.[0]?.oInit?.ajax) {
        if (clear) {
            table.context[0]._select_set = [];
        }
        table.ajax.reload(null, false);
    }
}

$(window).resize(function () {
    $('.off-sidebar-container .auto-scroll').height($(window).height() - 90);
    setTableScrollHeight($('#index-table_wrapper'));
    setTableScrollHeight($('#off-sidebar-table_wrapper'));
});

function setTableScrollHeight(table) {
    if (!table.length) return;
    table.find('.dt-scroll-body').height($(window).height() - table.offset().top - ($('#timeline').length > 0 ? 250 : 150) + 7);
}