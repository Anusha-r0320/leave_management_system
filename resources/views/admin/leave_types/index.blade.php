@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Leave Configurations</h2>
        <div class="text-muted small fw-semibold">
            <i class="bi bi-gear-fill me-1"></i> Global Settings
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4 fw-semibold border-0 py-3">Leave Type Name</th>
                            <th class="fw-semibold border-0 py-3">Default Allocation (Days)</th>
                            <th class="pe-4 fw-semibold border-0 py-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leave_types as $type)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">{{ $type->name }}</span>
                            </td>
                            <td>
                                <div style="max-width: 150px;">
                                    <input type="number"
                                           name="default_allocation"
                                           value="{{ $type->default_allocation }}"
                                           form="update-form-{{ $type->id }}"
                                           class="form-control bg-light border-0 py-2 fw-semibold"
                                           min="0"
                                           required>
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <form id="update-form-{{ $type->id }}" action="{{ route('admin.leave_types.update', $type->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary btn-sm px-4 rounded-3 shadow-sm fw-bold">
                                        <i class="bi bi-save me-1"></i> Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
