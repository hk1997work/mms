@extends('layout.main')

@section('content')
    <div class="row flex-row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-end">
                <ul class="nav">
                    @yield('content_btn')
                </ul>
            </div>
            <div class="table-responsive">
                @yield("content_table")
            </div>
        </div>
    </div>
@endsection

@push('page-css')
    @stack('page-css-after-1')
@endpush
@push('page-js-after')
    @stack('page-js-after-1')
@endpush
