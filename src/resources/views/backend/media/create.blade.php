@extends('layouts/layoutMaster')

@section('title', ' Media - Forms')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.17.0/full/ckeditor.js"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-basic-inputs.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script>
        CKEDITOR.replace('editor', {
            toolbar: 'Basic'
        });
    </script>
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Media</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Media</h5>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($media) {{ route('media.update', $media->uuid) }} @endisset @empty($media) {{ route('media.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($media)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            @include('partials.errors')
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="title">Title</label>
                            <div class="col-sm-10">
                                {!! Form::text('title', isset($media) ? $media->title : @old('title'), [
                                    'class' => 'form-control',
                                    'placeholder' => 'Masukkan Title',
                                    'required' => false,
                                ]) !!}
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="file">File</label>
                            <div class="col-sm-10">
                                {!! Form::file('file', [
                                    'class' => 'form-control',
                                    'id' => 'formFile',
                                    'required' => true,
                                ]) !!}
                                @error('file')
                                    <br>
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row justify-content-end text-end">
                            <div class="col-sm-10">
                                <a href="{{ route('media.index') }}" class="btn btn-warning">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
