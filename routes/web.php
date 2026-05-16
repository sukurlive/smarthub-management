<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function ()
{
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Equipment Management
    Route::resource('equipment', App\Http\Controllers\Admin\EquipmentController::class);
    
    // Loan Management
    Route::get('/loans', [App\Http\Controllers\Admin\LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/{id}', [App\Http\Controllers\Admin\LoanController::class, 'show'])->name('loans.show');
    Route::patch('/loans/{id}/checkin', [App\Http\Controllers\Admin\LoanController::class, 'checkin'])->name('loans.checkin');

    // Member Management
    Route::resource('members', App\Http\Controllers\Admin\MemberController::class);

    Route::get('/members/{id}/reset-password', [App\Http\Controllers\Admin\MemberController::class, 'resetPassword'])->name('members.reset-password');
    Route::get('/members/{id}/destroy', [App\Http\Controllers\Admin\MemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/export/csv', [App\Http\Controllers\Admin\MemberController::class, 'export'])->name('members.export');
});

// Member Routes (require auth)
Route::middleware(['auth'])->prefix('member')->name('member.')->group(function ()
{
    // Dashboard & Catalog
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'memberDashboard'])->name('dashboard');
    Route::get('/catalog', [App\Http\Controllers\HomeController::class, 'catalog'])->name('catalog');
    Route::get('/loans', [App\Http\Controllers\HomeController::class, 'loanHistory'])->name('loans');
    
    // Borrow Equipment
    Route::get('/borrow/{id}', [App\Http\Controllers\HomeController::class, 'borrowForm'])->name('borrow.form');
    Route::post('/borrow/{id}', [App\Http\Controllers\HomeController::class, 'borrowStore'])->name('borrow.store');
    
    // Checkin Equipment
    Route::patch('/checkin/{id}', [App\Http\Controllers\HomeController::class, 'checkin'])->name('checkin');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');