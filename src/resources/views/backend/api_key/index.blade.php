@extends('layouts/layoutMaster')

@section('title', 'ApiKey List - Pages')

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
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ html()->label('Key')->class('form-label') }}
                                        {{ html()->text('key')->class('form-control')->placeholder('Cari Key')->value(@old('key')) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ html()->label('Aktif')->class('form-label') }}
                                        {{ html()->text('is_active')->class('form-control')->placeholder('Cari Aktif')->value(@old('is_active')) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ html()->label('Terakhir Dipakai')->class('form-label') }}
                                        {{ html()->text('last_used_at')->class('form-control')->placeholder('Cari Terakhir Dipakai')->value(@old('last_used_at')) }}
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
        <div class="card mt-2">

            <div class="card-header border-bottom">
                <div class="row">
                    <div class="col-md">
                        <h5 class="card-title mb-3">ApiKey</h5>
                    </div>
                    @can('api_key-create')
                        <div class="col-md">
                            <div
                                class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                                <div class="dt-buttons btn-group flex-wrap">
                                    @can('api_key-create')
                                        <form action="{{ route('api_key.store') }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-primary">
                                                <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                                    <span class="d-none d-sm-inline-block">Generate Key</span>
                                                </span>
                                            </button>
                                        </form>
                                        <a href="{{ route('api_key.create') }}" class="btn btn-secondary btn-primary">
                                            <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                                <span class="d-none d-sm-inline-block">Tambah</span>
                                            </span>
                                        </a>
                                    @endcan

                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table text-wrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Key</th>
                                <th>Aktif</th>
                                <th>Terakhir Dipakai</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = ($api_keys->currentPage() - 1) * $api_keys->perPage() + 1;
                            @endphp
                            @foreach ($api_keys as $api_key)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $api_key->key }}</td>
                                    <td>{{ $api_key->is_active }}</td>
                                    <td>{{ $api_key->last_used_at }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
                                            <div class="dropdown-menu">
                                                @can('api_key-edit')
                                                    <a class="dropdown-item"
                                                        href="{{ route('api_key.edit', $api_key->uuid) }}"><i
                                                            class="ti ti-pencil me-1"></i>
                                                        Edit</a>
                                                @endcan
                                                @can('api_key-delete')
                                                    <form action="{{ route('api_key.destroy', $api_key->uuid) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus ApiKey ini?')">
                                                            <i class="ti ti-trash me-1"></i> Delete
                                                        </button>
                                                    </form>
                                                @endcan

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{ $api_keys->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
            </div>

        </div>
    @endsection
