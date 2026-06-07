@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Pending Leave Requests</h2>
        <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-3 shadow-sm">
            <i class="bi bi-arrow-left"></i> Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="requestsTable">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4 fw-semibold border-0">Employee</th>
                            <th class="fw-semibold border-0">Department</th>
                            <th class="fw-semibold border-0">Leave Type</th>
                            <th class="fw-semibold border-0">Duration</th>
                            <th class="fw-semibold border-0 text-center">Days</th>
                            <th class="fw-semibold border-0">Reason</th>
                            <th class="fw-semibold border-0">Status</th>
                            <th class="pe-4 fw-semibold border-0 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr id="row-{{ $req->id }}">
                            <td class="ps-4">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $req->user->name ?? 'Unknown User' }}</span>
                                    <small class="text-muted">{{ $req->user->employee_id ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>{{ $req->user->department ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                    {{ $req->leaveType->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-dark">{{ \Carbon\Carbon::parse($req->start_date)->format('d M Y') }}</span>
                                    <br>
                                    <span class="text-muted">to {{ \Carbon\Carbon::parse($req->end_date)->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                    {{ $req->total_days }}
                                </span>
                            </td>
                            <td class="text-wrap" style="max-width: 200px;">
                                <small class="text-muted">{{ $req->reason }}</small>
                            </td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    <button class="btn btn-success btn-sm approve-btn px-3" data-id="{{ $req->id }}" title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm reject-btn px-3" data-id="{{ $req->id }}" title="Reject">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-inbox fs-1 text-light mb-2"></i>
                                    <p class="mb-0">No pending leave requests found.</p>
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

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark" id="rejectModalLabel">Reject Leave Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4">
        <input type="hidden" id="reject_id">
        <label for="manager_remarks" class="form-label fw-semibold text-muted">Manager Remarks (Mandatory)</label>
        <textarea id="manager_remarks" class="form-control bg-light border-0 rounded-3" rows="4" placeholder="e.g. Project deployment scheduled during requested dates..."></textarea>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light fw-bold rounded-3" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger fw-bold rounded-3 px-4 submit-reject">Confirm Rejection</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    $('.approve-btn').click(function() {
        let id = $(this).data('id');
        if(confirm("Are you sure you want to approve this leave request?")) {
            $.post(`/manager/leave_requests/${id}/approve`, function(response) {
                alert(response.success);
                $(`#row-${id}`).fadeOut(400, function() { $(this).remove(); if($('#requestsTable tbody tr').length == 0) location.reload(); });
            }).fail(function(xhr) {
                alert(xhr.responseJSON?.error || 'Failed to approve.');
            });
        }
    });

    $('.reject-btn').click(function() {
        $('#reject_id').val($(this).data('id'));
        var myModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        myModal.show();
    });

    $('.submit-reject').click(function() {
        let id = $('#reject_id').val();
        let remarks = $('#manager_remarks').val();
        if(!remarks.trim()) { alert("Manager remarks are mandatory for rejection."); return; }

        let btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Submitting...');

        $.post(`/manager/leave_requests/${id}/reject`, { manager_remarks: remarks }, function(response) {
            alert(response.success);
            bootstrap.Modal.getInstance(document.getElementById('rejectModal')).hide();
            $(`#row-${id}`).fadeOut(400, function() { $(this).remove(); if($('#requestsTable tbody tr').length == 0) location.reload(); });
            $('#manager_remarks').val('');
        }).fail(function(xhr) {
            alert(xhr.responseJSON?.message || 'Failed to reject.');
        }).always(function() {
            btn.prop('disabled', false).html('Confirm Rejection');
        });
    });
});
</script>
@endpush
@endsection
