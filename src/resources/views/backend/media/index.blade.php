@extends('layouts/layoutMaster')

@section('title', 'Media List - Pages')

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
                    <h5 class="card-title mb-3">Media</h5>
                </div>
                @can('media-create')
                    <div class="col-md">
                        <div
                            class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                            <div class="dt-buttons btn-group flex-wrap">
                                @can('media-create')
                                    <a href="{{ route('media.create') }}" class="btn btn-secondary btn-primary">
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
    </div>

    <div class="row mt-3">

        @foreach ($medias as $media)
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <img class="card-img-top" height="300px" style="object-fit: contain"
                        src="{{ asset('storage/' . $media->file) }}" alt="Card image cap">
                    <div class="card-body">
                        <p>{{ $media->title }}</p>
                        <div class="mt-3">
                            <div class="text-end">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                        data-bs-toggle="modal"
                                        data-bs-target="#paymentMethods{{ $media->uuid }}">Info</button>
                                    @include('backend.media.partials.modalInfo')
                                    @can('media-delete')
                                        <form action="{{ route('media.destroy', $media->uuid) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm waves-effect waves-light"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus Media ini?')">
                                                <i class="ti ti-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $medias->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
@endsection
