@extends('layouts.app')

@section('content')
<style>
    .form-label .required-star {
        color: #dc3545;
        font-weight: bold;
        margin-left: 2px;
    }
    .duration-card {
        transition: all 0.3s ease;
    }
    .apply-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }
    .input-custom {
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.2s;
    }
    .input-custom:focus {
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-9">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Apply for Leave</h2>
                    <p class="text-muted mb-0">Fill in the details below to submit your request.</p>
                </div>
                <a href="{{ route('employee.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
            </div>

            <div class="card apply-card shadow-lg">
                <div class="card-body p-4 p-md-5">

                    <div id="alert-box" class="alert d-none rounded-4 shadow-sm mb-4 fw-semibold animate__animated animate__fadeIn" role="alert"></div>

                    <form id="leaveForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small uppercase">
                                Leave Type <span class="required-star">*</span>
                            </label>
                            <select name="leave_type_id" class="form-select input-custom bg-light border-0 fs-6" required>
                                <option value="" selected disabled>Select leave category...</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small uppercase">
                                    Start Date <span class="required-star">*</span>
                                </label>
                                <input type="date" name="start_date" id="start_date" class="form-control input-custom bg-light border-0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small uppercase">
                                    End Date <span class="required-star">*</span>
                                </label>
                                <input type="date" name="end_date" id="end_date" class="form-control input-custom bg-light border-0" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="duration-card p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-4 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center text-primary">
                                    <i class="bi bi-calendar-check fs-4 me-3"></i>
                                    <span class="fw-bold">Total Duration:</span>
                                </div>
                                <span class="badge bg-primary text-white fs-5 px-4 py-2 rounded-pill shadow-sm">
                                    <span id="calc_days">0</span> Days
                                </span>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-secondary small uppercase">
                                Reason for Leave <span class="required-star">*</span>
                            </label>
                            <textarea name="reason" class="form-control input-custom bg-light border-0" rows="4" placeholder="Briefly explain the purpose of your leave..." required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="submitBtn" class="btn btn-primary py-3 fw-bold shadow rounded-pill fs-5 transition-all">
                                <i class="bi bi-send-fill me-2"></i> Submit Application
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="text-center mt-4">
                <small class="text-muted italic">
                    <i class="bi bi-info-circle me-1"></i> Your request will be sent to your assigned manager for approval.
                </small>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    });

    $('#start_date, #end_date').on('change', function() {
        let startVal = $('#start_date').val();
        let endVal = $('#end_date').val();

        if(startVal && endVal) {
            let start = new Date(startVal);
            let end = new Date(endVal);

            if(end >= start) {
                let days = ((end - start) / (1000 * 60 * 60 * 24)) + 1;
                $('#calc_days').text(Math.round(days));
                $('.duration-card').addClass('bg-opacity-20');
            } else {
                $('#calc_days').text(0);
                $('.duration-card').removeClass('bg-opacity-20');
            }
        }
    });

    $('#leaveForm').submit(function(e) {
        e.preventDefault();

        let btn = $('#submitBtn');
        let alertBox = $('#alert-box');

        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Processing...');
        alertBox.addClass('d-none').removeClass('alert-success alert-danger').html('');

        $.ajax({
            url: "{{ route('employee.leave.store') }}",
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                alertBox.removeClass('d-none alert-danger').addClass('alert-success')
                        .html('<i class="bi bi-check-circle-fill me-2"></i> ' + response.success);

                $('#leaveForm')[0].reset();
                $('#calc_days').text(0);
                btn.prop('disabled', false).html('<i class="bi bi-send-fill me-2"></i> Submit Application');

                $('html, body').animate({ scrollTop: 0 }, 'slow');
            },
            error: function(xhr) {
                let errorMsg = "Something went wrong.";

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        let flatErrors = Object.values(xhr.responseJSON.errors).flat();
                        errorMsg = flatErrors.length > 0 ? flatErrors[0] : errorMsg;
                    } else if (xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                } else {
                    errorMsg = xhr.statusText || "Communication failed.";
                }

                alertBox.removeClass('d-none alert-success').addClass('alert-danger')
                        .html('<i class="bi bi-exclamation-triangle-fill me-2"></i> ' + errorMsg);

                btn.prop('disabled', false).html('<i class="bi bi-send-fill me-2"></i> Submit Application');
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });
});
</script>
@endpush
@endsection
