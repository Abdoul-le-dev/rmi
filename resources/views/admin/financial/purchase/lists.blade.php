@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.purchase_packages') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.purchase_packages') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <!-- <th>#</th> -->
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th class="text-left">{{ trans('admin/main.sales') }}</th>
                                        <th class="text-center">{{ trans('admin/main.price') }}</th>
                                        <th class="text-center">{{ trans('admin/main.created_at') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($purchasemodel as $purchasemodels)
                                        <tr>
                                            <!-- <td>
                                                <img src="{{ $purchasemodels->icon }}" width="50" height="50" alt="">
                                            </td> -->
                                            <td class="text-left">{{ $purchasemodels->title }}</td>
                                            <td class="text-left">{{ $purchasemodels->purchase_sales_count }}</td>
                                            <td class="text-center">{{ handlePrice($purchasemodels->price) }}</td>

                                            <td class="text-center">{{ dateTimeFormat($purchasemodels->created_at, 'Y M j | H:i') }}</td>
                                            <td>
                                                @can('admin_subscribe_edit')
                                                    <a href="{{ getAdminPanelUrl() }}/financial/purchase-model/{{ $purchasemodels->id }}/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_subscribe_delete')
                                                    @include('admin.includes.delete_button',['url' => getAdminPanelUrl().'/financial/purchase-model/'. $purchasemodels->id.'/delete','btnClass' => 'btn-sm'])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $purchasemodel->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.subscribes_list_hint_title_1')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.subscribes_list_hint_description_1')}}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.subscribes_list_hint_title_2')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.subscribes_list_hint_description_2')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
