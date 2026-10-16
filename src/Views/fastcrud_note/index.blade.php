@extends('layouts/layoutMaster')

@section('title', 'Catatan Rapat')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Catatan Rapat</h4>
            <p class="text-muted mb-0">Semua catatan dari pengguna berizin.</p>
        </div>
        @can('fastcrud_note-create')
            <a href="{{ route('fastcrud_note.create') }}" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Catatan Baru</a>
        @endcan
    </div>

    @include('partials.success')
    <div class="card">
        <div class="card-header">
            <form class="row g-2" method="get">
                <div class="col-md-10"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari judul catatan"></div>
                <div class="col-md-2 d-grid"><button class="btn btn-outline-primary"><i class="ti ti-search me-1"></i>Cari</button></div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Judul</th><th>Pembuat</th><th>Diperbarui</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                @forelse($notes as $note)
                    <tr>
                        <td class="fw-semibold">{{ $note->title }}</td>
                        <td>{{ $note->user?->name ?? 'User terhapus' }}</td>
                        <td>{{ $note->updated_at->format('d M Y H:i') }}</td>
                        <td class="text-end">
                            @can('fastcrud_note-update')
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('fastcrud_note.edit', $note->uuid) }}"><i class="ti ti-edit"></i></a>
                            @endcan
                            @can('fastcrud_note-delete')
                                <form class="d-inline" method="post" action="{{ route('fastcrud_note.destroy', $note->uuid) }}" onsubmit="return confirm('Hapus catatan ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button></form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-5">Belum ada catatan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $notes->withQueryString()->links('pagination::bootstrap-5') }}</div>
    </div>
@endsection
