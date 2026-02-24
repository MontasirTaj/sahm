@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">{{ __('إدارة المدن') }}</h4>
                    <p class="text-muted mb-0">{{ __('إضافة وتعديل وحذف المدن المتاحة في النظام') }}</p>
                </div>
                <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> {{ __('إضافة مدينة جديدة') }}
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="citiesTable" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('الاسم بالعربية') }}</th>
                                    <th>{{ __('الاسم بالإنجليزية') }}</th>
                                    <th>{{ __('المنطقة') }}</th>
                                    <th>{{ __('الترتيب') }}</th>
                                    <th>{{ __('الحالة') }}</th>
                                    <th>{{ __('الإجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cities as $city)
                                    <tr>
                                        <td>{{ $city->id }}</td>
                                        <td>
                                            <strong>{{ $city->name_ar }}</strong>
                                        </td>
                                        <td>{{ $city->name_en ?? '-' }}</td>
                                        <td>{{ $city->region ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $city->sort_order }}</span>
                                        </td>
                                        <td>
                                            @if ($city->is_active)
                                                <span class="badge badge-success">
                                                    <i class="mdi mdi-check-circle"></i> {{ __('نشط') }}
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="mdi mdi-close-circle"></i> {{ __('غير نشط') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.cities.edit', $city) }}"
                                                    class="btn btn-sm btn-outline-primary" title="{{ __('تعديل') }}">
                                                    <i class="mdi mdi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.cities.destroy', $city) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذه المدينة؟') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="{{ __('حذف') }}">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugin-styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
@endpush

@push('custom-scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#citiesTable').DataTable({
                language: {
                    "sProcessing": "جارٍ التحميل...",
                    "sLengthMenu": "أظهر _MENU_ مدخلات",
                    "sZeroRecords": "لم يعثر على أية سجلات",
                    "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                    "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                    "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                    "sInfoPostFix": "",
                    "sSearch": "ابحث:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "الأول",
                        "sPrevious": "السابق",
                        "sNext": "التالي",
                        "sLast": "الأخير"
                    },
                    "oAria": {
                        "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                        "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                    },
                    "select": {
                        "rows": {
                            "_": "%d صفوف محددة",
                            "0": "",
                            "1": "صف واحد محدد"
                        }
                    },
                    "buttons": {
                        "print": "طباعة",
                        "copy": "نسخ",
                        "copyTitle": "نسخ إلى الحافظة",
                        "copySuccess": {
                            "_": "%d صف تم نسخه",
                            "1": "صف واحد تم نسخه"
                        }
                    }
                },
                order: [
                    [4, 'asc'],
                    [1, 'asc']
                ],
                pageLength: 25,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                responsive: true
            });
        });
    </script>
@endpush
