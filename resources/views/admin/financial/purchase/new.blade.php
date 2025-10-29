@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.new_purchase') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.new_purchase_module') }}</div>
            </div>
        </div>


        <div class="section-body card">

            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <h2 class="section-title ml-4">{{ !empty($purchasemodel) ? trans('admin/main.edit') : trans('admin/main.create') }}</h2>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                        <div class="card-body">
                            <form action="{{ getAdminPanelUrl() }}/financial/purchase-model/{{ !empty($purchasemodel) ? $purchasemodel->id.'/update' : 'store' }}" method="Post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label>{{ trans('admin/main.title') }}</label>
                                    <input type="text" name="title"
                                           class="form-control  @error('title') is-invalid @enderror"
                                           value="{{ !empty($purchasemodel) ? $purchasemodel->title : old('title') }}"/>
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('admin/main.price') }} ({{ $currency }})</label>
                                    <input type="text" name="price"
                                           class="form-control  @error('price') is-invalid @enderror"
                                           value="{{ !empty($purchasemodel) ? $purchasemodel->price : old('price') }}"/>
                                    @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('admin/main.actual_price') }} ({{ $currency }})</label>
                                    <input type="text" name="actual_price"
                                        class="form-control @error('actual_price') is-invalid @enderror"
                                        value="{{ !empty($purchasemodel) ? $purchasemodel->actual_price : old('actual_price') }}"/>
                                    @error('actual_price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Status Dropdown -->
                                <div class="form-group">
                                    <label>{{ trans('admin/main.status') }}</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="active"
                                        {{ (!empty($purchasemodel) && $purchasemodel->status == 'active') ? 'selected' : '' }}
                                        >{{ trans('admin/main.active') }}</option>
                                        <option value="inactive"
                                        {{ (!empty($purchasemodel) && $purchasemodel->status == 'inactive') ? 'selected' : '' }}
                                        >{{ trans('admin/main.not_active') }}</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <!-- Subscription Dropdown -->
                                <div class="form-group">
                                    <label>{{ trans('admin/main.subscription') }}</label>
                                    <select name="subscription" class="form-control @error('subscription') is-invalid @enderror">
                                        @foreach ($subscribes as $subscribe )
                                            <option value="{{$subscribe->id}}"
                                            {{ (!empty($purchasemodel) && $purchasemodel->subscribe_id == $subscribe->id) ? 'selected' : '' }}
                                            >{{ $subscribe->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('subscription')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('admin/main.popular') }}</label>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="is_popular" value="0">
                                        <!-- Toggle button -->
                                        <input type="checkbox" name="is_popular"
                                            class="custom-control-input" id="isPopularSwitch"
                                            value="1" {{ !empty($purchasemodel) && $purchasemodel->is_popular ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="isPopularSwitch"></label>
                                    </div>
                                </div>
                                <div class=" mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </section>
@endsection
