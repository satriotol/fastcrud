@extends('layouts/layoutMaster')

@section('title', ' Config - Forms')

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
            $('#type').change(function() {
                console.log('change');

                var selectedType = $(this).val();
                var newContent = '';

                if (selectedType === 'string' || selectedType === 'url') {
                    newContent = `
                        <input name="config_value" id="config_value" class="form-control" type="text" placeholder="Masukkan Value" required>
                    `;
                } else if (selectedType === 'int') {
                    newContent = `
                        <input name="config_value" id="config_value" class="form-control" type="number" placeholder="Masukkan Value" onkeydown="return event.key !== 'e' && event.key !== 'E' && event.key !== '+' && event.key !== '-'" required>
                    `;
                } else if (selectedType === 'boolean') {
                    newContent = `
                        <div class="form-check">
                            <input name="config_value" class="form-check-input" type="radio" value="true" id="config_value_true">
                            <label class="form-check-label" for="config_value_true">True</label>
                        </div>
                        <div class="form-check">
                            <input name="config_value" class="form-check-input" type="radio" value="false" id="config_value_false">
                            <label class="form-check-label" for="config_value_false">False</label>
                        </div>
                    `;
                }

                $('#container_value').html(newContent);
            });
        });
    </script>


@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Config</h4>

    <!-- Basic Layout & Basic with Icons -->
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Config</h5>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($config) {{ route('config.update', $config->uuid) }} @endisset @empty($config) {{ route('config.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($config)
                            @method('PUT')
                        @endisset
                        <div class="row mb-3">
                            @include('partials.errors')
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="description">Deskripsi</label>
                            <div class="col-sm-10">
                                {{ html()->text('description', isset($config) ? $config->description : @old('description'))->class('form-control')->placeholder('Masukkan Deskripsi')->required(true) }}
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="type">Tipe</label>
                            <div class="col-sm-10">
                                {{ html()->select('type', array_combine($types, $types), isset($config) ? $config->type : @old('type'))->class('form-control')->required(true)->attributes(['id' => 'type'])->placeholder('Pilih Tipe') }}
                                @error('type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label" for="config_value">Value</label>
                            <div class="col-sm-10">
                                <div id="container_value">

                                    @if ((isset($config) && $config->type == 'NUMBER') || old('type') == 'NUMBER')
                                        <input name="config_value" id="config_value" class="form-control" type="number"
                                            placeholder="Masukkan Value"
                                            value="{{ isset($config) ? $config->config_value : @old('config_value') }}"
                                            onkeydown="return event.key !== 'e' && event.key !== 'E' && event.key !== '+' && event.key !== '-'"
                                            required>
                                    @elseif ((isset($config) && $config->type == 'BOOLEAN') || old('type') == 'BOOLEAN')
                                        <div class="form-check">
                                            <input name="config_value" class="form-check-input" type="radio"
                                                value="true" id="config_value_true"
                                                {{ (isset($config) && $config->config_value == 'true') || old('type') == 'true' ? 'checked=checked' : '' }}>
                                            <label class="form-check-label" for="config_value_true">True</label>
                                        </div>
                                        <div class="form-check">
                                            <input name="config_value" class="form-check-input" type="radio"
                                                value="false" id="config_value_false"
                                                {{ (isset($config) && $config->config_value == 'false') || old('type') == 'false' ? 'checked=checked' : '' }}>
                                            <label class="form-check-label" for="config_value_false">False</label>
                                        </div>
                                    @else
                                        <input name="config_value" id="config_value" class="form-control" type="text"
                                            placeholder="Masukkan Value"
                                            value="{{ isset($config) ? $config->config_value : @old('config_value') }}"
                                            required>
                                    @endif

                                </div>
                                @error('config_value')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row justify-content-end text-end">
                            <div class="col-sm-10">
                                <a href="{{ route('config.index') }}" class="btn btn-warning">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
