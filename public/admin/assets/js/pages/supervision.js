$('.btn-copy').on('click', async function () {
    const text = $('#supervision').html().replace(/<br>/g, '\n');
    await navigator.clipboard.writeText(text);
    notifications('复制成功');
    $(this).closest('.off-sidebar').removeClass('is-visible');
});