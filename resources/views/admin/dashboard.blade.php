@extends('admin.layouts.app')

@push('libraries_top')
    <link rel="stylesheet" href="/assets/admin/vendor/owl.carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/owl.carousel/owl.theme.min.css">

@endpush

@section('content')


    <section class="section">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="hero text-white hero-bg-image hero-bg" data-background="{{ !empty(getPageBackgroundSettings('admin_dashboard')) ? getPageBackgroundSettings('admin_dashboard') : '' }}">
                    <div class="hero-inner">
                        <h2>{{trans('admin/main.welcome')}}, {{ $authUser->full_name }}!</h2>

                        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                            @can('admin_general_dashboard_quick_access_links')
                                <div>
                                    <p class="lead">{{trans('admin/main.welcome_card_text')}}</p>

                                    <div class="mt-2 mb-2 d-flex flex-column flex-md-row">
                                        <a href="{{ getAdminPanelUrl() }}/comments/webinars" class="mt-2 mt-md-0 btn btn-outline-white btn-lg btn-icon icon-left ml-0 ml-md-2"><i class="far fa-comment"></i>{{trans('admin/main.comments')}} </a>
                                        <a href="{{ getAdminPanelUrl() }}/supports" class="mt-2 mt-md-0 btn btn-outline-white btn-lg btn-icon icon-left ml-0 ml-md-2"><i class="far fa-envelope"></i>{{trans('admin/main.tickets')}}</a>
                                        <a href="{{ getAdminPanelUrl() }}/reports/webinars" class="mt-2 mt-md-0 btn btn-outline-white btn-lg btn-icon icon-left ml-0 ml-md-2"><i class="fas fa-info"></i>{{trans('admin/main.reports')}}</a>
                                    </div>
                                </div>
                            @endcan

                            @can('admin_clear_cache')
                                <div class="w-xs-to-lg-100">
                                    <p class="lead d-none d-lg-block">&nbsp;</p>

                                    @include('admin.includes.delete_button',[
                                             'url' => getAdminPanelUrl().'/clear-cache',
                                             'btnClass' => 'btn btn-outline-white btn-lg btn-icon icon-left mt-2 w-100',
                                             'btnText' => trans('admin/main.clear_all_cache'),
                                             'hideDefaultClass' => true
                                          ])
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/admin/vendor/owl.carousel/owl.carousel.min.js"></script>

    <script src="/assets/admin/js/dashboard.min.js"></script>

    <script>
        (function ($) {
            "use strict";

            @if(!empty($getMonthAndYearSalesChart))
            makeStatisticsChart('saleStatisticsChart', saleStatisticsChart, 'Sale', @json($getMonthAndYearSalesChart['labels']),@json($getMonthAndYearSalesChart['data']));
            @endif

            @if(!empty($usersStatisticsChart))
            makeStatisticsChart('usersStatisticsChart', usersStatisticsChart, 'Users', @json($usersStatisticsChart['labels']),@json($usersStatisticsChart['data']));
            @endif

        })(jQuery)
    </script>
@endpush
