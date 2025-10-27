$(function () {
    let arr = []
    let btn = []
    for (const [index, unit] of units.entries()) {
        if (index === 0 || units[index].date !== units[index - 1].date) {
            arr.push({
                id: unit.date, content: unit.date, start: `${unit.date}-01 00:00:00`, end: `${new Date(new Date(unit.date).getFullYear(), (new Date(unit.date).getMonth() + 1), 0).toLocaleDateString('en-CA')} 23:59:59`,
            })
        }
        btn.push({
            date: unit.date, unit: unit.position4,
        })
    }
    let timeline = new vis.Timeline(document.getElementById('timeline'), new vis.DataSet(arr), {
        height: '100px', stack: false, min: arr.at(0).start, max: arr.at(-1).end,
    });
    let selected = new Date().toISOString().slice(0, 7);
    timeline.setSelection([selected]);
    timeline.setWindow(new Date(new Date().getFullYear(), new Date().getMonth(), 1), new Date(new Date().getFullYear() + 1, new Date().getMonth(), 1), {animation: true});
    timeline.on('select', function (properties) {
        if (properties.items.length > 0) {
            selected = properties.items[0]
            $('.dropdown-menu-end').html('')
            $.each(btn.filter(item => item.date === selected), function (key, value) {
                let url = `id=${value['unit']}&date=${selected}`
                if (key === 0) {
                    $('.nav').find('.dropdown-toggle').text(value['unit'])
                    $('#index-table').attr('data-menu', `cyclical?${url}`)
                    if (properties.event) {
                        dataTable['index'].ajax.url(`/ajax_cyclical?${url}`).load();
                    }
                }
                $('.dropdown-menu-end').append(`<li><a class="dropdown-item" data-url="${url}" href="#">${value['unit']}</a></li>`)
            });
        }
        if (properties.items.length === 0) {
            timeline.setSelection([selected]);
        }
    });
    timeline.emit('select', {items: [selected]});
    $('.dropdown-menu-end').on('click', '.dropdown-item', function () {
        $('.nav').find('.dropdown-toggle').text($(this).text())
        dataTable['index'].ajax.url(`/ajax_cyclical?${$(this).data('url')}`).load();
    })
    $('.submit-download').click(function () {
        $('#date-download').val(selected)
        $('#form-download').submit()
    })
});