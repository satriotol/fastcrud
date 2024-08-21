@extends('layouts/layoutMaster')

@section('title', ' ApiKey - Forms')

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
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> ApiKey</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">ApiKey</h5>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($api_key) {{ route('api_key.update', $api_key->uuid) }} @endisset @empty($api_key) {{ route('api_key.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($api_key)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            @include('partials.errors')
                        </div>
                        <div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="key">Key</label>
    <div class="col-sm-10">
        {{html()->text('key', isset($api_key) ? $api_key->key : @old('key'))->class('form-control')->placeholder('Masukkan Key')->required(true)}}
        @error('key')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
    
    <div class="form-check mt-3">
        {{html()->checkbox('is_active', isset($api_key) ? $api_key->is_active : @old('is_active'))->class('form-check-input')->id('is_active')}}
        <label class="form-check-label" for="is_active"> Aktif </label>     
    </div>
<div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="last_used_at">Terakhir Dipakai</label>
    <div class="col-sm-10">
        {{html()->date('last_used_at', isset($api_key) ? $api_key->last_used_at : @old('last_used_at'))->class('form-control')->placeholder('Masukkan Terakhir Dipakai')->required(false)}}
        @error('last_used_at')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
                        <div class="row justify-content-end text-end">
                            <div class="col-sm-10">
                                <a href="{{ route('api_key.index') }}" class="btn btn-warning">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
