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
    <div class="accordion" id="accordionPencarian">
        <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                    aria-controls="panelsStayOpen-collapseOne">
                    Pencarian
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                aria-labelledby="panelsStayOpen-headingOne">
                <div class="accordion-body">
                    <form action="">
                        <div class="row">
                            <div class="col-md-12">
                                {{ html()->label('Nama')->class('form-label') }}
                                {{ html()->text('name')->class('form-control')->placeholder('Cari Nama')->value(@old('name')) }}
                            </div>
                        </div>
                        <div class="text-end mt-2">

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
            </div>
        </div>
    </div>
    <div class="card mt-3">

        <div class="card-header border-bottom">
            <div class="row">
                <div class="col-md">
                    <h5 class="card-title mb-3">Pengguna</h5>
                </div>
                @can('user-create')
                    <div class="col-md">
                        <div
                            class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                            <div class="dt-buttons btn-group flex-wrap">
                                <a href="{{ route('user.create') }}" class="btn btn-secondary btn-primary">
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
                            <th>No</th>
                            <th>Nama</th>
                            <th>E-mail</th>
                            <th>Role</th>
                            <th>Aktivitas</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($users->currentPage() - 1) * $users->perPage() + 1;
                        @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->getRole()->name }}</td>
                                <td>{{ $user->last_used_sign_in_at }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                        <div class="dropdown-menu">
                                            @can('user-edit')
                                                <a class="dropdown-item" href="{{ route('user.edit', $user->uuid) }}"><i
                                                        class="ti ti-pencil me-1"></i>
                                                    Edit</a>
                                            @endcan
                                            @if (Auth::user()->id != $user->id)
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
                                            @endif

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            {{ $users->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
        </div>

    </div>
@endsection
