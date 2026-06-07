@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Leave History</h2>
        <a href="{{ route('employee.leave.apply') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> New Application
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('employee.leave.history') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-muted">Leave Type</label>
                    <select name="leave_type_id" class="form-select bg-light border-0">
                        <option value="">All Types</option>
                        @foreach($leave_types as $type)
                            <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-muted">Status</label>
                    <select name="status" class="form-select bg-light border-0">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-muted">From Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control bg-light border-0">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold text-muted">To Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control bg-light border-0">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100 fw-bold">Filter</button>
                    <a href="{{ route('employee.leave.history') }}" class="btn btn-outline-secondary w-100 fw-bold">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4 fw-semibold border-0">Request ID</th>
                            <th class="fw-semibold border-0">Leave Type</th>
                            <th class="fw-semibold border-0">Start Date</th>
                            <th class="fw-semibold border-0">End Date</th>
                            <th class="fw-semibold border-0 text-center">Days</th>
                            <th class="fw-semibold border-0">Applied Date</th>
                            <th class="fw-semibold border-0">Status</th>
                            <th class="fw-semibold border-0">Manager Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#REQ-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td><span class="fw-semibold text-dark">{{ $request->leaveType->name ?? 'N/A' }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                        {{ $request->total_days }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($request->status == 'approved')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i> Approved
                                        </span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">
                                            <i class="bi bi-x-circle me-1"></i> Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">
                                            <i class="bi bi-hourglass-split me-1"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted small italic">
                                    {{ $request->manager_remarks ?? '---' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-folder2-open fs-1 text-light mb-2"></i>
                                        <p class="mb-0">No leave history found with selected filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
