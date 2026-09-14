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
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h5 class="mb-1"><i class="ti ti-history me-2"></i>Audit Log Sistem</h5>
                    <small class="text-muted">Pantau semua aktivitas dan perubahan data dalam sistem</small>
                </div>
                <span class="badge bg-label-primary">Total Log: {{ $audits->total() }}</span>
            </div>

            <form action="" id="audit-filter">
                <div class="row g-3">
                    <div class="col-md-4 col-lg-2">
                        <label class="form-label">Event</label>
                        {{ html()->text('event')->class('form-control')->placeholder('created, updated, ...')->value(@old('event')) }}
                    </div>
                    <div class="col-md-4 col-lg-2">
                        <label class="form-label">User ID</label>
                        {{ html()->text('user_id')->class('form-control')->placeholder('ID user')->value(@old('user_id')) }}
                    </div>
                    <div class="col-md-4 col-lg-2">
                        <label class="form-label">Tipe Model</label>
                        {{ html()->text('auditable_type')->class('form-control')->placeholder('App\Models\User')->value(@old('auditable_type')) }}
                    </div>
                    <div class="col-md-4 col-lg-2">
                        <label class="form-label">ID Model</label>
                        {{ html()->text('auditable_id')->class('form-control')->placeholder('ID model')->value(@old('auditable_id')) }}
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <label class="form-label">Alamat IP</label>
                        {{ html()->text('ip_address')->class('form-control')->placeholder('127.0.0.1')->value(@old('ip_address')) }}
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label">Dari Tanggal</label>
                        {{ html()->date('created_from')->class('form-control')->value(@old('created_from')) }}
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label">Sampai Tanggal</label>
                        {{ html()->date('created_to')->class('form-control')->value(@old('created_to')) }}
                    </div>
                    <div class="col-md-8 col-lg-6 d-flex align-items-end justify-content-end gap-2">
                        <a href="{{ url()->current() }}" class="btn btn-label-secondary">Reset</a>
                        <button class="btn btn-primary" type="submit"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
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
                    $userName = $audit->user->name ?? null;
                    $role = $audit->user && method_exists($audit->user, 'getRoleNames') ? $audit->user->getRoleNames()->first() : null;
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
                                @if ($role)
                                    <span class="badge rounded-pill bg-label-secondary">{{ $role }}</span>
                                @endif
                            @else
                                <span class="badge rounded-pill bg-label-secondary"><i class="ti ti-robot ti-xs me-1"></i>Sistem</span>
                            @endif
                            <span class="text-muted">•</span>
                            <span class="text-muted"><i class="ti ti-database ti-xs me-1"></i>{{ $model }}</span>
                            <span class="text-heading">#{{ $audit->auditable_id }}</span>
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
                                                    @endphp
                                                    <tr>
                                                        <td class="text-heading">{{ Str::headline($key) }}</td>
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
                <p class="mb-0">Belum ada log audit yang sesuai dengan filter.</p>
            </div>
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        {{ $audits->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endsection
