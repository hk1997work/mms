$(document).ready(function () {
    settings = settings ? settings.split(',') : [];
    dataTable['sidebar'].on('draw', function () {
        dataTable['sidebar'].rows(function (idx, data, node) {
            return settings.includes(data[1]);
        }).select();
    });
});