<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of leave types for configuration.
     */
    public function index()
    {
        $leave_types = LeaveType::all();
        return view('admin.leave_types.index', compact('leave_types'));
    }

    /**
     * Update the specified leave type quota in storage and reflect changes globally.
     */
    public function update(UpdateLeaveTypeRequest $request, $id)
    {
        $leave_type = LeaveType::findOrFail($id);
        $new_allocation = $request->default_allocation;

        $leave_type->update([
            'default_allocation' => $new_allocation
        ]);

        \App\Models\LeaveBalances::where('leave_type_id', $id)->each(function ($balance) use ($new_allocation) {
            $balance->update([
                'allocated_days' => $new_allocation,
                'remaining_days' => $new_allocation - $balance->used_days
            ]);
        });

        return redirect()->back()->with('success', 'Leave quota for ' . $leave_type->name . ' updated globally for all members.');
    }
}
