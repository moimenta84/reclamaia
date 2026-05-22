<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ViabilityController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', fn() => view('welcome'))->name('home');

// Viability analysis — subscribers only
Route::middleware(['auth', 'subscriber'])->group(function () {
    Route::get('/analizar', [ViabilityController::class, 'show'])->name('viability.show');
    Route::post('/analizar', [ViabilityController::class, 'analyze'])->name('viability.analyze');
    Route::get('/analizar/{claim}', [ViabilityController::class, 'forClaim'])->name('viability.for-claim');
});

// Policy PDF upload — subscribers only
Route::middleware(['auth', 'subscriber'])->group(function () {
    Route::post('/poliza/{claim}/subir', [PolicyController::class, 'upload'])->name('policy.upload');
    Route::get('/poliza/{claim}/analisis', [PolicyController::class, 'analysis'])->name('policy.analysis');
});

// Claim flow (public)
Route::get('/reclamar', [ClaimController::class, 'create'])->name('claim.create');
Route::post('/reclamar', [ClaimController::class, 'store'])->name('claim.store');
Route::get('/pago/{claim}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/pago/{claim}/intent', [PaymentController::class, 'createIntent'])->name('payment.intent');
Route::get('/pago/{claim}/completado', [PaymentController::class, 'success'])->name('payment.success');

// Downloads (authenticated or in session)
Route::get('/descargar/{claim}/word', [ClaimController::class, 'downloadWord'])->name('claim.download.word');
Route::get('/descargar/{claim}/pdf', [ClaimController::class, 'downloadPdf'])->name('claim.download.pdf');
Route::get('/descargar/{claim}/escalada', [ClaimController::class, 'downloadEscalation'])->name('claim.download.escalation');
Route::get('/descargar/{claim}', fn($claim) => view('claim.download', ['claim' => \App\Models\Claim::findOrFail($claim)]))->name('claim.download');

// Mark claim as sent to insurer (activates 30-day escalation tracking)
Route::post('/reclamacion/{claim}/enviada', [ClaimController::class, 'markAsSent'])->name('claim.mark-sent')->middleware('auth');

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
