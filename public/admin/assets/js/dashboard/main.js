(function ($) {
    'use strict';

    function getDate() {
        var date = new Date();
        var weekday = date.getDay();
        var month = date.getMonth();
        var day = date.getDate();
        var year = date.getFullYear();
        var hour = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();

        if (hour < 10) hour = "0" + hour;
        if (minutes < 10) minutes = "0" + minutes;
        if (seconds < 10) seconds = "0" + seconds;

        var monthNames = ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"];
        var weekdayNames = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"]

        var showDay = weekdayNames[weekday];
        var showDate = day;
        var showYear = year + " " + monthNames[month];
        var showTime = hour + ":" + minutes + ":" + seconds;
        document.getElementById('events-day').innerHTML = showDay;
        document.getElementById('events-date').innerHTML = showDate;
        document.getElementById('events-year').innerHTML = showYear;
        document.getElementById('events-time').innerHTML = showTime;
        requestAnimationFrame(getDate);
    }

    getDate();

    $('.certificate_per').circleProgress({
        value: (pdf_count / pdf_total),
        size: 140,
        startAngle: -Math.PI / 2,
        thickness: 6,
        lineCap: 'round',
        emptyFill: 'rgba(255, 255, 255, 0.15)',
        fill: {
            gradient: ['#fff', '#fff']
        }
    }).on('circle-animation-progress', function (event, progress) {
        $(this).find('.percent').html(((pdf_count / pdf_total) * progress * 100).toFixed(2) + '<i>%</i>');
    });
    $('.certificate_count').text(pdf_count + '/' + pdf_total)

    var randomScalingFactor = function () {
        return (Math.random() > 0.5 ? 1.0 : 1.0) * Math.round(Math.random() * 100);
    };

    Chart.helpers.drawRoundedTopRectangle = function (ctx, x, y, width, height, radius) {
        ctx.beginPath();
        ctx.moveTo(x + radius, y);
        ctx.lineTo(x + width - radius, y);
        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
        ctx.lineTo(x + width, y + height);
        ctx.lineTo(x, y + height);
        ctx.lineTo(x, y + radius);
        ctx.quadraticCurveTo(x, y, x + radius, y);
        ctx.closePath();
    };

    Chart.elements.RoundedTopRectangle = Chart.elements.Rectangle.extend({
        draw: function () {
            var ctx = this._chart.ctx;
            var vm = this._view;
            var left, right, top, bottom, signX, signY, borderSkipped;
            var borderWidth = vm.borderWidth;

            if (!vm.horizontal) {
                left = vm.x - vm.width / 2;
                right = vm.x + vm.width / 2;
                top = vm.y;
                bottom = vm.base;
                signX = 1;
                signY = bottom > top ? 1 : -1;
                borderSkipped = vm.borderSkipped || 'bottom';
            } else {
                left = vm.base;
                right = vm.x;
                top = vm.y - vm.height / 2;
                bottom = vm.y + vm.height / 2;
                signX = right > left ? 1 : -1;
                signY = 1;
                borderSkipped = vm.borderSkipped || 'left';
            }

            if (borderWidth) {
                var barSize = Math.min(Math.abs(left - right), Math.abs(top - bottom));
                borderWidth = borderWidth > barSize ? barSize : borderWidth;
                var halfStroke = borderWidth / 2;
                var borderLeft = left + (borderSkipped !== 'left' ? halfStroke * signX : 0);
                var borderRight = right + (borderSkipped !== 'right' ? -halfStroke * signX : 0);
                var borderTop = top + (borderSkipped !== 'top' ? halfStroke * signY : 0);
                var borderBottom = bottom + (borderSkipped !== 'bottom' ? -halfStroke * signY : 0);
                if (borderLeft !== borderRight) {
                    top = borderTop;
                    bottom = borderBottom;
                }
                if (borderTop !== borderBottom) {
                    left = borderLeft;
                    right = borderRight;
                }
            }

            var barWidth = Math.abs(left - right);
            var roundness = this._chart.config.options.barRoundness || 0.2;
            var radius = barWidth * roundness * 0.2;

            var prevTop = top;

            top = prevTop + radius;
            var barRadius = top - prevTop;

            ctx.beginPath();
            ctx.fillStyle = vm.backgroundColor;
            ctx.strokeStyle = vm.borderColor;
            ctx.lineWidth = borderWidth;

            Chart.helpers.drawRoundedTopRectangle(ctx, left, (top - barRadius + 1), barWidth, bottom - prevTop, barRadius);

            ctx.fill();
            if (borderWidth) {
                ctx.stroke();
            }

            top = prevTop;
        },
    });

    Chart.defaults.roundedBar = Chart.helpers.clone(Chart.defaults.bar);

    Chart.controllers.roundedBar = Chart.controllers.bar.extend({
        dataElementType: Chart.elements.RoundedTopRectangle
    });
    var ctx = document.getElementById("orders").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'roundedBar',
        data: {
            labels: [parseInt(year_plan[0]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[1]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[2]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[3]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[4]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[5]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[6]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[7]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[8]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[9]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[10]['MONTH'].split('-')[1]) + '月', parseInt(year_plan[11]['MONTH'].split('-')[1]) + '月'],
            datasets: [{
                label: '送检',
                data: [year_plan[0]['sj'], year_plan[1]['sj'], year_plan[2]['sj'], year_plan[3]['sj'], year_plan[4]['sj'], year_plan[5]['sj'], year_plan[6]['sj'], year_plan[7]['sj'], year_plan[8]['sj'], year_plan[9]['sj'], year_plan[10]['sj'], year_plan[11]['sj']],
                borderColor: "#fff",
                backgroundColor: "#5d5386",
                hoverBackgroundColor: "#483d77"
            }, {
                label: '报检',
                data: [year_plan[0]['bj'], year_plan[1]['bj'], year_plan[2]['bj'], year_plan[3]['bj'], year_plan[4]['bj'], year_plan[5]['bj'], year_plan[6]['bj'], year_plan[7]['bj'], year_plan[8]['bj'], year_plan[9]['bj'], year_plan[10]['bj'], year_plan[11]['bj']],
                borderColor: "#fff",
                backgroundColor: "#e4e8f0",
                hoverBackgroundColor: "#dde1e9"
            }]
        },
        options: {
            responsive: true,
            barRoundness: 1,
            tooltips: {
                backgroundColor: 'rgba(47, 49, 66, 0.8)',
                titleFontSize: 13,
                titleFontColor: '#fff',
                caretSize: 0,
                cornerRadius: 4,
                xPadding: 5,
                displayColors: false,
                yPadding: 5,
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    fontColor: "#2e3451",
                    usePointStyle: true,
                    padding: 50,
                    fontSize: 13
                }
            },
            scales: {
                xAxes: [{
                    barThickness: 20,
                    stacked: false,
                    gridLines: {
                        drawBorder: false,
                        display: false
                    },
                    ticks: {
                        display: true
                    }
                }],
                yAxes: [{
                    stacked: false,
                    gridLines: {
                        drawBorder: false,
                        display: false
                    },
                    ticks: {
                        display: false
                    }
                }]
            }
        }
    });
    $('.circle-orders').circleProgress({
        value: (month_plan - (parseInt(year_plan[0]['sj']) + parseInt(year_plan[0]['bj']))) / month_plan,
        size: 120,
        startAngle: -Math.PI / 2,
        thickness: 6,
        lineCap: 'round',
        emptyFill: '#e4e8f0',
        fill: {
            gradient: ['#5d5386', '#5d5386']
        }
    }).on('circle-animation-progress', function (event, progress) {
        $(this).find('.percent-orders').html(Math.round((month_plan - (parseInt(year_plan[0]['sj']) + parseInt(year_plan[0]['bj']))) / month_plan * progress * 100) + '<i>%</i>');
    });
})(jQuery);
