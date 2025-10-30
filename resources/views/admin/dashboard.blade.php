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

        

        <div class="row">
            @can('admin_general_dashboard_sales_statistics_chart')
                <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{trans('admin/main.sales_statistics')}}</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <button type="button" class="js-sale-chart-month btn">{{trans('admin/main.month')}}</button>
                                    <button type="button" class="js-sale-chart-year btn btn-primary">{{trans('admin/main.year')}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="position-relative">
                                        <canvas id="saleStatisticsChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    @if(!empty($getMonthAndYearSalesChartStatistics))
                                        <div class="statistic-details mt-4 position-relative">
                                            <div class="statistic-details-item">
                                                <span class="text-muted">
                                                    @if($getMonthAndYearSalesChartStatistics['todaySales']['grow_percent']['status'] == 'up')
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    @endif

                                                    {{ $getMonthAndYearSalesChartStatistics['todaySales']['grow_percent']['percent'] }}
                                                </span>

                                                <div class="detail-value">{{ handlePrice($getMonthAndYearSalesChartStatistics['todaySales']['amount']) }}</div>
                                                <div class="detail-name">{{trans('admin/main.today_sales')}}</div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-muted">
                                                    @if($getMonthAndYearSalesChartStatistics['weekSales']['grow_percent']['status'] == 'up')
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    @endif

                                                    {{ $getMonthAndYearSalesChartStatistics['weekSales']['grow_percent']['percent'] }}
                                                </span>

                                                <div class="detail-value">{{ handlePrice($getMonthAndYearSalesChartStatistics['weekSales']['amount']) }}</div>
                                                <div class="detail-name">{{trans('admin/main.week_sales')}}</div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-muted">
                                                    @if($getMonthAndYearSalesChartStatistics['monthSales']['grow_percent']['status'] == 'up')
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    @endif

                                                    {{ $getMonthAndYearSalesChartStatistics['monthSales']['grow_percent']['percent'] }}
                                                </span>

                                                <div class="detail-value">{{ handlePrice($getMonthAndYearSalesChartStatistics['monthSales']['amount']) }}</div>
                                                <div class="detail-name">{{trans('admin/main.month_sales')}}</div>
                                            </div>
                                            <div class="statistic-details-item">
                                                <span class="text-muted">
                                                    @if($getMonthAndYearSalesChartStatistics['yearSales']['grow_percent']['status'] == 'up')
                                                        <span class="text-primary"><i class="fas fa-caret-up"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-caret-down"></i></span>
                                                    @endif

                                                    {{ $getMonthAndYearSalesChartStatistics['yearSales']['grow_percent']['percent'] }}
                                                </span>

                                                <div class="detail-value">{{ handlePrice($getMonthAndYearSalesChartStatistics['yearSales']['amount']) }}</div>
                                                <div class="detail-name">{{trans('admin/main.year_sales')}}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('admin_general_dashboard_recent_comments')
                <div class="col-lg-4 col-md-12 col-12 col-sm-12 @if(count($recentComments) < 6) pb-30 @endif">
                    <div class="card @if(count($recentComments) < 6) h-100 @endif">
                        <div class="card-header">
                            <h4>{{trans('admin/main.recent_comments')}}</h4>
                        </div>

                        <div class="card-body d-flex flex-column justify-content-between">
                            <ul class="list-unstyled list-unstyled-border">
                                @foreach($recentComments as $recentComment)
                                    <li class="media">
                                        <img class="mr-3 rounded-circle" width="50" height="50" src="{{ $recentComment->user->getAvatar() }}" alt="avatar">
                                        <div class="media-body">
                                            <div class="float-right text-primary font-12">{{ dateTimeFormat($recentComment->created_at, 'j M Y | H:i') }}</div>
                                            <div class="media-title">{{ $recentComment->user->full_name }}</div>
                                            <span class="text-small text-muted">{{ truncate($recentComment->comment, 150) }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="text-center pt-1 pb-1">
                                <a href="{{ getAdminPanelUrl() }}/comments/webinars" class="btn btn-primary btn-lg btn-round ">
                                    {{trans('admin/main.view_all')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>


        <div class="row">

            @can('admin_general_dashboard_recent_tickets')
                @if(!empty($recentTickets))
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h5>{{trans('admin/main.recent_tickets')}}</h5>
                                <div class="card-description">{{ $recentTickets['pendingReply'] }} {{ trans('admin/main.pending_reply') }}</div>
                            </div>

                            <div class="card-body p-0">
                                <div class="tickets-list">

                                    @foreach($recentTickets['tickets'] as $ticket)
                                        <a href="{{ getAdminPanelUrl() }}/supports/{{ $ticket->id }}/conversation" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4>{{ $ticket->title }}</h4>
                                            </div>
                                            <div class="ticket-info">
                                                <div>{{ $ticket->user->full_name }}</div>
                                                <div class="bullet"></div>
                                                @if($ticket->status == 'replied' or $ticket->status == 'open')
                                                    <span class="text-warning  text-small font-600-bold">{{ trans('admin/main.pending_reply') }}</span>
                                                @elseif($ticket->status == 'close')
                                                    <span class="text-danger  text-small font-600-bold">{{ trans('admin/main.close') }}</span>
                                                @else
                                                    <span class="text-primary  text-small font-600-bold">{{ trans('admin/main.replied') }}</span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach

                                    <a href="{{ getAdminPanelUrl() }}/supports" class="ticket-item ticket-more">
                                        {{trans('admin/main.view_all')}} <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

            @can('admin_general_dashboard_recent_webinars')
                @if(!empty($recentWebinars))
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5>{{trans('admin/main.recent_live_classes')}}</h5>
                                <div class="card-description">{{ $recentWebinars['pendingReviews'] }} {{trans('admin/main.pending_review')}}</div>
                            </div>
                            <div class="card-body p-0">
                                <div class="tickets-list">
                                    @foreach($recentWebinars['webinars'] as $webinar)
                                        <a href="{{ getAdminPanelUrl() }}/webinars/{{ $webinar->id }}/edit" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4>{{ $webinar->title }}</h4>
                                            </div>

                                            <div class="ticket-info">
                                                <div>{{ $webinar->teacher->full_name }}</div>
                                                <div class="bullet"></div>
                                                @switch($webinar->status)
                                                    @case(\App\Models\Webinar::$active)
                                                    <span class="text-success">{{ trans('admin/main.publish') }}</span>
                                                    @if($webinar->isProgressing())
                                                        <div class="text-warning text-small font-600-bold">({{  trans('webinars.in_progress') }})</div>
                                                    @elseif($webinar->start_date > time())
                                                        <div class="text-danger text-small font-600-bold">({{  trans('admin/main.not_conducted') }})</div>
                                                    @else
                                                        <span class="text-success text-small font-600-bold">({{ trans('public.finished') }})</span>
                                                    @endif
                                                    @break
                                                    @case(\App\Models\Webinar::$isDraft)
                                                    <span class="text-dark">{{ trans('admin/main.is_draft') }}</span>
                                                    @break
                                                    @case(\App\Models\Webinar::$pending)
                                                    <span class="text-warning">{{ trans('admin/main.waiting') }}</span>
                                                    @break
                                                    @case(\App\Models\Webinar::$inactive)
                                                    <span class="text-danger">{{ trans('public.rejected') }}</span>
                                                    @break
                                                @endswitch
                                            </div>
                                        </a>
                                    @endforeach

                                    <a href="{{ getAdminPanelUrl() }}/webinars?type=webinar" class="ticket-item ticket-more">
                                        {{trans('admin/main.view_all')}} <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

            @can('admin_general_dashboard_recent_courses')
                @if(!empty($recentCourses))
                    <div class="col-md-4">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-play-circle"></i>
                                </div>
                                <h5>{{trans('admin/main.recent_courses')}}</h5>
                                <div class="card-description">{{ $recentCourses['pendingReviews'] }} {{trans('admin/main.pending_review')}}</div>
                            </div>
                            <div class="card-body p-0">
                                <div class="tickets-list">


                                    @foreach($recentCourses['courses'] as $course)
                                        <a href="{{ getAdminPanelUrl() }}/webinars/{{ $course->id }}/edit" class="ticket-item">
                                            <div class="ticket-title">
                                                <h4>{{ $course->title }}</h4>
                                            </div>

                                            <div class="ticket-info">
                                                <div>{{ $course->teacher->full_name }}</div>
                                                <div class="bullet"></div>
                                                @switch($course->status)
                                                    @case(\App\Models\Webinar::$active)
                                                    <span class="text-success">{{ trans('admin/main.publish') }}</span>
                                                    @if($course->isProgressing())
                                                        <div class="text-warning text-small font-600-bold">({{  trans('webinars.in_progress') }})</div>
                                                    @elseif($course->start_date > time())
                                                        <div class="text-danger text-small font-600-bold">({{  trans('admin/main.not_conducted') }})</div>
                                                    @else
                                                        <span class="text-success text-small font-600-bold">({{ trans('public.finished') }})</span>
                                                    @endif
                                                    @break
                                                    @case(\App\Models\Webinar::$isDraft)
                                                    <span class="text-dark">{{ trans('admin/main.is_draft') }}</span>
                                                    @break
                                                    @case(\App\Models\Webinar::$pending)
                                                    <span class="text-warning">{{ trans('admin/main.waiting') }}</span>
                                                    @break
                                                    @case(\App\Models\Webinar::$inactive)
                                                    <span class="text-danger">{{ trans('public.rejected') }}</span>
                                                    @break
                                                @endswitch
                                            </div>
                                        </a>
                                    @endforeach


                                    <a href="{{ getAdminPanelUrl() }}/webinars?type=course" class="ticket-item ticket-more">
                                        {{trans('admin/main.view_all')}} <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan
        </div>

        @can('admin_general_dashboard_users_statistics_chart')
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{trans('admin/main.new_registration_statistics')}}</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    {{--<a href="#" class="btn">Views
                                    </a>--}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="position-relative">
                                        <canvas id="usersStatisticsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
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
