'use strict';
let index = 0
let swiper_certificate = new Swiper(".swiper-certificate", {
    pagination: {
        el: ".swiper-pagination-certificate", clickable: true, renderBullet: function (index, className) {
            let text = $(this.slides[index]).find('.instrument').text();
            let state = $(this.slides[index]).data('state');
            return '<div class="badge bg-' + state + '-subtle border border-' + state + '-subtle text-' + state + '-emphasis rounded-pill my-1 ' + className + '">' + text + "</div>";
        },
    }, navigation: {
        nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev",
    },
});
let swiper_position = new Swiper(".swiper-position", {
    pagination: {
        el: ".swiper-pagination-position", clickable: true, renderBullet: function (index, className) {
            let text = $(this.slides[index]).text();
            let state = $(this.slides[index]).data('state');
            return '<div class="badge bg-' + state + '-subtle border border-' + state + '-subtle text-' + state + '-emphasis rounded-pill my-1 ' + className + '">' + text + "</div>";
        },
    }, on: {
        slideChangeTransitionStart: function () {
            let position = $('.swiper-position .swiper-slide-active').text();
            let formData = new FormData();
            formData.append('_method', 'put');
            formData.append('_token', csrf_token);
            $.ajax({
                url: "/check/" + encodeURIComponent(position), type: "POST", data: formData, processData: false, contentType: false, success: function (result) {
                    swiper_certificate.removeAllSlides();
                    $.each(result.certificates, function (i, item) {
                        swiper_certificate.appendSlide([` 
                            <div class="swiper-slide" data-state="${item.state}">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <input type="hidden" name="certificate_id" value="${item.id}">
                                        <div>${item.order}</div>
                                        <div class="instrument">${item.instrument}</div>
                                        <div>${item.model}</div>
                                        <div class="mt-4">${item.number}</div>
                                        <div class="text-${item.state}">${item.verification_date}</div>
                                        <div class="text-${item.state}">${item.validity_date}</div>
                                        <div>${item.department}</div>
                                        <a href="#" data-menu="check" data-id="${item.id}" data-pos="up" class="btn-show ${item.date || 'invisible'}" ><div hidden>删除</div><i class="fa-solid fa-eye"></i></a>
                                    </div>
                                    <div class="col-5 text-center my-3">
                                        <div class="certificate-count">${item.count}</div>
                                        <div>累计检查次数</div>
                                    </div>
                                    <div class="col-7 text-center my-3">
                                        <div class=" certificate-date text-${item.state}">${item.date || '待检查'}</div>
                                        <div>最近检查日期</div>
                                    </div>
                                </div>
                            </div>`]);
                    })
                    let position = $('.swiper-pagination-position .swiper-pagination-bullet-active')
                    position.removeClass()
                    position.addClass('badge bg-' + result.position.state + '-subtle border border-' + result.position.state + '-subtle text-' + result.position.state + '-emphasis rounded-pill my-1 swiper-pagination-bullet swiper-pagination-bullet-active')
                    positions.find(item => item.name === result.position.name && item.level === 4).state = result.position.state;
                    let unit = $('.swiper-pagination-unit .swiper-pagination-bullet-active')
                    unit.removeClass()
                    unit.addClass('badge bg-' + result.unit.state + '-subtle border border-' + result.unit.state + '-subtle text-' + result.unit.state + '-emphasis rounded-pill my-1 swiper-pagination-bullet swiper-pagination-bullet-active')
                    unit.find(item => item.name === result.unit.name && item.level === 1).state = result.unit.state;
                    swiper_certificate.slideTo(index)
                    index = 0
                    $("#preloader").fadeOut();
                }, error: function (xhr) {
                    xhr.status == 401 ? document.location.reload() : notifications('加载失败');
                },
            });
        },
    },
});
let swiper_unit = new Swiper(".swiper-unit", {
    pagination: {
        el: ".swiper-pagination-unit", clickable: true, renderBullet: function (index, className) {
            let text = $(this.slides[index]).text();
            let state = $(this.slides[index]).data('state');
            return '<div class="badge bg-' + state + '-subtle border border-' + state + '-subtle text-' + state + '-emphasis rounded-pill my-1 ' + className + '">' + text + "</div>";
        },
    }, on: {
        init: function () {
            this.emit('slideChangeTransitionStart');
        }, slideChangeTransitionStart: function () {
            let unit = $('.swiper-unit .swiper-slide-active').text();
            swiper_position.removeAllSlides();
            $.each(positions, function (i, item) {
                if (item.pname === unit && item.level == 4) {
                    swiper_position.appendSlide([`<div class="swiper-slide" data-state="${item.state}">${item.name}</div>`]);
                }
            })
            swiper_position.emit('slideChangeTransitionStart');
        },
    },
});
