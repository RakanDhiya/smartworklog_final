<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class)->except(['show']);

    Route::resource('cases', CaseController::class);
    Route::post('cases/{case}/team', [CaseController::class, 'addTeamMember'])->name('cases.team.store');
    Route::delete('cases/{case}/team/{user}', [CaseController::class, 'removeTeamMember'])->name('cases.team.destroy');
});