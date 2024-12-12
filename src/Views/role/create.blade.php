@extends('layouts/layoutMaster')

@section('title', 'Assign Role - Permissions')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-icons/bootstrap-icons.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 plugin
            $('.select2').select2();

            // Handle group checkbox changes
            $('.check-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                var prefix = $(this).data('prefix');

                // Toggle all checkboxes in the group
                $('input[name="permissions[]"]').each(function() {
                    if ($(this).data('prefix') === prefix) {
                        $(this).prop('checked', isChecked);
                    }
                });
            });

            // Handle global check all and clear all
            $('#checkAll').on('click', function() {
                $('input[name="permissions[]"]').prop('checked', true);
            });

            $('#clearAll').on('click', function() {
                $('input[name="permissions[]"]').prop('checked', false);
            });
        });
    </script>
@endsection

@section('content')
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Forms/</span> Assign Role Permissions</h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assign Permissions to Role</h5>
                    <div>
                        <button type="button" id="checkAll" class="btn btn-sm btn-success me-2">Check All</button>
                        <button type="button" id="clearAll" class="btn btn-sm btn-danger">Clear All</button>
                    </div>
                </div>
                <div class="card-body">
                    <form
                        action="@isset($role) {{ route('role.update', $role->id) }} @endisset @empty($role) {{ route('role.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($role)
                            @method('PUT')
                        @endisset

                        <!-- Role Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter role name" value="{{ isset($role) ? $role->name : old('name') }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div class="mb-4">
                            <label class="form-label">Permissions</label>
                            <table class="table table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 25%;">Group Prefix</th>
                                        <th scope="col">Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissionsGrouped as $groupedPrefix => $permissions)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input check-all" data-prefix="{{ $groupedPrefix }}">
                                                    <label class="form-check-label ms-2">{{ $groupedPrefix }}</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap">
                                                    @foreach ($permissions as $permission)
                                                        <div class="form-check me-4 mb-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="permission{{ $permission->id }}" name="permissions[]"
                                                                value="{{ $permission->name }}"
                                                                data-prefix="{{ $groupedPrefix }}"
                                                                @isset($role) {{ $role->permissions->contains($permission) ? 'checked' : '' }} @endisset>
                                                            <label class="form-check-label"
                                                                for="permission{{ $permission->id }}">
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @error('permissions')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('role.index') }}" class="btn btn-secondary me-2">Back</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection