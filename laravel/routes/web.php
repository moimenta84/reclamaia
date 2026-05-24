<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BaremoController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\DeceasedInsuranceController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SignaturitController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\ViabilityController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', fn() => view('welcome'))->name('home');

// Sitemap & robots
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt',  [SitemapController::class, 'robots'])->name('robots');

// SEO landing pages
Route::get('/reclamaciones',            [SeoController::class, 'reclamaciones'])->name('seo.reclamaciones');
Route::get('/reclamar-seguro',          [SeoController::class, 'reclamarSeguro'])->name('seo.reclamar-seguro');
Route::get('/reclamar-seguro-hogar',    [SeoController::class, 'hogar'])->name('seo.hogar');
Route::get('/reclamar-seguro-coche',    [SeoController::class, 'coche'])->name('seo.coche');
Route::get('/reclamar-seguro-vida',     [SeoController::class, 'vida'])->name('seo.vida');
Route::get('/reclamar-seguro-salud',    [SeoController::class, 'salud'])->name('seo.salud');
Route::get('/buscar-seguros-fallecidos',[SeoController::class, 'fallecidos'])->name('seo.fallecidos');
Route::get('/danos-desastres-naturales',[SeoController::class, 'desastres'])->name('seo.desastres');

// Blog
Route::get('/blog',        [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Guías
Route::get('/guias',        [BlogController::class, 'guiasIndex'])->name('guias.index');
Route::get('/guias/{slug}', [BlogController::class, 'guia'])->name('guias.show');

// Legal pages (public)
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/aviso-legal', [LegalController::class, 'avisoLegal'])->name('aviso');
    Route::get('/privacidad', [LegalController::class, 'privacidad'])->name('privacidad');
    Route::get('/cookies', [LegalController::class, 'cookies'])->name('cookies');
    Route::get('/terminos', [LegalController::class, 'terminos'])->name('terminos');
    Route::get('/reembolso', [LegalController::class, 'reembolso'])->name('reembolso');
    Route::post('/consent', [LegalController::class, 'recordConsent'])->name('consent');
});

// Baremo de tráfico — public tool
Route::get('/baremo', [BaremoController::class, 'show'])->name('tools.baremo.show');
Route::post('/baremo/calcular', [BaremoController::class, 'calculate'])->name('tools.baremo.calculate');

// Signaturit webhook — public (validated inside controller)
Route::post('/webhook/signaturit', [SignaturitController::class, 'webhook'])->name('webhook.signaturit');

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

// Pro tools — subscriber only
Route::middleware(['auth', 'subscriber'])->prefix('herramientas')->name('tools.')->group(function () {
    Route::get('/', [ToolsController::class, 'index'])->name('index');
    Route::get('/ocr', [ToolsController::class, 'showOcr'])->name('ocr.show');
    Route::post('/ocr', [ToolsController::class, 'processOcr'])->name('ocr.process');
    Route::get('/valoracion', [ToolsController::class, 'showValoracion'])->name('valoracion.show');
    Route::post('/valoracion', [ToolsController::class, 'calculateValoracion'])->name('valoracion.calculate');
    Route::get('/jurisprudencia', [ToolsController::class, 'showJurisprudencia'])->name('jurisprudencia.show');
    Route::post('/jurisprudencia', [ToolsController::class, 'searchJurisprudencia'])->name('jurisprudencia.search');
});

// Digital signature — auth + own claim
Route::middleware('auth')->group(function () {
    Route::get('/reclamacion/{claim}/firma', [SignaturitController::class, 'showSign'])
        ->name('tools.firma.show');
    Route::post('/reclamacion/{claim}/firma', [SignaturitController::class, 'requestSign'])
        ->name('tools.firma.request');
});

// Seguros del fallecido — RCSCF (subscriber only)
Route::middleware(['auth', 'subscriber'])
    ->prefix('herramientas/seguros-fallecido')
    ->name('tools.fallecido.')
    ->group(function () {
        Route::get('/',                                    [DeceasedInsuranceController::class, 'index'])->name('index');
        Route::get('/nuevo',                               [DeceasedInsuranceController::class, 'create'])->name('create');
        Route::post('/',                                   [DeceasedInsuranceController::class, 'store'])->name('store');
        Route::get('/{search}',                            [DeceasedInsuranceController::class, 'show'])->name('show');
        Route::patch('/{search}',                          [DeceasedInsuranceController::class, 'update'])->name('update');
        Route::delete('/{search}',                         [DeceasedInsuranceController::class, 'destroy'])->name('destroy');
    });
