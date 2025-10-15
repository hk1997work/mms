@extends('layout.index')
@section('content_table')
    <div class="swiper-pagination-unit text-center my-5"></div>
    <div class="swiper-pagination-position text-center my-5"></div>
    <div class="swiper swiper-unit">
        <div class="swiper-wrapper" hidden>
            @foreach($positions->where('level',1) as $position)
                <div class="swiper-slide" data-state="{{$position->state}}">{{$position->name}}</div>
            @endforeach
        </div>
    </div>
    <div class="swiper swiper-position">
        <div class="swiper-wrapper" hidden></div>
    </div>
    <div class="swiper swiper-certificate my-5">
        <div class="swiper-wrapper"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    <div class="text-center my-5">
        <input type="file" id="file-upload" accept="image/*" capture hidden>
        <a href="#" class="btn btn-outline-primary" id="btn-upload">上传照片</a>
    </div>
    <div class="swiper-pagination-certificate text-center my-5"></div>
@endsection
@push('page-js-after-1')
    <script>
        let positions = {!! $positions !!};
    </script>
    <script src="/admin/assets/js/pages/check.js"></script>
@endpush
