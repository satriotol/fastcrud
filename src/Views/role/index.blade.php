@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@endsection

@section('page-script')
    @include('partials.success')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables untuk setiap modal
            $('table[id^="datatableRole"]').each(function() {
                $(this).DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true
                });
            });

            // Re-initialize DataTables jika modal dibuka kembali
            $('div.modal').on('shown.bs.modal', function() {
                var table = $(this).find('table').DataTable();
                table.columns.adjust().draw();
            });
        });
    </script>
@endsection

@section('content')
    <div class="card">

        <div class="card-header border-bottom">
            <div class="row">
                <div class="col-md">
                    <h5 class="card-title mb-3">{Role}</h5>
                </div>
                <div class="col-md">
                    <div
                        class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <a href="{{ route('role.create') }}" class="btn btn-secondary btn-primary">
                                <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Tambah</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <form action="">

                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0 mt-3">
                    <div class="col-md-4">
                        {{ html()->text('name')->class('form-control')->placeholder('Cari Nama')->value(@old('name')) }}
                    </div>
                </div>
                <div
                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <button class="btn btn-secondary add-new btn-primary" type="submit">
                            <span><i class="ti ti-search me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">Cari</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>

        </div>
        <div class="card-body">
            @include('partials.success')

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jumlah</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($roles->currentPage() - 1) * $roles->perPage() + 1;
                        @endphp
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalRole{{ $role->id }}">
                                        {{ $role->users->count() }}
                                    </button>
                                    <div class="modal fade" id="modalRole{{ $role->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                        {{ $role->name }}</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table" id="datatableRole{{ $role->id }}">
                                                        <thead>
                                                            <tr>
                                                                <th>User</th>
                                                                <th>Reset Password</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($role->users as $user)
                                                                <tr>
                                                                    <td>{{ $user->name }}</td>
                                                                    <td>{{ $user->must_change_password ? '❌' : '✅' }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('role.edit', $role->id) }}"><i
                                                    class="ti ti-pencil me-1"></i>
                                                Edit</a>
                                            <form action="{{ route('role.destroy', $role->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="dropdown-item"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    <i class="ti ti-trash me-1"></i> Delete
                                                </button>
                                            </form>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            {{ $roles->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
        </div>

    </div>
@endsection
