@extends('layout.master')

@section('title', 'إدارة أنواع العقارات')

@section('content')

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">
                            <i class="mdi mdi-home-variant-outline me-2"></i>
                            إدارة أنواع العقارات
                        </h2>
                        <p class="text-muted mb-0">إضافة وتعديل وحذف أنواع العقارات</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.property-types.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i>
                            إضافة نوع جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Property Types List -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if ($propertyTypes->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>الاسم بالعربية</th>
                                    <th>الاسم بالإنجليزية</th>
                                    <th>الترتيب</th>
                                    <th>الحالة</th>
                                    <th width="200">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($propertyTypes as $type)
                                    <tr>
                                        <td>{{ $type->id }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $type->name_ar }}</span>
                                        </td>
                                        <td>{{ $type->name_en }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $type->sort_order }}</span>
                                        </td>
                                        <td>
                                            @if ($type->is_active)
                                                <span class="badge bg-success">
                                                    <i class="mdi mdi-check me-1"></i>
                                                    نشط
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="mdi mdi-close me-1"></i>
                                                    غير نشط
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.property-types.edit', $type->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-pencil me-1"></i>
                                                تعديل
                                            </a>
                                            <form action="{{ route('admin.property-types.destroy', $type->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذا النوع؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="mdi mdi-delete me-1"></i>
                                                    حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-5 text-center text-muted">
                        <i class="mdi mdi-home-variant-outline" style="font-size: 64px; opacity: 0.3;"></i>
                        <p class="mb-0 mt-3 fs-5">لا توجد أنواع عقارات</p>
                        <a href="{{ route('admin.property-types.create') }}" class="btn btn-primary mt-3">
                            <i class="mdi mdi-plus me-1"></i>
                            إضافة نوع جديد
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
