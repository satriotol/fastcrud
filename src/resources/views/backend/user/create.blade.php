@extends('backend_layouts.main')
@section('content')
    <div class="page-header">
        <h1 class="page-title">User</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">User Tabel</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form User</h3>
                </div>
                <div class="card-body">
                    @include('partials.errors')
                    <form
                        action="@isset($user) {{ route('user.update', $user->id) }} @endisset @empty($user) {{ route('user.store') }} @endempty"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($user)
                            @method('PUT')
                        @endisset
                        <div class="form-group">
                            {!! Form::label('name', 'Nama') !!}
                            {!! Form::text('name', isset($user) ? $user->name : @old('name'), [
                                'required',
                                'class' => 'form-control',
                                'placeholder' => 'Masukkan Nama',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('email', 'Email') !!}
                            {!! Form::email('email', isset($user) ? $user->email : @old('email'), [
                                'placeholder' => 'Masukkan Email',
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('password', 'Password') !!}
                            {!! Form::password('password', ['class' => 'form-control', empty($user) ? 'required' : '']) !!}
                        </div>
                        <div class="form-group">
                            <label>Password Confirmation</label>
                            <input type="password" class="form-control" value=""
                                @empty($user) required @endempty name="password_confirmation">
                        </div>
                        @empty($editProfile)
                            <div class="form-group">
                                {!! Form::label('roles', 'Role') !!}
                                <select name="roles" class="form-control" id="" required>
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->id }}"
                                            @isset($user) @if ($r->name === $user->getOtherRole()) selected @endif
                                @endisset>
                                            {{ $r->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endempty
                        @isset($editProfile)
                            {!! Form::hidden('editProfile', 'iniTest', []) !!}
                        @endisset
                        <div class="text-end">
                            @empty($editProfile)
                                <a class="btn btn-warning" href="{{ url()->previous() }}">Kembali</a>
                            @endempty
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
