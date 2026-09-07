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
                                <th>Catatan</th>
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
                                    <td>
                                        @can('api_key-edit')
                                            <form action="{{ route('api_key.toggle', $api_key->uuid) }}" method="post">
                                                @csrf
                                                @method('patch')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        onchange="this.form.submit()"
                                                        {{ $api_key->is_active ? 'checked' : '' }}>
                                                </div>
                                            </form>
                                        @else
                                            <span
                                                class="badge bg-label-{{ $api_key->is_active ? 'success' : 'secondary' }}">
                                                {{ $api_key->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        @endcan
                                    </td>
                                    <td>{{ $api_key->note }}</td>
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
