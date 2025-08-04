@extends('layouts/layoutMaster')

@section('title', 'Audit - Pages')

@section('vendor-style')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #696cff 0%, #5a5fe7 100%);
        }

        .text-white-75 {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .card {
            border-radius: 12px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #5a5fe7;
            padding: 1rem 0.75rem;
            white-space: nowrap;
        }

        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-color: #f0f2f8;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .form-control {
            border-radius: 8px;
            border-color: #d5d9dd;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.25);
        }

        .audit-event {
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .audit-event.created {
            background-color: #e8f5e8;
            color: #28a745;
            border: 1px solid #d4edda;
        }

        .audit-event.updated {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .audit-event.deleted {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .audit-event.restored {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .audit-values {
            max-height: 200px;
            overflow-y: auto;
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .audit-values ul {
            margin: 0;
            padding-left: 1rem;
        }

        .audit-values li {
            margin-bottom: 0.25rem;
            word-break: break-word;
        }

        .audit-values li strong {
            color: #495057;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #696cff 0%, #5a5fe7 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.75rem;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .ip-address {
            font-family: 'Courier New', monospace;
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            border: 1px solid #e9ecef;
        }

        .auditable-info {
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
        }

        .auditable-type {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #bbdefb;
            margin-bottom: 0.25rem;
            display: inline-block;
        }

        .auditable-id {
            background-color: #f3e5f5;
            color: #7b1fa2;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #e1bee7;
            font-weight: 600;
        }

        .datetime-info {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .datetime-date {
            font-weight: 600;
            color: #495057;
        }

        .filter-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 767.98px) {
            .card-body {
                padding: 1rem;
            }

            .btn {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }

            .table-responsive {
                border-radius: 8px;
            }

            .audit-values {
                max-height: 150px;
            }

            .user-avatar {
                width: 28px;
                height: 28px;
                font-size: 0.7rem;
            }
        }

        .pagination-container .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 8px;
            border-color: #e9ecef;
            color: #6c757d;
            margin: 0 2px;
        }

        .pagination .page-link:hover {
            background-color: #696cff;
            border-color: #696cff;
            color: white;
        }

        .pagination .page-item.active .page-link {
            background-color: #696cff;
            border-color: #696cff;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
@endsection

@section('vendor-script')
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show loading state on form submit
            document.querySelector('form').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span>Mencari...';
                }
            });

            // Tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Expandable audit values
            document.querySelectorAll('.audit-values').forEach(function(element) {
                if (element.scrollHeight > element.clientHeight) {
                    element.style.cursor = 'pointer';
                    element.title = 'Klik untuk expand/collapse';
                    element.addEventListener('click', function() {
                        if (this.style.maxHeight === 'none') {
                            this.style.maxHeight = '200px';
                        } else {
                            this.style.maxHeight = 'none';
                        }
                    });
                }
            });
        });
    </script>
@endsection

