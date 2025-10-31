(function ($) {
    'use strict';

    $("#preloader").fadeOut();

    let swiper = new Swiper("#swiper-main", {loop: "true",});

    //电子证书进度
    $('.circle-certificate .circle').circleProgress({
        value: (pdf_count / pdf_total), size: 140, startAngle: -Math.PI / 2, thickness: 6, lineCap: 'round', emptyFill: '#f0eff4', fill: {
            gradient: ['#e76c90']
        }
    }).on('circle-animation-progress', function (event, progress) {
        $(this).find('strong').html(Math.round((pdf_count / pdf_total) * 100 * progress) + '<i>%</i>');
    })
    //周检计划进度
    $('.circle-plan .circle').circleProgress({
        value: (month_plan - (parseInt(year_plan[0]['sj']) + parseInt(year_plan[0]['bj']))) / month_plan, size: 120, startAngle: -Math.PI / 2, thickness: 6, lineCap: 'round', emptyFill: '#e4e8f0', fill: {
            gradient: ['#5d5386']
        }
    }).on('circle-animation-progress', function (event, progress) {
        $(this).find('strong').html(Math.round((month_plan - (parseInt(year_plan[0]['sj']) + parseInt(year_plan[0]['bj']))) / month_plan * progress * 100) + '<i>%</i>');
    });
    //周检计划柱状图
    let ctx = document.getElementById('chart-plan');
    const labels = year_plan.map(item => parseInt(item.month.split('-')[1]) + '月');
    const sj_data = year_plan.map(item => item.sj);
    const bj_data = year_plan.map(item => item.bj);
    new Chart(ctx, {
        type: 'bar', data: {
            labels: labels, datasets: [{
                label: '送检', data: sj_data, backgroundColor: "#5d5386", hoverBackgroundColor: "#483d77"
            }, {
                label: '报检', data: bj_data, backgroundColor: "#e4e8f0", hoverBackgroundColor: "#dde1e9"
            }]
        }, options: {
            scales: {
                x: {
                    grid: {
                        display: false
                    }, ticks: {
                        display: true
                    }
                }, y: {
                    display: false
                }
            }, plugins: {
                legend: {
                    position: 'bottom', align: 'end', labels: {
                        color: "#2e3451", usePointStyle: true, boxWidth: 8, boxHeight: 8, padding: 15, font: {
                            size: 11
                        }
                    }
                }, tooltip: {
                    caretSize: 0, displayColors: false, callbacks: {
                        title: function (context) {
                            const month = context[0].label;
                            const type = context[0].datasetIndex === 0 ? '送检' : '报检';
                            const value = context[0].parsed.y;
                            const number = year_plan[context[0].dataIndex][context[0].datasetIndex === 0 ? 'sj_instruments' : 'bj_instruments'].split('<br>').length;
                            return `${month} - ${type} ${number} 类 ${value} 件`;
                        }, label: function (context) {
                            return year_plan[context.dataIndex][context.datasetIndex === 0 ? 'sj_instruments' : 'bj_instruments'].split('<br>');
                        },
                    }
                }
            }
        }
    });


    //今日抽检-复制
    $('.btn-copy').on('click', async function () {
        const text = $('#text-copy').html().replace(/<br>/g, '\n');
        await navigator.clipboard.writeText(text);
        notifications('复制成功');
    });

    //今日抽检-抽检详情
    $('.btn-show').click(function () {
        $("#preloader")[0].style.display = 'block';
        $.ajax({
            url: '/sample/' + $(this).data('id'), success: function (data) {
                if (data) {
                    $('.from-up').html(data);
                    $('.from-up').find('.sidebar-btn').text('抽捡详情')
                    $(window).trigger('resize')
                    $('.from-up').addClass('is-visible');
                } else {
                    notifications('查看失败')
                }
                $("#preloader").fadeOut();
            }, error: function (xhr) {
                xhr.status === 401 ? document.location.reload() : notifications('查看失败')
                $("#preloader").fadeOut();
            },
        });
        event.preventDefault()
    });
})(jQuery);
