@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Reporting Module</h2>
        <button onclick="window.print()" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-printer me-1"></i> Print Report
        </button>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form id="reportFilterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Employee Name</label>
                    <input type="text" name="employee_name" class="form-control bg-light border-0 filter-input" placeholder="Search name...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Department</label>
                    <select name="department" class="form-select bg-light border-0 filter-input">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)<option value="{{ $dept }}">{{ $dept }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Leave Type</label>
                    <select name="leave_type_id" class="form-select bg-light border-0 filter-input">
                        <option value="">All Types</option>
                        @foreach($leaveTypes as $type)<option value="{{ $type->id }}">{{ $type->name }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">Status</label>
                    <select name="status" class="form-select bg-light border-0 filter-input">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Date Range</label>
                    <div class="input-group">
                        <input type="date" name="start_date" class="form-control bg-light border-0 filter-input">
                        <input type="date" name="end_date" class="form-control bg-light border-0 filter-input">
                    </div>
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
                            <th class="ps-4 fw-semibold border-0">Employee</th>
                            <th class="fw-semibold border-0">Department</th>
                            <th class="fw-semibold border-0">Leave Type</th>
                            <th class="fw-semibold border-0">Duration</th>
                            <th class="fw-semibold border-0 text-center">Days</th>
                            <th class="fw-semibold border-0">Status</th>
                            <th class="fw-semibold border-0">Approved By</th>
                            <th class="pe-4 fw-semibold border-0">Approval Date</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    function loadReports() {
        $('#reportTableBody').html('<tr><td colspan="8" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Loading...</td></tr>');

        $.ajax({
            url: "{{ route('admin.reports') }}",
            type: "GET",
            data: $('#reportFilterForm').serialize(),
            success: function(response) {
                let rows = '';
                if(response.reports.length === 0) {
                    rows = '<tr><td colspan="8" class="text-center py-5 text-muted">No records found matching filters.</td></tr>';
                } else {
                    response.reports.forEach(report => {
                        let statusBadge = '';
                        if(report.status === 'approved') statusBadge = '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">Approved</span>';
                        else if(report.status === 'rejected') statusBadge = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">Rejected</span>';
                        else statusBadge = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill">Pending</span>';

                        rows += `<tr>
                            <td class="ps-4">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">${report.user ? report.user.name : 'N/A'}</span>
                                    <small class="text-muted">${report.user ? report.user.employee_id : ''}</small>
                                </div>
                            </td>
                            <td>${report.user ? report.user.department : 'N/A'}</td>
                            <td>${report.leave_type ? report.leave_type.name : 'N/A'}</td>
                            <td>
                                <div class="small">
                                    <span class="text-dark">${report.start_date}</span>
                                    <br>
                                    <span class="text-muted">to ${report.end_date}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">${report.total_days}</span>
                            </td>
                            <td>${statusBadge}</td>
                            <td class="text-muted">${report.manager ? report.manager.name : '---'}</td>
                            <td class="pe-4 text-muted small">${report.approved_at ? report.approved_at : '---'}</td>
                        </tr>`;
                    });
                }
                $('#reportTableBody').html(rows);
            }
        });
    }

    loadReports();

    $('.filter-input').on('change keyup', function() {
        loadReports();
    });
});
</script>
@endpush
@endsection
