@extends('layouts/layoutMaster')

@section('title', ' Config - Forms')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {

            function destroySummernote() {
                if ($('#config_value').hasClass('summernote')) {
                    $('#config_value').summernote('destroy');
                }
            }

            function initSummernote() {
                $('#config_value').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline']],
                        ['para', ['ul', 'ol']],
                        ['insert', ['link']],
                        ['view', ['codeview']]
                    ]
                });
            }

            function updateConfigValueInput(type, oldValue = '') {
                destroySummernote();

                let html = '';

                if (type === 'string' || type === 'url') {
                    html = `
                <input name="config_value" id="config_value" class="form-control"
                       type="text" value="${oldValue}" required>
            `;
                } else if (type === 'int') {
                    html = `
                <input name="config_value" id="config_value" class="form-control"
                       type="number" value="${oldValue}"
                       onkeydown="return !['e','E','+','-'].includes(event.key)" required>
            `;
                } else if (type === 'boolean') {
                    html = `
                <div class="form-check">
                    <input name="config_value" type="radio" value="true"
                        ${oldValue === 'true' ? 'checked' : ''}> True
                </div>
                <div class="form-check">
                    <input name="config_value" type="radio" value="false"
                        ${oldValue === 'false' ? 'checked' : ''}> False
                </div>
            `;
                } else if (type === 'text') {
                    html = `
                <textarea name="config_value" id="config_value"
                          class="form-control summernote">${oldValue}</textarea>
            `;
                }

                $('#container_value').html(html);

                if (type === 'text') {
                    initSummernote();
                }
            }

            $('#type').on('change', function() {
                updateConfigValueInput(this.value);
            });

            // init awal
            updateConfigValueInput(
                $('#type').val(),
                @json(isset($config) ? $config->config_value : old('config_value'))
            );

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
                                    @if ((isset($config) && $config->type === 'int') || old('type') === 'int')
                                        <input name="config_value" id="config_value" class="form-control" type="number"
                                            value="{{ isset($config) ? $config->config_value : old('config_value') }}"
                                            placeholder="Masukkan Value"
                                            onkeydown="return event.key !== 'e' && event.key !== 'E' && event.key !== '+' && event.key !== '-'"
                                            required>
                                    @elseif ((isset($config) && $config->type === 'boolean') || old('type') === 'boolean')
                                        <div class="form-check">
                                            <input name="config_value" class="form-check-input" type="radio"
                                                value="true" id="config_value_true"
                                                {{ (isset($config) && $config->config_value === 'true') || old('config_value') === 'true' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="config_value_true">True</label>
                                        </div>
                                        <div class="form-check">
                                            <input name="config_value" class="form-check-input" type="radio"
                                                value="false" id="config_value_false"
                                                {{ (isset($config) && $config->config_value === 'false') || old('config_value') === 'false' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="config_value_false">False</label>
                                        </div>
                                    @else
                                        <input name="config_value" id="config_value" class="form-control" type="text"
                                            value="{{ isset($config) ? $config->config_value : old('config_value') }}"
                                            placeholder="Masukkan Value" required>
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
