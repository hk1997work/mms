$(document).ready(function () {
    $('.btn-copy').click(function () {
        let htmlContent = $('#supervision')[0].innerHTML;
        let tempTextArea = $('<textarea>');
        tempTextArea.val(htmlContent.replace(/<br>/g, '\n'));
        $('body').append(tempTextArea);
        tempTextArea.select();
        if (document.execCommand('copy')) {
            notifications('复制成功')
            $(this).closest('.off-sidebar').removeClass('is-visible');
        } else {
            notifications('复制失败')
        }
        tempTextArea.remove();
    });
});