@extends('layout.master')

@section('content')
    @php $subdomain = request()->route('subdomain'); @endphp
    <div class="tenant-page-header mb-3">
        <div class="card tenant-page-header-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="tenant-page-header-title">{{ __('إنشاء عرض أسهم') }}</div>
                    <p class="tenant-page-header-subtitle mb-0">{{ __('أدخل تفاصيل العرض والصور والسعر وعدد الأسهم') }}</p>
                </div>
                <div class="tenant-page-header-actions">
                    <a href="{{ route('tenant.subdomain.shares.index', ['subdomain' => $subdomain]) }}"
                        class="btn btn-outline-primary">{{ __('رجوع للعروض') }}</a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('tenant.subdomain.shares.store', ['subdomain' => $subdomain]) }}"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('العنوان') }}</label>
                        <input name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('الوصف') }}</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('المدينة') }}</label>
                        <input name="city" class="form-control" required>
                    </div>
                    {{-- تم حذف العنوان التفصيلي حسب الطلب --}}
                    <div class="form-group">
                        <label class="d-flex align-items-center justify-content-between">
                            <span>{{ __('صور العرض') }}</span>
                            <small class="text-muted">{{ __('حد أقصى 15 صورة') }}</small>
                        </label>
                        <div id="offer-images-container"></div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-image">+
                                {{ __('إضافة صورة') }}</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="btn-remove-image">-
                                {{ __('إزالة آخر صورة') }}</button>
                            <span class="small text-muted ml-2" id="offer-images-count">0/15</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('سعر السهم') }}</label>
                        <input name="price_per_share" type="number" step="0.01" min="0" class="form-control"
                            required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('إجمالي الأسهم') }}</label>
                        <input name="total_shares" type="number" min="1" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('الأسهم المتاحة') }}</label>
                        <input name="available_shares" type="number" min="0" class="form-control" required>
                    </div>
                    {{-- تم إخفاء/حذف العملة حسب الطلب --}}
                    <div class="form-group">
                        <label>{{ __('الحالة') }}</label>
                        <select name="status" class="form-control" required>
                            <option value="active">{{ __('نشط') }}</option>
                            <option value="draft">{{ __('مسودة') }}</option>
                            <option value="paused">{{ __('موقوف مؤقتًا') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <button class="btn btn-primary">{{ __('حفظ ومزامنة') }}</button>
            <a href="{{ route('tenant.subdomain.shares.index', ['subdomain' => $subdomain]) }}"
                class="btn btn-light">{{ __('إلغاء') }}</a>
        </form>
    </div>
@endsection

@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var container = document.getElementById('offer-images-container');
            var addBtn = document.getElementById('btn-add-image');
            var removeBtn = document.getElementById('btn-remove-image');
            var countEl = document.getElementById('offer-images-count');
            var maxImages = 15;

            function updateCount() {
                var current = container.querySelectorAll('input[type=file]').length;
                if (countEl) countEl.textContent = current + '/' + maxImages;
                removeBtn.disabled = current === 0;
                addBtn.disabled = current >= maxImages;
            }

            function addImageInput() {
                var current = container.querySelectorAll('input[type=file]').length;
                if (current >= maxImages) return;
                var wrapper = document.createElement('div');
                wrapper.className = 'd-flex align-items-center mb-2';
                var input = document.createElement('input');
                input.type = 'file';
                input.name = 'images[]';
                input.accept = 'image/*';
                input.className = 'form-control';
                input.style.maxWidth = '380px';
                var removeInline = document.createElement('button');
                removeInline.type = 'button';
                removeInline.className = 'btn btn-sm btn-outline-danger ml-2';
                removeInline.textContent = '{{ __('إزالة') }}';
                removeInline.addEventListener('click', function() {
                    wrapper.parentNode.removeChild(wrapper);
                    updateCount();
                });
                wrapper.appendChild(input);
                wrapper.appendChild(removeInline);
                container.appendChild(wrapper);
                updateCount();
            }

            addBtn && addBtn.addEventListener('click', addImageInput);
            removeBtn && removeBtn.addEventListener('click', function() {
                var groups = container.querySelectorAll('div.d-flex');
                if (groups.length) {
                    var last = groups[groups.length - 1];
                    last.parentNode.removeChild(last);
                    updateCount();
                }
            });

            updateCount();
        });
    </script>
    <style>
        /* إخفاء أسهم الحقول الرقمية */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush
