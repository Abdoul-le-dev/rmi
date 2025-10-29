@extends('admin.layouts.app')
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.new_promo') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('admin/main.new_promo') }}</div>
            </div>
        </div>

        <div class="section-body card">
            <div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <h2 class="section-title ml-4">{{ !empty($promo) ? trans('admin/main.edit_promo') : trans('admin/main.new_promo') }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card-body">
                        <form action="{{ getAdminPanelUrl() }}/financial/promo/{{ empty($promo) ? 'store' : $promo->id . '/update' }}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>{{ trans('admin/main.title') }}</label>
                                <input type="text" name="title" class="form-control" value="{{ $promo->title ?? old('title') }}" required>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('admin/main.description') }}</label>
                                <textarea name="description" class="form-control" required>{{ $promo->description ?? old('description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('admin/main.percentage') }}</label>
                                <input type="number" name="percentage" class="form-control" value="{{ $promo->percentage ?? old('percentage') }}" required>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('admin/main.start_date') }}</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $promo->start_date ?? old('start_date') }}" required>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('admin/main.end_date') }}</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $promo->end_date ?? old('end_date') }}" required>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('admin/main.status') }}</label>
                                <select name="status" class="form-control" required>
                                    <option value="active" {{ (isset($promo) && $promo->status == 'active') ? 'selected' : '' }}>{{ trans('admin/main.active') }}</option>
                                    <option value="inactive" {{ (isset($promo) && $promo->status == 'inactive') ? 'selected' : '' }}>{{ trans('admin/main.inactive') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ trans('admin/main.type') }}</label>
                                <select name="type" class="form-control @error('type') is-invalid @enderror">
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" 
                                            {{ !empty($promo) && $promo->type == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
