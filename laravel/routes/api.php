<?php

use App\Http\Controllers\PipelineController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AemetController;
use App\Http\Controllers\PublicDataController;
use Illuminate\Support\Facades\Route;

// Stripe webhook — no CSRF, no auth
Route::post('/stripe/webhook', [WebhookController::class, 'handleStripe'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// n8n pipeline log endpoint
Route::post('/pipeline/log', [PipelineController::class, 'log']);

// AEMET weather alerts — proxied to Python service
Route::middleware('auth')->get('/aemet/alertas', [AemetController::class, 'alertas']);
Route::middleware('auth')->post('/aemet/historial', [AemetController::class, 'historial']);

// BOE — normativa vigente (auth required, cached 24h)
Route::middleware('auth')->group(function () {
    Route::get('/boe/norma/{key}',              [PublicDataController::class, 'boeNorma']);
    Route::post('/boe/search',                  [PublicDataController::class, 'boeSearch']);
    Route::post('/boe/normativa-reclamacion',   [PublicDataController::class, 'boeNormativaReclamacion']);
});

// DGSFP — registro aseguradoras (público, cached 24h)
Route::middleware('auth')->group(function () {
    Route::post('/dgsfp/aseguradora', [PublicDataController::class, 'dgsfpAseguradora']);
    Route::post('/dgsfp/sanciones',   [PublicDataController::class, 'dgsfpSanciones']);
    Route::post('/dgsfp/defensor',    [PublicDataController::class, 'dgsfpDefensor']);
});
