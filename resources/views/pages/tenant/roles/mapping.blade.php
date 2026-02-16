@extends('layout.master')

@section('content')
    @php
        $sub = request()->route('subdomain');
    @endphp
    <div class="row tenant-page-header">
        <div class="col-xl-10 mx-auto">
            <div class="card tenant-page-header-card">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h2 class="tenant-page-header-title">{{ __('ربط الأدوار بالصلاحيات') }}</h2>
                        <p class="tenant-page-header-subtitle">{{ __('فعّل أو عطّل الصلاحيات لكل دور') }}</p>
                    </div>
                    <div class="tenant-page-header-actions mt-3 mt-md-0">
                        <a href="{{ route('tenant.subdomain.dashboard', ['subdomain' => $sub]) }}"
                            class="btn btn-outline-primary">
                            <i class="mdi mdi-view-dashboard-outline"></i>
                            <span>{{ __('app.tenant_panel') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-10 mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-secondary">{{ __('إدارة الصلاحيات لكل دور') }}</h5>
                <input id="perm-filter" type="text" class="form-control form-control-sm"
                    placeholder="{{ __('بحث عن صلاحية') }}">
            </div>

            <div class="row g-4">
                @foreach ($roles as $role)
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary rounded-pill me-2">{{ $role->name }}</span>
                                    <small class="text-muted">{{ __('الصلاحيات المفعلة') }}:
                                        {{ $role->permissions->count() }}</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                    @foreach ($permissions as $perm)
                                        @php $has = $role->permissions->contains('id', $perm->id); @endphp
                                        <div class="col perm-col" data-perm="{{ strtolower($perm->name) }}">
                                            <div class="perm-item d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="mdi mdi-shield-check-outline text-primary me-2"></i>
                                                    <span class="perm-name">{{ $perm->name }}</span>
                                                </div>
                                                <form method="POST" class="perm-form"
                                                    action="{{ $has ? route('tenant.subdomain.roles.detach', ['subdomain' => $sub]) : route('tenant.subdomain.roles.attach', ['subdomain' => $sub]) }}">
                                                    @csrf
                                                    <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                    <input type="hidden" name="permission_id" value="{{ $perm->id }}">
                                                    <label class="toggle-switch m-0">
                                                        <input class="perm-switch" type="checkbox"
                                                            {{ $has ? 'checked' : '' }}>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle handling
            const forms = document.querySelectorAll('.perm-form');
            forms.forEach(form => {
                const checkbox = form.querySelector('.perm-switch');
                if (!checkbox) return;
                checkbox.addEventListener('change', function(e) {
                    e.preventDefault();
                    const fd = new FormData(form);
                    const url = form.action;
                    const method = 'POST';
                    const originalChecked = checkbox.checked;
                    fetch(url, {
                            method,
                            body: fd,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(resp => {
                            if (!resp.ok) throw new Error('Request failed');
                            return resp.text();
                        })
                        .then(() => {
                            showToast(originalChecked ? 'تم ربط الصلاحية بالدور' :
                                'تم حذف الصلاحية من الدور');
                        })
                        .catch(() => {
                            checkbox.checked = !originalChecked;
                            showToast('حدث خطأ أثناء التحديث', true);
                        });
                });
            });

            // Filter permissions by name
            const filterInput = document.getElementById('perm-filter');
            if (filterInput) {
                filterInput.addEventListener('input', function() {
                    const q = this.value.trim().toLowerCase();
                    document.querySelectorAll('.perm-col').forEach(el => {
                        const name = el.dataset.perm || '';
                        el.style.display = name.includes(q) ? '' : 'none';
                    });
                });
            }

            function showToast(message, isError) {
                let toast = document.getElementById('role-map-toast');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'role-map-toast';
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
                // Bootstrap 5 toast
                const t = new bootstrap.Toast(toast, {
                    delay: 2000
                });
                t.show();
            }
        });
    </script>
    <style>
        .perm-item {
            border: 1px solid #e9ecef;
            border-radius: .75rem;
            padding: .75rem 1rem;
            transition: box-shadow .15s ease, transform .15s ease;
            background: #fff;
        }

        .perm-item:hover {
            box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .06);
            transform: translateY(-1px);
        }

        .perm-name {
            font-weight: 500;
        }

        /* Custom toggle switch independent of Bootstrap version */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #dee2e6;
            transition: .2s;
            border-radius: 999px;
        }

        .toggle-slider::before {
            content: "";
            position: absolute;
            height: 24px;
            width: 24px;
            left: 2px;
            top: 2px;
            background: #fff;
            transition: .2s;
            border-radius: 50%;
            box-shadow: 0 .1rem .3rem rgba(0, 0, 0, .15);
        }

        .toggle-switch input:checked+.toggle-slider {
            background-color: #0d6efd;
        }

        .toggle-switch input:checked+.toggle-slider::before {
            transform: translateX(24px);
        }

        .toggle-switch input:focus+.toggle-slider {
            box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .25);
        }

        /* Add spacing between permissions */
        .perm-col {
            margin-bottom: 1rem;
        }

        .card-header {
            border-bottom: 0;
        }

        #perm-filter {
            max-width: 320px;
        }
    </style>
@endpush
