<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\LeaveTypeController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            default => redirect()->route('employee.dashboard'),
        };
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');


        Route::resource('users', AdminController::class)->except(['show']);
        Route::get('/users/search', [AdminController::class, 'searchUsers'])->name('users.search');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggleStatus');

        Route::resource('leave_types', LeaveTypeController::class)->only(['index', 'update']);


        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    });

    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('dashboard');

        Route::get('/leave_requests', [ManagerController::class, 'leaveRequests'])->name('leave_requests');
        Route::post('/leave_requests/{request}/approve', [ManagerController::class, 'approve'])->name('approve');
        Route::post('/leave_requests/{request}/reject', [ManagerController::class, 'reject'])->name('reject');
    });

    Route::middleware(['role:employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('dashboard');

        Route::get('/leave/apply', [EmployeeController::class, 'showApplyForm'])->name('leave.apply');
        Route::post('/leave/store', [EmployeeController::class, 'storeLeave'])->name('leave.store');

        Route::get('/leave/history', [EmployeeController::class, 'history'])->name('leave.history');
    });
});

require __DIR__ . '/auth.php';
