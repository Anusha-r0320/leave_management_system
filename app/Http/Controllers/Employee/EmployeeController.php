<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeaveRequest;
use App\Models\LeaveBalances;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $activeLeaveTypes = LeaveType::where('status', 'active')->get();

        foreach ($activeLeaveTypes as $leaveType) {
            LeaveBalances::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id
                ],
                [
                    'allocated_days' => $leaveType->default_allocation,
                    'used_days' => 0,
                    'remaining_days' => $leaveType->default_allocation,
                ]
            );
        }

        $requests = LeaveRequest::with('leaveType')
                                ->where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get();


        $stats = [
            'total' => $requests->count(),
            'approved' => $requests->where('status', 'approved')->count(),
            'rejected' => $requests->where('status', 'rejected')->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'balance' => LeaveBalances::where('user_id', $user->id)->sum('remaining_days')
        ];

        return view('employee.dashboard', compact('stats', 'requests'));
    }

    public function showApplyForm()
    {
        $leaveTypes = LeaveType::where('status', 'active')->get();
        return view('employee.apply', compact('leaveTypes'));
    }

    public function storeLeave(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'required|string',
        ]);

        $user = Auth::user();
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->startOfDay();
        $today = Carbon::today();

        if ($startDate->lt($today)) {
            return response()->json(['error' => 'Start Date cannot be earlier than the current date.'], 422);
        }

        if ($endDate->lt($startDate)) {
            return response()->json(['error' => 'End Date cannot be earlier than Start Date.'], 422);
        }

        $totalDays = $startDate->diffInDays($endDate) + 1;

        $balance = LeaveBalances::where('user_id', $user->id)
            ->where('leave_type_id', $request->leave_type_id)
            ->first();

        if (!$balance) {
            $leaveType = LeaveType::find($request->leave_type_id);

            if ($leaveType) {
                $balance = LeaveBalances::create([
                    'user_id' => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'allocated_days' => $leaveType->default_allocation,
                    'used_days' => 0,
                    'remaining_days' => $leaveType->default_allocation,
                ]);
            }
        }

        if (!$balance || $balance->remaining_days < $totalDays) {
            return response()->json(['error' => "Insufficient leave balance. You only have {$balance->remaining_days} days left."], 422);
        }

        $overlap = LeaveRequest::where('user_id', $user->id)
            ->where('status', '!=', 'rejected')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })->exists();

        if ($overlap) {
            return response()->json(['error' => 'Leave dates overlap with an existing request.'], 422);
        }

        LeaveRequest::create([
            'user_id' => $user->id,
            'manager_id' => $user->manager_id,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return response()->json(['success' => 'Leave application submitted successfully.']);
    }

    public function history()
    {
        $user = Auth::user();

        $query = LeaveRequest::with('leaveType')
                             ->where('user_id', $user->id)
                             ->orderBy('created_at', 'desc');

        if (request()->filled('leave_type_id')) {
            $query->where('leave_type_id', request()->leave_type_id);
        }

        if (request()->filled('status')) {
            $query->where('status', request()->status);
        }

        if (request()->filled('start_date') && request()->filled('end_date')) {
            $query->whereBetween('start_date', [request()->start_date, request()->end_date]);
        }

        $requests = $query->get();
        $leave_types = LeaveType::all();

        return view('employee.history', compact('requests', 'leave_types'));
    }
}
