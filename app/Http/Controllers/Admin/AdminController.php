<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_employees' => User::withTrashed()->where('role', '!=', 'admin')->count(),
            'active_employees' => User::where('role', '!=', 'admin')->where('status', 'active')->count(),
            'inactive_employees' => User::withTrashed()->where('role', '!=', 'admin')->where(function($q) {
                $q->where('status', 'inactive')->orWhereNotNull('deleted_at');
            })->count(),
            'total_requests' => LeaveRequest::count(),
            'approved_leaves' => LeaveRequest::where('status', 'approved')->count(),
            'rejected_leaves' => LeaveRequest::where('status', 'rejected')->count(),
            'pending_approvals' => LeaveRequest::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function index()
    {
        $users = User::withTrashed()->where('role', '!=', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $managers = User::where('role', 'manager')->where('status', 'active')->get();
        return view('admin.users.create', compact('managers'));
    }

    public function store(StoreUserRequest $request)
    {
        User::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'department' => $request->department,
            'designation' => $request->designation,
            'role' => $request->role,
            'manager_id' => $request->manager_id,
            'status' => $request->status,
            'password' => Hash::make('Password@123'), // Default password
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $managers = User::where('role', 'manager')->where('status', 'active')->get();
        return view('admin.users.edit', compact('user', 'managers'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->update($request->only(['name', 'mobile', 'department', 'designation', 'role', 'manager_id']));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function searchUsers(Request $request)
    {
        $query = User::withTrashed()->where('role', '!=', 'admin');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('employee_id', 'like', "%{$searchTerm}%")
                  ->orWhere('department', 'like', "%{$searchTerm}%");
            });
        }

        $users = $query->get();

        return response()->json(['users' => $users]);
    }

    public function toggleStatus($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->role === 'admin' && $user->id === auth()->id()) {
            return response()->json(['error' => 'Cannot modify your own admin status.'], 403);
        }

        if ($user->trashed()) {
            $user->restore();
            $user->status = 'active';
            $user->save();
            $new_status = 'active';
        } else {
            $user->status = 'inactive';
            $user->save();
            $user->delete();
            $new_status = 'inactive';
        }

        return response()->json([
            'success' => 'User status changed successfully.',
            'new_status' => $new_status
        ]);
    }

    public function reports(Request $request)
    {
        if ($request->ajax()) {
            $query = LeaveRequest::with(['user', 'leaveType', 'manager']);

            if ($request->filled('employee_name')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->employee_name . '%');
                });
            }
            if ($request->filled('department')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('department', $request->department);
                });
            }
            if ($request->filled('leave_type_id')) {
                $query->where('leave_type_id', $request->leave_type_id);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
            }

            return response()->json(['reports' => $query->get()]);
        }

        $departments = User::select('department')->whereNotNull('department')->distinct()->pluck('department');
        $leaveTypes = LeaveType::all();

        return view('admin.reports', compact('departments', 'leaveTypes'));
    }
}
