(function ($) {
    'use strict';

    $("#preloader").fadeOut();

    let swiper = new Swiper("#swiper-main", {loop: "true",});

    //电子证书
    $('.certificate_circle').circleProgress({
        value: (pdf_count / pdf_total),
        size: 120,
        startAngle: -Math.PI / 2,
        thickness: 6,
        lineCap: 'round',
        emptyFill: '#e4e8f0',
        fill: {
            gradient: ['#e76c90', '#5d5386']
        }
    })


    //今日抽检-复制
    $('.btn-copy').click(function () {
        var htmlContent = $('#text-copy')[0].innerHTML;
        var tempTextArea = $('<textarea>');
        tempTextArea.val(htmlContent.replace(/<br>/g, '\n'));
        $('body').append(tempTextArea);
        tempTextArea.select();
        if (document.execCommand('copy')) {
            notifications('复制成功')
        } else {
            notifications('复制失败')
        }
        tempTextArea.remove();
        event.preventDefault()
    });

    //今日抽检-抽检详情
    $('.btn-show').click(function () {
        $("#preloader")[0].style.display = 'block';
        $.ajax({
            url: '/sample/' + $(this).data('id'),
            success: function (data) {
                if (data) {
                    $('.from-up').html(data);
                    $('.from-up').find('.sidebar-btn').text('抽捡详情')
                    $(window).trigger('resize')
                    $('.from-up').addClass('is-visible');
                } else {
                    notifications('查看失败')
                }
                $("#preloader").fadeOut();
            },
            error: function (xhr) {
                xhr.status === 401 ? document.location.reload() : notifications('查看失败')
                $("#preloader").fadeOut();
            },
        });
        event.preventDefault()
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

    Chart.elements.RoundedTopRectangle = Chart.elements.register({
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

    const labels = year_plan.map(item => parseInt(item.MONTH.split('-')[1]) + '月');
    const sj_data = year_plan.map(item => item.sj);
    const bj_data = year_plan.map(item => item.bj);

    const myChart = new Chart(ctx, {
        type: 'roundedBar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: '送检',
                    data: sj_data,
                    borderColor: "#fff",
                    backgroundColor: "#5d5386",
                    hoverBackgroundColor: "#483d77"
                },
                {
                    label: '报检',
                    data: bj_data,
                    borderColor: "#fff",
                    backgroundColor: "#e4e8f0",
                    hoverBackgroundColor: "#dde1e9"
                }
            ]
        },
        options: {
            responsive: true,
            barRoundness: 1,
            tooltips: {
                backgroundColor: 'rgba(47, 49, 66, 0.8)',
                titleFontSize: 12,
                titleFontColor: '#fff',
                caretSize: 0,
                cornerRadius: 4,
                xPadding: 5,
                displayColors: false,
                yPadding: 5,
                callbacks: {
                    label: function(tooltipItem) {
                        const datasetLabel = tooltipItem.datasetIndex === 0 ? '送检' : '报检';
                        const value = tooltipItem.yLabel;

                        const instruments = year_plan[tooltipItem.index][
                            tooltipItem.datasetIndex === 0 ? 'sj_instruments' : 'bj_instruments'
                            ];

                        return [
                            `${datasetLabel}: ${value}`,
                            ...instruments.split('<br>')
                        ];
                    }
                }
            },
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    fontColor: "#2e3451",
                    usePointStyle: true,
                    padding: 50,
                    fontSize: 12
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
                        display: false,
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    $('#swiper-main .swiper-slide >.row:eq(1)').css('min-height', container_fluid_height - $('#swiper-main .swiper-slide >.row:eq(0)').height())

})(jQuery);
