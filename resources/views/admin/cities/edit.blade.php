@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">{{ __('تعديل المدينة') }}: {{ $city->name_ar }}</h4>
                    <p class="text-muted mb-0">{{ __('تحديث تفاصيل المدينة') }}</p>
                </div>
                <a href="{{ route('admin.cities.index') }}" class="btn btn-outline-secondary">
                    <i class="mdi mdi-arrow-left"></i> {{ __('رجوع للقائمة') }}
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">
                        <i class="mdi mdi-alert-circle me-2"></i>{{ __('يوجد أخطاء في النموذج') }}
                    </h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.cities.update', $city) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_ar">
                                        {{ __('الاسم بالعربية') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                                        id="name_ar" name="name_ar" value="{{ old('name_ar', $city->name_ar) }}" required
                                        placeholder="{{ __('مثال: الرياض') }}">
                                    @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_en">{{ __('الاسم بالإنجليزية') }}</label>
                                    <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                                        id="name_en" name="name_en" value="{{ old('name_en', $city->name_en) }}"
                                        placeholder="{{ __('مثال: Riyadh') }}">
                                    @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="region">{{ __('المنطقة') }}</label>
                                    <input type="text" class="form-control @error('region') is-invalid @enderror"
                                        id="region" name="region" value="{{ old('region', $city->region) }}"
                                        placeholder="{{ __('مثال: منطقة الرياض') }}">
                                    @error('region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sort_order">{{ __('الترتيب') }}</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                        id="sort_order" name="sort_order"
                                        value="{{ old('sort_order', $city->sort_order) }}" min="0" placeholder="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('للتحكم في ترتيب ظهور المدينة') }}</small>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="is_active">{{ __('الحالة') }}</label>
                                    <select class="form-control @error('is_active') is-invalid @enderror" id="is_active"
                                        name="is_active">
                                        <option value="1"
                                            {{ old('is_active', $city->is_active) == '1' ? 'selected' : '' }}>
                                            {{ __('نشط') }}</option>
                                        <option value="0"
                                            {{ old('is_active', $city->is_active) == '0' ? 'selected' : '' }}>
                                            {{ __('غير نشط') }}</option>
                                    </select>
                                    @error('is_active')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> {{ __('حفظ التغييرات') }}
                            </button>
                            <a href="{{ route('admin.cities.index') }}" class="btn btn-light">
                                <i class="mdi mdi-close"></i> {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
