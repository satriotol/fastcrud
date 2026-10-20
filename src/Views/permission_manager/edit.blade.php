@extends('layouts/layoutMaster')

@section('title', 'Atur Permission - ' . $role->name)

@section('content')
    @include('partials.success')

    <form id="missingPermissionsForm" action="{{ route('permission_manager.missing_permissions.store', $role->id) }}" method="post" class="d-none">
        @csrf
    </form>

    <form id="permissionForm" action="{{ route('permission_manager.update', $role->id) }}" method="post">
        @csrf
        @method('put')

        <div class="card">
            <div class="card-header border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h5 class="card-title mb-1">
                        Permission untuk role
                        <span class="badge bg-primary">{{ $role->name }}</span>
                    </h5>
                    <p class="text-muted mb-0">Centang permission yang boleh diakses role ini. Perubahan berlaku langsung setelah disimpan.</p>
                </div>
                <div class="d-flex gap-2">
                    @if (session('impersonator_id'))
                        <a href="{{ route('dashboard.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-eye me-1"></i> Lihat Hasil
                        </a>
                    @else
                        <a href="{{ route('permission_manager.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> Simpan
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="card border mb-4">
                    <div class="card-header border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h6 class="mb-1"><i class="ti ti-brackets-angle me-1"></i> Sinkronisasi JSON</h6>
                            <small class="text-muted">Copy konfigurasi dari local, paste di environment lain, lalu review sebelum diterapkan.</small>
                        </div>
                        <button type="button" id="copyRoleJson" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-copy me-1"></i> Copy JSON Saat Ini
                        </button>
                    </div>
                    <div class="card-body">
                        <textarea id="roleJson" class="form-control font-monospace" rows="8"
                            placeholder='Paste JSON, contoh: {"role":"ADMIN","permissions":["user-index"]}'></textarea>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" id="reviewRoleJson" class="btn btn-info text-white">
                                <i class="ti ti-eye me-1"></i> Review Perubahan
                            </button>
                            <button type="button" id="clearRoleJson" class="btn btn-outline-secondary">Bersihkan</button>
                        </div>
                        <div id="jsonReview" class="mt-3 d-none"></div>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach ($permissions as $module => $items)
                        <div class="col-md-6 col-lg-4">
                            <div class="border rounded h-100">
                                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-lighter">
                                    <strong class="text-uppercase">{{ str_replace('_', ' ', $module) }}</strong>
                                    <div class="form-check form-check-sm mb-0">
                                        <input class="form-check-input group-toggle" type="checkbox"
                                            id="toggle-{{ $loop->index }}" data-group="group-{{ $loop->index }}">
                                        <label class="form-check-label small" for="toggle-{{ $loop->index }}">Semua</label>
                                    </div>
                                </div>
                                <div class="p-3">
                                    @foreach ($items as $permission)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input group-group-{{ $loop->parent->index }}"
                                                type="checkbox" name="permissions[]"
                                                value="{{ $permission->name }}"
                                                id="perm-{{ $permission->id }}"
                                                data-group="group-{{ $loop->parent->index }}"
                                                @checked(in_array($permission->name, $assigned))>
                                            <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                {{ \Illuminate\Support\Str::afterLast($permission->name, '-') }}
                                                <span class="text-muted small">({{ $permission->name }})</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
@endsection

