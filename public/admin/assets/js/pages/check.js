'use strict';
$('#btn-upload').click(function () {
    $('#file-upload').click();
})
$('#file-upload').on('change', function () {
    if ($('#file-upload').val()) {
        $("#preloader").show();
        let formData = new FormData();
        formData.append('file', $('#file-upload')[0].files[0]);
        formData.append('_token', csrf_token);
        formData.append('certificate_id', $('.swiper-certificate').find('.swiper-slide:eq(' + swiper_certificate.activeIndex + ')').find('[name="certificate_id"]').val());
        $.ajax({
            url: "/check", type: "POST", data: formData, processData: false, contentType: false, success: function (result) {
                index = swiper_certificate.activeIndex;
                swiper_position.emit('slideChangeTransitionStart');
                if (result == true) {
                    notifications('上传成功');
                } else if (result == false) {
                    notifications('上传失败');
                } else {
                    notifications(result);
                }
                $('#file-upload').val('');
            }, error: function (xhr) {
                xhr.status == 401 ? document.location.reload() : notifications('上传失败');
                $("#preloader").fadeOut();
                $('#file-upload').val('');
            },
        });
    }
});

