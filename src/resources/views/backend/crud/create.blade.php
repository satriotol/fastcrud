@extends('backend_layouts.main')
@section('content')
    <div class="page-header">
        <h1 class="page-title">Crud</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('crud.index') }}">Crud</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crud Form</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Crud</h3>
                </div>
                <div class="card-body">
                    @include('partials.errors')
                    <form
                        action="@isset($crud) {{ route('crud.update', $crud->id) }} @endisset @empty($crud) {{ route('crud.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($crud)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            {!! Form::label('model', 'Model') !!}
                            {!! Form::text('model', isset($crud) ? $crud->model : @old('model'), [
                                'required',
                                'placeholder' => 'Masukkan Nama Model',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('singular', 'Singular') !!}
                            {!! Form::text('singular', isset($crud) ? $crud->singular : @old('singular'), [
                                'required',
                                'placeholder' => 'Masukkan Tunggal',
                                'class' => 'form-control',
                            ]) !!}
                        </div>
                        {{-- <div class="form-group">
                            {!! Form::label('plural', 'Plural') !!}
                            {!! Form::text('plural', isset($crud) ? $crud->plural : @old('plural'), [
                                'required',
                                'placeholder' => 'Masukkan Jamak',
                                'class' => 'form-control',
                            ]) !!}
                        </div> --}}
                        <table class="table table-bordered" id="dynamicAddRemove">
                            <tr>
                                <th>Nama Tabel | Tampilan</th>
                                <th>Tipe Data</th>
                                <th>Nullable</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="tables[0][name]" required placeholder="Masukkan Nama Tabel"
                                        class="form-control" />
                                    <input type="text" name="tables[0][tampilan]" required
                                        placeholder="Masukkan Tampilan Nama" class="form-control">
                                </td>
                                <td>
                                    <select name="tables[0][type]" class="form-control" required>
                                        <option value="">Pilih Tipe</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type }}">{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="tables[0][is_null]" class="form-control">
                                        <option value="0">Tidak</option>
                                        <option value="nullable">Ya</option>
                                    </select>
                                </td>
                                <td><button type="button" name="add" id="dynamic-ar"
                                        class="btn btn-outline-primary">Tambah</button></td>
                            </tr>
                        </table>
                        <div class="text-end">
                            <a class="btn btn-warning" href="{{ route('crud.index') }}">Kembali</a>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script type="text/javascript">
        var i = 0;
        $("#dynamic-ar").click(function() {
            ++i;
            const inputTemplate = `
            <tr>
                <td>
                    <input type="text" name="tables[` + i + `][name]" required placeholder="Masukkan Nama Tabel"
                        class="form-control" />
                    <input type="text" name="tables[` + i + `][tampilan]" required
                        placeholder="Masukkan Tampilan Nama" class="form-control">
                </td>
                <td>
                    <select name="tables[` + i + `][type]" class="form-control" required>
                        <option value="">Pilih Tipe</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="tables[` + i + `][is_null]" class="form-control">
                        <option value="0">Tidak</option>
                        <option value="nullable">Ya</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger remove-input-field">Hapus</button></td>
            </tr>
            `;
            $("#dynamicAddRemove").append(inputTemplate);
        });
        $(document).on('click', '.remove-input-field', function() {
            $(this).parents('tr').remove();
        });
    </script>
@endpush
