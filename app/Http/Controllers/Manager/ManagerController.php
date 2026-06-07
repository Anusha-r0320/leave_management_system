<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalances;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
   public function dashboard()
    {
        $all_requests = LeaveRequest::where('manager_id', Auth::id())->get();
        $requests = LeaveRequest::with(['user', 'leaveType'])
            ->where('manager_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        $stats = [
            'total' => $all_requests->count(),
            'pending' => $all_requests->where('status', 'pending')->count(),
            'approved' => $all_requests->where('status', 'approved')->count(),
            'rejected' => $all_requests->where('status', 'rejected')->count(),
        ];

        return view('manager.dashboard', compact('stats', 'requests'));
    }

    public function leaveRequests()
    {
        $requests = LeaveRequest::with(['user', 'leaveType'])
            ->where('manager_id', Auth::id())
            ->where('status', 'pending')
            ->get();

        return view('manager.requests', compact('requests'));
    }

    public function approve(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);

        if($leave->manager_id !== Auth::id() || $leave->status !== 'pending') {
            return response()->json(['error' => 'Unauthorized or invalid state.'], 403);
        }

        $balance = LeaveBalances::where('user_id', $leave->user_id)
            ->where('leave_type_id', $leave->leave_type_id)
            ->first();

        $balance->used_days += $leave->total_days;
        $balance->remaining_days -= $leave->total_days;
        $balance->save();

        $leave->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        return response()->json(['success' => 'Leave approved successfully.']);
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['manager_remarks' => 'required|string']);

        $leave = LeaveRequest::findOrFail($id);

        if($leave->manager_id !== Auth::id() || $leave->status !== 'pending') {
            return response()->json(['error' => 'Unauthorized or invalid state.'], 403);
        }

        $leave->update([
            'status' => 'rejected',
            'manager_remarks' => $request->manager_remarks
        ]);

        return response()->json(['success' => 'Leave rejected successfully.']);
    }
}
