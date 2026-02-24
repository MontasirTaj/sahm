@extends('layout.master')

@section('title', 'إضافة نوع عقار')

@section('content')

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.property-types.index') }}">أنواع العقارات</a>
                        </li>
                        <li class="breadcrumb-item active">إضافة نوع جديد</li>
                    </ol>
                </nav>
                <h2 class="fw-bold mb-0">
                    <i class="mdi mdi-plus-circle-outline me-2"></i>
                    إضافة نوع عقار جديد
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.property-types.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">الاسم بالعربية <span class="text-danger">*</span></label>
                                <input type="text" name="name_ar"
                                    class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar') }}"
                                    required placeholder="مثال: شقة سكنية">
                                @error('name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">الاسم بالإنجليزية <span class="text-danger">*</span></label>
                                <input type="text" name="name_en"
                                    class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en') }}"
                                    required placeholder="Example: Residential Apartment">
                                @error('name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">الترتيب</label>
                                <input type="number" name="sort_order"
                                    class="form-control @error('sort_order') is-invalid @enderror"
                                    value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                                <small class="form-text text-muted">الأرقام الأقل تظهر أولاً</small>
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        نشط
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save me-1"></i>
                                    حفظ
                                </button>
                                <a href="{{ route('admin.property-types.index') }}" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-arrow-right me-1"></i>
                                    إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
