<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityTypeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Clients & Cases
    Route::resource('clients', ClientController::class)->except(['show']);
    Route::resource('cases', CaseController::class);
    Route::post('cases/{case}/team', [CaseController::class, 'addTeamMember'])->name('cases.team.store');
    Route::delete('cases/{case}/team/{user}', [CaseController::class, 'removeTeamMember'])->name('cases.team.destroy');

    // Activities
    Route::resource('activity-types', ActivityTypeController::class)
        ->only(['index', 'store', 'update', 'destroy']);
    Route::resource('activities', ActivityController::class)->except(['show']);

    // Attendance
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('attendances/checkin', [AttendanceController::class, 'checkIn'])->name('attendances.checkin');
    Route::post('attendances/checkout', [AttendanceController::class, 'checkOut'])->name('attendances.checkout');
    Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
    Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');

    // Tasks
    Route::resource('tasks', TaskController::class)->except(['show']);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
});