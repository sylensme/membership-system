<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipApplicationController;
use App\Http\Controllers\PublicMembershipApplicationController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/membership/apply', [PublicMembershipApplicationController::class, 'create'])->name('applications.create');
Route::post('/membership/apply', [PublicMembershipApplicationController::class, 'store'])->middleware('throttle:5,1')->name('applications.store');
Route::get('/weather', WeatherController::class)->middleware('throttle:30,1')->name('weather.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/', DashboardController::class)->name('home');
    Route::get('/applications', [MembershipApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [MembershipApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/members/{member}/deactivate', [MemberController::class, 'deactivate'])->name('members.deactivate');
    Route::resource('members', MemberController::class);
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('levels', LevelController::class)->except('show');
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/transactions/{transaction}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
});
