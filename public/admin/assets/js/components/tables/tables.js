(function ($) {

    'use strict';
    let CH = {
        sProcessing: "处理中...",
        sLengthMenu: "显示 _MENU_ 项结果",
        sZeroRecords: "没有匹配结果",
        sInfo: "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        sInfoEmpty: "显示第 0 至 0 项结果，共 0 项",
        sInfoFiltered: "(由 _MAX_ 项结果过滤)",
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
        oAria: {
            sSortAscending: ": 以升序排列此列",
            sSortDescending: ": 以降序排列此列"
        }
    }
    // ------------------------------------------------------- //
    // Auto Hide
    // ------------------------------------------------------ //
    $(function () {
        if ($('#sorting-table').length > 0) {
            $('#sorting-table').DataTable({
                language: CH,
                scrollY: $(window).height() - $('#sorting-table').offset().top - 220,
                lengthChange: false,
                paging: false,
                stateSave: true,
            });
        }

        if ($('#unsorted-table').length > 0) {
            $('#unsorted-table').DataTable({
                language: CH,
                scrollY: $(window).height() - $('#unsorted-table').offset().top - 220,
                lengthChange: false,
                paging: false,
                stateSave: true,
                ordering: false,
            });
        }

        if ($('#modal-table').length > 0) {
            $('#modal-table').DataTable({
                language: CH,
                lengthChange: false,
                paging: false,
                stateSave: true,
            });
        }

        if ($('#export-table').length > 0) {
            $('#export-table').DataTable({
                language: CH,
                scrollY: $(window).height() - $('#export-table').offset().top - 230,
                lengthChange: false,
                paging: false,
                stateSave: true,
                order: [1, 'asc'],
                columnDefs: [
                    {orderable: false, targets: 0}
                ],
            });
        }

        if ($('#filter-table').length > 0) {
            $('#filter-table').DataTable({
                language: CH,
                lengthChange: false,
                paging: false,
                stateSave: true,
                order: [0, 'desc'],
            });
        }

        window.onbeforeunload = function (e) {
            localStorage.setItem('scrollpos', $('.dataTables_scrollBody').scrollTop());
        };
        $(document).ready(function () {
            // 设置页面滚动位置
            $('.dataTables_scrollBody').scrollTop(localStorage.getItem('scrollpos'));
        });
    })
})(jQuery);
