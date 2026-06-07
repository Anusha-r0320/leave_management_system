@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark mb-0">Create New User</h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 shadow-sm px-3">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <h5 class="fw-bold text-muted mb-4 pb-2 border-bottom">Personal Information</h5>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Employee ID</label>
                                <input type="text" name="employee_id" class="form-control bg-light border-0 py-2" placeholder="e.g. EMP101" required>
                                @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Full Name</label>
                                <input type="text" name="name" class="form-control bg-light border-0 py-2" placeholder="e.g. John Doe" required>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Email Address</label>
                                <input type="email" name="email" class="form-control bg-light border-0 py-2" placeholder="e.g. john@example.com" required>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Mobile Number</label>
                                <input type="text" name="mobile" class="form-control bg-light border-0 py-2" placeholder="e.g. +1234567890" required>
                                @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <h5 class="fw-bold text-muted mb-4 pb-2 border-bottom">Employment Details</h5>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Department</label>
                                <input type="text" name="department" class="form-control bg-light border-0 py-2" placeholder="e.g. IT Department" required>
                                @error('department') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Designation</label>
                                <input type="text" name="designation" class="form-control bg-light border-0 py-2" placeholder="e.g. Senior Developer" required>
                                @error('designation') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Role Assignment</label>
                                <select name="role" class="form-select bg-light border-0 py-2">
                                    <option value="employee" selected>Employee</option>
                                    <option value="manager">Manager</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Assign Manager</label>
                                <select name="manager_id" class="form-select bg-light border-0 py-2">
                                    <option value="">No Manager Assigned</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->employee_id }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Account Status</label>
                                <select name="status" class="form-select bg-light border-0 py-2">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 mb-4 text-muted small border-start border-4 border-info">
                            <i class="bi bi-info-circle-fill me-2"></i> Note: The default password for new users will be <strong>Password@123</strong>.
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm rounded-3 py-3 fw-bold">
                                <i class="bi bi-person-plus-fill me-2"></i> Save and Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
