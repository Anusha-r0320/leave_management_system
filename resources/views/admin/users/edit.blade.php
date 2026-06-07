@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark mb-0">Edit User: <span class="text-primary">{{ $user->name }}</span></h2>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 shadow-sm px-3">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf 
                        @method('PUT')
                        
                        <h5 class="fw-bold text-muted mb-4 pb-2 border-bottom">Personal Information</h5>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control bg-light border-0 py-2" required>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Mobile Number</label>
                                <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}" class="form-control bg-light border-0 py-2" required>
                                @error('mobile') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <h5 class="fw-bold text-muted mb-4 pb-2 border-bottom">Employment Details</h5>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Department</label>
                                <input type="text" name="department" value="{{ old('department', $user->department) }}" class="form-control bg-light border-0 py-2" required>
                                @error('department') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Designation</label>
                                <input type="text" name="designation" value="{{ old('designation', $user->designation) }}" class="form-control bg-light border-0 py-2" required>
                                @error('designation') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Role Assignment</label>
                                <select name="role" class="form-select bg-light border-0 py-2">
                                    <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee</option>
                                    <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Assigned Manager</label>
                                <select name="manager_id" class="form-select bg-light border-0 py-2">
                                    <option value="">No Manager Assigned</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->id }}" {{ $user->manager_id == $manager->id ? 'selected' : '' }}>
                                            {{ $manager->name }} ({{ $manager->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small uppercase">Account Status</label>
                                <div class="py-2 px-3 bg-light rounded-3 d-flex align-items-center">
                                    @if($user->status == 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill small me-2">Active</span>
                                        <span class="text-muted small">Status can be toggled from the user list.</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 rounded-pill small me-2">Inactive</span>
                                        <span class="text-muted small">Status can be toggled from the user list.</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm rounded-3 py-3 fw-bold">
                                <i class="bi bi-save-fill me-2"></i> Update User Details
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-lg rounded-3 py-3 fw-bold text-muted border-0">
                                Cancel and Return
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
