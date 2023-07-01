@extends('backend_layouts.main')
@section('content')
    <div class="page-header">
        <h1 class="page-title">Audit</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('audit.index') }}">Audit</a></li>
                <li class="breadcrumb-item active" aria-current="page">Audit Tabel</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Audit</h3>
                </div>
                <div class="card-body">
                    <form action="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">User</label>
                                    <select name="user_id" class="form-control select2-show-search" id="">
                                        <option value="">Pilih User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected(@old('user_id'))>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('event', 'Event') !!}
                                    {!! Form::select('event', $events, old('event'), [
                                        'class' => 'select2-show-search form-select',
                                        'placeholder' => 'Pilih Event',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('auditable_type', 'Auditable Type') !!}
                                    {!! Form::select('auditable_type', $auditable_types, old('auditable_type'), [
                                        'class' => 'select2-show-search form-select',
                                        'placeholder' => 'Pilih Auditable Type',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Cari</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>User</th>
                                    <th>Event</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audits as $audit)
                                    <tr>
                                        <td>{{ $audit->created_at }}</td>
                                        <td>{{ $audit->user->name }} <br>{{ $audit->auditable_type }}
                                            <br>
                                            <div class="badge bg-danger">
                                                {{ $audit->ip_address }}
                                            </div>
                                            <br>
                                            <a href="{{ $audit->url }}" target="_blank">{{ $audit->url }}</a>
                                            <br>
                                            <div class="badge bg-success">
                                                {{ $audit->event }}
                                            </div>
                                        </td>
                                        <td>
                                            <textarea name="" id="" rows="5" readonly class="form-control">{{ $audit->old_values }}</textarea>
                                            <textarea name="" id="" rows="5" readonly class="form-control mt-2">{{ $audit->new_values }}</textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $audits->appends($_GET)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
