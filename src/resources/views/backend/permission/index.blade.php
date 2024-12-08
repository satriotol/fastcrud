@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    @include('partials.success')
@endsection

@section('content')
    <div class="card">

        <div class="card-header border-bottom">
            <div class="row">
                <div class="col-md">
                    <h5 class="card-title mb-3">{Permission}</h5>
                </div>
                <div class="col-md">
                    <div
                        class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                        <div class="dt-buttons btn-group flex-wrap">
                            <a href="{{ route('permission.create') }}" class="btn btn-secondary btn-primary">
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
                            $no = ($permissions->currentPage() - 1) * $permissions->perPage() + 1;
                        @endphp
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalPermission{{ $permission->id }}">
                                        {{ $permission->roles->count() }}
                                    </button>
                                    <div class="modal fade" id="modalPermission{{ $permission->id }}" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                        {{ $permission->name }}</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Role</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($permission->roles as $role)
                                                                <tr>
                                                                    <td>{{ $role->name }}</td>
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
                                            <a class="dropdown-item"
                                                href="{{ route('permission.edit', $permission->id) }}"><i
                                                    class="ti ti-pencil me-1"></i>
                                                Edit</a>
                                            <form action="{{ route('permission.destroy', $permission->id) }}"
                                                method="post">
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
            {{ $permissions->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
        </div>

    </div>
@endsection
