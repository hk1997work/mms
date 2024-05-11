@extends('layout.index')
@section('content_table')
    <div class="widget widget-05">
        <div class="widget-body no-padding hidden">
            <div class="swiper-type">
                <div class="swiper-wrapper">
                    @foreach($positions->where('level',2) as $position)
                        <div class="swiper-slide author-name" data-id="{{$position->id}}">{{$position->name1}}</div>
                    @endforeach
                </div>
            </div>
            <div class="author-avatar"></div>
            <div class="swiper-unit">
                <div class="swiper-wrapper"></div>
            </div>
            <div class="swiper-position">
                <div class="swiper-wrapper"></div>
            </div>
            <div class="swiper swiper-certificate">
                <div class="swiper-wrapper mt-5 mb-5"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="actions text-center mt-5">
                <input type="file" id="file-upload" accept="image/*" capture hidden>
                <a href="#" class="btn btn-primary" id="btn-upload">上传照片</a>
            </div>
        </div>
    </div>
@endsection
@push('page-css-after-1')
    <link rel="stylesheet" href="/admin/assets/css/swiper/swiper-bundle.css">
@endpush
@push('page-js-after-1')
    <script>
        let positions = {!! $positions !!};
    </script>
    <script src="/admin/assets/vendors/js/swiper/swiper-bundle.js"></script>
    <script src="/admin/assets/js/components/swiper/swiper.js"></script>
    <script src="/admin/assets/js/pages/check.js"></script>
@endpush
