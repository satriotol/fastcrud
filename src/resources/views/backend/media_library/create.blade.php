@extends('backend_layouts.main')
@section('content')
    <div class="page-header">
        <h1 class="page-title">MediaLibrary</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('media_library.index') }}">MediaLibrary</a></li>
                <li class="breadcrumb-item active" aria-current="page">MediaLibrary Form</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form MediaLibrary</h3>
                </div>
                <div class="card-body">
                    @include('partials.errors')
                    <form
                        action="@isset($media_library) {{ route('media_library.update', $media_library->uuid) }} @endisset @empty($media_library) {{ route('media_library.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($media_library)
                            @method('PUT')
                        @endisset
                        <div class='form-group'>
{!! Form::label('name', 'Nama') !!}
{!! Form::text('name', isset($media_library) ? $media_library->name : @old('name'), [
                        'required',
                        'class' => 'form-control',
                        'placeholder' => 'Masukkan Nama',
                    ]) !!}</div>

<div class='form-group'>
{!! Form::label('file', 'File') !!}
{!! Form::file('file', ['id' => 'filepond','required','data-allow-reorder' => 'ture']) !!}</div>
                        <div class="text-end">
                            <a class="btn btn-warning" href="{{ route('media_library.index') }}">Kembali</a>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
@endpush
