@extends('layouts/layoutMaster')

@section('title', ' Menu - Forms')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.17.0/full/ckeditor.js"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replaceClass = 'ckeditor';
        });
    </script>
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Menu</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Menu</h5>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($menu) {{ route('menu.update', $menu->uuid) }} @endisset @empty($menu) {{ route('menu.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($menu)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            @include('partials.errors')
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="title">Title</label>
                            <div class="col-sm-10">
                                {{ html()->text('title', isset($menu) ? $menu->title : @old('title'))->placeholder('Masukkan Title')->class('form-control')->required(true) }}
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="content">Konten</label>
                            <div class="col-sm-10">
                                {{ html()->textarea('content', isset($menu) ? $menu->content : @old('content'))->class('form-control ckeditor')->placeholder('Masukkan Konten')->required(true) }}
                                @error('content')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end text-end">
                            <div class="col-sm-10">
                                <a href="{{ route('menu.index') }}" class="btn btn-warning">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
