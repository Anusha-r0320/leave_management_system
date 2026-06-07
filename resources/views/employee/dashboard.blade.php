@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    /* Subtle hover effect for the cards */
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-bg {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">My Dashboard</h2>
        <a href="{{ route('employee.leave.apply') }}" class="btn btn-primary shadow-sm hover-lift">
            <i class="bi bi-plus-circle me-1"></i> Apply for Leave
        </a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-primary bg-primary bg-opacity-10">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-primary fw-bold mb-1 uppercase">Available Balance</h6>
                        <h2 class="fw-bold mb-0 text-primary">{{ $stats['balance'] }} <span class="fs-6 text-muted fw-normal">Days</span></h2>
                    </div>
                    <div class="icon-bg bg-primary text-white fs-3 shadow-sm">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-info">
                <div class="card-body d-flex flex-column align-items-start justify-content-center">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                        <h6 class="text-muted fw-semibold mb-0 uppercase">Total</h6>
                        <i class="bi bi-file-earmark-text text-info fs-5"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-success">
                <div class="card-body d-flex flex-column align-items-start justify-content-center">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                        <h6 class="text-muted fw-semibold mb-0 uppercase">Approved</h6>
                        <i class="bi bi-check-circle text-success fs-5"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['approved'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-warning">
                <div class="card-body d-flex flex-column align-items-start justify-content-center">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                        <h6 class="text-muted fw-semibold mb-0 uppercase">Pending</h6>
                        <i class="bi bi-hourglass-split text-warning fs-5"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-2">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-danger">
                <div class="card-body d-flex flex-column align-items-start justify-content-center">
                    <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                        <h6 class="text-muted fw-semibold mb-0 uppercase">Rejected</h6>
                        <i class="bi bi-x-circle text-danger fs-5"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['rejected'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
            <h5 class="fw-bold text-dark mb-0">Recent Leave Requests</h5>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4 fw-semibold border-0">Leave Type</th>
                            <th class="fw-semibold border-0">Start Date</th>
                            <th class="fw-semibold border-0">End Date</th>
                            <th class="fw-semibold border-0">Days</th>
                            <th class="fw-semibold border-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-semibold text-dark">{{ $request->leaveType->name ?? 'N/A' }}</span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($request->start_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</td>
                                <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">{{ $request->total_days }}</span></td>
                                <td>
                                    @if($request->status == 'approved')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i> Approved</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i> Rejected</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i> Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-folder2-open fs-1 text-light mb-2"></i>
                                        <p class="mb-0">You have no leave requests.</p>
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
