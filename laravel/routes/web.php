<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', fn() => view('welcome'))->name('home');

// Claim flow (public)
Route::get('/reclamar', [ClaimController::class, 'create'])->name('claim.create');
Route::post('/reclamar', [ClaimController::class, 'store'])->name('claim.store');
Route::get('/pago/{claim}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/pago/{claim}/intent', [PaymentController::class, 'createIntent'])->name('payment.intent');
Route::get('/pago/{claim}/completado', [PaymentController::class, 'success'])->name('payment.success');

// Downloads (authenticated or in session)
Route::get('/descargar/{claim}/word', [ClaimController::class, 'downloadWord'])->name('claim.download.word');
Route::get('/descargar/{claim}/pdf', [ClaimController::class, 'downloadPdf'])->name('claim.download.pdf');
Route::get('/descargar/{claim}', fn($claim) => view('claim.download', ['claim' => \App\Models\Claim::findOrFail($claim)]))->name('claim.download');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registro', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated area
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Subscriptions
    Route::get('/suscripcion', [SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::post('/suscripcion', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::delete('/suscripcion', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});