@section('content')
    <!-- Header dengan Informasi -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="text-white mb-2">
                                <i class="ti ti-history me-2"></i>
                                Audit Log Sistem
                            </h3>
                            <p class="text-white-75 mb-0">
                                Pantau dan lacak semua aktivitas dan perubahan data dalam sistem secara real-time
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="bg-white bg-opacity-20 rounded p-3">
                                <h6 class="mb-1">Total Log</h6>
                                <h2 class="fw-bold mb-0">{{ $audits->total() }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <!-- Filter Section -->
        <div class="card-header bg-light border-0">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-filter me-2 text-primary"></i>
                        Filter Audit Log
                    </h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body pb-0">
        <form action="" class="filter-section">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-activity me-1"></i>Event/Action
                    </label>
                    {{ html()->text('event')->class('form-control')->placeholder('Contoh: created, updated, deleted')->value(@old('event')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-user me-1"></i>User ID
                    </label>
                    {{ html()->text('user_id')->class('form-control')->placeholder('Masukkan ID atau nama user')->value(@old('user_id')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-id me-1"></i>ID Model
                    </label>
                    {{ html()->text('auditable_id')->class('form-control')->placeholder('Masukkan ID Model')->value(@old('auditable_id')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-database me-1"></i>Tipe Model
                    </label>
                    {{ html()->text('auditable_type')->class('form-control')->placeholder('Contoh: User, Operational')->value(@old('auditable_type')) }}
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">
                        <i class="ti ti-world me-1"></i>IP Address
                    </label>
                    {{ html()->text('ip_address')->class('form-control')->placeholder('Masukkan IP Address')->value(@old('ip_address')) }}
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 text-end">
                    <button class="btn btn-primary" type="submit">
                        <i class="ti ti-search me-1"></i>
                        Cari Data
                    </button>
                </div>
            </div>
        </form>
        <div class="alert alert-info mt-3">
            <i class="ti ti-info-circle me-2"></i>
            <b>Tips:</b> Gunakan filter di atas untuk mencari log tertentu. Kolom <b>Nilai Lama</b> dan <b>Nilai Baru</b>
            dapat di-klik untuk melihat detail lebih banyak jika datanya panjang.
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-body pt-0">
        @if ($audits->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-semibold">No</th>
                            <th class="border-0 fw-semibold">IP Address <br><span class="text-muted fw-normal small">Alamat
                                    IP pengguna</span></th>
                            <th class="border-0 fw-semibold">Action <br><span class="text-muted fw-normal small">Jenis aksi
                                    (created, updated, dst)</span></th>
                            <th class="border-0 fw-semibold">Model Info <br><span class="text-muted fw-normal small">Tipe &
                                    ID data</span></th>
                            <th class="border-0 fw-semibold">User <br><span class="text-muted fw-normal small">Nama &
                                    ID</span></th>
                            <th class="border-0 fw-semibold">Nilai Lama <br><span class="text-muted fw-normal small">Data
                                    sebelum perubahan</span></th>
                            <th class="border-0 fw-semibold">Nilai Baru <br><span class="text-muted fw-normal small">Data
                                    setelah perubahan</span></th>
                            <th class="border-0 fw-semibold">Waktu <br><span class="text-muted fw-normal small">Tanggal,
                                    jam, & selisih waktu</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($audits->currentPage() - 1) * $audits->perPage() + 1;
                        @endphp
                        @foreach ($audits as $audit)
                            <tr>
                                <td class="fw-semibold">{{ $no++ }}</td>

                                <!-- IP Address -->
                                <td>
                                    <span class="ip-address" data-bs-toggle="tooltip" title="IP Address pengguna">
                                        {{ $audit->ip_address }}
                                    </span>
                                </td>

                                <!-- Action/Event -->
                                <td>
                                    <span class="audit-event {{ strtolower($audit->event) }}">
                                        {{ $audit->event }}
                                    </span>
                                </td>

                                <!-- Model Info -->
                                <td>
                                    <div class="auditable-info">
                                        <div class="auditable-type mb-1">
                                            {{ $audit->auditable_type }}
                                        </div>
                                        <div class="auditable-id">
                                            ID: {{ $audit->auditable_id }}
                                        </div>
                                    </div>
                                </td>

                                <!-- User Info -->
                                <td>
                                    <div class="user-info">
                                        @if ($audit->user)
                                            <div class="user-avatar">
                                                {{ substr($audit->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $audit->user->name }}</div>
                                                <small class="text-muted">ID: {{ $audit->user_id }}</small>
                                            </div>
                                        @else
                                            <div class="user-avatar bg-secondary">
                                                <i class="ti ti-robot"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">System</div>
                                                <small class="text-muted">Automated</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Old Values -->
                                <td>
                                    @if (!empty($audit->old_values))
                                        <div class="audit-values" data-bs-toggle="tooltip" title="Klik untuk expand">
                                            <ul class="mb-0">
                                                @foreach ($audit->old_values as $key => $old_value)
                                                    <li>
                                                        <strong>{{ $key }}:</strong>
                                                        <span class="text-muted">
                                                            {{ is_string($old_value) ? Str::limit($old_value, 50) : $old_value }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">
                                            <i class="ti ti-minus"></i> Tidak ada
                                        </span>
                                    @endif
                                </td>

                                <!-- New Values -->
                                <td>
                                    @if (!empty($audit->new_values))
                                        <div class="audit-values" data-bs-toggle="tooltip" title="Klik untuk expand">
                                            <ul class="mb-0">
                                                @foreach ($audit->new_values as $key => $new_value)
                                                    <li>
                                                        <strong>{{ $key }}:</strong>
                                                        <span class="text-success">
                                                            {{ is_string($new_value) ? Str::limit($new_value, 50) : $new_value }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">
                                            <i class="ti ti-minus"></i> Tidak ada
                                        </span>
                                    @endif
                                </td>

                                <!-- Date Time -->
                                <td>
                                    <div class="datetime-info">
                                        <div class="datetime-date">
                                            {{ $audit->created_at->format('d M Y') }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $audit->created_at->format('H:i:s') }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            {{ $audit->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $audits->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
            </div>
        @else
            <div class="empty-state">
                <i class="ti ti-search-off"></i>
                <h5 class="mb-2">Tidak Ada Data Audit</h5>
                <p class="text-muted mb-0">
                    Belum ada log audit yang ditemukan atau sesuai dengan filter yang diterapkan.
                </p>
            </div>
        @endif
    </div>
    </div>
@endsection
