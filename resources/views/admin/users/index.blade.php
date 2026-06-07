@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">User Management</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm rounded-3 px-4">
            <i class="bi bi-person-plus-fill me-1"></i> Create New User
        </a>
    </div>

    <!-- Search Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchUser" class="form-control bg-light border-0 py-2" placeholder="Search by Name, Employee ID, or Department...">
            </div>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4 fw-semibold border-0">Employee ID</th>
                            <th class="fw-semibold border-0">User Details</th>
                            <th class="fw-semibold border-0">Department</th>
                            <th class="fw-semibold border-0 text-center">Role</th>
                            <th class="fw-semibold border-0 text-center">Status</th>
                            <th class="pe-4 fw-semibold border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @forelse($users as $user)
                        <tr id="user-{{ $user->id }}">
                            <td class="ps-4 fw-bold text-primary">{{ $user->employee_id }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </td>
                            <td>{{ $user->department }}</td>
                            <td class="text-center">
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill small">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="text-center user-status-cell">
                                @if($user->trashed())
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill small">Inactive (Trashed)</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill small">Active</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    @if($user->trashed())
                                        <button class="btn btn-light btn-sm px-3 edit-btn" disabled title="User is deleted can't edit enable to edit the user">
                                            <i class="bi bi-pencil-square text-muted"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-light btn-sm px-3 edit-btn" title="Edit User">
                                            <i class="bi bi-pencil-square text-warning"></i>
                                        </a>
                                    @endif
                                    <button class="btn btn-light btn-sm px-3 toggle-status" data-id="{{ $user->id }}" title="{{ $user->trashed() ? 'Restore' : 'Disable' }}">
                                        @if($user->trashed())
                                            <i class="bi bi-person-check-fill text-success"></i>
                                        @else
                                            <i class="bi bi-person-x-fill text-danger"></i>
                                        @endif
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-people fs-1 text-light mb-2"></i>
                                    <p class="mb-0">No users found.</p>
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

@push('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    function renderUserRows(users) {
        let rows = '';
        if (users.length === 0) {
            return `<tr><td colspan="6" class="text-center py-5 text-muted">No users matching your search.</td></tr>`;
        }

        users.forEach(user => {
            let isTrashed = user.deleted_at !== null;
            let statusBadge = !isTrashed
                ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill small">Active</span>'
                : '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill small">Inactive (Trashed)</span>';

            let actionIcon = !isTrashed
                ? '<i class="bi bi-person-x-fill text-danger"></i>'
                : '<i class="bi bi-person-check-fill text-success"></i>';

            let editButton = isTrashed
                ? `<button class="btn btn-light btn-sm px-3 edit-btn" disabled title="User is deleted can't edit enable to edit the user"><i class="bi bi-pencil-square text-muted"></i></button>`
                : `<a href="/admin/users/${user.id}/edit" class="btn btn-light btn-sm px-3 edit-btn" title="Edit User"><i class="bi bi-pencil-square text-warning"></i></a>`;

            rows += `
            <tr id="user-${user.id}">
                <td class="ps-4 fw-bold text-primary">${user.employee_id}</td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-dark">${user.name}</span>
                        <small class="text-muted">${user.email}</small>
                    </div>
                </td>
                <td>${user.department || '---'}</td>
                <td class="text-center">
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill small">
                        ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                    </span>
                </td>
                <td class="text-center user-status-cell">${statusBadge}</td>
                <td class="pe-4 text-end">
                    <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                        ${editButton}
                        <button class="btn btn-light btn-sm px-3 toggle-status" data-id="${user.id}" title="${!isTrashed ? 'Disable' : 'Restore'}">
                            ${actionIcon}
                        </button>
                    </div>
                </td>
            </tr>`;
        });
        return rows;
    }

    let searchTimer;
    $('#searchUser').on('keyup', function() {
        clearTimeout(searchTimer);
        let query = $(this).val();

        searchTimer = setTimeout(function() {
            $.ajax({
                url: "{{ route('admin.users.search') }}",
                type: "GET",
                data: { search: query },
                success: function(response) {
                    $('#userTableBody').html(renderUserRows(response.users));
                }
            });
        }, 300);
    });

    $(document).on('click', '.toggle-status', function() {
        let id = $(this).data('id');
        let btn = $(this);
        let row = btn.closest('tr');

        btn.prop('disabled', true);

        $.post(`/admin/users/${id}/toggle-status`, function(response) {
            if(response.success) {
                let isAct = response.new_status === 'active';

                let newBadge = isAct
                    ? '<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill small">Active</span>'
                    : '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill small">Inactive (Trashed)</span>';
                row.find('.user-status-cell').html(newBadge);

                let editBtn = row.find('.edit-btn');
                if (isAct) {
                    editBtn.replaceWith(`<a href="/admin/users/${id}/edit" class="btn btn-light btn-sm px-3 edit-btn" title="Edit User"><i class="bi bi-pencil-square text-warning"></i></a>`);
                } else {
                    editBtn.replaceWith(`<button class="btn btn-light btn-sm px-3 edit-btn" disabled title="User is deleted can't edit enable to edit the user"><i class="bi bi-pencil-square text-muted"></i></button>`);
                }

                btn.attr('title', isAct ? 'Disable' : 'Restore');
                btn.html(isAct ? '<i class="bi bi-person-x-fill text-danger"></i>' : '<i class="bi bi-person-check-fill text-success"></i>');
            }
        }).always(function() {
            btn.prop('disabled', false);
        });
    });
});
</script>
@endpush
@endsection
