$(function () {
    let arr = [];
    for (const date of dates) {
        arr.push({
            id: date, content: date, start: `${date} 00:00:00`, end: `${date} 23:59:59`,
        })
    }
    let timeline = new vis.Timeline(document.getElementById('timeline'), new vis.DataSet(arr), {
        height: '100px', stack: false, min: arr.at(-1).start, max: arr.at(0).end,
    });
    let selected = dates[0];
    let beginDate = new Date(selected);
    beginDate.setMonth(beginDate.getMonth() - 1);
    $('.btn-show').data('id', selected);
    timeline.setSelection([selected]);
    timeline.setWindow(`${beginDate.toLocaleDateString('en-CA')} 00:00:00`, arr.at(0).end, {animation: true});
    timeline.on('select', function (properties) {
        if (properties.items.length > 0) {
            selected = properties.items[0];
            $('.btn-show').data('id', selected);
            $('#index-table').attr('data-menu', `sample?id=${selected}`);
            dataTable['index'].ajax.url(`/ajax_sample?id=${selected}`).load();
        }
        if (properties.items.length === 0) {
            timeline.setSelection([selected]);
        }
    });
    $('.submit-download').click(function () {
        $('#date-download').val(selected);
        $('#form-download').submit();
    })
});