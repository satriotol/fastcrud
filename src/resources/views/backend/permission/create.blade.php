@extends('layouts/layoutMaster')

@section('title', ' Horizontal Layouts - Forms')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> {Permission}</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{Permission}</h5>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($permission) {{ route('permission.update', $permission->id) }} @endisset @empty($permission) {{ route('permission.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($permission)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="name">Nama Permission</label>
                            <div class="col-sm-10">
                                {!! Form::text('name', isset($permission) ? $permission->name : @old('name'), [
                                    'class' => 'form-control',
                                    'placeholder' => 'Masukkan Nama Permission',
                                    'required',
                                ]) !!}
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check mt-3">
                                {!! Form::checkbox('isDefault', 'checked', '', [
                                    'class' => 'form-check-input',
                                    'for' => 'defaultCheck1',
                                    'id' => 'defaultCheck1',
                                ]) !!}
                                <label class="form-check-label" for="defaultCheck1">
                                    Default Permission
                                </label>
                            </div>
                        </div>
                        <div class="row justify-content-end text-end">
                            <div class="col-sm-10">
                                <a href="{{ route('permission.index') }}" class="btn btn-warning">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
