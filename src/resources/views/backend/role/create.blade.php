@extends('backend_layouts.main')
@section('content')
    <div class="page-header">
        <h1 class="page-title">Role</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
                <li class="breadcrumb-item active" aria-current="page">Role Tabel</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Role</h3>
                </div>
                <div class="card-body">
                    @include('partials.errors')
                    <form
                        action="@isset($role) {{ route('role.update', $role->id) }} @endisset @empty($role) {{ route('role.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($role)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control"
                                value="{{ isset($role) ? $role->name : @old('name') }}" required name="name">
                        </div>
                        <div class="form-group">
                            <label class="custom-switch form-switch me-5">
                                <input type="checkbox" id="checkAll" class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Select All</span>
                            </label><br>
                            <div class="row">
                                @foreach ($permissions as $permission)
                                    <div class="col-md-3">
                                        <label class="custom-switch form-switch me-5">
                                            <input type="checkbox" name="permission[]"
                                                @isset($role)
                                        {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                    @endisset
                                                value="{{ $permission->id }}" class="custom-switch-input name">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">{{ $permission->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        </div>
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
    <script>
        $("#checkAll").click(function() {
            $('.name').not(this).prop('checked', this.checked);
        });
    </script>
@endpush
