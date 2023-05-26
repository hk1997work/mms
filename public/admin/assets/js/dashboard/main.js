(function ($) {

    'use strict';

    $.get("/main_ajax", {},
        function (arr) {

            $('#SJ').text(arr['plan'][0]['SJ']);
            $('#BJ').text(arr['plan'][0]['BJ']);
            // ------------------------------------------------------- //
            // Pdf Per
            // ------------------------------------------------------ //
            $('.pdf_per').circleProgress({
                value: arr['pdf_per'] / 100,
                size: 140,
                startAngle: -Math.PI / 2,
                thickness: 6,
                lineCap: 'round',
                emptyFill: '#f0eff4',
                fill: {
                    gradient: ['#e76c90', '#e76c90']
                }
            }).on('circle-animation-progress', function (event, progress) {
                $(this).find('.percent').html(Math.round(arr['pdf_per'] * progress) + '<i>%</i>');
            });

            // ------------------------------------------------------- //
            // Pdf Per
            // ------------------------------------------------------ //
            $('.img_per').circleProgress({
                value: arr['img_per'] / 100,
                size: 140,
                startAngle: -Math.PI / 2,
                thickness: 6,
                lineCap: 'round',
                emptyFill: 'rgba(255, 255, 255, 0.15)',
                fill: {
                    gradient: ['#fff', '#fff']
                }
            }).on('circle-animation-progress', function (event, progress) {
                $(this).find('.percent').html(Math.round(arr['img_per'] * progress) + '<i>%</i>');
            });

            // ------------------------------------------------------- //
            // Delivered Orders
            // ------------------------------------------------------ //
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
                    labels: [arr['plan'][0]['MONTH'], arr['plan'][1]['MONTH'], arr['plan'][2]['MONTH'], arr['plan'][3]['MONTH'], arr['plan'][4]['MONTH'], arr['plan'][5]['MONTH'], arr['plan'][6]['MONTH'], arr['plan'][7]['MONTH'], arr['plan'][8]['MONTH'], arr['plan'][9]['MONTH'], arr['plan'][10]['MONTH'], arr['plan'][11]['MONTH']],
                    datasets: [{
                        label: '送检',
                        data: [arr['plan'][0]['SJ'], arr['plan'][1]['SJ'], arr['plan'][2]['SJ'], arr['plan'][3]['SJ'], arr['plan'][4]['SJ'], arr['plan'][5]['SJ'], arr['plan'][6]['SJ'], arr['plan'][7]['SJ'], arr['plan'][8]['SJ'], arr['plan'][9]['SJ'], arr['plan'][10]['SJ'], arr['plan'][11]['SJ']],
                        borderColor: "#fff",
                        backgroundColor: "#5d5386",
                        hoverBackgroundColor: "#483d77"
                    }, {
                        label: '报检',
                        data: [arr['plan'][0]['BJ'], arr['plan'][1]['BJ'], arr['plan'][2]['BJ'], arr['plan'][3]['BJ'], arr['plan'][4]['BJ'], arr['plan'][5]['BJ'], arr['plan'][6]['BJ'], arr['plan'][7]['BJ'], arr['plan'][8]['BJ'], arr['plan'][9]['BJ'], arr['plan'][10]['BJ'], arr['plan'][11]['BJ']],
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

            // ------------------------------------------------------- //
            // Circle Orders
            // ------------------------------------------------------ //
            $('.circle-orders').circleProgress({
                value: arr['complete_per'] / 100,
                size: 120,
                startAngle: -Math.PI / 2,
                thickness: 6,
                lineCap: 'round',
                emptyFill: '#e4e8f0',
                fill: {
                    gradient: ['#0087a4', '#08a6c3']
                }
            }).on('circle-animation-progress', function (event, progress) {
                $(this).find('.percent-orders').html(Math.round(arr['complete_per'] * progress) + '<i>%</i>');
            });
        })
})(jQuery);
