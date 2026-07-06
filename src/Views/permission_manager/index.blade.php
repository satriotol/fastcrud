@extends('layouts/layoutMaster')

@section('title', 'Manajemen Permission')

@section('content')
    @include('partials.success')

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-1">Manajemen Permission Role</h5>
            <p class="text-muted mb-0">Pilih role untuk mengatur permission yang dimilikinya.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Role</th>
                            <th>Jumlah Permission</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $index => $role)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                </td>
                                <td>{{ $role->permissions_count }}</td>
                                <td>
                                    @if ($role->name === 'SUPERADMIN')
                                        <span class="text-muted"><i class="ti ti-lock me-1"></i> Terkunci</span>
                                    @else
                                        <a href="{{ route('permission_manager.edit', $role->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="ti ti-adjustments me-1"></i> Atur Permission
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
