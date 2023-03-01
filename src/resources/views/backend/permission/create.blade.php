@extends('backend_layouts.main')
@section('content')
    <div class="page-header">
        <h1 class="page-title">Permission</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Permission</a></li>
                <li class="breadcrumb-item active" aria-current="page">Permission Tabel</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Permission</h3>
                </div>
                <div class="card-body">
                    @include('partials.errors')
                    <form
                        action="@isset($permission) {{ route('permission.update', $permission->id) }} @endisset @empty($permission) {{ route('permission.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($permission)
                            @method('PUT')
                        @endisset
                        <table class="table table-bordered" id="dynamicAddRemove">
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td><input type="text" name="name[]" placeholder="Name" required class="form-control" />
                                </td>
                                <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add
                                        More</button></td>
                            </tr>
                        </table>
                        <div class="text-end">
                            <a class="btn btn-warning" href="{{ url()->previous() }}">Kembali</a>
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
        $("#add-btn").click(function() {
            ++i;
            $("#dynamicAddRemove").append('<tr><td><input type="text" required name="name[' + i +
                ']" placeholder="Name" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
            );
        });
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });
    </script>
@endpush
