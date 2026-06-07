@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
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
        <h2 class="fw-bold text-dark mb-0">Admin Dashboard</h2>
        <div class="text-muted small fw-semibold">
            <i class="bi bi-calendar3 me-1"></i> {{ date('D, d M Y') }}
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-primary">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted fw-bold mb-1 uppercase small">Total Employees</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_employees'] }}</h3>
                    </div>
                    <div class="icon-bg bg-primary bg-opacity-10 text-primary fs-4">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-success">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted fw-bold mb-1 uppercase small">Active</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $stats['active_employees'] }}</h3>
                    </div>
                    <div class="icon-bg bg-success bg-opacity-10 text-success fs-4">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-danger">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted fw-bold mb-1 uppercase small">Inactive</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $stats['inactive_employees'] }}</h3>
                    </div>
                    <div class="icon-bg bg-danger bg-opacity-10 text-danger fs-4">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift border-start border-4 border-info">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted fw-bold mb-1 uppercase small">Total Requests</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_requests'] }}</h3>
                    </div>
                    <div class="icon-bg bg-info bg-opacity-10 text-info fs-4">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Leave Trends</h5>
                    <i class="bi bi-graph-up text-primary"></i>
                </div>
                <div class="card-body p-4">
                    <canvas id="leaveChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Employee Status</h5>
                    <i class="bi bi-pie-chart text-success"></i>
                </div>
                <div class="card-body p-4">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success bg-opacity-10">
                <div class="card-body p-4 text-center">
                    <h1 class="fw-bold text-success mb-1">{{ $stats['approved_leaves'] }}</h1>
                    <p class="text-muted fw-semibold mb-0">Total Approved</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-danger bg-opacity-10">
                <div class="card-body p-4 text-center">
                    <h1 class="fw-bold text-danger mb-1">{{ $stats['rejected_leaves'] }}</h1>
                    <p class="text-muted fw-semibold mb-0">Total Rejected</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-warning bg-opacity-10">
                <div class="card-body p-4 text-center">
                    <h1 class="fw-bold text-warning mb-1">{{ $stats['pending_approvals'] }}</h1>
                    <p class="text-muted fw-semibold mb-0">Total Pending</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {

    const ctxLeave = document.getElementById('leaveChart').getContext('2d');
    new Chart(ctxLeave, {
        type: 'bar',
        data: {
            labels: ['Approved', 'Rejected', 'Pending'],
            datasets: [{
                label: 'Leave Requests',
                data: [{{ $stats['approved_leaves'] }}, {{ $stats['rejected_leaves'] }}, {{ $stats['pending_approvals'] }}],
                backgroundColor: ['#198754', '#dc3545', '#ffc107'],
                borderRadius: 8,
                barThickness: 50
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { display: false } }, x: { grid: { display: false } } }
        }
    });

    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [{{ $stats['active_employees'] }}, {{ $stats['inactive_employees'] }}],
                backgroundColor: ['#198754', '#6c757d'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' }, cutout: '70%' }
        }
    });
});
</script>
@endpush
@endsection
