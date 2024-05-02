'use strict';
var index = 0
let swiper_certificate = new Swiper(".swiper-certificate", {
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
        renderBullet: function (index, className) {
            return '<span class="' + className + ' alert-' + $('.swiper-certificate').find('.swiper-slide:eq(' + index + ')').find('[name="certificate_color"]').val() + '"></span>';
        },
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    autoHeight: true,
});
let swiper_position = new Swiper(".swiper-position", {
    on: {
        slideChangeTransitionStart: function () {
            let position_id = $('.swiper-position').find('.swiper-slide:eq(' + this.activeIndex + ')').data('id')
            let formData = new FormData();
            formData.append('_method', 'put');
            formData.append('_token', csrf_token);
            $.ajax({
                url: "/check/" + position_id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (result) {
                    swiper_certificate.removeAllSlides();
                    $.each(result, function (i, item) {
                        swiper_certificate.appendSlide([
                            `<div class="swiper-slide">
                            <div class="row no-margin justify-content-center">
                                <div class="col-12 col-xl-12 col-md-12 col-sm-12">
                                    <div class="row no-margin align-items-center">
                                        <div class="col-12 no-padding text-center">
                                            <div class="chart-text">
                                                <span class="heading">${item.order}</span>
                                                <span class="number">${item.instrument}</span>
                                                <div class="cxg">${item.model}</div>
                                                <input type="hidden" name="certificate_id" value="${item.id}">
                                                <input type="hidden" name="certificate_color" value="${item.color}">
                                            </div>
                                        </div>
                                        <div class="col-12 no-padding text-center mt-4">
                                            <div class="chart-text">
                                                <span class="number">${item.number}</span>
                                                <div class="cxg  text-${item.color}">${item.verification_date}</div>
                                                <div class="cxg  text-${item.color}">${item.validity_date}</div>
                                                <span class="heading">${item.department}</span>
                                                <a href="#" data-menu="check" data-id="${item.id}" data-pos="up" class="number btn-show" ${item.hidden}><i class="la la-eye"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="social-stats mt-5">
                                        <div class="row d-flex justify-content-between">
                                            <div class="col-5 text-center">
                                                <div class="counter certificate-count">${item.count}</div>
                                                <div class="heading">累计检查次数</div>
                                            </div>
                                            <div class="col-7 text-center">
                                                <div class="counter certificate-date text-${item.color}">${item.last}</div>
                                                <div class="heading">最近检查日期</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`,
                        ]);
                    })
                    swiper_certificate.slideTo(index)
                    index = 0
                    $("#preloader").fadeOut();
                },
                error: function (xhr) {
                    xhr.status == 401 ? document.location.reload() : notifications('加载失败')
                },
            });
        },
    },
});
let swiper_unit = new Swiper(".swiper-unit", {
    on: {
        slideChangeTransitionStart: function () {
            let unit_id = $('.swiper-unit').find('.swiper-slide:eq(' + this.activeIndex + ')').data('id')
            swiper_position.removeAllSlides();
            $.each(positions, function (i, item) {
                if (item.id3 === unit_id) {
                    swiper_position.appendSlide([
                        `<div class="swiper-slide author-name" data-id="${item.id}">${item.name1}</div>`,
                    ]);
                }
            })
            swiper_position.emit('slideChangeTransitionStart');
        },
    },
});
let swiper_type = new Swiper(".swiper-type", {
    on: {
        init: function () {
            this.emit('slideChangeTransitionStart');
        },
        slideChangeTransitionStart: function () {
            let type_id = $('.swiper-type').find('.swiper-slide:eq(' + this.activeIndex + ')').data('id')
            swiper_unit.removeAllSlides();
            $.each(positions, function (i, item) {
                if (item.id2 === type_id) {
                    swiper_unit.appendSlide([
                        `<div class="swiper-slide author-name" data-id="${item.id}">${item.name1}</div>`,
                    ]);
                }
            })
            swiper_unit.emit('slideChangeTransitionStart');
        },
    },
});
