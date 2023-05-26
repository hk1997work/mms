@extends('layout.main')

@section('content')
    <form action="/nanjing/certificate" method="post" id="form_nanjing">
        {{method_field("put")}}
        {{csrf_field()}}
        <input type="hidden" name="json" id="json">
    </form>
@endsection

@push('page-js-after')
    @if(isset($result))
        <script>
            $(function () {
                $.cookie("nanjing", $("#cookie").val(), {expires: 7});
                var parseData = $.base64.decode("{{$result}}", "utf-8");
                $("#json").val(parseData);
                $("#form_nanjing").submit();
            });
        </script>
    @endif
@endpush