@section('page-script')
    <script>
        (function() {
            var currentRole = @json($role->name);
            var exportPayload = @json([
                'role' => $role->name,
                'permissions' => collect($assigned)->values()->all(),
            ]);
            var importedPermissions = [];

            function permissionCheckboxes() {
                return Array.from(document.querySelectorAll('input[name="permissions[]"]'));
            }

            function uniqueSorted(values) {
                return Array.from(new Set(values.map(function(value) {
                    return String(value).trim();
                }).filter(Boolean))).sort();
            }

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function listHtml(title, badgeClass, items) {
                var list = items.length ? items.map(function(item) {
                    return '<li><code>' + escapeHtml(item) + '</code></li>';
                }).join('') : '<li class="text-muted">Tidak ada</li>';

                return '<div class="col-md-6 col-xl-3">' +
                    '<div class="border rounded h-100 p-3">' +
                    '<div class="d-flex align-items-center justify-content-between mb-2">' +
                    '<strong>' + title + '</strong>' +
                    '<span class="badge ' + badgeClass + '">' + items.length + '</span>' +
                    '</div>' +
                    '<ul class="mb-0 ps-3 small" style="max-height: 220px; overflow:auto;">' + list + '</ul>' +
                    '</div>' +
                    '</div>';
            }

            function setReview(html, type) {
                var review = document.getElementById('jsonReview');
                review.classList.remove('d-none');
                review.innerHTML = '<div class="alert alert-' + type + ' mb-0">' + html + '</div>';
            }

            function refreshGroupToggles() {
                document.querySelectorAll('.group-toggle').forEach(function(master) {
                    var group = master.getAttribute('data-group');
                    var members = document.querySelectorAll('input[data-group="' + group + '"]:not(.group-toggle)');
                    master.checked = members.length > 0 && Array.from(members).every(function(cb) {
                        return cb.checked;
                    });
                });
            }

            document.querySelectorAll('.group-toggle').forEach(function(master) {
                var group = master.getAttribute('data-group');
                var members = document.querySelectorAll('input[data-group="' + group + '"]:not(.group-toggle)');

                // Set kondisi awal "Semua" berdasarkan anggota grup.
                master.checked = members.length > 0 && Array.from(members).every(function(cb) {
                    return cb.checked;
                });

                master.addEventListener('change', function() {
                    members.forEach(function(cb) {
                        cb.checked = master.checked;
                    });
                });
            });

            document.getElementById('copyRoleJson').addEventListener('click', function() {
                var textarea = document.getElementById('roleJson');
                var text = JSON.stringify(exportPayload, null, 2);
                textarea.value = text;

                var fallbackCopy = function(message, type) {
                    textarea.focus();
                    textarea.select();
                    try {
                        document.execCommand('copy');
                    } catch (e) {}
                    setReview(message, type);
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(function() {
                        setReview('JSON role saat ini berhasil dicopy ke clipboard.', 'success');
                    }).catch(function() {
                        fallbackCopy('JSON sudah disiapkan di textarea. Clipboard diblokir browser, silakan copy manual (Ctrl+C).', 'info');
                    });
                } else {
                    fallbackCopy('JSON sudah disiapkan di textarea dan dicoba dicopy. Jika belum tercopy, tekan Ctrl+C.', 'info');
                }
            });

            document.getElementById('clearRoleJson').addEventListener('click', function() {
                var review = document.getElementById('jsonReview');
                document.getElementById('roleJson').value = '';
                importedPermissions = [];
                review.classList.add('d-none');
                review.innerHTML = '';
            });

            document.getElementById('reviewRoleJson').addEventListener('click', function() {
                var raw = document.getElementById('roleJson').value.trim();
                var payload;

                if (!raw) {
                    setReview('JSON masih kosong. Paste JSON dari environment local terlebih dahulu.', 'warning');
                    return;
                }

                try {
                    payload = JSON.parse(raw);
                } catch (error) {
                    setReview('Format JSON tidak valid: ' + escapeHtml(error.message), 'danger');
                    return;
                }

                if (!payload || !Array.isArray(payload.permissions)) {
                    setReview('JSON wajib memiliki properti <code>permissions</code> berupa array.', 'danger');
                    return;
                }

                if (!payload.permissions.every(function(permission) {
                    return typeof permission === 'string';
                })) {
                    setReview('Semua isi <code>permissions</code> harus berupa string nama permission.', 'danger');
                    return;
                }

                var available = permissionCheckboxes().map(function(cb) { return cb.value; });
                var current = permissionCheckboxes().filter(function(cb) { return cb.checked; })
                    .map(function(cb) { return cb.value; });
                var desired = uniqueSorted(payload.permissions);
                var availableSet = new Set(available);
                var currentSet = new Set(current);
                var validDesired = desired.filter(function(name) { return availableSet.has(name); });
                var validDesiredSet = new Set(validDesired);
                var invalid = desired.filter(function(name) { return !availableSet.has(name); });
                var added = validDesired.filter(function(name) { return !currentSet.has(name); });
                var removed = current.filter(function(name) { return !validDesiredSet.has(name); }).sort();
                var unchanged = validDesired.filter(function(name) { return currentSet.has(name); });

                importedPermissions = validDesired;

                var warnings = '';
                if (payload.role && payload.role !== currentRole) {
                    warnings += '<div class="alert alert-warning mb-3">Nama role JSON adalah <strong>' +
                        escapeHtml(payload.role) + '</strong>, sedangkan role halaman ini <strong>' +
                        escapeHtml(currentRole) + '</strong>. Perubahan tetap diterapkan ke role halaman ini.</div>';
                }
                if (invalid.length) {
                    warnings += '<div class="alert alert-warning mb-3">Ada ' + invalid.length +
                        ' permission yang tidak tersedia di environment ini.</div>';
                }

                var createButton = invalid.length
                    ? '<button type="button" id="createMissingPermissions" class="btn btn-warning">' +
                        '<i class="ti ti-plus me-1"></i> Buat Permission Tidak Tersedia</button>'
                    : '';
                var html = warnings +
                    '<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">' +
                    '<div><strong>Review selesai.</strong><br><small>Permission valid: ' + validDesired.length +
                    ' dari ' + desired.length + '.</small></div>' +
                    '<div class="d-flex flex-wrap gap-2">' + createButton +
                    '<button type="button" id="applyRoleJson" class="btn btn-primary">' +
                    '<i class="ti ti-check me-1"></i> Terapkan ke Form</button></div></div>' +
                    '<div class="row g-3">' +
                    listHtml('Ditambahkan', 'bg-success', added) +
                    listHtml('Dihapus', 'bg-danger', removed) +
                    listHtml('Tetap', 'bg-secondary', unchanged) +
                    listHtml('Tidak tersedia', 'bg-warning', invalid) +
                    '</div>';

                var review = document.getElementById('jsonReview');
                review.classList.remove('d-none');
                review.innerHTML = html;

                document.getElementById('applyRoleJson').addEventListener('click', function() {
                    var selectedSet = new Set(importedPermissions);
                    permissionCheckboxes().forEach(function(cb) {
                        cb.checked = selectedSet.has(cb.value);
                    });
                    refreshGroupToggles();
                    setReview('Permission JSON sudah diterapkan ke checkbox. Klik <strong>Simpan</strong> untuk menyimpan ke database.', 'success');
                });

                var createMissingButton = document.getElementById('createMissingPermissions');
                if (createMissingButton) {
                    createMissingButton.addEventListener('click', function() {
                        var form = document.getElementById('missingPermissionsForm');
                        invalid.forEach(function(name) {
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'permissions[]';
                            input.value = name;
                            form.appendChild(input);
                        });
                        form.submit();
                    });
                }
            });
        })();
    </script>
@endsection
