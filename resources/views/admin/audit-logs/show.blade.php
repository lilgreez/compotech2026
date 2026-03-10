@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Audit Log Details</h3>
                    <a href="{{ route('audit-logs.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Date:</th>
                                    <td>{{ $auditLog->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>User:</th>
                                    <td>{{ $auditLog->user->name ?? 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <th>Action:</th>
                                    <td>
                                        <span class="badge bg-{{ $auditLog->action === 'CREATE' ? 'success' : ($auditLog->action === 'UPDATE' ? 'warning' : 'danger') }}">
                                            {{ $auditLog->action }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Table:</th>
                                    <td>{{ $auditLog->table_name }}</td>
                                </tr>
                                <tr>
                                    <th>Record ID:</th>
                                    <td>{{ $auditLog->record_id }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($auditLog->old_values)
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h5>Old Values</h5>
                                <pre class="bg-light p-3 rounded">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif

                    @if($auditLog->new_values)
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h5>New Values</h5>
                                <pre class="bg-light p-3 rounded">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection