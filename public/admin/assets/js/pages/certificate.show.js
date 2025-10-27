$(document).ready(function () {
    let arr = []
    for (const certificate of certificates) {
        arr.push({
            id: certificate.id, content: certificate.number, start: `${certificate.start_date} 00:00:00`, end: `${certificate.end_date} 23:59:59`,
        })
    }
    let timeline = new vis.Timeline(document.getElementById('timeline'), new vis.DataSet(arr), {
        height: '100px', stack: false, min: min, max: max,
    });
    let selected
    timeline.setSelection([disable]);
    timeline.on('select', function (properties) {
        if (properties.items.length > 0) {
            selected = properties.items[0]
            $(`#${properties.items[0]}-btn`).click()
        }
        if (properties.items.length == 0) {
            timeline.setSelection([selected]);
        }
    });
    if ($(window).height() < $(window).width()) {
        $('.tab-content').height($('.off-sidebar-content').height() - 160)
    }
});