@extends('backend_layouts.main')
@section('content')
    <div class="page-header">
        <h1 class="page-title">Crud</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('crud.index') }}">Crud</a></li>
                <li class="breadcrumb-item active" aria-current="page">Crud Tabel</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Crud</h3>
                </div>
                <div class="card-body">
                    <div class="text-end mb-2">
                        <a href="{{ route('crud.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table border table-bordered text-nowrap text-md-nowrap table-sm mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>Model</th>
                                    <th>Singular</th>
                                    <th>Plural</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cruds as $crud)
                                    <tr>
                                        <td>{{ $crud->model }}</td>
                                        <td>{{ $crud->singular }}</td>
                                        <td>{{ $crud->plural }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('crud.destroy', $crud->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <a class="btn btn-sm btn-warning"
                                                    href="{{ route('crud.edit', $crud->id) }}">
                                                    Edit
                                                </a>
                                                <input type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')" value="Delete" id="">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
