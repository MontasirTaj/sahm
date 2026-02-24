@extends('layout.master')

@section('content')
    @php $subdomain = request()->route('subdomain'); @endphp
    <div class="tenant-page-header mb-3">
        <div class="card tenant-page-header-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="tenant-page-header-title">{{ __('تعديل عرض أسهم') }}</div>
                    <p class="tenant-page-header-subtitle mb-0">{{ __('قم بتحديث تفاصيل العرض والصور والحالة') }}</p>
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
        <form method="POST" enctype="multipart/form-data"
            action="{{ route('tenant.subdomain.shares.update', ['subdomain' => $subdomain, 'share' => $offer->id]) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('العنوان') }}</label>
                        <input name="title" class="form-control" required value="{{ $offer->title }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('الوصف') }}</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ $offer->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('المدينة') }}</label>
                        <select name="city" id="city-select" class="form-control" required>
                            <option value="">{{ __('-- اختر المدينة --') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->name }}"
                                    {{ old('city', $offer->city) == $city->name ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('نوع العقار') }} <span class="text-danger">*</span></label>
                        <select name="property_type" class="form-control" required>
                            <option value="">{{ __('-- اختر نوع العقار --') }}</option>
                            @foreach ($propertyTypes as $type)
                                <option value="{{ $type->name_ar }}"
                                    {{ old('property_type', $offer->property_type) == $type->name_ar ? 'selected' : '' }}>
                                    {{ $type->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- تم حذف العنوان التفصيلي من النموذج --}}
                    <div class="form-group">
                        <label class="d-flex align-items-center justify-content-between">
                            <span>{{ __('صور جديدة') }}</span>
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
                        @if (is_array($offer->media) && count($offer->media))
                            <div class="mt-3">
                                <label class="d-block">{{ __('الصور الحالية') }}</label>
                                <div class="d-flex flex-wrap" id="current-media">
                                    @foreach ($offer->media as $img)
                                        <div class="position-relative mr-2 mb-2 media-item" data-path="{{ $img }}"
                                            style="width:120px;height:90px;overflow:hidden;border:1px solid #eee;border-radius:6px;">
                                            <img src="{{ asset('storage/' . $img) }}" alt="صورة العرض"
                                                style="width:100%;height:100%;object-fit:cover;"
                                                onerror="this.onerror=null; this.src='{{ asset('assets/images/placeholder.png') }}'; this.alt='فشل تحميل الصورة';">
                                            <button type="button" class="btn btn-sm btn-danger position-absolute"
                                                title="{{ __('حذف') }}"
                                                style="top:4px;{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}:4px"
                                                data-action="remove-image">&times;</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('سعر السهم') }}</label>
                        <input name="price_per_share" type="number" step="0.01" min="0" class="form-control"
                            required value="{{ $offer->price_per_share }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('إجمالي الأسهم') }}</label>
                        <input name="total_shares" type="number" min="1" class="form-control" required
                            value="{{ $offer->total_shares }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('الأسهم المتاحة') }}</label>
                        <input name="available_shares" type="number" min="0" class="form-control" required
                            value="{{ $offer->available_shares }}">
                    </div>
                    {{-- تم إخفاء/حذف العملة من النموذج --}}
                    <div class="form-group">
                        <label>{{ __('الحالة') }}</label>
                        <select name="status" class="form-control" required>
                            @php
                                $statuses = [
                                    'draft' => 'مسودة',
                                    'active' => 'نشط',
                                    'paused' => 'متوقف مؤقتاً',
                                    'completed' => 'مكتمل',
                                    'cancelled' => 'ملغي',
                                ];
                            @endphp
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" @selected($offer->status === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
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

            // Handle media delete
            const mediaWrap = document.getElementById('current-media');
            if (mediaWrap) {
                mediaWrap.addEventListener('click', function(e) {
                    const btn = e.target.closest('button[data-action="remove-image"]');
                    if (!btn) return;
                    const item = btn.closest('.media-item');
                    const path = item?.dataset?.path;
                    if (!path) return;

                    // التحقق من عدد الصور المتبقية
                    const currentImages = mediaWrap.querySelectorAll('.media-item').length;
                    if (currentImages <= 1) {
                        showToast(
                            '{{ __('لا يمكن حذف جميع الصور. يجب أن يبقى صورة واحدة على الأقل للعرض') }}',
                            true);
                        return;
                    }

                    if (!confirm('{{ __('حذف هذه الصورة؟') }}')) return;
                    const url =
                        '{{ route('tenant.subdomain.shares.media.remove', ['subdomain' => $subdomain, 'share' => $offer->id]) }}';
                    const fd = new FormData();
                    fd.append('image', path);
                    fd.append('_token', '{{ csrf_token() }}');
                    fetch(url, {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => {
                            if (!r.ok) {
                                return r.json().then(data => {
                                    throw new Error(data.error || 'fail');
                                });
                            }
                            return r.json();
                        })
                        .then((data) => {
                            if (data.ok) {
                                item.remove();
                                showToast('{{ __('تم حذف الصورة') }}');
                            } else {
                                showToast(data.error || '{{ __('تعذر حذف الصورة') }}', true);
                            }
                        })
                        .catch((err) => {
                            showToast(err.message || '{{ __('تعذر حذف الصورة') }}', true);
                        });
                });
            }

            function showToast(message, isError) {
                let toast = document.getElementById('edit-offer-toast');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'edit-offer-toast';
                    toast.className = 'toast align-items-center text-bg-' + (isError ? 'danger' : 'success') +
                        ' border-0 position-fixed bottom-0 end-0 m-3';
                    toast.setAttribute('role', 'alert');
                    toast.setAttribute('aria-live', 'assertive');
                    toast.setAttribute('aria-atomic', 'true');
                    toast.innerHTML =
                        '<div class="d-flex"><div class="toast-body"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>';
                    document.body.appendChild(toast);
                }
                toast.querySelector('.toast-body').textContent = message;
                const t = new bootstrap.Toast(toast, {
                    delay: 1800
                });
                t.show();
            }
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

@push('plugin-styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush

@push('custom-scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#city-select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '{{ __('-- اختر المدينة --') }}',
                allowClear: false,
                language: {
                    noResults: function() {
                        return 'لا توجد نتائج';
                    },
                    searching: function() {
                        return 'جاري البحث...';
                    }
                }
            });
        });
    </script>
@endpush
