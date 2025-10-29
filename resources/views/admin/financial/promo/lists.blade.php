@extends('admin.layouts.app')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.list_promo') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.list_promo') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped font-14">
                                <tr>
                                    <th class="text-left">{{ trans('admin/main.title') }}</th>
                                    <th class="text-center">{{ trans('admin/main.percentage') }}</th>
                                    <th class="text-center">{{ trans('admin/main.start_date') }}</th>
                                    <th class="text-center">{{ trans('admin/main.end_date') }}</th>
                                    <th>{{ trans('admin/main.actions') }}</th>
                                </tr>
                                @foreach($promos as $promo)
                                    <tr>
                                        <td class="text-left">{{ $promo->title }}</td>
                                        <td class="text-center">{{ $promo->percentage }}%</td>
                                        <td class="text-center">{{ $promo->start_date }}</td>
                                        <td class="text-center">{{ $promo->end_date }}</td>
                                        <td>
                                            <a href="{{ getAdminPanelUrl() }}/financial/promo/{{ $promo->id }}/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ getAdminPanelUrl() }}/financial/promo/{{ $promo->id }}/delete" class="btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.delete') }}">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
