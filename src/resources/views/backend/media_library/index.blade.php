@extends('backend_layouts.main')
@section('content')
    <div class="page-header">
        <h1 class="page-title">MediaLibrary</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('media_library.index') }}">MediaLibrary</a></li>
                <li class="breadcrumb-item active" aria-current="page">MediaLibrary Tabel</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">MediaLibrary</h3>
                </div>
                <div class="card-body">
                    <div class="text-end mb-2">
                        <a href="{{ route('media_library.create') }}" class="btn btn-sm btn-primary">Tambah</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table border table-bordered text-nowrap text-md-nowrap table-sm mb-0">
                            <thead>
                                <tr class="text-center">
                                    <th>Nama</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($media_libraries as $media_library)
                                    <tr>
                                        <td>{{ $media_library->name }} <br>
                                            <small>
                                                {{ asset('storage/' . $media_library->file) }}
                                            </small>
                                        </td>
                                        <td>
                                            @if (in_array(strtolower(pathinfo($media_library->file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ asset('storage/' . $media_library->file) }}"
                                                    style="height: 100px" alt="">
                                            @else
                                                <a href="{{ asset('storage/' . $media_library->file) }}" target="_blank"
                                                    rel="noopener noreferrer">Download File</a>
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('media_library.destroy', $media_library->uuid) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <a class="btn btn-sm btn-warning"
                                                    href="{{ route('media_library.edit', $media_library->uuid) }}">
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
                    {{ $media_libraries->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
                </div>
            </div>
        </div>
    </div>
@endsection
