@extends("layout.main")
@section("content")
    <!-- Begin Row -->
    <div class="row flex-row">
        <div class="col-xl-4 col-md-6">
            <div class="widget widget-21 has-shadow">
                <div class="widget-body h-100 d-flex align-items-center">
                    <div class="section-title">
                        <h3>证书扫描</h3>
                    </div>
                    <div class="pdf_per">
                        <div class="percent"></div>
                    </div>
                    <div class="value-progress">

                    </div>
                </div>
            </div>
            <div class="widget widget-22 bg-gradient-03 has-shadow">
                <div class="widget-body h-100 d-flex align-items-center">
                    <div class="section-title">
                        <h3>符合性验证</h3>
                    </div>
                    <div class="img_per">
                        <div class="percent"></div>
                    </div>
                    <div class="value-progress">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-md-6">
            <!-- Begin Widget 09 -->
            <div class="widget widget-09 has-shadow">
                <!-- Begin Widget Header -->
                <div class="widget-header d-flex align-items-center">
                    <h2>检定计划</h2>
                    <div class="widget-options">
                        <div class="dropdown">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    class="dropdown-toggle">
                                <i class="la la-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Widget Header -->
                <!-- Begin Widget Body -->
                <div class="widget-body">
                    <div class="row">
                        <div class="col-xl-10 col-12 no-padding">
                            <div>
                                <canvas id="orders"></canvas>
                            </div>
                        </div>
                        <div class="col-xl-2 col-12 d-flex flex-column my-auto no-padding text-center">
                            <div class="new-orders">
                                <div class="title">本月完成率</div>
                                <div class="circle-orders">
                                    <div class="percent-orders"></div>
                                </div>
                            </div>
                            <div class="some-stats mt-5">
                                <div class="title">本月待送检</div>
                                <div class="number text-blue" id="SJ"></div>
                            </div>
                            <div class="some-stats mt-3">
                                <div class="title">本月待报检</div>
                                <div class="number text-blue" id="BJ"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Widget 09 -->
        </div>
    </div>
    <!-- End Row -->
@endsection
@push('page-css')
    <link rel="stylesheet" href="/admin/assets/css/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="/admin/assets/css/owl-carousel/owl.theme.min.css">
@endpush
@push('page-js-after')
    <script src="/admin/assets/vendors/js/chart/chart.min.js"></script>
    <script src="/admin/assets/vendors/js/progress/circle-progress.min.js"></script>

    <script src="/admin/assets/js/dashboard/main.js"></script>
@endpush
