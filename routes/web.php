<?php

use App\Http\Controllers\Admin\EquipmentController as AdminEquipmentController;
use App\Http\Controllers\Admin\LoanController as AdminLoanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('equipment', AdminEquipmentController::class);
    Route::resource('loans', AdminLoanController::class);
    Route::patch('/loans/{loan}/checkin', [AdminLoanController::class, 'checkin'])->name('admin.loans.checkin');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

