@extends('layouts/layoutMaster')

@section('title', 'Audit - Pages')

@section('page-style')
    <style>
        .audit-day { font-size: .8125rem; }
        .audit-day hr { flex: 1; margin: 0; opacity: .15; }
        .audit-time { width: 64px; flex-shrink: 0; }
        .audit-icon { width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 0 5px rgba(115, 103, 240, .08); }
        .audit-details summary { list-style: none; cursor: pointer; width: fit-content; }
        .audit-details summary::-webkit-details-marker { display: none; }
        .audit-details .label-hide, .audit-details[open] .label-show { display: none; }
        .audit-details[open] .label-hide { display: inline; }
        .audit-diff th { font-size: .6875rem; letter-spacing: .04em; }
        .audit-diff td { word-break: break-word; }
        .audit-meta dt { font-weight: 400; min-width: 70px; }
        .audit-meta dd { min-width: 0; }
        .audit-meta .col-12, .audit-meta .col-md-6 { display: flex; gap: .5rem; }
    </style>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('audit-filter').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mencari...';
            });
        });
    </script>
@endsection

@section('content')
    @php
        $events = [
            'created' => ['Dibuat', 'success', 'ti-plus'],
            'updated' => ['Diubah', 'info', 'ti-pencil'],
            'deleted' => ['Dihapus', 'danger', 'ti-trash'],
            'restored' => ['Dipulihkan', 'warning', 'ti-restore'],
        ];
        $formatValue = function ($value) {
            if ($value === null || $value === '') {
                return '—';
            }
            if (is_bool($value)) {
                return $value ? 'Ya' : 'Tidak';
            }
            if (is_array($value) || is_object($value)) {
                return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            return (string) $value;
        };
    @endphp

    <div class="card mb-4">
        <div class="card-body">
            @php
                $exportLimit = config('fastcrud.audit_export_limit', 500);
                $firstOnPage = $audits->first();
                $lastOnPage = $audits->getCollection()->last();
                $advancedKeys = ['auditable_type', 'auditable_id', 'ip_address'];
                $advancedOpen = collect($advancedKeys)->contains(fn($key) => filled(request()->query($key)));
                $quickRanges = [
                    'Hari ini' => [now()->toDateString(), now()->toDateString()],
                    '7 hari' => [now()->subDays(6)->toDateString(), now()->toDateString()],
                    '30 hari' => [now()->subDays(29)->toDateString(), now()->toDateString()],
                ];
            @endphp

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h5 class="mb-1"><i class="ti ti-history me-2"></i>Audit Log Sistem</h5>
                    <small class="text-muted">Pantau semua aktivitas dan perubahan data dalam sistem</small>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @if ($firstOnPage)
                        <span class="badge bg-label-primary" title="Total keseluruhan tidak dihitung agar halaman tetap cepat">
                            <i class="ti ti-list-numbers ti-xs me-1"></i>Log #{{ number_format($lastOnPage->id) }}–#{{ number_format($firstOnPage->id) }}
                        </span>
                    @endif
                    <span class="badge bg-label-secondary">Halaman {{ $audits->currentPage() }}</span>
                    <a href="{{ route('audit.export.excel') }}?{{ http_build_query(request()->query()) }}"
                        class="btn btn-sm btn-label-success"
                        title="Export sesuai filter aktif, maks. {{ number_format($exportLimit) }} record terbaru">
                        <i class="ti ti-file-spreadsheet me-1"></i>Export Excel
                    </a>
                </div>
            </div>

            <div class="alert alert-info d-flex align-items-center py-2 mb-3 small" role="alert">
                <i class="ti ti-bolt me-2"></i>
                <div>
                    Halaman ini menampilkan log terbaru secara bertahap tanpa menghitung total keseluruhan, agar tetap
                    ringan pada tabel audit berukuran besar. Export merekap maks.
                    <strong>{{ number_format($exportLimit) }}</strong> record terbaru sesuai filter aktif — persempit
                    rentang tanggal, User ID, atau model agar rekap lebih lengkap.
                </div>
            </div>

            @if (count($activeFilters))
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="small text-muted"><i class="ti ti-filter ti-xs me-1"></i>Filter aktif:</span>
                    @foreach ($activeFilters as $filter)
                        <span class="badge bg-label-primary d-inline-flex align-items-center gap-1">
                            {{ $filter['label'] }}: {{ Str::limit($filter['value'], 28) }}
                            <a href="{{ request()->fullUrlWithQuery([$filter['key'] => null, 'page' => null]) }}"
                                class="text-reset text-decoration-none" title="Hapus filter {{ $filter['label'] }}">
                                <i class="ti ti-x ti-xs"></i>
                            </a>
                        </span>
                    @endforeach
                    <a href="{{ url()->current() }}" class="btn btn-xs btn-label-secondary">Hapus semua</a>
                </div>
            @endif

            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="small text-muted"><i class="ti ti-calendar-stats ti-xs me-1"></i>Rentang cepat:</span>
                @foreach ($quickRanges as $rangeLabel => [$rangeFrom, $rangeTo])
                    <a href="{{ request()->fullUrlWithQuery(['created_from' => $rangeFrom, 'created_to' => $rangeTo, 'page' => null]) }}"
                        class="btn btn-sm btn-label-secondary">{{ $rangeLabel }}</a>
                @endforeach
            </div>

            <form action="" id="audit-filter">
                <div class="row g-3">
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label" for="audit-event">Event</label>
                        <select name="event" id="audit-event" class="form-select">
                            <option value="">Semua event</option>
                            @foreach (['created' => 'Dibuat', 'updated' => 'Diubah', 'deleted' => 'Dihapus', 'restored' => 'Dipulihkan'] as $eventValue => $eventText)
                                <option value="{{ $eventValue }}" @selected(request('event') === $eventValue)>{{ $eventText }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label">User ID</label>
                        {{ html()->text('user_id')->class('form-control')->attribute('inputmode', 'numeric')->placeholder('ID user')->value(@old('user_id')) }}
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label">Dari Tanggal</label>
                        {{ html()->date('created_from')->class('form-control')->value(@old('created_from')) }}
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label">Sampai Tanggal</label>
                        {{ html()->date('created_to')->class('form-control')->value(@old('created_to')) }}
                    </div>
                </div>

                <details class="mt-3 audit-details" @if ($advancedOpen) open @endif>
                    <summary class="small fw-semibold text-muted">
                        <i class="ti ti-adjustments-horizontal me-1"></i>Filter lanjutan
                        @if ($advancedOpen)
                            <span class="badge rounded-pill bg-label-primary ms-1">aktif</span>
                        @endif
                    </summary>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label class="form-label">Tipe Model</label>
                            {{ html()->text('auditable_type')->class('form-control')->placeholder('App\Models\User')->value(@old('auditable_type')) }}
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ID Model</label>
                            {{ html()->text('auditable_id')->class('form-control')->attribute('inputmode', 'numeric')->placeholder('ID model')->value(@old('auditable_id')) }}
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Alamat IP</label>
                            {{ html()->text('ip_address')->class('form-control')->placeholder('127.0.0.1')->value(@old('ip_address')) }}
                        </div>
                    </div>
                </details>

                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ url()->current() }}" class="btn btn-label-secondary">Reset</a>
                    <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i>Cari</button>
                </div>
            </form>
        </div>
    </div>

    @forelse ($audits->getCollection()->groupBy(fn($audit) => $audit->created_at->toDateString()) as $date => $items)
        @php $day = $items->first()->created_at; @endphp
        <div class="audit-day d-flex align-items-center gap-2 mb-2 {{ $loop->first ? '' : 'mt-4' }}">
            <span class="fw-semibold text-heading">
                {{ $day->isToday() ? 'Hari Ini' : ($day->isYesterday() ? 'Kemarin' : $day->translatedFormat('l')) }}
            </span>
            <span class="text-muted">{{ $day->translatedFormat('d F Y') }}</span>
            <hr>
            <span class="text-muted">{{ $dayCounts[$date] ?? $items->count() }} aktivitas</span>
        </div>

        <div class="card">
            @foreach ($items as $audit)
                @php
                    [$eventLabel, $color, $icon] = $events[$audit->event] ?? [Str::headline($audit->event), 'secondary', 'ti-shield'];
                    $model = Str::headline(class_basename($audit->auditable_type));
                    $old = $audit->old_values ?? [];
                    $new = $audit->new_values ?? [];
                    $keys = array_unique(array_merge(array_keys($old), array_keys($new)));
                    $user = $audit->user;
                    $userName = $user->name ?? null;
                    $userId = $audit->user_id;
                    $userEmail = $user->email ?? null;
                    $role = $user && method_exists($user, 'getRoleNames') ? $user->getRoleNames()->first() : null;
                    $totalChangedFields = collect($keys)->filter(fn($k) => ($old[$k] ?? null) !== ($new[$k] ?? null))->count();
                @endphp
                <div class="card-body d-flex gap-3 {{ $loop->last ? '' : 'border-bottom' }}">
                    <div class="audit-time text-center">
                        <div class="fw-semibold text-heading mb-2">{{ $audit->created_at->format('H:i') }}</div>
                        <span class="audit-icon bg-label-{{ $color }}"><i class="ti {{ $icon }}"></i></span>
                    </div>

                    <div class="flex-grow-1" style="min-width:0">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h6 class="mb-0">Data {{ $model }} {{ Str::lower($eventLabel) }}</h6>
                            <span class="badge rounded-pill bg-label-{{ $color }}">{{ $eventLabel }}</span>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 small mb-2">
                            @if ($userName)
                                <span class="avatar avatar-xs">
                                    <span class="avatar-initial rounded-circle bg-label-secondary" style="font-size:.625rem">
                                        {{ collect(explode(' ', $userName))->take(2)->map(fn($w) => Str::upper(Str::substr($w, 0, 1)))->join('') }}
                                    </span>
                                </span>
                                <span class="text-heading">{{ $userName }}</span>
                                <a href="{{ request()->fullUrlWithQuery(['user_id' => $userId]) }}"
                                    class="badge rounded-pill bg-label-primary font-monospace text-decoration-none"
                                    title="Filter aktivitas User ID {{ $userId }}">ID: {{ $userId }}</a>
                                @if ($role)
                                    <span class="badge rounded-pill bg-label-secondary">{{ $role }}</span>
                                @endif
                            @else
                                <span class="badge rounded-pill bg-label-secondary"><i class="ti ti-robot ti-xs me-1"></i>Sistem</span>
                                <span class="badge rounded-pill bg-label-secondary font-monospace" title="Tidak ada user terautentikasi">ID: —</span>
                            @endif
                            <span class="text-muted">•</span>
                            <span class="text-muted"><i class="ti ti-database ti-xs me-1"></i>{{ $model }}</span>
                            <a href="{{ request()->fullUrlWithQuery(['auditable_type' => $audit->auditable_type, 'auditable_id' => $audit->auditable_id]) }}"
                                class="text-heading text-decoration-none" title="Lihat seluruh riwayat entitas ini">#{{ $audit->auditable_id }}</a>
                            @if ($audit->tags)
                                <span class="badge rounded-pill bg-label-warning"><i class="ti ti-tag ti-xs me-1"></i>{{ $audit->tags }}</span>
                            @endif
                        </div>

                        <div class="d-flex flex-wrap gap-3 small text-muted mb-2">
                            <span title="Nomor log audit"><i class="ti ti-hash ti-xs me-1"></i>Log #{{ $audit->id }}</span>
                            <span title="Email pengguna"><i class="ti ti-mail ti-xs me-1"></i>{{ $userEmail ?: 'Tanpa email' }}</span>
                            <span title="Alamat IP"><i class="ti ti-network ti-xs me-1"></i>{{ $audit->ip_address ?: '—' }}</span>
                            <span title="Jumlah kolom berubah dari total kolom tercatat">
                                <i class="ti ti-columns-3 ti-xs me-1"></i>{{ $totalChangedFields }}/{{ count($keys) }} kolom berubah
                            </span>
                            <span title="Waktu relatif"><i class="ti ti-clock ti-xs me-1"></i>{{ $audit->created_at->diffForHumans() }}</span>
                        </div>

                        <details class="audit-details">
                            <summary class="small fw-medium text-muted">
                                <span class="label-show">Tampilkan rincian</span>
                                <span class="label-hide">Sembunyikan rincian</span>
                                <span class="badge rounded-pill bg-label-secondary ms-1">{{ count($keys) }}</span>
                            </summary>

                            <div class="border rounded mt-2 p-3">
                                @if (count($keys))
                                    <div class="table-responsive">
                                        <table class="table table-sm audit-diff mb-3">
                                            <thead>
                                                <tr>
                                                    <th class="text-muted">Kolom</th>
                                                    <th class="text-muted">Sebelum</th>
                                                    <th class="text-muted">Sesudah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($keys as $key)
                                                    @php
                                                        $before = $formatValue($old[$key] ?? null);
                                                        $after = $formatValue($new[$key] ?? null);
                                                        $isChanged = ($old[$key] ?? null) !== ($new[$key] ?? null);
                                                    @endphp
                                                    <tr class="{{ $isChanged ? '' : 'opacity-50' }}">
                                                        <td class="text-heading">
                                                            {{ Str::headline($key) }}
                                                            @if ($isChanged)
                                                                <i class="ti ti-arrows-diff ti-xs text-warning ms-1" title="Kolom ini berubah"></i>
                                                            @endif
                                                        </td>
                                                        <td class="{{ $before !== '—' && $before !== $after ? 'text-danger' : 'text-muted' }}" title="{{ $before }}">
                                                            {{ Str::limit($before, 120) }}
                                                        </td>
                                                        <td class="{{ $after !== '—' ? 'text-success' : 'text-muted' }}" title="{{ $after }}">
                                                            {{ Str::limit($after, 120) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                <dl class="row g-1 small mb-0 audit-meta">
                                    <div class="col-md-6">
                                        <dt class="text-muted">Log ID</dt>
                                        <dd class="mb-0 text-heading font-monospace">{{ $audit->id }}</dd>
                                    </div>
                                    <div class="col-md-6">
                                        <dt class="text-muted">User ID</dt>
                                        <dd class="mb-0 text-heading font-monospace">
                                            {{ $userId ?? '—' }}
                                            @if ($userName)
                                                <span class="text-muted">({{ $userName }}{{ $userEmail ? ', ' . $userEmail : '' }})</span>
                                            @else
                                                <span class="text-muted">(Sistem / tidak terautentikasi)</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="col-md-6">
                                        <dt class="text-muted">Waktu</dt>
                                        <dd class="mb-0 text-heading">{{ $audit->created_at->translatedFormat('d F Y, H:i:s') }}</dd>
                                    </div>
                                    <div class="col-md-6">
                                        <dt class="text-muted">Alamat IP</dt>
                                        <dd class="mb-0 text-heading">{{ $audit->ip_address ?: '—' }}</dd>
                                    </div>
                                    <div class="col-12">
                                        <dt class="text-muted">Halaman</dt>
                                        <dd class="mb-0 text-heading text-truncate" title="{{ $audit->url }}">{{ $audit->url ?: '—' }}</dd>
                                    </div>
                                    <div class="col-12">
                                        <dt class="text-muted">Perangkat</dt>
                                        <dd class="mb-0 text-heading text-truncate" title="{{ $audit->user_agent }}">{{ $audit->user_agent ?: '—' }}</dd>
                                    </div>
                                    <div class="col-12">
                                        <dt class="text-muted">ID Entitas</dt>
                                        <dd class="mb-0 text-heading font-monospace">{{ $audit->auditable_id }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </details>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5 text-muted">
                <i class="ti ti-search-off mb-2" style="font-size:2.5rem;opacity:.5"></i>
                <h6 class="mb-1">Tidak Ada Data Audit</h6>
                <p class="mb-2">
                    @if (count($activeFilters))
                        Tidak ada log yang sesuai dengan filter saat ini.
                    @else
                        Belum ada log audit yang tercatat.
                    @endif
                </p>
                @if (count($activeFilters))
                    <a href="{{ url()->current() }}" class="btn btn-sm btn-label-secondary">
                        <i class="ti ti-filter-off me-1"></i>Reset filter
                    </a>
                @endif
            </div>
        </div>
    @endforelse

    @if ($audits->count())
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-4">
            <small class="text-muted">Menampilkan {{ $audits->count() }} log pada halaman ini</small>
            <div>{{ $audits->links('pagination::simple-bootstrap-5') }}</div>
        </div>
    @endif
@endsection
