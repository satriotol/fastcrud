@extends('layouts/layoutMaster')

@section('title', 'Audit - Pages')

@section('vendor-style')
@endsection

@section('vendor-script')
@endsection

@section('page-script')
@endsection

@section('content')
    <div class="card">

        <div class="card-header border-bottom">
            <div class="row">
                <div class="col-md">
                    <h5 class="card-title mb-3">{Audit}</h5>
                </div>
            </div>
            <form action="">

                <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0 mt-3">
                    <div class="col-md-4">
                        {{ html()->text('name')->class('form-control')->placeholder('Cari Nama')->value(@old('name')) }}
                    </div>
                </div>
                <div
                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                    <div class="dt-buttons btn-group flex-wrap">
                        <button class="btn btn-secondary add-new btn-primary" type="submit">
                            <span><i class="ti ti-search me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">Cari</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>

        </div>
        <div class="card-body">

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Action</th>
                            <th>Auditable Type</th>
                            <th>Auditable ID</th>
                            <th>User</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($audits->currentPage() - 1) * $audits->perPage() + 1;
                        @endphp
                        @foreach ($audits as $audit)
                            <tr>
                                <td>{{ $no }}</td>
                                <td>{{ $audit->event }}</td>
                                <td>{{ $audit->auditable_type }}</td>
                                <td>{{ $audit->auditable_id }}</td>
                                <td>{{ $audit->user ? $audit->user->name : 'System' }}</td>
                                <td>
                                    <ul>
                                        @foreach ($audit->old_values as $key => $old_value)
                                            <li><strong>{{ $key }}:</strong> {{ $old_value }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        @foreach ($audit->new_values as $key => $new_value)
                                            <li><strong>{{ $key }}:</strong> {{ $new_value }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $audit->created_at }}</td>
                            </tr>
                            @php
                                $no++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>                
            </div>
            {{ $audits->appends($_GET)->links('pagination::bootstrap-5')->withClass('pagination-container') }}
        </div>

    </div>
@endsection
