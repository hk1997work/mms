@extends('layout.show')
@section('content_title')
    <li><a class="active" data-toggle="tab" href="#supervision-tab" role="tab" id="supervision-btn">监督检查</a></li>
@endsection
@section('content_form')
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane show active fade" id="supervision-tab" aria-labelledby="supervision-btn">
            <div class="col-12 ckp text-dark" id="supervision">{!! $str !!}</div>
            <div class="enter-message">
                <button class="btn btn-outline-primary ripple btn-copy">复 制</button>
                <button class="btn btn-outline-secondary ripple sidebar-close">返 回</button>
            </div>
        </div>
    </div>
    <script>
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
    </script>
@endsection
