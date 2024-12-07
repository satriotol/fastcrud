@extends('layouts/layoutMaster')

@section('title', 'Crud List - Pages')

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
                    <h5 class="card-title mb-3">{CRUD}</h5>
                </div>
                @can('crud-create')
                    <div class="col-md">
                        <div
                            class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a href="{{ route('crud.create') }}" class="btn btn-secondary btn-primary">
                                    <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Tambah</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>E-mail</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($tables as $table) --}}
                            <tr>
                                {{-- <td>{{ $table->Tables_in_laravel }}</td> --}}
                                <td></td>
                                {{-- <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            @can('user-edit')
                                                <a class="dropdown-item" href="{{ route('user.edit', $user->id) }}"><i
                                                        class="ti ti-pencil me-1"></i>
                                                    Edit</a>
                                            @endcan
                                            @can('user-delete')
                                                <form action="{{ route('user.destroy', $user->id) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                        <i class="ti ti-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            @endcan

                                        </div>
                                    </div>
                                </td> --}}
                            </tr>
                        {{-- @endforeach --}}

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
